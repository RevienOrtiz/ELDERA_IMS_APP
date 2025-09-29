import 'dart:developer' as developer;
import 'dart:io';
import 'package:path_provider/path_provider.dart';

/// SECURE LOGGING SERVICE
/// 
/// This service provides secure logging functionality that automatically
/// sanitizes sensitive information before logging. It prevents accidental
/// exposure of passwords, tokens, personal data, and other sensitive information.
/// 
/// FEATURES:
/// - Automatic sanitization of sensitive data patterns
/// - Different log levels (debug, info, warning, error)
/// - File-based logging for production
/// - Console logging for development
/// - Configurable sensitive data patterns
/// 
/// USAGE:
/// SecureLogger.info('User logged in successfully');
/// SecureLogger.error('Login failed', error: exception);
/// SecureLogger.debug('API response: $response'); // Automatically sanitized
class SecureLogger {
  static const String _logFileName = 'eldera_app.log';
  static const int _maxLogFileSize = 5 * 1024 * 1024; // 5MB
  static const int _maxLogFiles = 3;
  
  // Patterns for sensitive data that should be sanitized
  static final List<RegExp> _sensitivePatterns = [
    // Passwords
    RegExp(r'"password"\s*:\s*"[^"]*"', caseSensitive: false),
    RegExp(r'password\s*=\s*[^\s&]+', caseSensitive: false),
    RegExp(r'pwd\s*=\s*[^\s&]+', caseSensitive: false),
    
    // JWT Tokens
    RegExp(r'"access_token"\s*:\s*"[^"]*"', caseSensitive: false),
    RegExp(r'"refresh_token"\s*:\s*"[^"]*"', caseSensitive: false),
    RegExp(r'Bearer\s+[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+', caseSensitive: false),
    RegExp(r'Authorization:\s*Bearer\s+[^\s]+', caseSensitive: false),
    
    // API Keys
    RegExp(r'"api_key"\s*:\s*"[^"]*"', caseSensitive: false),
    RegExp(r'api_key\s*=\s*[^\s&]+', caseSensitive: false),
    
    // Personal Information
    RegExp(r'"phone_number"\s*:\s*"[^"]*"', caseSensitive: false),
    RegExp(r'"email"\s*:\s*"[^"]*"', caseSensitive: false),
    RegExp(r'"id"\s*:\s*"[^"]*"', caseSensitive: false),
    
    // Credit Card Numbers (basic pattern)
    RegExp(r'\b\d{4}[\s-]?\d{4}[\s-]?\d{4}[\s-]?\d{4}\b'),
    
    // Social Security Numbers (Philippine format)
    RegExp(r'\b\d{2}-\d{7}-\d{1}\b'),
    
    // CSRF Tokens
    RegExp(r'"csrf_token"\s*:\s*"[^"]*"', caseSensitive: false),
    RegExp(r'X-CSRF-Token:\s*[^\s]+', caseSensitive: false),
  ];
  
  // Replacement patterns for sanitization
  static const Map<String, String> _replacements = {
    'password': '***SANITIZED_PASSWORD***',
    'token': '***SANITIZED_TOKEN***',
    'key': '***SANITIZED_KEY***',
    'phone': '***SANITIZED_PHONE***',
    'email': '***SANITIZED_EMAIL***',
    'id': '***SANITIZED_ID***',
    'card': '***SANITIZED_CARD***',
    'ssn': '***SANITIZED_SSN***',
    'csrf': '***SANITIZED_CSRF***',
  };

  // Sanitize sensitive information from log messages
  static String _sanitizeMessage(String message) {
    String sanitized = message;
    
    // Apply sanitization patterns
    for (final pattern in _sensitivePatterns) {
      if (pattern.hasMatch(sanitized)) {
        if (pattern.pattern.contains('password')) {
          sanitized = sanitized.replaceAll(pattern, '"password":"${_replacements['password']}"');
        } else if (pattern.pattern.contains('token')) {
          sanitized = sanitized.replaceAll(pattern, '"token":"${_replacements['token']}"');
        } else if (pattern.pattern.contains('api_key')) {
          sanitized = sanitized.replaceAll(pattern, '"api_key":"${_replacements['key']}"');
        } else if (pattern.pattern.contains('phone')) {
          sanitized = sanitized.replaceAll(pattern, '"phone_number":"${_replacements['phone']}"');
        } else if (pattern.pattern.contains('email')) {
          sanitized = sanitized.replaceAll(pattern, '"email":"${_replacements['email']}"');
        } else if (pattern.pattern.contains('id')) {
          sanitized = sanitized.replaceAll(pattern, '"id":"${_replacements['id']}"');
        } else if (pattern.pattern.contains('csrf')) {
          sanitized = sanitized.replaceAll(pattern, '"csrf_token":"${_replacements['csrf']}"');
        } else if (pattern.pattern.contains('Bearer')) {
          sanitized = sanitized.replaceAll(pattern, 'Bearer ${_replacements['token']}');
        } else if (pattern.pattern.contains('Authorization')) {
          sanitized = sanitized.replaceAll(pattern, 'Authorization: Bearer ${_replacements['token']}');
        } else if (pattern.pattern.contains('X-CSRF-Token')) {
          sanitized = sanitized.replaceAll(pattern, 'X-CSRF-Token: ${_replacements['csrf']}');
        } else if (pattern.pattern.contains('\\d{4}')) {
          sanitized = sanitized.replaceAll(pattern, _replacements['card']!);
        } else if (pattern.pattern.contains('\\d{2}-\\d{7}')) {
          sanitized = sanitized.replaceAll(pattern, _replacements['ssn']!);
        }
      }
    }
    
    return sanitized;
  }

  // Get log file path
  static Future<String> _getLogFilePath() async {
    final directory = await getApplicationDocumentsDirectory();
    return '${directory.path}/$_logFileName';
  }

  // Rotate log files if they exceed size limit
  static Future<void> _rotateLogFiles() async {
    try {
      final logPath = await _getLogFilePath();
      final logFile = File(logPath);
      
      if (await logFile.exists()) {
        final fileSize = await logFile.length();
        if (fileSize > _maxLogFileSize) {
          // Rotate existing log files
          for (int i = _maxLogFiles - 1; i > 0; i--) {
            final oldFile = File('$logPath.$i');
            final newFile = File('$logPath.${i + 1}');
            if (await oldFile.exists()) {
              await oldFile.rename(newFile.path);
            }
          }
          
          // Move current log to .1
          await logFile.rename('$logPath.1');
        }
      }
    } catch (e) {
      developer.log('Error rotating log files: $e', name: 'SecureLogger');
    }
  }

  // Write log to file
  static Future<void> _writeToFile(String level, String message, {Object? error, StackTrace? stackTrace}) async {
    try {
      await _rotateLogFiles();
      
      final logPath = await _getLogFilePath();
      final logFile = File(logPath);
      final timestamp = DateTime.now().toIso8601String();
      
      String logEntry = '[$timestamp] [$level] $message';
      if (error != null) {
        logEntry += '\nError: $error';
      }
      if (stackTrace != null) {
        logEntry += '\nStack trace: $stackTrace';
      }
      logEntry += '\n';
      
      await logFile.writeAsString(logEntry, mode: FileMode.append);
    } catch (e) {
      developer.log('Error writing to log file: $e', name: 'SecureLogger');
    }
  }

  // Log debug messages (development only)
  static void debug(String message, {Object? error, StackTrace? stackTrace}) {
    final sanitizedMessage = _sanitizeMessage(message);
    developer.log(sanitizedMessage, name: 'Eldera', level: 500, error: error, stackTrace: stackTrace);
    
    // Only write debug logs to file in debug mode
    assert(() {
      _writeToFile('DEBUG', sanitizedMessage, error: error, stackTrace: stackTrace);
      return true;
    }());
  }

  // Log info messages
  static void info(String message, {Object? error, StackTrace? stackTrace}) {
    final sanitizedMessage = _sanitizeMessage(message);
    developer.log(sanitizedMessage, name: 'Eldera', level: 800, error: error, stackTrace: stackTrace);
    _writeToFile('INFO', sanitizedMessage, error: error, stackTrace: stackTrace);
  }

  // Log warning messages
  static void warning(String message, {Object? error, StackTrace? stackTrace}) {
    final sanitizedMessage = _sanitizeMessage(message);
    developer.log(sanitizedMessage, name: 'Eldera', level: 900, error: error, stackTrace: stackTrace);
    _writeToFile('WARNING', sanitizedMessage, error: error, stackTrace: stackTrace);
  }

  // Log error messages
  static void error(String message, {Object? error, StackTrace? stackTrace}) {
    final sanitizedMessage = _sanitizeMessage(message);
    developer.log(sanitizedMessage, name: 'Eldera', level: 1000, error: error, stackTrace: stackTrace);
    _writeToFile('ERROR', sanitizedMessage, error: error, stackTrace: stackTrace);
  }

  // Clear log files
  static Future<void> clearLogs() async {
    try {
      final logPath = await _getLogFilePath();
      
      // Delete main log file
      final mainLogFile = File(logPath);
      if (await mainLogFile.exists()) {
        await mainLogFile.delete();
      }
      
      // Delete rotated log files
      for (int i = 1; i <= _maxLogFiles; i++) {
        final rotatedLogFile = File('$logPath.$i');
        if (await rotatedLogFile.exists()) {
          await rotatedLogFile.delete();
        }
      }
      
      info('Log files cleared successfully');
    } catch (e) {
      error('Error clearing log files', error: e);
    }
  }

  // Get log file contents (for debugging purposes)
  static Future<String> getLogContents() async {
    try {
      final logPath = await _getLogFilePath();
      final logFile = File(logPath);
      
      if (await logFile.exists()) {
        return await logFile.readAsString();
      } else {
        return 'No log file found';
      }
    } catch (e) {
      error('Error reading log file', error: e);
      return 'Error reading log file: $e';
    }
  }
}
import 'package:flutter/foundation.dart';

/// A secure logging utility that provides different log levels
/// and ensures sensitive information is properly handled.
class SecureLogger {
  /// Log levels for different types of messages
  static const int DEBUG = 0;
  static const int INFO = 1;
  static const int WARNING = 2;
  static const int ERROR = 3;

  /// Current log level threshold
  static int _currentLogLevel = kDebugMode ? DEBUG : INFO;

  /// Set the current log level
  static void setLogLevel(int level) {
    _currentLogLevel = level;
  }

  /// Log a debug message
  static void debug(String message) {
    if (_currentLogLevel <= DEBUG) {
      _log('DEBUG', message);
    }
  }

  /// Log an info message
  static void info(String message) {
    if (_currentLogLevel <= INFO) {
      _log('INFO', message);
    }
  }

  /// Log a warning message
  static void warning(String message) {
    if (_currentLogLevel <= WARNING) {
      _log('WARNING', message);
    }
  }

  /// Log an error message
  static void error(String message, {dynamic error, StackTrace? stackTrace}) {
    if (_currentLogLevel <= ERROR) {
      _log('ERROR', message);
      if (error != null) {
        _log('ERROR', 'Error details: $error');
      }
      if (stackTrace != null) {
        _log('ERROR', 'Stack trace: $stackTrace');
      }
    }
  }

  /// Internal logging method
  static void _log(String level, String message) {
    if (kDebugMode) {
      print('[$level] $message');
    }
    // In production, we could send logs to a secure service
    // or store them locally with encryption
  }
}
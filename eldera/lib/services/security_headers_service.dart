import 'dart:io';
import '../config/environment_config.dart';
import 'secure_logger_service.dart';

/// Security Headers Service for API Requests
/// 
/// Manages security headers for all HTTP requests to protect against
/// common web vulnerabilities and ensure secure communication.
/// 
/// Features:
/// - Content Security Policy (CSP)
/// - Cross-Origin Resource Sharing (CORS)
/// - Security headers for API requests
/// - Request/Response validation
/// - SSL/TLS configuration
class SecurityHeadersService {
  static const String _userAgent = 'ElderaHealth/1.0.0 (Flutter Mobile App)';
  
  /// Get standard security headers for API requests
  static Map<String, String> getSecurityHeaders() {
    final headers = <String, String>{
      // Basic headers
      'User-Agent': _userAgent,
      'Accept': 'application/json',
      'Content-Type': 'application/json; charset=utf-8',
      
      // Security headers
      'X-Content-Type-Options': 'nosniff',
      'X-Frame-Options': 'DENY',
      'X-XSS-Protection': '1; mode=block',
      'Referrer-Policy': 'strict-origin-when-cross-origin',
      'Cache-Control': 'no-cache, no-store, must-revalidate',
      'Pragma': 'no-cache',
      'Expires': '0',
      
      // API versioning
      'API-Version': '1.0',
      
      // Request tracking
      'X-Request-ID': _generateRequestId(),
      
      // Client information
      'X-Client-Platform': 'flutter',
      'X-Client-Version': '1.0.0',
    };
    
    // Environment-specific headers removed for production
    
    return headers;
  }
  
  /// Get authentication headers with security token
  static Map<String, String> getAuthHeaders(String? accessToken) {
    final headers = getSecurityHeaders();
    
    if (accessToken != null && accessToken.isNotEmpty) {
      headers['Authorization'] = 'Bearer $accessToken';
    }
    
    return headers;
  }
  
  /// Get headers for webhook requests
  static Map<String, String> getWebhookHeaders(String signature) {
    final headers = getSecurityHeaders();
    
    headers.addAll({
      'X-Webhook-Signature': signature,
      'X-Webhook-Timestamp': DateTime.now().millisecondsSinceEpoch.toString(),
      'X-Webhook-Version': '1.0',
    });
    
    return headers;
  }
  
  /// Validate response headers for security compliance
  static SecurityValidationResult validateResponseHeaders(Map<String, String> headers) {
    final issues = <String>[];
    final warnings = <String>[];
    
    // Check for required security headers
    if (!headers.containsKey('x-content-type-options')) {
      issues.add('Missing X-Content-Type-Options header');
    }
    
    if (!headers.containsKey('x-frame-options')) {
      warnings.add('Missing X-Frame-Options header');
    }
    
    if (!headers.containsKey('strict-transport-security')) {
      warnings.add('Missing Strict-Transport-Security header');
    }
    
    // Check Content-Type
    final contentType = headers['content-type'];
    if (contentType != null && !contentType.contains('application/json')) {
      warnings.add('Unexpected content type: $contentType');
    }
    
    // Check for potentially dangerous headers
    if (headers.containsKey('server')) {
      warnings.add('Server header exposed: ${headers['server']}');
    }
    
    return SecurityValidationResult(
      isSecure: issues.isEmpty,
      issues: issues,
      warnings: warnings,
    );
  }
  
  /// Configure HTTP client with security settings
  static void configureHttpClient(HttpClient client) {
    // SSL/TLS configuration
    client.badCertificateCallback = (cert, host, port) {
      if (EnvironmentConfig.isProduction) {
        // In production, never accept bad certificates
        SecureLogger.warning('Bad certificate rejected for $host:$port');
        return false;
      } else {
        // In development, log but allow for testing
        SecureLogger.warning('Bad certificate accepted for development: $host:$port');
        return true;
      }
    };
    
    // Connection timeout
    client.connectionTimeout = const Duration(seconds: 30);
    
    // Idle timeout
    client.idleTimeout = const Duration(seconds: 60);
    
    // User agent
    client.userAgent = _userAgent;
  }
  
  /// Sanitize URL to prevent injection attacks
  static String sanitizeUrl(String url) {
    // Remove potentially dangerous characters
    final sanitized = url
        .replaceAll('<', '')
        .replaceAll('>', '')
        .replaceAll('"', '')
        .replaceAll("'", '')
        .replaceAll('\\', '')
        .replaceAll(RegExp(r'javascript:', caseSensitive: false), '')
        .replaceAll(RegExp(r'data:', caseSensitive: false), '')
        .replaceAll(RegExp(r'vbscript:', caseSensitive: false), '');
    
    // Validate URL format
    try {
      final uri = Uri.parse(sanitized);
      if (!uri.hasScheme || (!uri.scheme.startsWith('http'))) {
        throw FormatException('Invalid URL scheme');
      }
      return sanitized;
    } catch (e) {
      SecureLogger.warning('URL sanitization failed: $url');
      throw ArgumentError('Invalid URL format: $url');
    }
  }
  
  /// Generate secure request ID for tracking
  static String _generateRequestId() {
    final timestamp = DateTime.now().millisecondsSinceEpoch;
    final random = DateTime.now().microsecond;
    return 'req_${timestamp}_$random';
  }
  
  /// Validate API response for security issues
  static void validateApiResponse(String responseBody, Map<String, String> headers) {
    try {
      // Check response size
      if (responseBody.length > 10 * 1024 * 1024) { // 10MB limit
        SecureLogger.warning('Large response received: ${responseBody.length} bytes');
      }
      
      // Check for suspicious content
      if (responseBody.contains('<script') || 
          responseBody.contains('javascript:') ||
          responseBody.contains('data:text/html')) {
        SecureLogger.warning('Suspicious content detected in API response');
      }
      
      // Validate headers
      final validation = validateResponseHeaders(headers);
      if (!validation.isSecure) {
        SecureLogger.warning('Security issues in response headers: ${validation.issues}');
      }
      
      if (validation.warnings.isNotEmpty) {
        SecureLogger.info('Response header warnings: ${validation.warnings}');
      }
      
    } catch (e) {
      SecureLogger.error('Error validating API response: $e');
    }
  }
  
  /// Get CORS headers for development
  static Map<String, String> getCorsHeaders() {
    if (EnvironmentConfig.isProduction) {
      return {}; // No CORS headers in production
    }
    
    return {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
      'Access-Control-Allow-Headers': 'Content-Type, Authorization, X-Requested-With',
      'Access-Control-Max-Age': '86400',
    };
  }
  
  /// Rate limiting check
  static bool checkRateLimit(String endpoint, String clientId) {
    // This would typically integrate with a rate limiting service
    // For now, we'll implement a simple in-memory rate limiter
    
    final key = '${clientId}_$endpoint';
    final now = DateTime.now();
    
    // Clean up old entries (older than 1 minute)
    _rateLimitHistory.removeWhere((k, timestamps) {
      timestamps.removeWhere((timestamp) => 
          now.difference(timestamp).inMinutes >= 1);
      return timestamps.isEmpty;
    });
    
    // Check current rate
    final timestamps = _rateLimitHistory[key] ?? [];
    if (timestamps.length >= EnvironmentConfig.apiRateLimitPerMinute) {
      SecureLogger.warning('Rate limit exceeded for $clientId on $endpoint');
      return false;
    }
    
    // Record this request
    timestamps.add(now);
    _rateLimitHistory[key] = timestamps;
    
    return true;
  }
  
  // Rate limiting storage
  static final Map<String, List<DateTime>> _rateLimitHistory = {};
}

/// Security validation result
class SecurityValidationResult {
  final bool isSecure;
  final List<String> issues;
  final List<String> warnings;
  
  const SecurityValidationResult({
    required this.isSecure,
    required this.issues,
    required this.warnings,
  });
}

/// HTTP security interceptor for common security checks
class SecurityInterceptor {
  /// Intercept and validate outgoing requests
  static Map<String, String> interceptRequest(String url, Map<String, String> headers) {
    // Sanitize URL
    final sanitizedUrl = SecurityHeadersService.sanitizeUrl(url);
    
    // Add security headers
    final secureHeaders = Map<String, String>.from(headers);
    secureHeaders.addAll(SecurityHeadersService.getSecurityHeaders());
    
    // Log request (without sensitive data)
    SecureLogger.info('API Request: ${_sanitizeUrlForLogging(sanitizedUrl)}');
    
    return secureHeaders;
  }
  
  /// Intercept and validate incoming responses
  static void interceptResponse(String url, int statusCode, String responseBody, Map<String, String> headers) {
    // Validate response
    SecurityHeadersService.validateApiResponse(responseBody, headers);
    
    // Log response (without sensitive data)
    SecureLogger.info('API Response: ${_sanitizeUrlForLogging(url)} - Status: $statusCode');
    
    // Check for error responses that might leak information
    if (statusCode >= 400) {
      if (responseBody.contains('stack trace') || 
          responseBody.contains('internal server error') ||
          responseBody.contains('database error')) {
        SecureLogger.warning('Potentially sensitive error information in response');
      }
    }
  }
  
  static String _sanitizeUrlForLogging(String url) {
    // Remove query parameters that might contain sensitive data
    final uri = Uri.parse(url);
    return '${uri.scheme}://${uri.host}${uri.path}';
  }
}
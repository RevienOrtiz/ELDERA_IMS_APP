import 'dart:convert';
import 'package:http/http.dart' as http;
import '../utils/secure_logger.dart';
import '../config/environment_config.dart';

/// API Service for connecting to localhost XAMPP server
class ApiService {
  /// Base URL for the localhost API
  static String get baseUrl => '${EnvironmentConfig.apiBaseUrl}/api';

  /// Headers for API requests
  static Map<String, String> _headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  };

  /// Set authorization token
  static void setAuthToken(String token) {
    _headers['Authorization'] = 'Bearer $token';
  }

  /// GET request
  static Future<Map<String, dynamic>> get(String endpoint) async {
    try {
      final response = await http
          .get(
            Uri.parse('$baseUrl/$endpoint'),
            headers: _headers,
          )
          .timeout(const Duration(seconds: 5));

      return _handleResponse(response);
    } catch (e) {
      SecureLogger.error('GET request failed: $e, uri=$baseUrl/$endpoint');
      return {'success': false, 'message': 'Server unavailable', 'data': null};
    }
  }

  /// POST request
  static Future<Map<String, dynamic>> post(
      String endpoint, Map<String, dynamic> data) async {
    try {
      final response = await http
          .post(
            Uri.parse('$baseUrl/$endpoint'),
            headers: _headers,
            body: jsonEncode(data),
          )
          .timeout(const Duration(seconds: 5));

      return _handleResponse(response);
    } catch (e) {
      SecureLogger.error('POST request failed: $e, uri=$baseUrl/$endpoint');
      return {'success': false, 'message': 'Server unavailable', 'data': null};
    }
  }

  /// PUT request
  static Future<Map<String, dynamic>> put(
      String endpoint, Map<String, dynamic> data) async {
    try {
      final response = await http
          .put(
            Uri.parse('$baseUrl/$endpoint'),
            headers: _headers,
            body: jsonEncode(data),
          )
          .timeout(const Duration(seconds: 5));

      return _handleResponse(response);
    } catch (e) {
      SecureLogger.error('PUT request failed: $e, uri=$baseUrl/$endpoint');
      return {'success': false, 'message': 'Server unavailable', 'data': null};
    }
  }

  /// DELETE request
  static Future<Map<String, dynamic>> delete(String endpoint) async {
    try {
      final response = await http
          .delete(
            Uri.parse('$baseUrl/$endpoint'),
            headers: _headers,
          )
          .timeout(const Duration(seconds: 5));

      return _handleResponse(response);
    } catch (e) {
      SecureLogger.error('DELETE request failed: $e, uri=$baseUrl/$endpoint');
      return {'success': false, 'message': 'Server unavailable', 'data': null};
    }
  }

  /// Handle API response
  static Map<String, dynamic> _handleResponse(http.Response response) {
    if (response.statusCode >= 200 && response.statusCode < 300) {
      try {
        return jsonDecode(response.body);
      } catch (e) {
        return {'success': true, 'data': response.body};
      }
    } else {
      SecureLogger.error(
          'API error: ${response.statusCode} - ${response.body}');
      return {
        'success': false,
        'status': response.statusCode,
        'message': response.body,
        'data': null
      };
    }
  }
}

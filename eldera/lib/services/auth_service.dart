import 'dart:async';
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/user.dart' as app_user;
import '../utils/secure_logger.dart';
import '../services/secure_storage_service.dart';
import '../config/environment_config.dart';

/// Authentication service for the Eldera app
class AuthService {
  static bool _isAuthenticated = false;
  static app_user.User? _currentUser;
  static final String _baseUrl = EnvironmentConfig.apiBaseUrl;

  /// Sign in with OSCA ID and password
  static Future<Map<String, dynamic>> signIn(
      {required String oscaId, required String password}) async {
    try {
      // Enhanced logging for debugging
      SecureLogger.info('===== LOGIN ATTEMPT DEBUG =====');
      SecureLogger.info('Attempting login with OSCA ID: "$oscaId"');
      SecureLogger.info('Password length: ${password.length}');
      SecureLogger.info('API URL: $_baseUrl/api/senior/direct-login');
      
      // Prepare request body
      final requestBody = {
        'osca_id': oscaId,
        'password': password,
      };
      
      SecureLogger.info('Request body: ${json.encode(requestBody)}');
      
      // REAL API CALL
      final response = await http.post(
        Uri.parse('$_baseUrl/api/senior/direct-login'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode(requestBody),
      );

      // Log the response
      SecureLogger.info('Response status code: ${response.statusCode}');
      SecureLogger.info('Response body: ${response.body}');
      
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        SecureLogger.info('Login successful, received token and user data');
        
        // Store token securely
        await SecureStorageService.storeAuthToken(data['access_token'],
            expiresIn: Duration(days: 30));
        SecureLogger.info('Token stored securely');

        // Set authentication state
        _isAuthenticated = true;

        // Create user object from response
        try {
          _currentUser = app_user.User(
            id: data['user']['id'].toString(),
            name: data['user']['name'],
            age: 0, // Will be updated when profile is fetched
            phoneNumber: '', // Will be updated when profile is fetched
            idStatus: 'Senior Citizen',
          );
          SecureLogger.info('Created user object: ${_currentUser?.name}');
        } catch (e) {
          SecureLogger.error('Error creating user object: $e');
          // Continue with login even if user object creation fails
        }

        // Now that login is working, fetch the user profile
        try {
          SecureLogger.info('Fetching user profile...');
          await _fetchUserProfile();
          SecureLogger.info('Profile fetched successfully');
        } catch (e) {
          // Continue even if profile fetch fails
          SecureLogger.error('Error fetching profile: $e');
        }

        return {
          'success': true,
          'message': 'Login successful',
          'user': _currentUser,
        };
      } else {
        SecureLogger.error('Login failed with status code: ${response.statusCode}');
        try {
          final data = json.decode(response.body);
          SecureLogger.error('Error message: ${data['message'] ?? 'No error message'}');
          return {
            'success': false,
            'message': data['message'] ?? 'Authentication failed',
          };
        } catch (e) {
          SecureLogger.error('Could not parse error response: $e');
          return {
            'success': false,
            'message': 'Authentication failed: Could not parse server response',
          };
        }
      }
    } catch (e) {
      SecureLogger.error('Authentication error: $e');
      return {
        'success': false,
        'message': 'Authentication failed: ${e.toString()}',
      };
    }
  }

  /// Fetch user profile data after login
  static Future<void> _fetchUserProfile() async {
    try {
      final token = await SecureStorageService.getAuthToken();
      if (token == null) {
        SecureLogger.error('No authentication token found for profile fetch');
        return; // Continue with basic user info instead of throwing exception
      }

      final response = await http.get(
        Uri.parse('$_baseUrl/api/senior/profile'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final profileData = data['data'];

        try {
          // Calculate age from date of birth
          final DateTime dob = DateTime.parse(profileData['date_of_birth']);
          final int age = DateTime.now().difference(dob).inDays ~/ 365;

          // Update current user with complete profile data
          _currentUser = app_user.User(
            id: _currentUser!.id,
            name: profileData['name'],
            age: age,
            phoneNumber: profileData['contact_number'] ?? '',
            idStatus: 'Senior Citizen',
            birthDate: profileData['date_of_birth'],
            address:
                '${profileData['address']['barangay'] ?? ''}, ${profileData['address']['city'] ?? ''}, ${profileData['address']['province'] ?? ''}'.trim(),
            profileImageUrl: profileData['photo_path'],
          );
        } catch (parseError) {
          SecureLogger.error('Error parsing profile data: $parseError');
          // Keep existing user data if parsing fails
        }
      } else {
        SecureLogger.error('Profile fetch failed with status: ${response.statusCode}');
        // Continue with basic user info
      }
    } catch (e) {
      SecureLogger.error('Error fetching user profile: $e');
      // Continue with basic user info
    }
  }

  /// Sign out the current user
  static Future<Map<String, dynamic>> signOut() async {
    try {
      final token = await SecureStorageService.getAuthToken();

      if (token != null) {
        // Call logout API
        await http.post(
          Uri.parse('$_baseUrl/api/senior/logout'),
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer $token',
          },
        );
      }

      // Clear token and reset state
      await SecureStorageService.clearAuthToken();
      _isAuthenticated = false;
      _currentUser = null;

      return {
        'success': true,
      };
    } catch (e) {
      SecureLogger.error('Sign out error: $e');
      return {
        'success': false,
        'message': 'Sign out failed',
      };
    }
  }

  /// Get the current authenticated user
  static Future<app_user.User?> getCurrentUser() async {
    if (_currentUser == null) {
      final token = await SecureStorageService.getAuthToken();
      if (token != null) {
        _isAuthenticated = true;
        await _fetchUserProfile();
      }
    }
    return _currentUser;
  }

  /// Check if a user is currently authenticated
  static bool isAuthenticated() {
    return _isAuthenticated;
  }
}
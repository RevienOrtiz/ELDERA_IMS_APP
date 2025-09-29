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
      // Log the request for debugging
      SecureLogger.info('Attempting login with OSCA ID: $oscaId');
      
      final response = await http.post(
        Uri.parse('$_baseUrl/api/senior/login'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode({
          'osca_id': oscaId,
          'password': password,
        }),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        
        // Store token securely
        await SecureStorageService.storeAuthToken(
          data['access_token'], 
          expiresIn: Duration(days: 30)
        );
        
        // Set authentication state
        _isAuthenticated = true;
        
        // Create user object from response
        _currentUser = app_user.User(
          id: data['user']['id'].toString(),
          name: data['user']['name'],
          age: 0, // Will be updated when profile is fetched
          phoneNumber: '', // Will be updated when profile is fetched
          idStatus: 'Senior Citizen',
        );
        
        // Fetch complete profile
        await _fetchUserProfile();

        return {
          'success': true,
          'user': _currentUser,
        };
      } else {
        final data = json.decode(response.body);
        return {
          'success': false,
          'message': data['message'] ?? 'Authentication failed',
        };
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
        throw Exception('No authentication token found');
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
          address: '${profileData['address']['barangay']}, ${profileData['address']['city']}, ${profileData['address']['province']}',
          profileImageUrl: profileData['photo_path'],
        );
      }
    } catch (e) {
      SecureLogger.error('Error fetching user profile: $e');
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

  /// Register a new user account
  static Future<Map<String, dynamic>> register({
    required String oscaId,
    required String firstName,
    required String lastName,
    required String email,
    required String password,
  }) async {
    try {
      // Log the request for debugging
      SecureLogger.info('Attempting registration with OSCA ID: $oscaId');
      
      final response = await http.post(
        Uri.parse('$_baseUrl/api/senior/register'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode({
          'osca_id': oscaId,
          'first_name': firstName,
          'last_name': lastName,
          'email': email,
          'password': password,
        }),
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = json.decode(response.body);
        
        // Store token securely
        await SecureStorageService.storeAuthToken(
          data['access_token'], 
          expiresIn: Duration(days: 30)
        );
        
        // Set authentication state
        _isAuthenticated = true;
        
        // Create user object from response
        _currentUser = app_user.User(
          id: data['user']['id'].toString(),
          name: '${data['user']['first_name']} ${data['user']['last_name']}',
          age: 0, // Will be updated when profile is fetched
          phoneNumber: '', // Will be updated when profile is fetched
          idStatus: 'Senior Citizen',
        );
        
        // Fetch complete profile
        await _fetchUserProfile();

        return {
          'success': true,
          'user': _currentUser,
        };
      } else {
        final data = json.decode(response.body);
        return {
          'success': false,
          'message': data['message'] ?? 'Registration failed',
        };
      }
    } catch (e) {
      SecureLogger.error('Registration error: $e');
      return {
        'success': false,
        'message': 'Registration failed: ${e.toString()}',
      };
    }
  }
}
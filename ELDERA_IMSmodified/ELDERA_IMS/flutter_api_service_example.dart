import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

/// API Service for ELDERA_IMS Flutter App
/// 
/// This class handles all API communication with the Laravel backend
class ElderaApiService {
  // Base URL for API requests
  final String baseUrl = 'http://your-domain.com/api';
  
  // Singleton pattern
  static final ElderaApiService _instance = ElderaApiService._internal();
  
  factory ElderaApiService() {
    return _instance;
  }
  
  ElderaApiService._internal();
  
  // Get stored auth token
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }
  
  // Save auth token
  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }
  
  // Clear auth token (logout)
  Future<void> clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }
  
  // Get headers with auth token
  Future<Map<String, String>> getHeaders({bool withAuth = true}) async {
    Map<String, String> headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    
    if (withAuth) {
      final token = await getToken();
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }
    
    return headers;
  }
  
  // Login user
  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: await getHeaders(withAuth: false),
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );
      
      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        // Save token on successful login
        await saveToken(data['access_token']);
        return {'success': true, 'data': data};
      } else {
        return {'success': false, 'message': data['message'] ?? 'Login failed'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: ${e.toString()}'};
    }
  }
  
  // Register user
  Future<Map<String, dynamic>> register(String name, String email, String password, String passwordConfirmation) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/register'),
        headers: await getHeaders(withAuth: false),
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
        }),
      );
      
      final data = jsonDecode(response.body);
      
      if (response.statusCode == 201) {
        // Save token on successful registration
        await saveToken(data['access_token']);
        return {'success': true, 'data': data};
      } else {
        return {'success': false, 'message': data['message'] ?? 'Registration failed', 'errors': data['errors']};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: ${e.toString()}'};
    }
  }
  
  // Logout user
  Future<Map<String, dynamic>> logout() async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/logout'),
        headers: await getHeaders(),
      );
      
      // Clear token regardless of response
      await clearToken();
      
      if (response.statusCode == 200) {
        return {'success': true, 'message': 'Logged out successfully'};
      } else {
        return {'success': true, 'message': 'Logged out'}; // Still consider successful even if API fails
      }
    } catch (e) {
      await clearToken(); // Clear token even if request fails
      return {'success': true, 'message': 'Logged out'};
    }
  }
  
  // Get current user profile
  Future<Map<String, dynamic>> getCurrentUser() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/user'),
        headers: await getHeaders(),
      );
      
      if (response.statusCode == 200) {
        return {'success': true, 'data': jsonDecode(response.body)};
      } else if (response.statusCode == 401) {
        // Token expired or invalid
        await clearToken();
        return {'success': false, 'message': 'Session expired. Please login again.'};
      } else {
        return {'success': false, 'message': 'Failed to get user profile'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: ${e.toString()}'};
    }
  }
  
  // Get all seniors
  Future<Map<String, dynamic>> getSeniors() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/seniors'),
        headers: await getHeaders(),
      );
      
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return {'success': true, 'data': data['data']};
      } else if (response.statusCode == 401) {
        await clearToken();
        return {'success': false, 'message': 'Session expired. Please login again.'};
      } else {
        return {'success': false, 'message': 'Failed to get seniors list'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: ${e.toString()}'};
    }
  }
  
  // Get senior details
  Future<Map<String, dynamic>> getSeniorDetails(int id) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/seniors/$id'),
        headers: await getHeaders(),
      );
      
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return {'success': true, 'data': data['data']};
      } else if (response.statusCode == 401) {
        await clearToken();
        return {'success': false, 'message': 'Session expired. Please login again.'};
      } else {
        return {'success': false, 'message': 'Failed to get senior details'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: ${e.toString()}'};
    }
  }
  
  // Get all events
  Future<Map<String, dynamic>> getEvents() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/events'),
        headers: await getHeaders(),
      );
      
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return {'success': true, 'data': data['data']};
      } else if (response.statusCode == 401) {
        await clearToken();
        return {'success': false, 'message': 'Session expired. Please login again.'};
      } else {
        return {'success': false, 'message': 'Failed to get events list'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: ${e.toString()}'};
    }
  }
  
  // Get event details
  Future<Map<String, dynamic>> getEventDetails(int id) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/events/$id'),
        headers: await getHeaders(),
      );
      
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return {'success': true, 'data': data['data']};
      } else if (response.statusCode == 401) {
        await clearToken();
        return {'success': false, 'message': 'Session expired. Please login again.'};
      } else {
        return {'success': false, 'message': 'Failed to get event details'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: ${e.toString()}'};
    }
  }
  
  // Submit ID application with files
  Future<Map<String, dynamic>> submitIdApplication({
    required String fullName,
    required String birthdate,
    required String address,
    required String contactNumber,
    required File photo,
    required List<File> idDocuments,
  }) async {
    try {
      final token = await getToken();
      if (token == null) {
        return {'success': false, 'message': 'Not authenticated'};
      }
      
      var request = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/applications/id'),
      );
      
      // Add auth header
      request.headers['Authorization'] = 'Bearer $token';
      
      // Add text fields
      request.fields['full_name'] = fullName;
      request.fields['birthdate'] = birthdate;
      request.fields['address'] = address;
      request.fields['contact_number'] = contactNumber;
      
      // Add photo
      request.files.add(await http.MultipartFile.fromPath(
        'photo',
        photo.path,
      ));
      
      // Add ID documents
      for (var i = 0; i < idDocuments.length; i++) {
        request.files.add(await http.MultipartFile.fromPath(
          'id_documents[$i]',
          idDocuments[i].path,
        ));
      }
      
      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);
      
      if (response.statusCode == 201) {
        return {'success': true, 'data': jsonDecode(response.body)};
      } else if (response.statusCode == 401) {
        await clearToken();
        return {'success': false, 'message': 'Session expired. Please login again.'};
      } else {
        final data = jsonDecode(response.body);
        return {'success': false, 'message': data['message'] ?? 'Application submission failed', 'errors': data['errors']};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: ${e.toString()}'};
    }
  }
  
  // Check application status
  Future<Map<String, dynamic>> checkApplicationStatus(int id) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/applications/status/$id'),
        headers: await getHeaders(),
      );
      
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return {'success': true, 'data': data['data']};
      } else if (response.statusCode == 401) {
        await clearToken();
        return {'success': false, 'message': 'Session expired. Please login again.'};
      } else {
        return {'success': false, 'message': 'Failed to get application status'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Network error: ${e.toString()}'};
    }
  }
}
import 'dart:typed_data';
import '../models/user.dart';
import '../utils/secure_logger.dart';
import 'api_service.dart';

/// User service for the Eldera app
class UserService {
  /// Get the current user profile
  static Future<User?> getCurrentUser() async {
    try {
      // Fetch user from localhost API
      final response = await ApiService.get('user/profile');

      if (response['success'] == true && response['data'] != null) {
        return User.fromJson(response['data']);
      }
      return null;
    } catch (e) {
      SecureLogger.error('Error fetching user profile: $e');
      return null;
    }
  }

  /// Update user profile
  static Future<Map<String, dynamic>> updateUserProfile(
      {required String userId,
      bool? isDswdPensionBeneficiary,
      String? name,
      String? phoneNumber,
      String? address}) async {
    try {
      // Update user profile via localhost API
      final data = {
        'user_id': userId,
        if (isDswdPensionBeneficiary != null)
          'is_dswd_pension_beneficiary': isDswdPensionBeneficiary,
        if (name != null) 'name': name,
        if (phoneNumber != null) 'phone_number': phoneNumber,
        if (address != null) 'address': address,
      };

      return await ApiService.put('user/profile', data);
    } catch (e) {
      SecureLogger.error('Error updating user profile: $e');
      return {
        'success': false,
        'message': 'Failed to update profile',
      };
    }
  }

  /// Update profile image
  static Future<Map<String, dynamic>> updateProfileImage(
      {required String userId,
      required Uint8List imageBytes,
      required String fileName}) async {
    try {
      // Simulate successful image update
      return {
        'success': true,
        'imageUrl': 'https://example.com/profile.jpg',
      };
    } catch (e) {
      SecureLogger.error('Error updating profile image: $e');
      return {
        'success': false,
        'message': 'Failed to update profile image',
      };
    }
  }

  /// Download profile image
  static Future<Map<String, dynamic>> downloadProfileImage(
      {required String userId, required String imageUrl}) async {
    try {
      // Return mock image data
      return {
        'success': true,
        'imageData': null, // Would contain actual image data
      };
    } catch (e) {
      SecureLogger.error('Error downloading profile image: $e');
      return {
        'success': false,
        'message': 'Failed to download profile image',
      };
    }
  }
}
import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import '../models/attendance.dart';
import '../utils/secure_logger.dart';
import 'secure_storage_service.dart';

/// Attendance service for fetching user attendance records from IMS API
/// This service connects to the IMS system where admins can toggle attendance status
class AttendanceService {
  static const String _baseUrl =
      'https://your-ims-api.com/api'; // Replace with actual IMS API URL
  static const Duration _timeout = Duration(seconds: 30);

  /// Get attendance records for a specific user
  static Future<List<Attendance>> getUserAttendance(String userId) async {
    try {
      final token = await _getAuthToken();
      if (token == null) {
        throw Exception('Authentication token not found');
      }

      final response = await http.get(
        Uri.parse('$_baseUrl/attendance/user/$userId'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      ).timeout(_timeout);

      if (response.statusCode == 200) {
        final List<dynamic> jsonData = json.decode(response.body);
        return jsonData.map((json) => Attendance.fromJson(json)).toList();
      } else {
        throw HttpException(
            'Failed to fetch attendance: ${response.statusCode}');
      }
    } catch (e) {
      print('Error fetching user attendance: $e');
      return [];
    }
  }

  /// Get attendance records for a specific event
  static Future<List<Attendance>> getEventAttendance(String eventId) async {
    try {
      final token = await _getAuthToken();
      if (token == null) {
        throw Exception('Authentication token not found');
      }

      final response = await http.get(
        Uri.parse('$_baseUrl/attendance/event/$eventId'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      ).timeout(_timeout);

      if (response.statusCode == 200) {
        final List<dynamic> jsonData = json.decode(response.body);
        return jsonData.map((json) => Attendance.fromJson(json)).toList();
      } else {
        throw HttpException(
            'Failed to fetch event attendance: ${response.statusCode}');
      }
    } catch (e) {
      print('Error fetching event attendance: $e');
      return [];
    }
  }

  /// Get attendance records for a user within a date range
  static Future<List<Attendance>> getUserAttendanceByDateRange({
    required String userId,
    required DateTime startDate,
    required DateTime endDate,
  }) async {
    try {
      final token = await _getAuthToken();
      if (token == null) {
        throw Exception('Authentication token not found');
      }

      final queryParams = {
        'start_date': startDate.toIso8601String().split('T')[0],
        'end_date': endDate.toIso8601String().split('T')[0],
      };

      final uri = Uri.parse('$_baseUrl/attendance/user/$userId/range')
          .replace(queryParameters: queryParams);

      final response = await http.get(
        uri,
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      ).timeout(_timeout);

      if (response.statusCode == 200) {
        final List<dynamic> jsonData = json.decode(response.body);
        return jsonData.map((json) => Attendance.fromJson(json)).toList();
      } else {
        throw HttpException(
            'Failed to fetch attendance by date range: ${response.statusCode}');
      }
    } catch (e) {
      print('Error fetching attendance by date range: $e');
      return [];
    }
  }

  /// Get attendance statistics for a user
  static Future<Map<String, int>> getUserAttendanceStats(String userId) async {
    try {
      final attendanceRecords = await getUserAttendance(userId);

      int attended = 0;
      int missed = 0;

      for (final record in attendanceRecords) {
        if (record.isAttended) {
          attended++;
        } else {
          missed++;
        }
      }

      return {
        'attended': attended,
        'missed': missed,
        'total': attended + missed,
      };
    } catch (e) {
      print('Error calculating attendance stats: $e');
      return {
        'attended': 0,
        'missed': 0,
        'total': 0,
      };
    }
  }

  /// Get authentication token from secure storage
  static Future<String?> _getAuthToken() async {
    try {
      return await SecureStorageService.getAuthToken();
    } catch (e) {
      print('Error getting auth token: $e');
      return null;
    }
  }

  /// Check if service is available
  static Future<bool> isServiceAvailable() async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl/health'),
        headers: {'Content-Type': 'application/json'},
      ).timeout(const Duration(seconds: 5));

      return response.statusCode == 200;
    } catch (e) {
      print('Attendance service not available: $e');
      return false;
    }
  }
}

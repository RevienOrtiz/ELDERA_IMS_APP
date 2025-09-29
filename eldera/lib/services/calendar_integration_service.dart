import 'package:device_calendar/device_calendar.dart';
import 'package:timezone/timezone.dart' as tz;
import 'package:flutter/foundation.dart';
import '../models/announcement.dart';

/// Service for integrating with the device's native calendar
/// Allows adding health announcements as calendar events
class CalendarIntegrationService {
  static final CalendarIntegrationService _instance = CalendarIntegrationService._internal();
  factory CalendarIntegrationService() => _instance;
  CalendarIntegrationService._internal();

  static CalendarIntegrationService get instance => _instance;

  final DeviceCalendarPlugin _deviceCalendarPlugin = DeviceCalendarPlugin();
  String? _defaultCalendarId;
  bool _isInitialized = false;

  /// Initialize the calendar service
  Future<bool> initialize() async {
    if (_isInitialized) return true;

    try {
      // Check and request permissions
      final permissionsGranted = await _requestPermissions();
      if (!permissionsGranted) {
        debugPrint('Calendar permissions not granted');
        return false;
      }

      // Get the default calendar
      await _getDefaultCalendar();
      _isInitialized = true;
      return true;
    } catch (e) {
      debugPrint('Error initializing calendar service: $e');
      return false;
    }
  }

  /// Request calendar permissions (public method)
  Future<bool> requestCalendarPermissions() async {
    try {
      final permissionsResult = await _deviceCalendarPlugin.requestPermissions();
      return permissionsResult.isSuccess && (permissionsResult.data ?? false);
    } catch (e) {
      debugPrint('Error requesting calendar permissions: $e');
      return false;
    }
  }

  /// Request calendar permissions (private method)
  Future<bool> _requestPermissions() async {
    return await requestCalendarPermissions();
  }

  /// Get the default calendar for adding events
  Future<void> _getDefaultCalendar() async {
    try {
      final calendarsResult = await _deviceCalendarPlugin.retrieveCalendars();
      if (calendarsResult.isSuccess && calendarsResult.data != null) {
        final calendars = calendarsResult.data!;
        
        // Find the primary calendar or first writable calendar
        Calendar? defaultCalendar;
        for (final calendar in calendars) {
          if (calendar.isDefault == true || defaultCalendar == null) {
            if (calendar.isReadOnly == false) {
              defaultCalendar = calendar;
              if (calendar.isDefault == true) break;
            }
          }
        }
        
        _defaultCalendarId = defaultCalendar?.id;
        debugPrint('Default calendar ID: $_defaultCalendarId');
      }
    } catch (e) {
      debugPrint('Error getting default calendar: $e');
    }
  }

  /// Check if calendar integration is available
  Future<bool> isAvailable() async {
    if (!_isInitialized) {
      await initialize();
    }
    return _defaultCalendarId != null;
  }

  /// Add an announcement to the device calendar
  Future<bool> addAnnouncementToCalendar(Announcement announcement) async {
    if (!await isAvailable()) {
      debugPrint('Calendar integration not available');
      return false;
    }

    try {
      final eventDateTime = _parseAnnouncementDateTime(announcement.when);
      if (eventDateTime == null) {
        debugPrint('Could not parse announcement date: ${announcement.when}');
        return false;
      }

      final event = Event(
        _defaultCalendarId!,
        eventId: 'eldera_${announcement.id}',
        title: announcement.what,
        description: '${announcement.what}\n\nDepartment: ${announcement.department}\nCategory: ${announcement.category}',
        start: tz.TZDateTime.from(eventDateTime, tz.local),
        end: tz.TZDateTime.from(eventDateTime.add(const Duration(hours: 1)), tz.local),
        location: announcement.where.isNotEmpty ? announcement.where : null,
      );

      final result = await _deviceCalendarPlugin.createOrUpdateEvent(event);
      if (result?.isSuccess == true) {
        debugPrint('Successfully added event to calendar: ${announcement.what}');
        return true;
      } else {
        debugPrint('Failed to add event to calendar: ${result?.errors}');
        return false;
      }
    } catch (e) {
      debugPrint('Error adding announcement to calendar: $e');
      return false;
    }
  }

  /// Remove an announcement from the device calendar
  Future<bool> removeAnnouncementFromCalendar(String announcementId) async {
    if (!await isAvailable()) {
      return false;
    }

    try {
      final eventId = 'eldera_$announcementId';
      final result = await _deviceCalendarPlugin.deleteEvent(_defaultCalendarId!, eventId);
      return result?.isSuccess == true;
    } catch (e) {
      debugPrint('Error removing announcement from calendar: $e');
      return false;
    }
  }

  /// Parse announcement date string to DateTime
  DateTime? _parseAnnouncementDateTime(String when) {
    try {
      // Handle different date formats
      // Example: "December 25, 2024" or "Dec 25, 2024 at 2:00 PM"
      
      // Remove "at" and time part for now, focus on date
      String datePart = when.split(' at ').first;
      
      // Try to parse common date formats
      final datePattern = RegExp(r'(\w+)\s+(\d+),\s+(\d+)');
      final match = datePattern.firstMatch(datePart);
      
      if (match != null) {
        final monthName = match.group(1)!;
        final day = int.parse(match.group(2)!);
        final year = int.parse(match.group(3)!);
        final month = _getMonthNumber(monthName);
        
        // Default to 9 AM if no time specified
        return DateTime(year, month, day, 9, 0);
      }
    } catch (e) {
      debugPrint('Error parsing date: $when, error: $e');
    }
    return null;
  }

  /// Convert month name to number
  int _getMonthNumber(String monthName) {
    const months = {
      'january': 1, 'jan': 1,
      'february': 2, 'feb': 2,
      'march': 3, 'mar': 3,
      'april': 4, 'apr': 4,
      'may': 5,
      'june': 6, 'jun': 6,
      'july': 7, 'jul': 7,
      'august': 8, 'aug': 8,
      'september': 9, 'sep': 9, 'sept': 9,
      'october': 10, 'oct': 10,
      'november': 11, 'nov': 11,
      'december': 12, 'dec': 12
    };
    return months[monthName.toLowerCase()] ?? 1;
  }

  /// Get list of available calendars
  Future<List<Calendar>> getAvailableCalendars() async {
    try {
      final result = await _deviceCalendarPlugin.retrieveCalendars();
      if (result.isSuccess && result.data != null) {
        return result.data!.where((cal) => cal.isReadOnly == false).toList();
      }
    } catch (e) {
      debugPrint('Error getting available calendars: $e');
    }
    return [];
  }

  /// Set a specific calendar as default
  Future<bool> setDefaultCalendar(String calendarId) async {
    try {
      final calendars = await getAvailableCalendars();
      final calendar = calendars.firstWhere((cal) => cal.id == calendarId);
      if (calendar != null) {
        _defaultCalendarId = calendarId;
        return true;
      }
    } catch (e) {
      debugPrint('Error setting default calendar: $e');
    }
    return false;
  }
}
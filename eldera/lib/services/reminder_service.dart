import 'package:flutter/material.dart';
import '../models/announcement.dart';
import 'local_notification_service.dart';
import 'calendar_integration_service.dart';

class ReminderService {
  static final ReminderService _instance = ReminderService._internal();
  factory ReminderService() => _instance;
  ReminderService._internal();

  // Store reminders in memory (in a real app, this would be persisted)
  final Map<String, Announcement> _reminders = {};

  // Add a reminder for an announcement
  Future<bool> setReminder(Announcement announcement, String reminderType, {DateTime? customTime, bool addToCalendar = false}) async {
    // Setting reminder for announcement
    
    try {
      DateTime? reminderTime;
      DateTime eventTime = parseEventDateTime(announcement.when);
      // Parsed event time

      switch (reminderType) {
        case '1_hour_before':
          reminderTime = eventTime.subtract(const Duration(hours: 1));
          break;
        case '1_day_before':
          reminderTime = eventTime.subtract(const Duration(days: 1));
          break;
        case 'custom':
          reminderTime = customTime;
          break;
        default:
          // Invalid reminder type
          return false;
      }

      // Calculated reminder time
      
      if (reminderTime == null) {
        // Reminder time calculation failed
        return false;
      }
      
      if (reminderTime.isBefore(DateTime.now())) {
        // Cannot set reminder in the past
        return false;
      }
      
      if (reminderTime.isAfter(eventTime)) {
        // Cannot set reminder after the event
        return false;
      }

      // Create updated announcement with reminder info
      final updatedAnnouncement = Announcement(
        id: announcement.id,
        title: announcement.title,
        postedDate: announcement.postedDate,
        what: announcement.what,
        when: announcement.when,
        where: announcement.where,
        category: announcement.category,
        department: announcement.department,
        iconType: announcement.iconType,
        hasReminder: announcement.hasReminder,
        hasListen: announcement.hasListen,
        backgroundColor: announcement.backgroundColor,
        isReminderSet: true,
        reminderTime: reminderTime,
        reminderType: reminderType,
      );

      _reminders[announcement.id] = updatedAnnouncement;
      // Reminder stored in memory
      
      // Schedule the local notification
      // Scheduling notification
      await _scheduleNotification(updatedAnnouncement);
      
      // Add to device calendar if requested
      if (addToCalendar) {
        // Adding event to calendar
        final calendarService = CalendarIntegrationService();
        final calendarSuccess = await calendarService.addAnnouncementToCalendar(updatedAnnouncement);
        if (calendarSuccess) {
          // Event added to calendar
        } else {
          // Failed to add event to calendar
        }
      }
      
      // Reminder set successfully
      return true;
    } catch (e) {
      debugPrint('Error setting reminder: $e');
      return false;
    }
  }

  // Remove a reminder
  Future<bool> removeReminder(String announcementId, {bool removeFromCalendar = false}) async {
    try {
      if (_reminders.containsKey(announcementId)) {
        final announcement = _reminders[announcementId]!;
        _reminders.remove(announcementId);
        
        // Cancel the scheduled notification
        await _cancelNotification(announcementId);
        
        // Remove from device calendar if requested
        if (removeFromCalendar) {
          // Removing event from calendar
          final calendarService = CalendarIntegrationService();
          final calendarSuccess = await calendarService.removeAnnouncementFromCalendar(announcement.id);
          if (calendarSuccess) {
            // Event removed from calendar
          } else {
            // Failed to remove event from calendar
          }
        }
        
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Error removing reminder: $e');
      return false;
    }
  }

  // Get reminder for an announcement
  Announcement? getReminder(String announcementId) {
    return _reminders[announcementId];
  }

  // Check if announcement has reminder set
  bool hasReminder(String announcementId) {
    return _reminders.containsKey(announcementId) && 
           _reminders[announcementId]!.isReminderSet;
  }

  // Get all reminders
  List<Announcement> getAllReminders() {
    return _reminders.values.toList();
  }

  // Get upcoming reminders (next 24 hours)
  List<Announcement> getUpcomingReminders() {
    final now = DateTime.now();
    final tomorrow = now.add(const Duration(days: 1));
    
    return _reminders.values.where((reminder) {
      return reminder.reminderTime != null &&
             reminder.reminderTime!.isAfter(now) &&
             reminder.reminderTime!.isBefore(tomorrow);
    }).toList();
  }

  // Parse event date time from string
  DateTime parseEventDateTime(String whenString) {
    try {
      // Parse formats like "August 26, 2025 at 9:00 AM" or "September 4, 2025 - 3:00 PM to 6:00 PM"
      List<String> parts;
      
      if (whenString.contains(' at ')) {
        parts = whenString.split(' at ');
      } else if (whenString.contains(' - ')) {
        parts = whenString.split(' - ');
        // If there's a time range, take only the start time
        if (parts.length == 2 && parts[1].contains(' to ')) {
          parts[1] = parts[1].split(' to ')[0].trim();
        }
      } else {
        throw FormatException('Invalid date format - missing time separator');
      }
      
      if (parts.length != 2) {
        throw FormatException('Invalid date format');
      }

      final datePart = parts[0].trim();
      final timePart = parts[1].trim();

      // Parse date part (e.g., "August 26, 2025")
      final dateComponents = datePart.split(' ');
      if (dateComponents.length != 3) {
        throw FormatException('Invalid date format');
      }

      final month = _getMonthNumber(dateComponents[0]);
      final day = int.parse(dateComponents[1].replaceAll(',', ''));
      final year = int.parse(dateComponents[2]);

      // Parse time part (e.g., "9:00 AM")
      final timeComponents = timePart.split(' ');
      if (timeComponents.length != 2) {
        throw FormatException('Invalid time format');
      }

      final time = timeComponents[0];
      final ampm = timeComponents[1].toUpperCase();

      final timeNumbers = time.split(':');
      int hour = int.parse(timeNumbers[0]);
      final minute = int.parse(timeNumbers[1]);

      if (ampm == 'PM' && hour != 12) {
        hour += 12;
      } else if (ampm == 'AM' && hour == 12) {
        hour = 0;
      }

      return DateTime(year, month, day, hour, minute);
    } catch (e) {
      // Error parsing date
      // Return a default date if parsing fails
      return DateTime.now().add(const Duration(days: 1));
    }
  }

  // Helper method to get month number from name
  int _getMonthNumber(String monthName) {
    const months = {
      'January': 1, 'February': 2, 'March': 3, 'April': 4,
      'May': 5, 'June': 6, 'July': 7, 'August': 8,
      'September': 9, 'October': 10, 'November': 11, 'December': 12
    };
    return months[monthName] ?? 1;
  }

  // Schedule notification using LocalNotificationService
  Future<void> _scheduleNotification(Announcement announcement) async {
    if (announcement.reminderTime == null) return;
    
    final notificationService = LocalNotificationService();
    final success = await notificationService.scheduleReminderNotification(
      announcementId: announcement.id,
      title: 'Event Reminder: ${announcement.title}',
      body: LocalNotificationService.createNotificationBody(
        announcement, 
        announcement.reminderType ?? 'custom'
      ),
      scheduledTime: announcement.reminderTime!,
    );
    
    if (success) {
      // Notification scheduled
    } else {
      // Failed to schedule notification
    }
  }

  // Cancel notification using LocalNotificationService
  Future<void> _cancelNotification(String announcementId) async {
    final notificationService = LocalNotificationService();
    final success = await notificationService.cancelNotification(announcementId);
    
    if (success) {
      // Notification cancelled
    } else {
      // Failed to cancel notification
    }
  }

  // Get reminder type display text
  static String getReminderTypeText(String? reminderType) {
    switch (reminderType) {
      case '1_hour_before':
        return '1 hour before';
      case '1_day_before':
        return '1 day before';
      case 'custom':
        return 'Custom time';
      default:
        return 'No reminder';
    }
  }

  // Format reminder time for display
  static String formatReminderTime(DateTime? reminderTime) {
    if (reminderTime == null) return '';
    
    final now = DateTime.now();
    final difference = reminderTime.difference(now);
    
    if (difference.inDays > 0) {
      return 'in ${difference.inDays} day${difference.inDays > 1 ? 's' : ''}';
    } else if (difference.inHours > 0) {
      return 'in ${difference.inHours} hour${difference.inHours > 1 ? 's' : ''}';
    } else if (difference.inMinutes > 0) {
      return 'in ${difference.inMinutes} minute${difference.inMinutes > 1 ? 's' : ''}';
    } else {
      return 'now';
    }
  }

  // Format complete reminder info for display
  static String formatCompleteReminderInfo(String? reminderType, DateTime? reminderTime) {
    if (reminderType == null || reminderTime == null) return '';
    
    final typeText = getReminderTypeText(reminderType);
    final timeText = formatReminderTime(reminderTime);
    
    return '$typeText (notification $timeText)';
  }
}
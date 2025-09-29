import 'package:flutter/material.dart';

/**
 * ANNOUNCEMENT MODEL - SUPABASE DATA STRUCTURE
 * 
 * This model represents health announcements and notifications in the Eldera Health app.
 * Updated to work with Supabase backend instead of IMS API.
 * 
 * FIELD REQUIREMENTS:
 * - id: Unique announcement identifier (UUID string, required)
 * - title: Announcement title (string, required, 5-200 characters)
 * - postedDate: Publication date (string, required, format: "YYYY-MM-DD")
 * - what: Description of the announcement (string, required, 10-500 characters)
 * - when: When the event/activity happens (string, required, 10-200 characters)
 * - where: Location information (string, required, 10-200 characters)
 * - category: Announcement category (string, required, e.g., "Health", "Social", "Emergency")
 * - department: Issuing department (string, required, e.g., "Department of Health", "DSWD")
 * - iconType: Icon identifier for mobile UI (string, required)
 * - hasReminder: Whether reminders can be set (boolean, default: true)
 * - hasListen: Whether text-to-speech is available (boolean, default: true)
 * - backgroundColor: UI background color (string, optional, hex format: "#RRGGBB")
 * - isReminderSet: Whether user has set a reminder (boolean, default: false)
 * - reminderTime: When reminder should trigger (ISO8601 datetime, optional)
 * - reminderType: Type of reminder (string, optional: "1_hour_before", "1_day_before", "custom")
 * 
 * JSON MAPPING:
 * - postedDate ↔ posted_date (Supabase uses snake_case)
 * - when ↔ when_event ("when" is a reserved keyword in SQL)
 * - where ↔ where_location ("where" is a reserved keyword in SQL)
 * - backgroundColor ↔ background_color
 * - hasReminder ↔ has_reminder
 * - hasListen ↔ has_listen
 * - isReminderSet ↔ is_reminder_set
 * - reminderTime ↔ reminder_time
 * - reminderType ↔ reminder_type
 * - iconType ↔ icon_type
 * 
 * CATEGORY VALUES (suggested):
 * - "Health": Medical services, screenings, vaccinations
 * - "Social": Community events, social services
 * - "Emergency": Urgent announcements, disaster alerts
 * - "Education": Health education, workshops
 * - "Benefits": Pension, financial assistance information
 * 
 * DEPARTMENT VALUES (examples):
 * - "Department of Health"
 * - "DSWD" (Department of Social Welfare and Development)
 * - "Barangay Health Office"
 * - "Local Government Unit"
 * 
 * FILTERING SUPPORT:
 * Backend should support filtering by:
 * - department (case-insensitive)
 * - category (case-insensitive)
 * - date (exact match or date range)
 * - Sort by posted_date (newest first) by default
 */
class Announcement {
  final String id;
  final String? uuid; // Added for unified schema compatibility
  final String title;
  final String postedDate;
  final String what;
  final String when;
  final String where;
  final String category;
  final String department;
  final String iconType;
  final bool hasReminder;
  final bool hasListen;
  final String? backgroundColor;
  final bool isReminderSet;
  final DateTime? reminderTime;
  final String? reminderType; // '1_hour_before', '1_day_before', 'custom'

  Announcement({
    required this.id,
    this.uuid,
    required this.title,
    required this.postedDate,
    required this.what,
    required this.when,
    required this.where,
    required this.category,
    required this.department,
    required this.iconType,
    this.hasReminder = true,
    this.hasListen = true,
    this.backgroundColor,
    this.isReminderSet = false,
    this.reminderTime,
    this.reminderType,
  });

  factory Announcement.fromJson(Map<String, dynamic> json) {
    return Announcement(
      id: json['id'] ?? '',
      uuid: json['uuid'],
      title: json['title'] ?? '',
      postedDate: json['posted_date'] ?? '',
      what: json['what'] ?? '',
      when: json['when_event'] ?? json['when'] ?? '',
      where: json['where_location'] ?? json['where'] ?? '',
      category: json['category'] ?? 'GENERAL',
      department: json['department'] ?? '',
      iconType: json['icon_type'] ?? 'announcement',
      hasReminder: json['has_reminder'] ?? true,
      hasListen: json['has_listen'] ?? true,
      backgroundColor: json['background_color'],
      isReminderSet: json['is_reminder_set'] ?? false,
      reminderTime: json['reminder_time'] != null
          ? DateTime.parse(json['reminder_time'])
          : null,
      reminderType: json['reminder_type'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'uuid': uuid,
      'title': title,
      'posted_date': postedDate,
      'what': what,
      'when_event': when,
      'where_location': where,
      'category': category,
      'department': department,
      'icon_type': iconType,
      'has_reminder': hasReminder,
      'has_listen': hasListen,
      'background_color': backgroundColor,
      'is_reminder_set': isReminderSet,
      'reminder_time': reminderTime?.toIso8601String(),
      'reminder_type': reminderType,
    };
  }

  // Helper method to get icon based on icon type
  static getIconData(String iconType) {
    switch (iconType.toLowerCase()) {
      case 'health':
      case 'medical':
        return Icons.local_hospital;
      case 'pharmacy':
        return Icons.local_pharmacy;
      case 'fitness':
      case 'exercise':
        return Icons.fitness_center;
      case 'card':
      case 'id':
        return Icons.card_membership;
      case 'heart':
      case 'blood_pressure':
        return Icons.favorite;
      case 'person':
      case 'profile':
        return Icons.account_circle;
      default:
        return Icons.announcement;
    }
  }

  // Helper method to get background color
  static getBackgroundColor(String? colorCode) {
    if (colorCode == null) return const Color(0xFFB8E6B8); // Default green

    try {
      // Handle hex color codes
      if (colorCode.startsWith('#')) {
        return Color(int.parse(colorCode.substring(1), radix: 16) + 0xFF000000);
      }
      // Handle predefined color names
      switch (colorCode.toLowerCase()) {
        case 'blue':
          return const Color(0xFFB8D4E6);
        case 'yellow':
          return const Color(0xFFFFE4B5);
        case 'purple':
          return const Color(0xFFE6E6FA);
        case 'pink':
          return const Color(0xFFFFB6C1);
        case 'green':
        default:
          return const Color(0xFFB8E6B8);
      }
    } catch (e) {
      return const Color(0xFFB8E6B8); // Default green on error
    }
  }
}

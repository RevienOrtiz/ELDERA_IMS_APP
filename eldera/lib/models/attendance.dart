import 'package:flutter/material.dart';

/**
 * ATTENDANCE MODEL - IMS DATA STRUCTURE
 * 
 * This model represents user attendance records for events/announcements in the Eldera Health app.
 * Connected to IMS API where admins can toggle attendance status (attended/missed).
 * 
 * FIELD REQUIREMENTS:
 * - id: Unique attendance record identifier (string, required)
 * - userId: User identifier (string, required)
 * - eventId: Related announcement/event ID (string, required)
 * - eventTitle: Title of the event attended (string, required)
 * - eventDate: Date of the event (string, required, format: "YYYY-MM-DD")
 * - attendanceStatus: Attendance status (string, required: "attended" or "missed")
 * - markedBy: Admin who marked the attendance (string, optional)
 * - markedAt: When attendance was marked (string, optional, ISO8601 datetime)
 * - notes: Additional notes about attendance (string, optional)
 * 
 * JSON MAPPING:
 * - userId ↔ user_id
 * - eventId ↔ event_id
 * - eventTitle ↔ event_title
 * - eventDate ↔ event_date
 * - attendanceStatus ↔ attendance_status
 * - markedBy ↔ marked_by
 * - markedAt ↔ marked_at
 * 
 * ATTENDANCE STATUS VALUES:
 * - "attended": User was present at the event
 * - "missed": User was absent from the event
 */

class Attendance {
  final String id;
  final String? uuid; // Added for unified schema compatibility
  final String userId;
  final String eventId;
  final String eventTitle;
  final String eventDate;
  final String attendanceStatus; // "attended" or "missed"
  final String? markedBy;
  final String? markedAt;
  final String? notes;

  const Attendance({
    required this.id,
    this.uuid,
    required this.userId,
    required this.eventId,
    required this.eventTitle,
    required this.eventDate,
    required this.attendanceStatus,
    this.markedBy,
    this.markedAt,
    this.notes,
  });

  // Factory constructor to create Attendance from JSON
  factory Attendance.fromJson(Map<String, dynamic> json) {
    return Attendance(
      id: json['id']?.toString() ?? '',
      uuid: json['uuid']?.toString(),
      userId: json['user_id']?.toString() ?? '',
      eventId: json['event_id']?.toString() ?? '',
      eventTitle: json['event_title']?.toString() ?? '',
      eventDate: json['event_date']?.toString() ?? '',
      attendanceStatus: json['attendance_status']?.toString() ?? 'missed',
      markedBy: json['marked_by']?.toString(),
      markedAt: json['marked_at']?.toString(),
      notes: json['notes']?.toString(),
    );
  }

  // Convert Attendance to JSON
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'uuid': uuid,
      'user_id': userId,
      'event_id': eventId,
      'event_title': eventTitle,
      'event_date': eventDate,
      'attendance_status': attendanceStatus,
      'marked_by': markedBy,
      'marked_at': markedAt,
      'notes': notes,
    };
  }

  // Helper method to check if user attended
  bool get isAttended => attendanceStatus.toLowerCase() == 'attended';

  // Helper method to check if user missed
  bool get isMissed => attendanceStatus.toLowerCase() == 'missed';

  // Helper method to get formatted event date
  String get formattedEventDate {
    try {
      final date = DateTime.parse(eventDate);
      return '${date.day}/${date.month}/${date.year}';
    } catch (e) {
      return eventDate;
    }
  }

  // Helper method to get attendance status color
  Color get statusColor {
    return isAttended ? Colors.green : Colors.red;
  }

  // Helper method to get attendance status icon
  IconData get statusIcon {
    return isAttended ? Icons.check_circle : Icons.cancel;
  }

  // Copy with method for immutable updates
  Attendance copyWith({
    String? id,
    String? uuid,
    String? userId,
    String? eventId,
    String? eventTitle,
    String? eventDate,
    String? attendanceStatus,
    String? markedBy,
    String? markedAt,
    String? notes,
  }) {
    return Attendance(
      id: id ?? this.id,
      uuid: uuid ?? this.uuid,
      userId: userId ?? this.userId,
      eventId: eventId ?? this.eventId,
      eventTitle: eventTitle ?? this.eventTitle,
      eventDate: eventDate ?? this.eventDate,
      attendanceStatus: attendanceStatus ?? this.attendanceStatus,
      markedBy: markedBy ?? this.markedBy,
      markedAt: markedAt ?? this.markedAt,
      notes: notes ?? this.notes,
    );
  }

  @override
  String toString() {
    return 'Attendance{id: $id, userId: $userId, eventId: $eventId, eventTitle: $eventTitle, eventDate: $eventDate, attendanceStatus: $attendanceStatus}';
  }

  @override
  bool operator ==(Object other) {
    if (identical(this, other)) return true;
    return other is Attendance &&
        other.id == id &&
        other.userId == userId &&
        other.eventId == eventId &&
        other.attendanceStatus == attendanceStatus;
  }

  @override
  int get hashCode {
    return id.hashCode ^
        userId.hashCode ^
        eventId.hashCode ^
        attendanceStatus.hashCode;
  }
}
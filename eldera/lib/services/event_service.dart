import 'package:intl/intl.dart';
import '../models/announcement.dart';
import '../utils/secure_logger.dart';
import 'api_service.dart';

/// Event service to fetch IMS events and adapt them for Home feed
class EventService {
  /// Fetch IMS events and convert them to Announcement cards
  static Future<List<Announcement>> getAllEventsAsAnnouncements() async {
    try {
      final response = await ApiService.get('events');
      if (response['success'] == true) {
        final data = response['data'];

        List<dynamic> items = [];
        if (data is List) {
          items = data;
        } else if (data is Map) {
          final list = data['data'] ?? data['events'];
          if (list is List) items = list;
        }

        return items.map<Announcement>((e) => _eventToAnnouncement(e)).toList();
      }
      return [];
    } catch (e, st) {
      SecureLogger.error('Error fetching IMS events: $e');
      SecureLogger.debug(st.toString());
      return [];
    }
  }

  /// Map IMS event JSON into Announcement used by UI
  static Announcement _eventToAnnouncement(Map<String, dynamic> e) {
    final String id = (e['id'] ?? '').toString();
    final String title = (e['title'] ?? '').toString();
    final String location = (e['location'] ?? e['where'] ?? '').toString();

    final String eventDateStr =
        (e['event_date'] ?? e['date'] ?? e['start']).toString();
    final String startTimeStr = (e['start_time'] ?? e['time'] ?? '').toString();

    final String whenFormatted = _formatWhen(eventDateStr, startTimeStr);

    final String eventType =
        (e['event_type'] ?? e['type'] ?? 'general').toString().toLowerCase();
    final String category = _mapEventTypeToCategory(eventType);
    final String iconType = _mapCategoryToIcon(category);

    final String postedDate =
        (e['created_at'] ?? eventDateStr ?? '').toString();
    final String what =
        (e['description'] ?? e['notes'] ?? 'Municipal event scheduled via IMS')
            .toString();

    return Announcement(
      id: id,
      title: title,
      postedDate: postedDate,
      what: what,
      when: whenFormatted,
      where: location,
      category: category,
      department: (e['department'] ?? 'IMS Events').toString(),
      iconType: iconType,
      hasReminder: true,
      hasListen: true,
    );
  }

  static String _formatWhen(String dateStr, String timeStr) {
    try {
      DateTime? date = DateTime.tryParse(dateStr);
      if (date == null) {
        // Attempt to parse common formats (YYYY-MM-DD, MM/DD/YYYY)
        final cleaned = dateStr.replaceAll('/', '-');
        date = DateTime.tryParse(cleaned);
      }

      DateTime dt;
      if (date != null && timeStr.isNotEmpty) {
        // Normalize HH:mm or HH:mm:ss
        String t = timeStr.trim();
        if (!RegExp(r'^\d{2}:\d{2}(:\d{2})?$').hasMatch(t)) {
          // Fallback: remove non-digits and try to rebuild
          final m = RegExp(r'(\d{1,2}):(\d{2})').firstMatch(timeStr);
          if (m != null) {
            t = '${m.group(1)!.padLeft(2, '0')}:${m.group(2)}';
          } else {
            t = '09:00';
          }
        }
        final parts = t.split(':');
        final h = int.tryParse(parts[0]) ?? 9;
        final m = int.tryParse(parts[1]) ?? 0;
        dt = DateTime(date.year, date.month, date.day, h, m);
      } else {
        dt = date ?? DateTime.now();
      }

      final dateFmt = DateFormat('MMMM d, y');
      final timeFmt = DateFormat('h:mm a');
      if (timeStr.isEmpty) {
        return dateFmt.format(dt);
      }
      return '${dateFmt.format(dt)} at ${timeFmt.format(dt)}';
    } catch (_) {
      return dateStr.isNotEmpty ? dateStr : 'Date to be announced';
    }
  }

  static String _mapEventTypeToCategory(String t) {
    switch (t) {
      case 'health':
        return 'HEALTH';
      case 'pension':
      case 'benefits':
        return 'PENSION';
      case 'general':
      case 'meeting':
      default:
        return 'GENERAL';
    }
  }

  static String _mapCategoryToIcon(String c) {
    switch (c) {
      case 'HEALTH':
        return 'health';
      case 'PENSION':
        return 'card';
      default:
        return 'announcement';
    }
  }
}
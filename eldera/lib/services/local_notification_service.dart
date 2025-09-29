import 'package:flutter/foundation.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:timezone/timezone.dart' as tz;
import 'package:timezone/data/latest.dart' as tz;
import '../models/announcement.dart';

class LocalNotificationService {
  static final LocalNotificationService _instance = LocalNotificationService._internal();
  factory LocalNotificationService() => _instance;
  LocalNotificationService._internal();

  final FlutterLocalNotificationsPlugin _flutterLocalNotificationsPlugin =
      FlutterLocalNotificationsPlugin();

  bool _isInitialized = false;

  /// Initialize the notification service
  Future<bool> initialize() async {
    if (_isInitialized) return true;

    try {
      // Initialize timezone data
      tz.initializeTimeZones();
      
      // Android initialization settings
      const AndroidInitializationSettings initializationSettingsAndroid =
          AndroidInitializationSettings('@mipmap/ic_launcher');

      // iOS initialization settings
      const DarwinInitializationSettings initializationSettingsIOS =
          DarwinInitializationSettings(
        requestAlertPermission: true,
        requestBadgePermission: true,
        requestSoundPermission: true,
      );

      // Combined initialization settings
      const InitializationSettings initializationSettings =
          InitializationSettings(
        android: initializationSettingsAndroid,
        iOS: initializationSettingsIOS,
      );

      // Initialize the plugin
      final bool? result = await _flutterLocalNotificationsPlugin.initialize(
        initializationSettings,
        onDidReceiveNotificationResponse: _onNotificationTapped,
      );

      _isInitialized = result ?? false;
      
      if (_isInitialized) {
        await _requestPermissions();
      }
      
      return _isInitialized;
    } catch (e) {
      debugPrint('Error initializing notifications: $e');
      return false;
    }
  }

  /// Request notification permissions
  Future<bool> _requestPermissions() async {
    try {
      // Request permissions for Android 13+
      final AndroidFlutterLocalNotificationsPlugin? androidImplementation =
          _flutterLocalNotificationsPlugin.resolvePlatformSpecificImplementation<
              AndroidFlutterLocalNotificationsPlugin>();

      if (androidImplementation != null) {
        final bool? granted = await androidImplementation.requestNotificationsPermission();
        return granted ?? false;
      }

      // For iOS, permissions are requested during initialization
      return true;
    } catch (e) {
      debugPrint('Error requesting permissions: $e');
      return false;
    }
  }

  /// Handle notification tap
  void _onNotificationTapped(NotificationResponse notificationResponse) {
    debugPrint('Notification tapped: ${notificationResponse.payload}');
    // Handle notification tap - could navigate to specific screen
    // This could be expanded to parse the payload and navigate accordingly
  }

  /// Schedule a notification for an announcement reminder
  Future<bool> scheduleReminderNotification({
    required String announcementId,
    required String title,
    required String body,
    required DateTime scheduledTime,
  }) async {
    // Scheduling notification for announcement
    
    if (!_isInitialized) {
      // Initialize notification service if needed
      final initialized = await initialize();
      if (!initialized) {
        return false;
      }
    }

    try {
      // Convert DateTime to TZDateTime
      final tz.TZDateTime scheduledTZTime = tz.TZDateTime.from(scheduledTime, tz.local);
      final tz.TZDateTime nowTZ = tz.TZDateTime.now(tz.local);
      
      // Validate scheduled time
      
      // Check if the scheduled time is in the future
      if (scheduledTZTime.isBefore(nowTZ)) {
        // Cannot schedule notification in the past
        return false;
      }

      // Create notification details
      const AndroidNotificationDetails androidPlatformChannelSpecifics =
          AndroidNotificationDetails(
        'reminder_channel',
        'Event Reminders',
        channelDescription: 'Notifications for event reminders',
        importance: Importance.high,
        priority: Priority.high,
        showWhen: true,
        enableVibration: true,
        playSound: true,
      );

      const DarwinNotificationDetails iOSPlatformChannelSpecifics =
          DarwinNotificationDetails(
        presentAlert: true,
        presentBadge: true,
        presentSound: true,
      );

      const NotificationDetails platformChannelSpecifics = NotificationDetails(
        android: androidPlatformChannelSpecifics,
        iOS: iOSPlatformChannelSpecifics,
      );

      // Schedule the notification
      final notificationId = announcementId.hashCode;
      // Using generated notification ID
      
      await _flutterLocalNotificationsPlugin.zonedSchedule(
        notificationId, // Use announcement ID hash as notification ID
        title,
        body,
        scheduledTZTime,
        platformChannelSpecifics,
        androidScheduleMode: AndroidScheduleMode.exactAllowWhileIdle,
        uiLocalNotificationDateInterpretation:
            UILocalNotificationDateInterpretation.absoluteTime,
        payload: announcementId,
      );

      // Notification scheduled successfully
      
      return true;
    } catch (e) {
      debugPrint('Error scheduling notification: $e');
      return false;
    }
  }

  /// Cancel a scheduled notification
  Future<bool> cancelNotification(String announcementId) async {
    if (!_isInitialized) return false;

    try {
      await _flutterLocalNotificationsPlugin.cancel(announcementId.hashCode);
      // Notification cancelled
      return true;
    } catch (e) {
      debugPrint('Error cancelling notification: $e');
      return false;
    }
  }

  /// Cancel all notifications
  Future<bool> cancelAllNotifications() async {
    if (!_isInitialized) return false;

    try {
      await _flutterLocalNotificationsPlugin.cancelAll();
      // All notifications cancelled
      return true;
    } catch (e) {
      debugPrint('Error cancelling all notifications: $e');
      return false;
    }
  }

  /// Get pending notifications
  Future<List<PendingNotificationRequest>> getPendingNotifications() async {
    if (!_isInitialized) return [];

    try {
      return await _flutterLocalNotificationsPlugin.pendingNotificationRequests();
    } catch (e) {
      debugPrint('Error getting pending notifications: $e');
      return [];
    }
  }

  /// Check if notifications are enabled
  Future<bool> areNotificationsEnabled() async {
    if (!_isInitialized) {
      await initialize();
    }

    try {
      final AndroidFlutterLocalNotificationsPlugin? androidImplementation =
          _flutterLocalNotificationsPlugin.resolvePlatformSpecificImplementation<
              AndroidFlutterLocalNotificationsPlugin>();

      if (androidImplementation != null) {
        final bool? enabled = await androidImplementation.areNotificationsEnabled();
        // Checked notification permissions
        return enabled ?? false;
      }
      return true; // Assume enabled for other platforms
    } catch (e) {
      debugPrint('Error checking notification permissions: $e');
      return false;
    }
  }

  /// Request exact alarm permissions (Android 12+)
  Future<bool> requestExactAlarmPermission() async {
    try {
      final AndroidFlutterLocalNotificationsPlugin? androidImplementation =
          _flutterLocalNotificationsPlugin.resolvePlatformSpecificImplementation<
              AndroidFlutterLocalNotificationsPlugin>();

      if (androidImplementation != null) {
        final bool? granted = await androidImplementation.requestExactAlarmsPermission();
        // Checked exact alarm permission
        return granted ?? false;
      }
      return true;
    } catch (e) {
      debugPrint('Error requesting exact alarm permission: $e');
      return false;
    }
  }

  /// Show an immediate notification
  Future<bool> showImmediateNotification({
    required String title,
    required String body,
    String? payload,
  }) async {
    if (!_isInitialized) {
      final initialized = await initialize();
      if (!initialized) return false;
    }

    try {
      const AndroidNotificationDetails androidPlatformChannelSpecifics =
          AndroidNotificationDetails(
        'immediate_channel',
        'Immediate Notifications',
        channelDescription: 'Immediate notifications',
        importance: Importance.high,
        priority: Priority.high,
        showWhen: true,
        enableVibration: true,
        playSound: true,
      );

      const DarwinNotificationDetails iOSPlatformChannelSpecifics =
          DarwinNotificationDetails(
        presentAlert: true,
        presentBadge: true,
        presentSound: true,
      );

      const NotificationDetails platformChannelSpecifics = NotificationDetails(
        android: androidPlatformChannelSpecifics,
        iOS: iOSPlatformChannelSpecifics,
      );

      await _flutterLocalNotificationsPlugin.show(
        DateTime.now().millisecondsSinceEpoch.remainder(100000),
        title,
        body,
        platformChannelSpecifics,
        payload: payload,
      );

      // Immediate notification shown
      return true;
    } catch (e) {
      debugPrint('Error showing immediate notification: $e');
      return false;
    }
  }



  /// Helper method to create notification content from announcement
  static String createNotificationBody(Announcement announcement, String reminderType) {
    final timeText = _getReminderTimeText(reminderType);
    return '${announcement.what} is starting $timeText at ${announcement.where}';
  }

  static String _getReminderTimeText(String reminderType) {
    switch (reminderType) {
      case '1_hour_before':
        return 'in 1 hour';
      case '1_day_before':
        return 'tomorrow';
      case 'custom':
        return 'soon';
      default:
        return 'soon';
    }
  }
}
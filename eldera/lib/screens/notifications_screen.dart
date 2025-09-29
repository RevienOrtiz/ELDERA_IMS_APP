import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/announcement.dart';
import '../services/announcement_service.dart';
import '../services/reminder_service.dart';
import '../services/local_notification_service.dart';
import '../services/font_size_service.dart';
import '../services/language_service.dart';
import '../services/mock_session_service.dart';

class NotificationsScreen extends StatefulWidget {
  const NotificationsScreen({super.key});

  @override
  State<NotificationsScreen> createState() => _NotificationsScreenState();
}

class _NotificationsScreenState extends State<NotificationsScreen> {
  List<Announcement> announcements = [];
  bool isLoading = true;
  String? errorMessage;
  final FontSizeService _fontSizeService = FontSizeService.instance;
  final LanguageService _languageService = LanguageService.instance;
  double _currentFontSize = 20.0;

  @override
  void initState() {
    super.initState();
    _loadFontSize();
    _initializeLanguageService();
    _loadAnnouncements();
  }

  Future<void> _initializeLanguageService() async {
    await _languageService.init();
    setState(() {});
  }

  String _getSafeText(String key) {
    try {
      return _languageService.getText(key);
    } catch (e) {
      return key.toUpperCase();
    }
  }

  Future<void> _loadFontSize() async {
    await _fontSizeService.init();
    setState(() {
      _currentFontSize = _fontSizeService.fontSize;
    });
  }

  double _getSafeScaledFontSize({
    double? baseSize,
    bool isTitle = false,
    bool isSubtitle = false,
  }) {
    // Check if FontSizeService is properly initialized
    if (!_fontSizeService.isInitialized) {
      // Return default font size if service not initialized
      double defaultSize = 20.0;
      double scaleFactor = baseSize ?? 1.0;

      if (isTitle) {
        scaleFactor = 1.2;
      } else if (isSubtitle) {
        scaleFactor = 1.1;
      }

      return defaultSize * scaleFactor;
    }

    return _fontSizeService.getScaledFontSize(
      baseSize: baseSize ?? 1.0,
      isTitle: isTitle,
      isSubtitle: isSubtitle,
    );
  }

  Future<void> _loadAnnouncements() async {
    try {
      setState(() {
        isLoading = true;
        errorMessage = null;
      });

      // Check if we're in mock session mode first
      final mockSession = MockSessionService.instance;
      if (mockSession.isTestMode) {
        // Load mock announcements
        final mockAnnouncements = mockSession.announcements;
        setState(() {
          announcements = mockAnnouncements;
          isLoading = false;
        });
        print(
            '📱 Loaded ${mockAnnouncements.length} mock announcements for notifications screen');
        return;
      }

      final loadedAnnouncements =
          await AnnouncementService.getAllAnnouncements();

      setState(() {
        announcements = loadedAnnouncements;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        errorMessage = 'Failed to load announcements: $e';
        isLoading = false;
      });
    }
  }

  String _getTimeAgo(String postedDate) {
    // Extract time from "Posted X hours/days ago" format
    if (postedDate.contains('hour')) {
      final hours =
          RegExp(r'(\d+)\s+hour').firstMatch(postedDate)?.group(1) ?? '0';
      return '$hours hours ago';
    } else if (postedDate.contains('day')) {
      final days =
          RegExp(r'(\d+)\s+day').firstMatch(postedDate)?.group(1) ?? '0';
      return '$days days ago';
    }
    return postedDate.replaceAll('Posted ', '').trim();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFFFFFF0), // Ivory white background
      appBar: AppBar(
        backgroundColor: const Color(0xFF2E8B8B),
        elevation: 0,
        automaticallyImplyLeading: false,
        title: Text(
          _getSafeText('notifications'),
          style: TextStyle(
            color: Colors.white,
            fontSize: _getSafeScaledFontSize(isTitle: true),
            fontWeight: FontWeight.bold,
          ),
        ),
      ),
      body: _buildBody(),
    );
  }

  Widget _buildBody() {
    if (isLoading) {
      return const Center(
        child: CircularProgressIndicator(
          valueColor: AlwaysStoppedAnimation<Color>(Color(0xFF2E8B8B)),
        ),
      );
    }

    if (errorMessage != null) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(
              Icons.error_outline,
              size: 64,
              color: Color(0xFF2E8B8B),
            ),
            const SizedBox(height: 16),
            Text(
              errorMessage!,
              style: TextStyle(
                fontSize: _getSafeScaledFontSize(isSubtitle: true),
                color: Colors.black87,
              ),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: _loadAnnouncements,
              child: Text(_getSafeText('try_again')),
            ),
          ],
        ),
      );
    }

    if (announcements.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(
              Icons.notifications_none,
              size: 64,
              color: Color(0xFF2E8B8B),
            ),
            const SizedBox(height: 16),
            Text(
              _getSafeText('no_notifications'),
              style: TextStyle(
                fontSize: _getSafeScaledFontSize(isTitle: true),
                fontWeight: FontWeight.bold,
                color: Colors.black87,
              ),
            ),
          ],
        ),
      );
    }

    return RefreshIndicator(
      onRefresh: _loadAnnouncements,
      child: ListView.builder(
        padding: const EdgeInsets.all(16.0),
        itemCount: announcements.length,
        itemBuilder: (context, index) {
          final announcement = announcements[index];
          return _buildNotificationCard(announcement);
        },
      ),
    );
  }

  Widget _buildNotificationCard(Announcement announcement) {
    final timeAgo = _getTimeAgo(announcement.postedDate);

    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.grey.shade200, width: 1),
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.1),
            spreadRadius: 1,
            blurRadius: 3,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Row(
        children: [
          // Icon based on category
          Container(
            width: 50,
            height: 50,
            decoration: BoxDecoration(
              color: Colors.grey.shade100,
              shape: BoxShape.circle,
            ),
            child: Icon(
              Announcement.getIconData(announcement.iconType),
              color: const Color(0xFF2E8B8B),
              size: 24,
            ),
          ),
          const SizedBox(width: 16),
          // Content
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  announcement.department,
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(isSubtitle: true),
                    fontWeight: FontWeight.bold,
                    color: Colors.black87,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  announcement.what,
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(baseSize: 0.7),
                    color: Colors.black54,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  timeAgo,
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(baseSize: 0.6),
                    color: Colors.grey.shade600,
                  ),
                ),
              ],
            ),
          ),
          // VIEW button
          GestureDetector(
            onTap: () {
              _showAnnouncementDetails(announcement);
            },
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              decoration: BoxDecoration(
                color: const Color(0xFF00BFFF), // Bright blue
                borderRadius: BorderRadius.circular(8),
              ),
              child: Text(
                _getSafeText('view'),
                style: TextStyle(
                  color: Colors.white,
                  fontSize: _getSafeScaledFontSize(baseSize: 0.7),
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  void _showAnnouncementDetails(Announcement announcement) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return StatefulBuilder(
          builder: (context, setState) {
            final reminderService = ReminderService();
            final hasReminder = reminderService.hasReminder(announcement.id);
            final reminderInfo = reminderService.getReminder(announcement.id);

            return AlertDialog(
              title: Text(
                announcement.title,
                style: const TextStyle(
                  fontWeight: FontWeight.bold,
                  fontSize: 18,
                ),
              ),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildDetailRow('What:', announcement.what),
                  const SizedBox(height: 8),
                  _buildDetailRow('When:', announcement.when),
                  const SizedBox(height: 8),
                  _buildDetailRow('Where:', announcement.where),
                  const SizedBox(height: 8),
                  _buildDetailRow('Department:', announcement.department),
                  const SizedBox(height: 8),
                  _buildDetailRow('Category:', announcement.category),
                  const SizedBox(height: 16),
                  const Divider(),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      const Icon(Icons.notifications,
                          size: 20, color: Colors.blue),
                      const SizedBox(width: 8),
                      Text(
                        'Reminder:',
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          fontSize: _getSafeScaledFontSize(baseSize: 0.7),
                        ),
                      ),
                      const Spacer(),
                      if (hasReminder)
                        Chip(
                          label: Text(
                            ReminderService.getReminderTypeText(
                                reminderInfo?.reminderType),
                            style: TextStyle(
                                fontSize:
                                    _getSafeScaledFontSize(baseSize: 0.6)),
                          ),
                          backgroundColor: Colors.green.withOpacity(0.1),
                          deleteIcon: const Icon(Icons.close, size: 16),
                          onDeleted: () async {
                            final prefs = await SharedPreferences.getInstance();
                            final calendarSyncEnabled =
                                prefs.getBool('calendar_sync_enabled') ?? false;

                            await reminderService.removeReminder(
                              announcement.id,
                              removeFromCalendar: calendarSyncEnabled,
                            );
                            setState(() {});
                          },
                        )
                      else
                        TextButton.icon(
                          onPressed: () {
                            _showReminderOptions(
                                context, announcement, setState);
                          },
                          icon: const Icon(Icons.add, size: 16),
                          label: const Text('Set Reminder'),
                          style: TextButton.styleFrom(
                            foregroundColor: Colors.blue,
                          ),
                        ),
                    ],
                  ),
                  if (hasReminder && reminderInfo?.reminderTime != null)
                    Padding(
                      padding: const EdgeInsets.only(left: 28, top: 4),
                      child: Text(
                        'Reminder: ${ReminderService.formatCompleteReminderInfo(reminderInfo?.reminderType ?? '', reminderInfo?.reminderTime ?? DateTime.now())}',
                        style: const TextStyle(
                          fontSize: 12,
                          color: Colors.grey,
                        ),
                      ),
                    ),
                ],
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.of(context).pop(),
                  child: const Text('Close'),
                ),
              ],
            );
          },
        );
      },
    );
  }

  void _showReminderOptions(
      BuildContext context, Announcement announcement, StateSetter setState) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Set Reminder'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              ListTile(
                leading: const Icon(Icons.schedule, color: Colors.blue),
                title: const Text('1 hour before'),
                onTap: () async {
                  Navigator.of(context).pop();
                  await _setReminder(announcement, '1_hour_before', setState);
                },
              ),
              ListTile(
                leading: const Icon(Icons.today, color: Colors.green),
                title: const Text('1 day before'),
                onTap: () async {
                  Navigator.of(context).pop();
                  await _setReminder(announcement, '1_day_before', setState);
                },
              ),
              ListTile(
                leading: const Icon(Icons.date_range, color: Colors.orange),
                title: const Text('Custom time'),
                onTap: () async {
                  Navigator.of(context).pop();
                  await _showCustomReminderPicker(announcement, setState);
                },
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text('Cancel'),
            ),
          ],
        );
      },
    );
  }

  Future<void> _setReminder(Announcement announcement, String reminderType,
      StateSetter setState) async {
    final prefs = await SharedPreferences.getInstance();
    final calendarSyncEnabled = prefs.getBool('calendar_sync_enabled') ?? false;

    final reminderService = ReminderService();
    final success = await reminderService.setReminder(
      announcement,
      reminderType,
      addToCalendar: calendarSyncEnabled,
    );

    if (success) {
      setState(() {});
      if (mounted) {
        String message =
            'Reminder set for ${ReminderService.getReminderTypeText(reminderType)}';
        if (calendarSyncEnabled) {
          message += ' and added to calendar';
        }
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(message),
            backgroundColor: Colors.green,
          ),
        );
      }
    } else {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Failed to set reminder. Please try again.'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  Future<void> _showCustomReminderPicker(
      Announcement announcement, StateSetter setState) async {
    final reminderService = ReminderService();
    final eventDateTime = reminderService.parseEventDateTime(announcement.when);

    final DateTime? pickedDate = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime.now(),
      lastDate: eventDateTime.subtract(const Duration(minutes: 1)),
    );

    if (pickedDate != null && mounted) {
      final TimeOfDay? pickedTime = await showTimePicker(
        context: context,
        initialTime: TimeOfDay.now(),
      );

      if (pickedTime != null) {
        var customDateTime = DateTime(
          pickedDate.year,
          pickedDate.month,
          pickedDate.day,
          pickedTime.hour,
          pickedTime.minute,
        );

        // If the selected time is in the past (for today), move it to tomorrow
        if (customDateTime.isBefore(DateTime.now())) {
          customDateTime = customDateTime.add(const Duration(days: 1));
        }

        final prefs = await SharedPreferences.getInstance();
        final calendarSyncEnabled =
            prefs.getBool('calendar_sync_enabled') ?? false;

        final reminderService = ReminderService();
        final success = await reminderService.setReminder(
          announcement,
          'custom',
          customTime: customDateTime,
          addToCalendar: calendarSyncEnabled,
        );

        if (success) {
          setState(() {});
          if (mounted) {
            String message =
                'Custom reminder set for ${DateFormat('MMM d, y h:mm a').format(customDateTime)}';
            if (calendarSyncEnabled) {
              message += ' and added to calendar';
            }
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(message),
                backgroundColor: Colors.green,
              ),
            );
          }
        } else {
          if (mounted) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(
                content: Text(
                    'Failed to set reminder. Please select a time before the event.'),
                backgroundColor: Colors.red,
              ),
            );
          }
        }
      }
    }
  }

  Widget _buildDetailRow(String label, String value) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SizedBox(
          width: 80,
          child: Text(
            label,
            style: TextStyle(
              fontWeight: FontWeight.bold,
              fontSize: _getSafeScaledFontSize(baseSize: 0.8),
            ),
          ),
        ),
        Expanded(
          child: Text(
            value,
            style: TextStyle(
              fontSize: _getSafeScaledFontSize(baseSize: 0.8),
            ),
          ),
        ),
      ],
    );
  }
}

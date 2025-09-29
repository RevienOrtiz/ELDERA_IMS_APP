import 'package:flutter/material.dart';
import 'package:flutter_tts/flutter_tts.dart';
import 'package:intl/intl.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/announcement.dart';
import '../services/announcement_service.dart';
import '../services/reminder_service.dart';
import '../services/font_size_service.dart';
import '../services/local_notification_service.dart';
import '../services/language_service.dart';
import '../services/mock_session_service.dart';
import '../config/app_colors.dart';
import '../utils/contrast_utils.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  String selectedCategory = 'ALL';
  List<String> categories = ['ALL', 'PENSION', 'HEALTH', 'GENERAL'];
  late FlutterTts flutterTts;
  List<Announcement> announcements = [];
  bool isLoading = true;
  String? errorMessage;
  final FontSizeService _fontSizeService = FontSizeService.instance;
  final LanguageService _languageService = LanguageService.instance;
  double _currentFontSize = 20.0;

  @override
  void initState() {
    super.initState();
    _initializeTts();
    _loadFontSize();
    _initializeLanguageService();
    _loadAnnouncements();
  }

  Future<void> _initializeLanguageService() async {
    await _languageService.init();
    setState(() {
      // Update categories with localized text
      categories = [
        _getSafeText('all'),
        _getSafeText('pension'),
        _getSafeText('health'),
        _getSafeText('general')
      ];
      if (selectedCategory == 'ALL') selectedCategory = _getSafeText('all');
    });
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

  void _initializeTts() async {
    flutterTts = FlutterTts();
    await flutterTts.setLanguage("en-US");
    await flutterTts.setSpeechRate(0.5); // Slower speech for elderly
    await flutterTts.setVolume(0.8);
    await flutterTts.setPitch(1.0);
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
            '📱 Loaded ${mockAnnouncements.length} mock announcements for home screen');
        return;
      }

      // Load announcements from IMS API
      final loadedAnnouncements =
          await AnnouncementService.getAllAnnouncements();

      setState(() {
        announcements = loadedAnnouncements;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        errorMessage = e.toString();
        isLoading = false;
      });
    }
  }

  Future<void> _refreshAnnouncements() async {
    await _loadAnnouncements();
  }

  Future<void> _triggerTestNotification() async {
    try {
      final notificationService = LocalNotificationService();
      final success = await notificationService.showImmediateNotification(
        title: 'Test Notification',
        body: 'This is a test notification triggered by the bell button!',
        payload: 'test_notification',
      );

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(success
                ? 'Test notification sent!'
                : 'Failed to send notification'),
            backgroundColor: success ? Colors.green : Colors.red,
            duration: const Duration(seconds: 2),
          ),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Failed to send notification: $e'),
            backgroundColor: Colors.red,
            duration: const Duration(seconds: 3),
          ),
        );
      }
    }
  }

  DateTime _parseAnnouncementDate(String when) {
    try {
      final datePattern = RegExp(r'(\w+)\s+(\d+),\s+(\d+)');
      final match = datePattern.firstMatch(when);
      if (match != null) {
        final monthName = match.group(1)!;
        final day = int.parse(match.group(2)!);
        final year = int.parse(match.group(3)!);
        final month = _getMonthNumber(monthName);
        return DateTime(year, month, day);
      }
    } catch (e) {
      return DateTime.now();
    }
    return DateTime.now();
  }

  int _getMonthNumber(String monthName) {
    const months = {
      'january': 1,
      'february': 2,
      'march': 3,
      'april': 4,
      'may': 5,
      'june': 6,
      'july': 7,
      'august': 8,
      'september': 9,
      'october': 10,
      'november': 11,
      'december': 12
    };
    return months[monthName.toLowerCase()] ?? 1;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFFFFFF0),
      body: Column(
        children: [
          Container(
            width: double.infinity,
            color: const Color(0xFF2E8B8B),
            padding: EdgeInsets.only(
              top: MediaQuery.of(context).padding.top,
            ),
            child: _buildCategoryFilters(),
          ),
          Expanded(
            child: _buildNotificationsList(),
          ),
        ],
      ),
    );
  }

  Color _getCategoryColor(String category) {
    switch (category) {
      case 'HEALTH':
        return const Color(0xFFFFB6C1); // Pink
      case 'PENSION':
        return const Color(0xFFB8D4E6); // Baby blue
      case 'GENERAL':
        return const Color(0xFFB8E6B8); // Green
      default:
        return Colors.grey[300]!; // Default for ALL
    }
  }

  Widget _buildCategoryFilters() {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      child: Row(
        children: [
          // Bell button for test notification
          GestureDetector(
            onTap: _triggerTestNotification,
            child: Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: Colors.orange.shade200,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: Colors.orange.shade400, width: 2),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(
                    Icons.notifications_active,
                    color: Colors.orange.shade800,
                    size: 20,
                  ),
                  const SizedBox(width: 4),
                  Text(
                    'Test',
                    style: TextStyle(
                      fontSize: _getSafeScaledFontSize(baseSize: 0.7),
                      fontWeight: FontWeight.bold,
                      color: Colors.orange.shade800,
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(width: 12),
          // Category filters
          Expanded(
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Row(
                children: categories.map((category) {
                  final isSelected = category == selectedCategory;
                  return Padding(
                    padding: const EdgeInsets.only(right: 12),
                    child: GestureDetector(
                      onTap: () {
                        setState(() {
                          selectedCategory = category;
                        });
                      },
                      child: Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 16, vertical: 8),
                        decoration: BoxDecoration(
                          color: isSelected
                              ? _getCategoryColor(category)
                              : Colors.grey[300],
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: Text(
                          category,
                          style: TextStyle(
                            fontSize: _getSafeScaledFontSize(baseSize: 0.8),
                            fontWeight: FontWeight.w500,
                            color: ContrastUtils.getAccessibleTextColor(
                              isSelected
                                  ? _getCategoryColor(category)
                                  : Colors.grey[300]!,
                              preferDark: true,
                            ),
                          ),
                        ),
                      ),
                    ),
                  );
                }).toList(),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildNotificationsList() {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      child: RefreshIndicator(
        onRefresh: _refreshAnnouncements,
        child: _buildAnnouncementContent(),
      ),
    );
  }

  Widget _buildAnnouncementContent() {
    if (isLoading) {
      return const Center(
        child: Padding(
          padding: EdgeInsets.all(50.0),
          child: CircularProgressIndicator(
            strokeWidth: 3.0,
            valueColor: AlwaysStoppedAnimation<Color>(Color(0xFF4CAF50)),
          ),
        ),
      );
    }

    if (errorMessage != null) {
      return Center(
        child: Padding(
          padding: const EdgeInsets.all(20.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(
                Icons.error_outline,
                size: 64,
                color: Colors.red,
              ),
              const SizedBox(height: 16),
              Text(
                _getSafeText('error_loading'),
                style: TextStyle(
                  fontSize: _getSafeScaledFontSize(isTitle: true),
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                errorMessage!,
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: _getSafeScaledFontSize(baseSize: 0.8),
                  color: AppColors.textSecondaryOnLight,
                ),
              ),
              const SizedBox(height: 16),
              ElevatedButton(
                onPressed: _refreshAnnouncements,
                child: Text(_getSafeText('try_again')),
              ),
            ],
          ),
        ),
      );
    }

    if (announcements.isEmpty) {
      return Center(
        child: Padding(
          padding: const EdgeInsets.all(50.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(
                Icons.inbox_outlined,
                size: 64,
                color: Colors.grey,
              ),
              const SizedBox(height: 16),
              Text(
                _getSafeText('no_announcements'),
                style: TextStyle(
                  fontSize: _getSafeScaledFontSize(isTitle: true),
                  fontWeight: FontWeight.bold,
                  color: Colors.grey,
                ),
              ),
            ],
          ),
        ),
      );
    }

    // Filter announcements by selected category and date (current/future only)
    final now = DateTime.now();
    final today = DateTime(now.year, now.month, now.day);

    List<Announcement> filteredAnnouncements =
        announcements.where((announcement) {
      // Parse announcement date
      final announcementDate = _parseAnnouncementDate(announcement.when);
      final announcementDay = DateTime(
          announcementDate.year, announcementDate.month, announcementDate.day);

      // Only show current and future announcements
      final isCurrentOrFuture = announcementDay.isAtSameMomentAs(today) ||
          announcementDay.isAfter(today);

      // Filter by category
      final matchesCategory = selectedCategory == _getSafeText('all') ||
          announcement.category == selectedCategory ||
          (selectedCategory == _getSafeText('pension') &&
              announcement.category == 'PENSION') ||
          (selectedCategory == _getSafeText('health') &&
              announcement.category == 'HEALTH') ||
          (selectedCategory == _getSafeText('general') &&
              announcement.category == 'GENERAL');

      return isCurrentOrFuture && matchesCategory;
    }).toList();

    // Sort by date (earliest first)
    filteredAnnouncements.sort((a, b) {
      final dateA = _parseAnnouncementDate(a.when);
      final dateB = _parseAnnouncementDate(b.when);
      return dateA.compareTo(dateB);
    });

    return ListView.builder(
      physics: const BouncingScrollPhysics(),
      itemCount: filteredAnnouncements.length,
      itemBuilder: (context, index) {
        final announcement = filteredAnnouncements[index];
        return Column(
          children: [
            _buildAnnouncementCard(announcement),
            if (index < filteredAnnouncements.length - 1)
              const SizedBox(height: 16),
            if (index == filteredAnnouncements.length - 1)
              const SizedBox(height: 100),
          ],
        );
      },
    );
  }

  void _speakCardContent(Announcement announcement) async {
    // Configure TTS settings
    await flutterTts.setLanguage("en-US");
    await flutterTts.setSpeechRate(0.5);
    await flutterTts.setVolume(1.0);
    await flutterTts.setPitch(1.0);

    // Create the text to speak
    String textToSpeak =
        "${announcement.title}. ${announcement.what}. Scheduled for ${announcement.when} at ${announcement.where}.";

    // Speak the content
    await flutterTts.speak(textToSpeak);
  }

  Future<void> _setReminder(
      String reminderType, Announcement announcement) async {
    final prefs = await SharedPreferences.getInstance();
    final calendarSyncEnabled = prefs.getBool('calendar_sync_enabled') ?? false;

    final reminderService = ReminderService();
    final success = await reminderService.setReminder(
      announcement,
      reminderType,
      addToCalendar: calendarSyncEnabled,
    );

    if (success) {
      setState(() {}); // Refresh the UI to show reminder indicator
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

  Widget _buildAnnouncementCard(Announcement announcement) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Announcement.getBackgroundColor(announcement.backgroundColor),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.grey.shade300, width: 1),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                width: 40,
                height: 40,
                decoration: BoxDecoration(
                  color: Colors.green.shade700,
                  shape: BoxShape.circle,
                ),
                child: Icon(
                  Announcement.getIconData(announcement.iconType),
                  color: Colors.white,
                  size: 24,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      announcement.title,
                      style: TextStyle(
                        fontSize: _getSafeScaledFontSize(isTitle: true),
                        fontWeight: FontWeight.w600,
                        color: Colors.black87,
                      ),
                    ),
                    Text(
                      announcement.postedDate,
                      style: TextStyle(
                        fontSize: _getSafeScaledFontSize(baseSize: 0.8),
                        color: Colors.grey[600],
                      ),
                    ),
                  ],
                ),
              ),
              if (announcement.hasListen)
                GestureDetector(
                  onTap: () {
                    _speakCardContent(announcement);
                  },
                  child: Container(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 6, vertical: 4),
                    decoration: BoxDecoration(
                      color: Color(0xFF007bff),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(
                          Icons.volume_up,
                          color: Colors.white,
                          size: _getSafeScaledFontSize(baseSize: 0.9),
                        ),
                        const SizedBox(width: 3),
                        Text(
                          _getSafeText('play'),
                          style: TextStyle(
                            fontSize: _getSafeScaledFontSize(baseSize: 0.8),
                            fontWeight: FontWeight.bold,
                            color: Colors.white,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
            ],
          ),
          const SizedBox(height: 16),
          RichText(
            text: TextSpan(
              children: [
                TextSpan(
                  text: 'WHAT: ',
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(),
                    fontWeight: FontWeight.bold,
                    color: Colors.black87,
                  ),
                ),
                TextSpan(
                  text: announcement.what,
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(),
                    fontWeight: FontWeight.w600,
                    color: Colors.black87,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 8),
          RichText(
            text: TextSpan(
              children: [
                TextSpan(
                  text: 'WHEN: ',
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(),
                    fontWeight: FontWeight.bold,
                    color: Colors.black87,
                  ),
                ),
                TextSpan(
                  text: announcement.when,
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(),
                    fontWeight: FontWeight.w600,
                    color: Colors.black87,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 8),
          RichText(
            text: TextSpan(
              children: [
                TextSpan(
                  text: 'WHERE: ',
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(),
                    fontWeight: FontWeight.bold,
                    color: Colors.black87,
                  ),
                ),
                TextSpan(
                  text: announcement.where,
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(),
                    fontWeight: FontWeight.w600,
                    color: Colors.black87,
                  ),
                ),
              ],
            ),
          ),
          if (announcement.hasReminder) ...[
            const SizedBox(height: 12),
            Align(
              alignment: Alignment.centerRight,
              child: _buildReminderButton(announcement),
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildReminderButton(Announcement announcement) {
    final reminderService = ReminderService();
    final hasReminder = reminderService.hasReminder(announcement.id);
    final reminderInfo = reminderService.getReminder(announcement.id);

    if (hasReminder) {
      // Show reminder status with option to remove
      return GestureDetector(
        onTap: () async {
          final shouldRemove = await showDialog<bool>(
            context: context,
            builder: (context) => AlertDialog(
              title: const Text('Reminder Set'),
              content: Text(
                'You have a reminder set for ${ReminderService.getReminderTypeText(reminderInfo?.reminderType)}. Would you like to remove it?',
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.of(context).pop(false),
                  child: const Text('Keep'),
                ),
                TextButton(
                  onPressed: () => Navigator.of(context).pop(true),
                  child: const Text('Remove'),
                ),
              ],
            ),
          );

          if (shouldRemove == true) {
            final prefs = await SharedPreferences.getInstance();
            final calendarSyncEnabled =
                prefs.getBool('calendar_sync_enabled') ?? false;

            await reminderService.removeReminder(
              announcement.id,
              removeFromCalendar: calendarSyncEnabled,
            );
            setState(() {}); // Refresh UI
            if (mounted) {
              String message = 'Reminder removed';
              if (calendarSyncEnabled) {
                message += ' and removed from calendar';
              }
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text(message),
                  backgroundColor: Colors.orange,
                ),
              );
            }
          }
        },
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
          constraints: const BoxConstraints(
            maxWidth: 120,
          ),
          decoration: BoxDecoration(
            color: Colors.green.shade200,
            borderRadius: BorderRadius.circular(20),
          ),
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Icon(
                Icons.check_circle,
                color: Colors.green,
                size: 14,
              ),
              const SizedBox(width: 4),
              Flexible(
                child: Text(
                  _getSafeText('reminder_set'),
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(baseSize: 0.7),
                    fontWeight: FontWeight.w500,
                    color: Colors.green.shade800,
                  ),
                  overflow: TextOverflow.ellipsis,
                ),
              ),
            ],
          ),
        ),
      );
    } else {
      // Show reminder options using dialog (same as schedule screen)
      return GestureDetector(
        onTap: () {
          _showReminderOptions(context, announcement);
        },
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
          constraints: const BoxConstraints(
            maxWidth: 120,
          ),
          decoration: BoxDecoration(
            color: Colors.purple.shade200,
            borderRadius: BorderRadius.circular(20),
          ),
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Flexible(
                child: Text(
                  _getSafeText('remind_me'),
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(baseSize: 0.8),
                    fontWeight: FontWeight.w500,
                    color: Colors.black87,
                  ),
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              const SizedBox(width: 4),
              const Icon(
                Icons.keyboard_arrow_down,
                color: Colors.black87,
                size: 18,
              ),
            ],
          ),
        ),
      );
    }
  }

  void _showReminderOptions(BuildContext context, Announcement announcement) {
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
                  await _setReminder('1_hour_before', announcement);
                },
              ),
              ListTile(
                leading: const Icon(Icons.today, color: Colors.green),
                title: const Text('1 day before'),
                onTap: () async {
                  Navigator.of(context).pop();
                  await _setReminder('1_day_before', announcement);
                },
              ),
              ListTile(
                leading: const Icon(Icons.date_range, color: Colors.orange),
                title: const Text('Custom time'),
                onTap: () async {
                  Navigator.of(context).pop();
                  await _showCustomReminderPicker(announcement);
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

  Future<void> _showCustomReminderPicker(Announcement announcement) async {
    final reminderService = ReminderService();
    final eventDateTime = reminderService.parseEventDateTime(announcement.when);

    final DateTime? pickedDate = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime.now(),
      lastDate: eventDateTime
          .subtract(const Duration(minutes: 1)), // Must be before event
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
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(
                    'Custom reminder set for ${DateFormat('MMM d, y h:mm a').format(customDateTime)}'),
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
}

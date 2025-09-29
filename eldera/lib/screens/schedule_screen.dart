import 'package:flutter/material.dart';
import 'package:table_calendar/table_calendar.dart';
import 'package:intl/intl.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/announcement.dart';
import '../models/attendance.dart';
import '../services/announcement_service.dart';
import '../services/attendance_service.dart';
import '../services/reminder_service.dart';
import '../services/font_size_service.dart';
import '../services/language_service.dart';
import '../services/auth_service.dart';
import '../services/mock_session_service.dart';

class ScheduleScreen extends StatefulWidget {
  const ScheduleScreen({super.key});

  @override
  State<ScheduleScreen> createState() => _ScheduleScreenState();
}

class _ScheduleScreenState extends State<ScheduleScreen>
    with TickerProviderStateMixin {
  CalendarFormat _calendarFormat = CalendarFormat.month;
  DateTime _focusedDay = DateTime.now();
  DateTime? _selectedDay;
  List<Announcement> _selectedDayAnnouncements = [];
  bool _isLoading = true;
  final FontSizeService _fontSizeService = FontSizeService.instance;
  final LanguageService _languageService = LanguageService.instance;
  final String _selectedFilter = 'CURRENT DATE';
  double _currentFontSize = 20.0;
  List<Announcement> _allAnnouncements = [];

  // Map to store announcements by date for quick lookup
  Map<DateTime, List<Announcement>> _announcementsByDate = {};

  // Tab controller and attendance-related variables
  late TabController _tabController;
  List<Attendance> _userAttendance = [];
  bool _isLoadingAttendance = false;
  Map<String, int> _attendanceStats = {'attended': 0, 'missed': 0, 'total': 0};
  String _attendanceFilter = 'all'; // 'all', 'attended', 'missed'

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
    _selectedDay = _focusedDay;
    _loadFontSize();
    _initializeLanguageService();
    _loadAnnouncements();
    _loadUserAttendance();
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
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

  Widget _buildEventsTab() {
    return _isLoading
        ? const Center(child: CircularProgressIndicator())
        : SingleChildScrollView(
            child: Column(
              children: [
                _buildCalendarSection(),
                _buildEventTypeSection(),
                const SizedBox(height: 16),
                _buildEventsList(),
              ],
            ),
          );
  }

  Widget _buildAttendanceTab() {
    return SingleChildScrollView(
      child: Column(
        children: [
          _buildAttendanceStats(),
          _buildAttendanceFilters(),
          const SizedBox(height: 16),
          _buildAttendanceList(),
        ],
      ),
    );
  }

  Widget _buildAttendanceStats() {
    return Container(
      margin: const EdgeInsets.all(16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.1),
            spreadRadius: 1,
            blurRadius: 5,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            _getSafeText('attendance_summary'),
            style: TextStyle(
              fontSize: _getSafeScaledFontSize(isTitle: true),
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 16),
          Row(
            children: [
              Expanded(
                child: _buildStatCard(
                  _getSafeText('attended'),
                  _attendanceStats['attended'].toString(),
                  Colors.green,
                  Icons.check_circle,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: _buildStatCard(
                  _getSafeText('missed'),
                  _attendanceStats['missed'].toString(),
                  Colors.red,
                  Icons.cancel,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: _buildStatCard(
                  _getSafeText('total'),
                  _attendanceStats['total'].toString(),
                  Colors.blue,
                  Icons.event,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildStatCard(
      String title, String value, Color color, IconData icon) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: color.withOpacity(0.3)),
      ),
      child: Column(
        children: [
          Icon(
            icon,
            color: color,
            size: 24,
          ),
          const SizedBox(height: 8),
          Text(
            value,
            style: TextStyle(
              fontSize: _getSafeScaledFontSize(isTitle: true),
              fontWeight: FontWeight.bold,
              color: color,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            title,
            style: TextStyle(
              fontSize: _getSafeScaledFontSize(baseSize: 0.7),
              color: Colors.grey[600],
            ),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }

  Widget _buildAttendanceFilters() {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        children: [
          Text(
            _getSafeText('filter_by'),
            style: TextStyle(
              fontSize: _getSafeScaledFontSize(baseSize: 0.8),
              fontWeight: FontWeight.w500,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Row(
                children: [
                  _buildFilterChip(_getSafeText('all'), 'all'),
                  const SizedBox(width: 8),
                  _buildFilterChip(_getSafeText('attended'), 'attended'),
                  const SizedBox(width: 8),
                  _buildFilterChip(_getSafeText('missed'), 'missed'),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFilterChip(String label, String value) {
    final isSelected = _attendanceFilter == value;
    final color = value == 'attended'
        ? Colors.green
        : value == 'missed'
            ? Colors.red
            : Colors.blue;

    return GestureDetector(
      onTap: () {
        setState(() {
          _attendanceFilter = value;
        });
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        decoration: BoxDecoration(
          color: isSelected ? color : Colors.transparent,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: color),
        ),
        child: Text(
          label,
          style: TextStyle(
            color: isSelected ? Colors.white : color,
            fontSize: _getSafeScaledFontSize(baseSize: 0.7),
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }

  Widget _buildAttendanceList() {
    if (_isLoadingAttendance) {
      return const Center(child: CircularProgressIndicator());
    }

    final filteredAttendance = _getFilteredAttendance();

    if (filteredAttendance.isEmpty) {
      return Container(
        height: 200,
        child: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(
                Icons.assignment_turned_in_outlined,
                size: 64,
                color: Colors.grey[400],
              ),
              const SizedBox(height: 16),
              Text(
                _getSafeText('no_attendance_records'),
                style: TextStyle(
                  color: Colors.grey[600],
                  fontSize: _getSafeScaledFontSize(baseSize: 0.9),
                ),
              ),
            ],
          ),
        ),
      );
    }

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      child: ListView.builder(
        shrinkWrap: true,
        physics: const NeverScrollableScrollPhysics(),
        itemCount: filteredAttendance.length,
        itemBuilder: (context, index) {
          return _buildAttendanceCard(filteredAttendance[index]);
        },
      ),
    );
  }

  Widget _buildAttendanceCard(Attendance attendance) {
    final isAttended = attendance.isAttended;
    final statusColor = isAttended ? Colors.green : Colors.red;
    final statusIcon = isAttended ? Icons.check_circle : Icons.cancel;

    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: Container(
        decoration: BoxDecoration(
          color: statusColor.withOpacity(0.05),
          borderRadius: BorderRadius.circular(8),
          border: Border.all(
            color: statusColor.withOpacity(0.3),
            width: 1,
          ),
        ),
        child: ListTile(
          leading: Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: statusColor.withOpacity(0.1),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Icon(
              statusIcon,
              color: statusColor,
              size: 24,
            ),
          ),
          title: Text(
            attendance.eventTitle,
            style: TextStyle(
              fontWeight: FontWeight.w600,
              fontSize: _getSafeScaledFontSize(baseSize: 0.85),
            ),
          ),
          subtitle: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 4),
              Text(
                attendance.formattedEventDate,
                style: TextStyle(
                  fontSize: _getSafeScaledFontSize(baseSize: 0.75),
                  color: Colors.grey[600],
                ),
              ),
              if (attendance.notes?.isNotEmpty == true) ...[
                const SizedBox(height: 2),
                Text(
                  attendance.notes!,
                  style: TextStyle(
                    fontSize: _getSafeScaledFontSize(baseSize: 0.7),
                    color: Colors.grey[500],
                    fontStyle: FontStyle.italic,
                  ),
                ),
              ],
            ],
          ),
          trailing: Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(
              color: statusColor,
              borderRadius: BorderRadius.circular(12),
            ),
            child: Text(
              isAttended ? _getSafeText('attended') : _getSafeText('missed'),
              style: TextStyle(
                color: Colors.white,
                fontSize: _getSafeScaledFontSize(baseSize: 0.65),
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ),
      ),
    );
  }

  Future<void> _loadAnnouncements() async {
    try {
      // Check if we're in mock session mode first
      final mockSession = MockSessionService.instance;
      if (mockSession.isTestMode) {
        // Load mock announcements
        final mockAnnouncements = mockSession.announcements;
        setState(() {
          _allAnnouncements = mockAnnouncements;
          _announcementsByDate = _groupAnnouncementsByDate(mockAnnouncements);
          _selectedDayAnnouncements =
              _getFilteredAnnouncementsForDay(_selectedDay ?? _focusedDay);
          _isLoading = false;
        });
        print(
            '📱 Loaded ${mockAnnouncements.length} mock announcements for schedule screen');
        return;
      }

      final announcements = await AnnouncementService.getAllAnnouncements();
      setState(() {
        _allAnnouncements = announcements;
        _announcementsByDate = _groupAnnouncementsByDate(announcements);
        _selectedDayAnnouncements =
            _getFilteredAnnouncementsForDay(_selectedDay ?? _focusedDay);
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error loading events: $e')),
        );
      }
    }
  }

  Map<DateTime, List<Announcement>> _groupAnnouncementsByDate(
      List<Announcement> announcements) {
    final Map<DateTime, List<Announcement>> grouped = {};
    for (final announcement in announcements) {
      final date = _parseAnnouncementDate(announcement.when);
      final dateKey = DateTime(date.year, date.month, date.day);
      if (grouped[dateKey] == null) {
        grouped[dateKey] = [];
      }
      grouped[dateKey]!.add(announcement);
    }
    return grouped;
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

  List<Announcement> _getAnnouncementsForDay(DateTime day) {
    final dateKey = DateTime(day.year, day.month, day.day);
    return _announcementsByDate[dateKey] ?? [];
  }

  List<Announcement> _getFilteredAnnouncementsForDay(DateTime day) {
    final announcements = _getAnnouncementsForDay(day);
    if (_selectedFilter == 'CURRENT DATE') {
      return announcements;
    }
    // For the original categories, we'll just return all announcements
    // since these were more like display labels than actual filters
    return announcements;
  }

  Future<void> _loadUserAttendance() async {
    setState(() {
      _isLoadingAttendance = true;
    });

    try {
      final user = await AuthService.getCurrentUser();
      if (user != null) {
        final attendance = await AttendanceService.getUserAttendance(user.id);
        final stats = await AttendanceService.getUserAttendanceStats(user.id);

        setState(() {
          _userAttendance = attendance;
          _attendanceStats = stats;
          _isLoadingAttendance = false;
        });
      } else {
        setState(() {
          _isLoadingAttendance = false;
        });
      }
    } catch (e) {
      setState(() {
        _isLoadingAttendance = false;
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error loading attendance: $e')),
        );
      }
    }
  }

  List<Attendance> _getFilteredAttendance() {
    switch (_attendanceFilter) {
      case 'attended':
        return _userAttendance.where((a) => a.isAttended).toList();
      case 'missed':
        return _userAttendance.where((a) => !a.isAttended).toList();
      default:
        return _userAttendance;
    }
  }

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2,
      child: Scaffold(
        appBar: AppBar(
          title: Text(_getSafeText('schedule')),
          automaticallyImplyLeading: false,
          backgroundColor: const Color(0xFF2E8B8B),
          foregroundColor: Colors.white,
          bottom: TabBar(
            controller: _tabController,
            indicatorColor: Colors.white,
            labelColor: Colors.white,
            unselectedLabelColor: Colors.white70,
            tabs: [
              Tab(
                icon: const Icon(Icons.event),
                text: _getSafeText('events'),
              ),
              Tab(
                icon: const Icon(Icons.assignment_turned_in),
                text: _getSafeText('attendance'),
              ),
            ],
          ),
        ),
        body: TabBarView(
          controller: _tabController,
          children: [
            _buildEventsTab(),
            _buildAttendanceTab(),
          ],
        ),
      ),
    );
  }

  Widget _buildCalendarSection() {
    return Container(
      margin: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.1),
            spreadRadius: 1,
            blurRadius: 5,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: TableCalendar<String>(
        firstDay: DateTime.utc(2020, 1, 1),
        lastDay: DateTime.utc(2030, 12, 31),
        focusedDay: _focusedDay,
        calendarFormat: _calendarFormat,
        eventLoader: (day) =>
            _getAnnouncementsForDay(day).map((a) => a.what).toList(),
        startingDayOfWeek: StartingDayOfWeek.monday,
        selectedDayPredicate: (day) {
          return isSameDay(_selectedDay, day);
        },
        onDaySelected: (selectedDay, focusedDay) {
          if (!isSameDay(_selectedDay, selectedDay)) {
            setState(() {
              _selectedDay = selectedDay;
              _focusedDay = focusedDay;
              _selectedDayAnnouncements =
                  _getFilteredAnnouncementsForDay(selectedDay);
            });
          }
        },
        onFormatChanged: (format) {
          if (_calendarFormat != format) {
            setState(() {
              _calendarFormat = format;
            });
          }
        },
        onPageChanged: (focusedDay) {
          _focusedDay = focusedDay;
        },
        calendarStyle: const CalendarStyle(
          outsideDaysVisible: false,
          weekendTextStyle: TextStyle(color: Colors.red),
          holidayTextStyle: TextStyle(color: Colors.red),
          selectedDecoration: BoxDecoration(
            color: Colors.blue,
            shape: BoxShape.circle,
          ),
          todayDecoration: BoxDecoration(
            color: Colors.orange,
            shape: BoxShape.circle,
          ),
          markerDecoration: BoxDecoration(
            color: Colors.pink,
            shape: BoxShape.circle,
          ),
        ),
        calendarBuilders: CalendarBuilders(
          markerBuilder: (context, day, events) {
            if (events.isNotEmpty) {
              return _buildCustomMarker(day);
            }
            return null;
          },
        ),
        headerStyle: const HeaderStyle(
          formatButtonVisible: true,
          titleCentered: true,
          formatButtonShowsNext: false,
          formatButtonDecoration: BoxDecoration(
            color: Colors.blue,
            borderRadius: BorderRadius.all(Radius.circular(12.0)),
          ),
          formatButtonTextStyle: TextStyle(
            color: Colors.white,
          ),
        ),
      ),
    );
  }

  Widget _buildEventTypeSection() {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      child: SingleChildScrollView(
        scrollDirection: Axis.horizontal,
        child: Row(
          children: [
            _buildEventTypeChip(
                _getSafeText('current_date'), Colors.orange, false),
            const SizedBox(width: 8),
            _buildEventTypeChip('PHYSICAL RELATED EVENT', Colors.blue, false),
            const SizedBox(width: 8),
            _buildEventTypeChip('APPOINTMENT', Colors.green, false),
          ],
        ),
      ),
    );
  }

  Widget _buildEventTypeChip(String label, Color color, bool isSelected) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      decoration: BoxDecoration(
        color: Colors.transparent,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: color,
          width: 1,
        ),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontSize: _getSafeScaledFontSize(baseSize: 0.7),
          fontWeight: FontWeight.w600,
        ),
      ),
    );
  }

  Widget _buildEventsList() {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            'Events for ${DateFormat('MMMM d, yyyy').format(_selectedDay ?? _focusedDay)}',
            style: TextStyle(
              fontSize: _getSafeScaledFontSize(isSubtitle: true),
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 12),
          if (_selectedDayAnnouncements.isEmpty)
            Container(
              height: 200,
              child: Center(
                child: Text(
                  _getSafeText('no_events'),
                  style: TextStyle(
                    color: Colors.grey,
                    fontSize: _getSafeScaledFontSize(baseSize: 0.8),
                  ),
                ),
              ),
            )
          else
            ListView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: _selectedDayAnnouncements.length,
              itemBuilder: (context, index) {
                return _buildAnnouncementCard(_selectedDayAnnouncements[index]);
              },
            ),
        ],
      ),
    );
  }

  Widget _buildAnnouncementCard(Announcement announcement) {
    final backgroundColor =
        Announcement.getBackgroundColor(announcement.backgroundColor);
    final iconData = Announcement.getIconData(announcement.iconType);
    final reminderService = ReminderService();
    final hasReminder = reminderService.hasReminder(announcement.id);

    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: Container(
        decoration: BoxDecoration(
          color: backgroundColor.withOpacity(0.1),
          borderRadius: BorderRadius.circular(8),
          border: Border.all(
            color: backgroundColor,
            width: 1,
          ),
        ),
        child: ListTile(
          leading: Stack(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: backgroundColor.withOpacity(0.2),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Icon(
                  iconData,
                  color: backgroundColor,
                  size: 20,
                ),
              ),
              if (hasReminder)
                Positioned(
                  right: -2,
                  top: -2,
                  child: Container(
                    padding: const EdgeInsets.all(2),
                    decoration: const BoxDecoration(
                      color: Colors.orange,
                      shape: BoxShape.circle,
                    ),
                    child: const Icon(
                      Icons.notifications,
                      color: Colors.white,
                      size: 10,
                    ),
                  ),
                ),
            ],
          ),
          title: Row(
            children: [
              Expanded(
                child: Text(
                  announcement.what,
                  style: TextStyle(
                    fontWeight: FontWeight.w500,
                    fontSize: _getSafeScaledFontSize(baseSize: 0.8),
                  ),
                ),
              ),
              if (hasReminder)
                const Icon(
                  Icons.notifications_active,
                  color: Colors.orange,
                  size: 16,
                ),
            ],
          ),
          subtitle: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                announcement.where,
                style: TextStyle(
                  fontSize: _getSafeScaledFontSize(baseSize: 0.7),
                  color: Colors.grey,
                ),
              ),
              const SizedBox(height: 2),
              Text(
                announcement.when,
                style: TextStyle(
                  fontSize: _getSafeScaledFontSize(baseSize: 0.65),
                  color: Colors.grey,
                  fontWeight: FontWeight.w400,
                ),
              ),
              if (hasReminder)
                Padding(
                  padding: const EdgeInsets.only(top: 2),
                  child: Text(
                    'Reminder set',
                    style: TextStyle(
                      fontSize: _getSafeScaledFontSize(baseSize: 0.6),
                      color: Colors.orange.shade700,
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ),
            ],
          ),
          trailing: Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
            decoration: BoxDecoration(
              color: backgroundColor,
              borderRadius: BorderRadius.circular(12),
            ),
            child: Text(
              announcement.category,
              style: TextStyle(
                color: Colors.white,
                fontSize: _getSafeScaledFontSize(baseSize: 0.6),
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
          onTap: () {
            _showAnnouncementDetails(announcement);
          },
        ),
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
                style: TextStyle(
                  fontWeight: FontWeight.bold,
                  fontSize: _getSafeScaledFontSize(isTitle: true),
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
                          fontSize: _getSafeScaledFontSize(baseSize: 0.8),
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
                                    _getSafeScaledFontSize(baseSize: 0.7)),
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
                        style: TextStyle(
                          fontSize: _getSafeScaledFontSize(baseSize: 0.7),
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
    // Parse the event date to set proper limits
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

  bool _hasReminderForDate(DateTime date) {
    final reminderService = ReminderService();
    final dayAnnouncements = _getAnnouncementsForDay(date);

    for (final announcement in dayAnnouncements) {
      if (reminderService.hasReminder(announcement.id)) {
        return true;
      }
    }
    return false;
  }

  Widget _buildCustomMarker(DateTime day) {
    final dayAnnouncements = _getAnnouncementsForDay(day);
    if (dayAnnouncements.isEmpty) return const SizedBox.shrink();

    // Check if any announcement is health-related
    final hasHealthEvent = dayAnnouncements.any((announcement) =>
        announcement.category.toLowerCase().contains('health') ||
        announcement.iconType.toLowerCase().contains('health') ||
        announcement.iconType.toLowerCase().contains('medical') ||
        announcement.iconType.toLowerCase().contains('heart'));

    final hasReminder = _hasReminderForDate(day);

    if (hasHealthEvent) {
      // Light pink heart background for health events
      return Container(
        margin: const EdgeInsets.only(top: 5),
        decoration: BoxDecoration(
          color: Colors.pink.shade100,
          shape: BoxShape.circle,
        ),
        width: 16,
        height: 16,
        child: Icon(
          Icons.favorite,
          color: Colors.pink.shade400,
          size: 12,
        ),
      );
    } else if (hasReminder) {
      // Orange circle for events with reminders
      return Container(
        margin: const EdgeInsets.only(top: 5),
        decoration: const BoxDecoration(
          color: Colors.orange,
          shape: BoxShape.circle,
        ),
        width: 16,
        height: 16,
        child: const Icon(
          Icons.notifications,
          color: Colors.white,
          size: 10,
        ),
      );
    } else {
      // Default pink circle for other events
      return Container(
        margin: const EdgeInsets.only(top: 5),
        decoration: const BoxDecoration(
          color: Colors.pink,
          shape: BoxShape.circle,
        ),
        width: 16,
        height: 16,
      );
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

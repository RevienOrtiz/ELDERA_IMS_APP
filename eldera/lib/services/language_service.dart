import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter/material.dart';

class LanguageService {
  static const String _languageKey = 'selected_language';
  static const String _defaultLanguage = 'en_US';

  static LanguageService? _instance;
  static LanguageService get instance {
    _instance ??= LanguageService._internal();
    return _instance!;
  }

  LanguageService._internal();

  String _currentLanguage = _defaultLanguage;

  // Available languages
  static const Map<String, String> availableLanguages = {
    'en_US': 'English (US)',
    'fil_PH': 'Filipino',
  };

  String get currentLanguage => _currentLanguage;
  String get currentLanguageDisplayName =>
      availableLanguages[_currentLanguage] ?? 'English (US)';

  Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();
    _currentLanguage = prefs.getString(_languageKey) ?? _defaultLanguage;
  }

  Future<void> setLanguage(String languageCode) async {
    if (availableLanguages.containsKey(languageCode)) {
      _currentLanguage = languageCode;
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString(_languageKey, languageCode);
    }
  }

  bool get isEnglish => _currentLanguage == 'en_US';
  bool get isFilipino => _currentLanguage == 'fil_PH';

  // Text translations
  String getText(String key) {
    final translations = _getTranslations();
    return translations[key] ?? key;
  }

  Map<String, String> _getTranslations() {
    switch (_currentLanguage) {
      case 'fil_PH':
        return _filipinoTranslations;
      case 'en_US':
      default:
        return _englishTranslations;
    }
  }

  // English translations
  static const Map<String, String> _englishTranslations = {
    // Navigation
    'home': 'HOME',
    'notification': 'NOTIFICATION',
    'schedule': 'SCHEDULE',
    'notifications': 'NOTIFICATIONS',
    'settings': 'SETTINGS',

    // Common actions
    'back': 'BACK',
    'view': 'VIEW',
    'play': 'Play',
    'change': 'Change',
    'cancel': 'Cancel',
    'confirm': 'Confirm',
    'save': 'Save',
    'edit': 'Edit',
    'delete': 'Delete',
    'add': 'Add',
    'search': 'Search',
    'filter': 'Filter',
    'refresh': 'Refresh',
    'loading': 'Loading...',

    // Settings screen
    'font_size': 'Font Size',
    'tutorial': 'Tutorial',
    'language': 'Language',
    'logout': 'Logout',
    'about': 'About',
    'select_language': 'Select Language',
    'language_changed': 'Language changed successfully',
    'sample_text_preview': 'Font size preview',

    // Profile screen
    'profile': 'Profile',
    'years_old': 'years Old',
    'birth_date': 'Birth Date',
    'address': 'Address',
    'name': 'Name',
    'age': 'Age',
    'phone': 'Phone',
    'email': 'Email',

    // Home screen
    'announcements': 'Announcements',
    'categories': 'Categories',
    'all': 'ALL',
    'pension': 'PENSION',
    'health': 'HEALTH',
    'general': 'GENERAL',
    'benefits': 'BENEFITS',
    'dswd_pension': 'DSWD Pension',
    'read_more': 'Read More',
    'no_announcements': 'No announcements available',
    'error_loading': 'Error loading data',

    // Schedule screen
    'calendar': 'Calendar',
    'today': 'Today',
    'events': 'Events',
    'no_events': 'No events for this date',
    'current_date': 'CURRENT DATE',
    'upcoming': 'Upcoming',
    'past': 'Past',

    // Attendance
    'attendance': 'Attendance',
    'attendance_summary': 'Attendance Summary',
    'attended': 'Attended',
    'missed': 'Missed',
    'total': 'Total',
    'filter_by': 'Filter by:',
    'no_attendance_records': 'No attendance records found',

    // Notifications screen
    'mark_as_read': 'Mark as Read',
    'mark_all_read': 'Mark All as Read',
    'no_notifications': 'No notifications',
    'new_notification': 'New Notification',

    // Time and date
    'morning': 'Morning',
    'afternoon': 'Afternoon',
    'evening': 'Evening',
    'night': 'Night',
    'today_date': 'Today',
    'yesterday': 'Yesterday',
    'tomorrow': 'Tomorrow',

    // Common phrases
    'welcome': 'Welcome',
    'good_morning': 'Good Morning',
    'good_afternoon': 'Good Afternoon',
    'good_evening': 'Good Evening',
    'thank_you': 'Thank You',
    'please_wait': 'Please wait...',
    'try_again': 'Try Again',
    'error_occurred': 'An error occurred',
    'success': 'Success',
    'failed': 'Failed',
    'reminder_set': 'Reminder Set',
    'remind_me': 'Remind Me',
    'select_image_source': 'Select Image Source',
    'gallery': 'Gallery',
    'camera': 'Camera',
    'guardian': 'Guardian:',
    'id_status': 'ID Status',
  };

  // Filipino translations
  static const Map<String, String> _filipinoTranslations = {
    // Navigation
    'home': 'TAHANAN',
    'notification': 'ABISO',
    'schedule': 'ISKEDYUL',
    'notifications': 'MGA ABISO',
    'settings': 'MGA SETTING',

    // Common actions
    'back': 'BALIK',
    'view': 'TINGNAN',
    'play': 'I-play',
    'change': 'Baguhin',
    'cancel': 'Kanselahin',
    'confirm': 'Kumpirmahin',
    'save': 'I-save',
    'edit': 'I-edit',
    'delete': 'Tanggalin',
    'add': 'Idagdag',
    'search': 'Maghanap',
    'filter': 'I-filter',
    'refresh': 'I-refresh',
    'loading': 'Naglo-load...',

    // Settings screen
    'font_size': 'Laki ng Font',
    'tutorial': 'Tutorial',
    'language': 'Wika',
    'logout': 'Mag-logout',
    'about': 'Tungkol',
    'select_language': 'Pumili ng Wika',
    'language_changed': 'Matagumpay na nabago ang wika',
    'sample_text_preview': 'Laki ng font',

    // Profile screen
    'profile': 'Profile',
    'years_old': 'taong gulang',
    'birth_date': 'Petsa ng Kapanganakan',
    'address': 'Address',
    'name': 'Pangalan',
    'age': 'Edad',
    'phone': 'Telepono',
    'email': 'Email',

    // Home screen
    'announcements': 'Mga Pabatid',
    'categories': 'Mga Kategorya',
    'all': 'LAHAT',
    'pension': 'PENSYON',
    'health': 'KALUSUGAN',
    'general': 'PANGKALAHATAN',
    'benefits': 'MGA BENEPISYO',
    'dswd_pension': 'DSWD Pension',
    'read_more': 'Basahin pa',
    'no_announcements': 'Walang mga pabatid',
    'error_loading': 'May error sa pag-load ng data',

    // Schedule screen
    'calendar': 'Kalendaryo',
    'today': 'Ngayon',
    'events': 'Mga Kaganapan',
    'no_events': 'Walang kaganapan sa petsang ito',
    'current_date': 'KASALUKUYANG PETSA',
    'upcoming': 'Paparating',
    'past': 'Nakaraan',

    // Attendance
    'attendance': 'Pagdalo',
    'attendance_summary': 'Buod ng Pagdalo',
    'attended': 'Dumalo',
    'missed': 'Hindi Dumalo',
    'total': 'Kabuuan',
    'filter_by': 'I-filter ayon sa:',
    'no_attendance_records': 'Walang nakitang record ng pagdalo',

    // Notifications screen
    'mark_as_read': 'Markahan bilang Nabasa',
    'mark_all_read': 'Markahan Lahat bilang Nabasa',
    'no_notifications': 'Walang mga abiso',
    'new_notification': 'Bagong Abiso',

    // Time and date
    'morning': 'Umaga',
    'afternoon': 'Hapon',
    'evening': 'Gabi',
    'night': 'Gabi',
    'today_date': 'Ngayon',
    'yesterday': 'Kahapon',
    'tomorrow': 'Bukas',

    // Common phrases
    'welcome': 'Maligayang pagdating',
    'good_morning': 'Magandang Umaga',
    'good_afternoon': 'Magandang Hapon',
    'good_evening': 'Magandang Gabi',
    'thank_you': 'Salamat',
    'please_wait': 'Pakihintay...',
    'try_again': 'Subukan Muli',
    'error_occurred': 'May naganap na error',
    'success': 'Tagumpay',
    'failed': 'Nabigo',
    'reminder_set': 'Naka-set na ang Reminder',
    'remind_me': 'Paalalahanan Mo Ako',
    'select_image_source': 'Pumili ng Source ng Larawan',
    'gallery': 'Gallery',
    'camera': 'Camera',
    'guardian': 'Guardian:',
    'id_status': 'Status ng ID',
  };
}

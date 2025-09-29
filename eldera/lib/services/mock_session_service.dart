import '../models/user.dart';
import '../models/announcement.dart';
import 'mock_data_service.dart';

/// Mock Session Service for Development Testing
///
/// This service manages mock user sessions and data for development testing.
/// It provides a simple way to simulate user authentication and data loading
/// without connecting to the actual backend services.
class MockSessionService {
  static MockSessionService? _instance;
  static MockSessionService get instance =>
      _instance ??= MockSessionService._();

  MockSessionService._();

  // Mock session data
  User? _currentUser;
  List<Announcement>? _mockAnnouncements;
  bool _isTestMode = false;

  /// Check if the app is running in test mode
  bool get isTestMode => _isTestMode;

  /// Get the current mock user
  User? get currentUser => _currentUser;

  /// Get mock announcements
  List<Announcement> get announcements => _mockAnnouncements ?? [];

  /// Initialize mock session with test data
  Future<void> initializeMockSession() async {
    _isTestMode = true;

    // Create mock user
    _currentUser = MockDataService.createMockUser();

    // Create mock announcements
    _mockAnnouncements = MockDataService.createMockAnnouncements();

    print('Mock session initialized:');
    print('- User: ${_currentUser?.name}');
    print('- Announcements: ${_mockAnnouncements?.length} items');
  }

  /// Clear mock session data
  void clearMockSession() {
    _isTestMode = false;
    _currentUser = null;
    _mockAnnouncements = null;
    print('Mock session cleared');
  }

  /// Check if user is authenticated (in test mode)
  bool get isAuthenticated => _isTestMode && _currentUser != null;

  /// Get user display name
  String get userDisplayName {
    if (_currentUser != null) {
      return _currentUser!.name;
    }
    return 'Test User';
  }

  /// Get user greeting based on time of day
  String get userGreeting {
    final hour = DateTime.now().hour;
    String greeting;

    if (hour < 12) {
      greeting = 'Good Morning';
    } else if (hour < 17) {
      greeting = 'Good Afternoon';
    } else {
      greeting = 'Good Evening';
    }

    return '$greeting, ${_currentUser?.name ?? 'User'}!';
  }

  /// Get announcements by category
  List<Announcement> getAnnouncementsByCategory(String category) {
    if (_mockAnnouncements == null) return [];

    return _mockAnnouncements!
        .where((announcement) =>
            announcement.category.toLowerCase() == category.toLowerCase())
        .toList();
  }

  /// Get recent announcements (last 7 days)
  List<Announcement> getRecentAnnouncements() {
    if (_mockAnnouncements == null) return [];

    final sevenDaysAgo = DateTime.now().subtract(const Duration(days: 7));

    return _mockAnnouncements!.where((announcement) {
      try {
        final postedDate = DateTime.parse(announcement.postedDate);
        return postedDate.isAfter(sevenDaysAgo);
      } catch (e) {
        return false;
      }
    }).toList();
  }

  /// Simulate logout
  void logout() {
    clearMockSession();
  }
}

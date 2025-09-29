import '../models/user.dart';
import '../models/announcement.dart';

/// Mock data service for development testing
/// This provides sample data to test the app functionality without real authentication
class MockDataService {
  
  /// Create a mock user for testing
  static User createMockUser() {
    return User(
      id: 'mock-user-001',
      name: 'Maria Santos',
      age: 68,
      phoneNumber: '+639123456789',
      profileImageUrl: null,
      idStatus: 'Senior Citizen',
      isDswdPensionBeneficiary: true,
      birthDate: '1956-03-15',
      address: 'Barangay Poblacion, Lingayen, Pangasinan',
      guardianName: 'Juan Santos Jr.',
      createdAt: DateTime.now().subtract(const Duration(days: 30)),
      updatedAt: DateTime.now(),
    );
  }

  /// Create mock announcements for testing
  static List<Announcement> createMockAnnouncements() {
    final now = DateTime.now();
    
    return [
      Announcement(
        id: 'ann-001',
        title: 'Free Health Check-up for Senior Citizens',
        postedDate: now.subtract(const Duration(days: 2)).toIso8601String().split('T')[0],
        what: 'Free comprehensive health screening including blood pressure, blood sugar, and basic physical examination for all registered senior citizens.',
        when: 'March 25, 2024 - 8:00 AM to 4:00 PM',
        where: 'Lingayen Municipal Health Office',
        category: 'Health',
        department: 'Municipal Health Office',
        iconType: 'health',
      ),
      
      Announcement(
        id: 'ann-002',
        title: 'DSWD Pension Distribution Schedule',
        postedDate: now.subtract(const Duration(days: 1)).toIso8601String().split('T')[0],
        what: 'Monthly pension distribution for qualified senior citizens and PWD beneficiaries. Please bring valid ID and pension booklet.',
        when: 'March 28-30, 2024 - 9:00 AM to 3:00 PM',
        where: 'Municipal Social Welfare Office',
        category: 'Social',
        department: 'DSWD - Municipal Office',
        iconType: 'social',
      ),
      
      Announcement(
        id: 'ann-003',
        title: 'Senior Citizens Exercise Program',
        postedDate: now.toIso8601String().split('T')[0],
        what: 'Weekly exercise and wellness program designed specifically for senior citizens. Includes light aerobics, stretching, and health education.',
        when: 'Every Tuesday and Thursday - 6:00 AM to 7:30 AM',
        where: 'Lingayen Plaza',
        category: 'Health',
        department: 'Municipal Health Office',
        iconType: 'health',
      ),
      
      Announcement(
        id: 'ann-004',
        title: 'Emergency Hotline Update',
        postedDate: now.toIso8601String().split('T')[0],
        what: 'Updated emergency contact numbers for medical emergencies, fire incidents, and police assistance. Save these numbers in your phone.',
        when: 'Effective immediately',
        where: 'Municipality-wide',
        category: 'Emergency',
        department: 'Municipal Disaster Risk Reduction Office',
        iconType: 'emergency',
      ),
      
      Announcement(
        id: 'ann-005',
        title: 'Digital Literacy Training for Seniors',
        postedDate: now.subtract(const Duration(hours: 6)).toIso8601String().split('T')[0],
        what: 'Learn basic smartphone and internet usage. Free training sessions to help senior citizens navigate digital services and stay connected with family.',
        when: 'April 5-7, 2024 - 2:00 PM to 4:00 PM',
        where: 'Municipal Library Computer Lab',
        category: 'Education',
        department: 'Municipal Library',
        iconType: 'education',
      ),
    ];
  }

  /// Get mock user data as JSON (for API simulation)
  static Map<String, dynamic> getMockUserJson() {
    return createMockUser().toJson();
  }

  /// Get mock announcements as JSON list (for API simulation)
  static List<Map<String, dynamic>> getMockAnnouncementsJson() {
    return createMockAnnouncements().map((announcement) => announcement.toJson()).toList();
  }
}
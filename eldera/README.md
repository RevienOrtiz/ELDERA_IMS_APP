# Eldera Health - Mobile Health Monitoring App

A comprehensive health monitoring mobile application designed specifically for elderly care and health management.

## Features

### 🏠 Home Screen
- Welcome section with user greeting and current date
- Health check-up cards displaying:
  - Monthly Check-ups
  - Free Check-ups
  - Library Hygiene Week events
  - Office of Senior Citizens Affairs information
- Department listings with detailed information

### 🔔 Notifications Screen
- Real-time health department alerts
- New notification indicators
- Department of Health announcements
- Free medical check-up notifications

### ⚙️ Settings Screen
- User profile management with avatar
- Personal information display
- Benefits tracking (Senior Beneficiary, SSS Pensioner)
- App preferences:
  - Font Size adjustment
  - Night Light mode
  - Tutorial toggle
  - Language settings
  - Logout option
- About section

### 📅 Schedule Screen
- Interactive calendar with event markers
- Event type filtering:
  - Current Date
  - Physical Related Events
  - Appointments
- Event details with location information
- Monthly/weekly calendar views

## Project Structure

```
eldera/
├── lib/
│   ├── eldera.dart          # Main app entry point
│   └── screens/
│       ├── main_screen.dart      # Bottom navigation controller
│       ├── home_screen.dart      # Home screen with health cards
│       ├── notifications_screen.dart  # Notifications display
│       ├── settings_screen.dart  # User settings and profile
│       └── schedule_screen.dart  # Calendar and appointments
├── assets/
│   └── images/              # App images and icons
├── pubspec.yaml             # Dependencies and configuration
└── README.md               # This file
```

## Dependencies

- **flutter**: Flutter SDK
- **cupertino_icons**: iOS-style icons
- **table_calendar**: Calendar widget for scheduling
- **intl**: Internationalization and date formatting
- **shared_preferences**: Local data storage
- **http**: HTTP requests for API communication

## Getting Started

### Prerequisites

1. Install Flutter SDK: https://flutter.dev/docs/get-started/install
2. Install Dart SDK (included with Flutter)
3. Set up an IDE (VS Code, Android Studio, or IntelliJ)

### Installation

1. Clone or download this project
2. Navigate to the project directory:
   ```bash
   cd eldera
   ```

3. Get dependencies:
   ```bash
   flutter pub get
   ```

4. Run the app:
   ```bash
   flutter run
   ```

### Running on Different Platforms

- **Android**: Connect an Android device or start an emulator, then run `flutter run`
- **iOS**: Open iOS Simulator or connect an iPhone, then run `flutter run`
- **Web**: Run `flutter run -d chrome`
- **Desktop**: Run `flutter run -d windows` (or `macos`/`linux`)

## Design Features

### Color Scheme
- Primary Blue: `#2196F3`
- Background: `#F5F5F5`
- Card backgrounds: White with subtle shadows
- Success/Health indicators: Green
- Alert/Important indicators: Pink/Red

### UI Components
- Material Design principles
- Rounded corners (12px radius)
- Card-based layout
- Bottom navigation with 4 tabs
- Responsive design for different screen sizes

### Accessibility
- Large, readable fonts
- High contrast colors
- Touch-friendly button sizes
- Clear navigation structure

## Target Users

- Senior citizens
- Elderly care providers
- Healthcare professionals
- Family members monitoring elderly health

## Future Enhancements

- Push notifications for health reminders
- Integration with health monitoring devices
- Telemedicine features
- Emergency contact system
- Medication tracking
- Health data analytics

## Support

For support and questions, please contact the development team.

---

*Built with Flutter for cross-platform mobile development*

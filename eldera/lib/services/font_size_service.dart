import 'package:shared_preferences/shared_preferences.dart';

class FontSizeService {
  static const String _fontSizeKey = 'font_size';
  static const double _defaultFontSize = 20.0;
  static const double _minFontSize = 14.0;
  static const double _maxFontSize = 30.0;

  static FontSizeService? _instance;
  static FontSizeService get instance {
    _instance ??= FontSizeService._internal();
    return _instance!;
  }

  FontSizeService._internal();

  SharedPreferences? _prefs;

  Future<void> init() async {
    _prefs = await SharedPreferences.getInstance();
  }

  bool get isInitialized => _prefs != null;

  double get fontSize {
    if (_prefs == null) {
      // Return default font size if not initialized yet
      return _defaultFontSize;
    }
    return _prefs!.getDouble(_fontSizeKey) ?? _defaultFontSize;
  }

  Future<void> setFontSize(double size) async {
    if (_prefs == null) await init();
    
    // Clamp the font size to the allowed range
    final clampedSize = size.clamp(_minFontSize, _maxFontSize);
    await _prefs!.setDouble(_fontSizeKey, clampedSize);
  }

  double get minFontSize => _minFontSize;
  double get maxFontSize => _maxFontSize;
  double get defaultFontSize => _defaultFontSize;

  // Helper method to get scaled font size for different text types
  double getScaledFontSize({
    double baseSize = 1.0,
    bool isTitle = false,
    bool isSubtitle = false,
    bool isCaption = false,
  }) {
    double scaleFactor = baseSize;
    
    if (isTitle) {
      scaleFactor = 1.2; // 20% larger for titles
    } else if (isSubtitle) {
      scaleFactor = 1.1; // 10% larger for subtitles
    } else if (isCaption) {
      scaleFactor = 0.9; // 10% smaller for captions
    }
    
    return fontSize * scaleFactor;
  }
}
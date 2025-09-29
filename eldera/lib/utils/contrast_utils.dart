import 'package:flutter/material.dart';
import '../config/app_colors.dart';

/// Utility class for contrast ratio calculations and accessibility validation
class ContrastUtils {
  /// WCAG AA contrast ratio requirements
  static const double normalTextMinRatio = 4.5;
  static const double largeTextMinRatio = 3.0;
  static const double enhancedMinRatio = 7.0; // WCAG AAA
  
  /// Font size threshold for large text (18pt regular or 14pt bold)
  static const double largeTextSizeThreshold = 18.0;
  static const double largeBoldTextSizeThreshold = 14.0;
  
  /// Validates if a text style meets WCAG contrast requirements
  static bool validateTextContrast({
    required Color textColor,
    required Color backgroundColor,
    required double fontSize,
    FontWeight fontWeight = FontWeight.normal,
    bool requireAAA = false,
  }) {
    final bool isLargeText = _isLargeText(fontSize, fontWeight);
    final double contrastRatio = AppColors.calculateContrastRatio(textColor, backgroundColor);
    
    if (requireAAA) {
      return contrastRatio >= enhancedMinRatio;
    }
    
    final double requiredRatio = isLargeText ? largeTextMinRatio : normalTextMinRatio;
    return contrastRatio >= requiredRatio;
  }
  
  /// Gets the appropriate text color for a given background
  static Color getAccessibleTextColor(Color backgroundColor, {bool preferDark = true}) {
    final double whiteContrast = AppColors.calculateContrastRatio(AppColors.pureWhite, backgroundColor);
    final double blackContrast = AppColors.calculateContrastRatio(AppColors.textOnWhite, backgroundColor);
    
    // If both meet requirements, use preference
    if (whiteContrast >= normalTextMinRatio && blackContrast >= normalTextMinRatio) {
      return preferDark ? AppColors.textOnWhite : AppColors.pureWhite;
    }
    
    // Use the one with better contrast
    return whiteContrast > blackContrast ? AppColors.pureWhite : AppColors.textOnWhite;
  }
  
  /// Gets the appropriate secondary text color for a given background
  static Color getAccessibleSecondaryTextColor(Color backgroundColor) {
    final Color primaryText = getAccessibleTextColor(backgroundColor);
    
    if (primaryText == AppColors.pureWhite) {
      return AppColors.textSecondaryOnPrimary;
    } else {
      return AppColors.textSecondaryOnLight;
    }
  }
  
  /// Validates if an interactive element has sufficient contrast
  static bool validateInteractiveElementContrast({
    required Color elementColor,
    required Color backgroundColor,
    bool isButton = false,
  }) {
    final double contrastRatio = AppColors.calculateContrastRatio(elementColor, backgroundColor);
    
    // Interactive elements need at least 3:1 contrast ratio
    return contrastRatio >= 3.0;
  }
  
  /// Gets contrast ratio level description
  static String getContrastLevel(double ratio) {
    if (ratio >= enhancedMinRatio) {
      return 'AAA Enhanced';
    } else if (ratio >= normalTextMinRatio) {
      return 'AA Normal';
    } else if (ratio >= largeTextMinRatio) {
      return 'AA Large Text Only';
    } else {
      return 'Insufficient';
    }
  }
  
  /// Checks if text size qualifies as "large text" per WCAG
  static bool _isLargeText(double fontSize, FontWeight fontWeight) {
    if (fontWeight.index >= FontWeight.bold.index) {
      return fontSize >= largeBoldTextSizeThreshold;
    }
    return fontSize >= largeTextSizeThreshold;
  }
  
  /// Generates a color palette with guaranteed contrast ratios
  static Map<String, Color> generateAccessiblePalette(Color baseColor) {
    return {
      'base': baseColor,
      'onBase': getAccessibleTextColor(baseColor),
      'onBaseSecondary': getAccessibleSecondaryTextColor(baseColor),
      'light': _lightenColor(baseColor, 0.8),
      'dark': _darkenColor(baseColor, 0.2),
    };
  }
  
  /// Lightens a color by a given factor (0.0 to 1.0)
  static Color _lightenColor(Color color, double factor) {
    final hsl = HSLColor.fromColor(color);
    return hsl.withLightness((hsl.lightness + factor).clamp(0.0, 1.0)).toColor();
  }
  
  /// Darkens a color by a given factor (0.0 to 1.0)
  static Color _darkenColor(Color color, double factor) {
    final hsl = HSLColor.fromColor(color);
    return hsl.withLightness((hsl.lightness - factor).clamp(0.0, 1.0)).toColor();
  }
  
  /// Validates an entire color scheme for accessibility
  static Map<String, bool> validateColorScheme({
    required Color primary,
    required Color onPrimary,
    required Color secondary,
    required Color onSecondary,
    required Color background,
    required Color onBackground,
    required Color surface,
    required Color onSurface,
  }) {
    return {
      'primaryContrast': AppColors.calculateContrastRatio(onPrimary, primary) >= normalTextMinRatio,
      'secondaryContrast': AppColors.calculateContrastRatio(onSecondary, secondary) >= normalTextMinRatio,
      'backgroundContrast': AppColors.calculateContrastRatio(onBackground, background) >= normalTextMinRatio,
      'surfaceContrast': AppColors.calculateContrastRatio(onSurface, surface) >= normalTextMinRatio,
    };
  }
  
  /// Debug function to print contrast ratios
  static void debugContrastRatios({
    required Color foreground,
    required Color background,
    String? label,
  }) {
    final double ratio = AppColors.calculateContrastRatio(foreground, background);
    final String level = getContrastLevel(ratio);
    
    print('${label ?? 'Contrast'}: ${ratio.toStringAsFixed(2)}:1 ($level)');
  }
}
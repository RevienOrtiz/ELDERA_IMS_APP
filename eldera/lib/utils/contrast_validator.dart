import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import '../config/app_colors.dart';

/// Development utility for validating contrast ratios
/// Only active in debug mode to avoid performance impact in production
class ContrastValidator {
  static const double _normalTextMinRatio = 4.5;
  static const double _largeTextMinRatio = 3.0;
  static const double _largeTextSizeThreshold = 18.0;
  
  /// Validate contrast ratio for a text widget
  /// Returns true if contrast is sufficient, false otherwise
  static bool validateTextContrast({
    required Color textColor,
    required Color backgroundColor,
    required double fontSize,
    String? debugLabel,
  }) {
    if (!kDebugMode) return true; // Skip validation in release mode
    
    final isLargeText = fontSize >= _largeTextSizeThreshold;
    final requiredRatio = isLargeText ? _largeTextMinRatio : _normalTextMinRatio;
    final actualRatio = AppColors.calculateContrastRatio(textColor, backgroundColor);
    
    final isValid = actualRatio >= requiredRatio;
    
    if (!isValid) {
      _logContrastViolation(
        textColor: textColor,
        backgroundColor: backgroundColor,
        actualRatio: actualRatio,
        requiredRatio: requiredRatio,
        fontSize: fontSize,
        debugLabel: debugLabel,
      );
    }
    
    return isValid;
  }
  
  /// Validate contrast for a widget with text
  static bool validateWidget({
    required Widget widget,
    required Color backgroundColor,
    String? debugLabel,
  }) {
    if (!kDebugMode) return true;
    
    if (widget is Text) {
      final textStyle = widget.style ?? const TextStyle();
      final textColor = textStyle.color ?? Colors.black;
      final fontSize = textStyle.fontSize ?? 14.0;
      
      return validateTextContrast(
        textColor: textColor,
        backgroundColor: backgroundColor,
        fontSize: fontSize,
        debugLabel: debugLabel ?? widget.data,
      );
    }
    
    return true; // Non-text widgets pass validation
  }
  
  /// Get suggested text color for a background
  static Color getSuggestedTextColor({
    required Color backgroundColor,
    required double fontSize,
    bool preferDark = true,
  }) {
    final isLargeText = fontSize >= _largeTextSizeThreshold;
    final requiredRatio = isLargeText ? _largeTextMinRatio : _normalTextMinRatio;
    
    // Test primary text colors
    final darkTextRatio = AppColors.calculateContrastRatio(
      AppColors.textOnWhite,
      backgroundColor,
    );
    final lightTextRatio = AppColors.calculateContrastRatio(
      AppColors.pureWhite,
      backgroundColor,
    );
    
    // Check if preferred color meets requirements
    if (preferDark && darkTextRatio >= requiredRatio) {
      return AppColors.textOnWhite;
    }
    if (!preferDark && lightTextRatio >= requiredRatio) {
      return AppColors.pureWhite;
    }
    
    // Return the color with better contrast
    return darkTextRatio > lightTextRatio 
        ? AppColors.textOnWhite 
        : AppColors.pureWhite;
  }
  
  /// Batch validate multiple text elements
  static List<ContrastViolation> validateMultipleTexts(
    List<TextValidationItem> items,
  ) {
    if (!kDebugMode) return [];
    
    final violations = <ContrastViolation>[];
    
    for (final item in items) {
      final isValid = validateTextContrast(
        textColor: item.textColor,
        backgroundColor: item.backgroundColor,
        fontSize: item.fontSize,
        debugLabel: item.debugLabel,
      );
      
      if (!isValid) {
        final actualRatio = AppColors.calculateContrastRatio(
          item.textColor,
          item.backgroundColor,
        );
        final isLargeText = item.fontSize >= _largeTextSizeThreshold;
        final requiredRatio = isLargeText ? _largeTextMinRatio : _normalTextMinRatio;
        
        violations.add(ContrastViolation(
          debugLabel: item.debugLabel,
          textColor: item.textColor,
          backgroundColor: item.backgroundColor,
          actualRatio: actualRatio,
          requiredRatio: requiredRatio,
          fontSize: item.fontSize,
        ));
      }
    }
    
    return violations;
  }
  
  /// Generate a contrast report for debugging
  static String generateContrastReport(List<ContrastViolation> violations) {
    if (violations.isEmpty) {
      return '✅ All text elements meet WCAG contrast requirements!';
    }
    
    final buffer = StringBuffer();
    buffer.writeln('❌ Contrast Violations Found: ${violations.length}');
    buffer.writeln('=' * 50);
    
    for (int i = 0; i < violations.length; i++) {
      final violation = violations[i];
      buffer.writeln('${i + 1}. ${violation.debugLabel ?? "Unnamed text"}');
      buffer.writeln('   Text: ${_colorToHex(violation.textColor)}');
      buffer.writeln('   Background: ${_colorToHex(violation.backgroundColor)}');
      buffer.writeln('   Actual Ratio: ${violation.actualRatio.toStringAsFixed(2)}:1');
      buffer.writeln('   Required Ratio: ${violation.requiredRatio.toStringAsFixed(1)}:1');
      buffer.writeln('   Font Size: ${violation.fontSize}px');
      buffer.writeln('   Suggestion: Use ${_colorToHex(getSuggestedTextColor(
        backgroundColor: violation.backgroundColor,
        fontSize: violation.fontSize,
      ))} for text color');
      buffer.writeln();
    }
    
    return buffer.toString();
  }
  
  /// Log contrast violation to debug console
  static void _logContrastViolation({
    required Color textColor,
    required Color backgroundColor,
    required double actualRatio,
    required double requiredRatio,
    required double fontSize,
    String? debugLabel,
  }) {
    final suggestion = getSuggestedTextColor(
      backgroundColor: backgroundColor,
      fontSize: fontSize,
    );
    
    debugPrint('🚨 CONTRAST VIOLATION 🚨');
    debugPrint('Widget: ${debugLabel ?? "Unknown"}');
    debugPrint('Text Color: ${_colorToHex(textColor)}');
    debugPrint('Background: ${_colorToHex(backgroundColor)}');
    debugPrint('Actual Ratio: ${actualRatio.toStringAsFixed(2)}:1');
    debugPrint('Required Ratio: ${requiredRatio.toStringAsFixed(1)}:1');
    debugPrint('Font Size: ${fontSize}px');
    debugPrint('Suggested Text Color: ${_colorToHex(suggestion)}');
    debugPrint('=' * 40);
  }
  
  /// Convert color to hex string for debugging
  static String _colorToHex(Color color) {
    return '#${color.value.toRadixString(16).padLeft(8, '0').toUpperCase()}';
  }
  
  /// Assert contrast compliance (throws in debug mode if invalid)
  static void assertContrastCompliance({
    required Color textColor,
    required Color backgroundColor,
    required double fontSize,
    String? debugLabel,
  }) {
    if (!kDebugMode) return;
    
    final isValid = validateTextContrast(
      textColor: textColor,
      backgroundColor: backgroundColor,
      fontSize: fontSize,
      debugLabel: debugLabel,
    );
    
    if (!isValid) {
      final actualRatio = AppColors.calculateContrastRatio(textColor, backgroundColor);
      final isLargeText = fontSize >= _largeTextSizeThreshold;
      final requiredRatio = isLargeText ? _largeTextMinRatio : _normalTextMinRatio;
      
      throw FlutterError(
        'Contrast violation in ${debugLabel ?? "widget"}: '
        'Actual ratio ${actualRatio.toStringAsFixed(2)}:1 is below '
        'required ${requiredRatio.toStringAsFixed(1)}:1 for ${fontSize}px text.'
      );
    }
  }
}

/// Data class for text validation items
class TextValidationItem {
  final Color textColor;
  final Color backgroundColor;
  final double fontSize;
  final String? debugLabel;
  
  const TextValidationItem({
    required this.textColor,
    required this.backgroundColor,
    required this.fontSize,
    this.debugLabel,
  });
}

/// Data class for contrast violations
class ContrastViolation {
  final String? debugLabel;
  final Color textColor;
  final Color backgroundColor;
  final double actualRatio;
  final double requiredRatio;
  final double fontSize;
  
  const ContrastViolation({
    this.debugLabel,
    required this.textColor,
    required this.backgroundColor,
    required this.actualRatio,
    required this.requiredRatio,
    required this.fontSize,
  });
}

/// Extension to add contrast validation to TextStyle
extension TextStyleContrastValidation on TextStyle {
  /// Validate this text style against a background color
  bool validateContrast(Color backgroundColor, {String? debugLabel}) {
    return ContrastValidator.validateTextContrast(
      textColor: color ?? Colors.black,
      backgroundColor: backgroundColor,
      fontSize: fontSize ?? 14.0,
      debugLabel: debugLabel,
    );
  }
  
  /// Get a contrast-compliant version of this text style
  TextStyle ensureContrast(Color backgroundColor, {bool preferDark = true}) {
    final suggestedColor = ContrastValidator.getSuggestedTextColor(
      backgroundColor: backgroundColor,
      fontSize: fontSize ?? 14.0,
      preferDark: preferDark,
    );
    
    return copyWith(color: suggestedColor);
  }
}
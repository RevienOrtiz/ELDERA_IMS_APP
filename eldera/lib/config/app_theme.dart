import 'package:flutter/material.dart';
import 'app_colors.dart';

/// Accessible theme configuration for the Eldera app
/// Ensures WCAG compliance with minimum 4.5:1 contrast ratios
class AppTheme {
  
  /// Main theme data with accessible colors
  static ThemeData get lightTheme {
    return ThemeData(
      useMaterial3: true,
      
      // Color scheme based on accessible colors
      colorScheme: const ColorScheme.light(
        primary: AppColors.primaryGreen,
        onPrimary: AppColors.textOnPrimary,
        secondary: AppColors.ivoryWhite,
        onSecondary: AppColors.textOnLight,
        surface: AppColors.backgroundCard,
        onSurface: AppColors.textOnWhite,
        background: AppColors.backgroundSecondary,
        onBackground: AppColors.textOnLight,
        error: AppColors.error,
        onError: AppColors.errorText,
      ),
      
      // App bar theme
      appBarTheme: const AppBarTheme(
        backgroundColor: AppColors.primaryGreen,
        foregroundColor: AppColors.textOnPrimary,
        elevation: 0,
        centerTitle: true,
        titleTextStyle: TextStyle(
          color: AppColors.textOnPrimary,
          fontSize: 20,
          fontWeight: FontWeight.w600,
        ),
        iconTheme: IconThemeData(
          color: AppColors.textOnPrimary,
        ),
      ),
      
      // Text theme with accessible colors
      textTheme: const TextTheme(
        displayLarge: TextStyle(
          color: AppColors.textOnLight,
          fontSize: 32,
          fontWeight: FontWeight.bold,
        ),
        displayMedium: TextStyle(
          color: AppColors.textOnLight,
          fontSize: 28,
          fontWeight: FontWeight.w600,
        ),
        displaySmall: TextStyle(
          color: AppColors.textOnLight,
          fontSize: 24,
          fontWeight: FontWeight.w500,
        ),
        headlineLarge: TextStyle(
          color: AppColors.textOnLight,
          fontSize: 22,
          fontWeight: FontWeight.w600,
        ),
        headlineMedium: TextStyle(
          color: AppColors.textOnLight,
          fontSize: 20,
          fontWeight: FontWeight.w500,
        ),
        headlineSmall: TextStyle(
          color: AppColors.textOnLight,
          fontSize: 18,
          fontWeight: FontWeight.w500,
        ),
        titleLarge: TextStyle(
          color: AppColors.textOnLight,
          fontSize: 16,
          fontWeight: FontWeight.w600,
        ),
        titleMedium: TextStyle(
          color: AppColors.textOnLight,
          fontSize: 14,
          fontWeight: FontWeight.w500,
        ),
        titleSmall: TextStyle(
          color: AppColors.textSecondaryOnLight,
          fontSize: 12,
          fontWeight: FontWeight.w500,
        ),
        bodyLarge: TextStyle(
          color: AppColors.textOnLight,
          fontSize: 16,
          fontWeight: FontWeight.normal,
        ),
        bodyMedium: TextStyle(
          color: AppColors.textOnLight,
          fontSize: 14,
          fontWeight: FontWeight.normal,
        ),
        bodySmall: TextStyle(
          color: AppColors.textSecondaryOnLight,
          fontSize: 12,
          fontWeight: FontWeight.normal,
        ),
        labelLarge: TextStyle(
          color: AppColors.textOnLight,
          fontSize: 14,
          fontWeight: FontWeight.w500,
        ),
        labelMedium: TextStyle(
          color: AppColors.textSecondaryOnLight,
          fontSize: 12,
          fontWeight: FontWeight.w500,
        ),
        labelSmall: TextStyle(
          color: AppColors.textSecondaryOnLight,
          fontSize: 10,
          fontWeight: FontWeight.w500,
        ),
      ),
      
      // Elevated button theme
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.buttonPrimary,
          foregroundColor: AppColors.buttonPrimaryText,
          elevation: 2,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(8),
          ),
          textStyle: const TextStyle(
            fontSize: 16,
            fontWeight: FontWeight.w500,
          ),
        ),
      ),
      
      // Outlined button theme
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          foregroundColor: AppColors.primaryGreen,
          side: const BorderSide(color: AppColors.primaryGreen, width: 1.5),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(8),
          ),
          textStyle: const TextStyle(
            fontSize: 16,
            fontWeight: FontWeight.w500,
          ),
        ),
      ),
      
      // Text button theme
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(
          foregroundColor: AppColors.primaryGreen,
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
          textStyle: const TextStyle(
            fontSize: 16,
            fontWeight: FontWeight.w500,
          ),
        ),
      ),
      
      // Input decoration theme
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: AppColors.backgroundCard,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: AppColors.border),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: AppColors.border),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: AppColors.focus, width: 2),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: AppColors.error, width: 2),
        ),
        labelStyle: const TextStyle(
          color: AppColors.textSecondaryOnLight,
          fontSize: 16,
        ),
        hintStyle: const TextStyle(
          color: AppColors.textSecondaryOnLight,
          fontSize: 16,
        ),
        errorStyle: const TextStyle(
          color: AppColors.error,
          fontSize: 12,
        ),
      ),
      
      // Card theme
      cardTheme: CardThemeData(
        color: AppColors.backgroundCard,
        elevation: 2,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(12),
        ),
        margin: const EdgeInsets.all(8),
      ),
      
      // Chip theme
      chipTheme: ChipThemeData(
        backgroundColor: AppColors.buttonSecondary,
        labelStyle: const TextStyle(
          color: AppColors.buttonSecondaryText,
          fontSize: 14,
        ),
        selectedColor: AppColors.primaryGreen,
        secondarySelectedColor: AppColors.primaryGreen,
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
        ),
      ),
      
      // Snack bar theme
      snackBarTheme: const SnackBarThemeData(
        backgroundColor: AppColors.primaryGreen,
        contentTextStyle: TextStyle(
          color: AppColors.textOnPrimary,
          fontSize: 16,
        ),
        actionTextColor: AppColors.textOnPrimary,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.all(Radius.circular(8)),
        ),
      ),
      
      // Dialog theme
      dialogTheme: DialogThemeData(
        backgroundColor: AppColors.backgroundCard,
        titleTextStyle: const TextStyle(
          color: AppColors.textOnWhite,
          fontSize: 20,
          fontWeight: FontWeight.w600,
        ),
        contentTextStyle: const TextStyle(
          color: AppColors.textOnWhite,
          fontSize: 16,
        ),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(12),
        ),
      ),
      
      // Bottom navigation bar theme
      bottomNavigationBarTheme: const BottomNavigationBarThemeData(
        backgroundColor: AppColors.primaryGreen,
        selectedItemColor: AppColors.textOnPrimary,
        unselectedItemColor: AppColors.textSecondaryOnPrimary,
        type: BottomNavigationBarType.fixed,
        elevation: 8,
      ),
      
      // Switch theme
      switchTheme: SwitchThemeData(
        thumbColor: MaterialStateProperty.resolveWith<Color>(
          (Set<MaterialState> states) {
            if (states.contains(MaterialState.selected)) {
              return AppColors.primaryGreen;
            }
            return AppColors.disabled;
          },
        ),
        trackColor: MaterialStateProperty.resolveWith<Color>(
          (Set<MaterialState> states) {
            if (states.contains(MaterialState.selected)) {
              return AppColors.primaryGreen.withOpacity(0.5);
            }
            return AppColors.disabled.withOpacity(0.3);
          },
        ),
      ),
      
      // Checkbox theme
      checkboxTheme: CheckboxThemeData(
        fillColor: MaterialStateProperty.resolveWith<Color>(
          (Set<MaterialState> states) {
            if (states.contains(MaterialState.selected)) {
              return AppColors.primaryGreen;
            }
            return Colors.transparent;
          },
        ),
        checkColor: MaterialStateProperty.all(AppColors.textOnPrimary),
      ),
      
      // Radio theme
      radioTheme: RadioThemeData(
        fillColor: MaterialStateProperty.resolveWith<Color>(
          (Set<MaterialState> states) {
            if (states.contains(MaterialState.selected)) {
              return AppColors.primaryGreen;
            }
            return AppColors.textSecondaryOnLight;
          },
        ),
      ),
    );
  }
  
  /// Dark theme variant (optional for future use)
  static ThemeData get darkTheme {
    return lightTheme.copyWith(
      colorScheme: const ColorScheme.dark(
        primary: AppColors.primaryGreen,
        onPrimary: AppColors.textOnPrimary,
        secondary: Color(0xFF2D2D2D),
        onSecondary: AppColors.pureWhite,
        surface: Color(0xFF1E1E1E),
        onSurface: AppColors.pureWhite,
        background: Color(0xFF121212),
        onBackground: AppColors.pureWhite,
        error: AppColors.error,
        onError: AppColors.errorText,
      ),
    );
  }
  
  /// Get text style for specific background color
  static TextStyle getTextStyleForBackground(Color backgroundColor, {
    double fontSize = 16,
    FontWeight fontWeight = FontWeight.normal,
    bool isLargeText = false,
  }) {
    final textColor = _getOptimalTextColor(backgroundColor, isLargeText);
    return TextStyle(
      color: textColor,
      fontSize: fontSize,
      fontWeight: fontWeight,
    );
  }
  
  /// Get optimal text color for a background
  static Color _getOptimalTextColor(Color backgroundColor, bool isLargeText) {
    final whiteContrast = AppColors.calculateContrastRatio(AppColors.pureWhite, backgroundColor);
    final darkContrast = AppColors.calculateContrastRatio(AppColors.textOnWhite, backgroundColor);
    
    final requiredRatio = isLargeText ? 3.0 : 4.5;
    
    if (whiteContrast >= requiredRatio && darkContrast >= requiredRatio) {
      // Both meet requirements, choose based on background lightness
      final hsl = HSLColor.fromColor(backgroundColor);
      return hsl.lightness > 0.5 ? AppColors.textOnWhite : AppColors.pureWhite;
    }
    
    return whiteContrast > darkContrast ? AppColors.pureWhite : AppColors.textOnWhite;
  }
}
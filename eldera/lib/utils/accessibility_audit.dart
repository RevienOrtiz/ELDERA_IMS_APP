import 'package:flutter/material.dart';
import '../config/app_colors.dart';
import 'contrast_utils.dart';

/// Accessibility audit report for the Eldera app
/// Identifies contrast ratio issues and provides recommendations
class AccessibilityAudit {
  
  /// Audit results for different screen elements
  static Map<String, AccessibilityIssue> auditCurrentImplementation() {
    Map<String, AccessibilityIssue> issues = {};
    
    // Home Screen Issues
    issues['home_category_text'] = AccessibilityIssue(
      element: 'Category filter text on colored backgrounds',
      currentColors: 'Colors.black87 on various category colors',
      contrastRatio: _calculateWorstCaseRatio(),
      isCompliant: false,
      severity: IssueSeverity.high,
      recommendation: 'Use AppColors.textOnLight for better contrast',
      location: 'home_screen.dart:285',
    );
    
    issues['home_error_text'] = AccessibilityIssue(
      element: 'Error message text',
      currentColors: 'Colors.grey on ivory white background',
      contrastRatio: AppColors.calculateContrastRatio(Colors.grey, AppColors.ivoryWhite),
      isCompliant: false,
      severity: IssueSeverity.medium,
      recommendation: 'Use AppColors.textSecondaryOnLight',
      location: 'home_screen.dart:350',
    );
    
    // Login Screen Issues
    issues['login_subtitle'] = AccessibilityIssue(
      element: 'Login subtitle text',
      currentColors: 'Colors.white70 on primary green',
      contrastRatio: AppColors.calculateContrastRatio(Colors.white70, AppColors.primaryGreen),
      isCompliant: false,
      severity: IssueSeverity.medium,
      recommendation: 'Use AppColors.textSecondaryOnPrimary',
      location: 'login_screen.dart:240',
    );
    
    issues['login_form_title'] = AccessibilityIssue(
      element: 'Login form title',
      currentColors: 'Color(0xFF2D5A5A) on white',
      contrastRatio: AppColors.calculateContrastRatio(const Color(0xFF2D5A5A), Colors.white),
      isCompliant: true,
      severity: IssueSeverity.none,
      recommendation: 'Already compliant, consider using AppColors.textOnWhite for consistency',
      location: 'login_screen.dart:270',
    );
    
    // Profile Screen Issues
    issues['profile_user_info'] = AccessibilityIssue(
      element: 'User info text on primary green',
      currentColors: 'Colors.white70 on primary green',
      contrastRatio: AppColors.calculateContrastRatio(Colors.white70, AppColors.primaryGreen),
      isCompliant: false,
      severity: IssueSeverity.medium,
      recommendation: 'Use AppColors.textSecondaryOnPrimary',
      location: 'profile_screen.dart:389-434',
    );
    
    // Settings Screen Issues
    issues['settings_secondary_text'] = AccessibilityIssue(
      element: 'Settings secondary text',
      currentColors: 'Colors.grey.shade600 on white',
      contrastRatio: AppColors.calculateContrastRatio(Colors.grey.shade600, Colors.white),
      isCompliant: true,
      severity: IssueSeverity.none,
      recommendation: 'Consider using AppColors.textSecondaryOnLight for consistency',
      location: 'settings_screen.dart:393',
    );
    
    // Schedule Screen Issues
    issues['schedule_status_text'] = AccessibilityIssue(
      element: 'Schedule status text',
      currentColors: 'Colors.grey[600] on white',
      contrastRatio: AppColors.calculateContrastRatio(Colors.grey[600]!, Colors.white),
      isCompliant: true,
      severity: IssueSeverity.none,
      recommendation: 'Already compliant',
      location: 'schedule_screen.dart:225',
    );
    
    // Notifications Screen Issues
    issues['notification_content'] = AccessibilityIssue(
      element: 'Notification content text',
      currentColors: 'Colors.black87 on white',
      contrastRatio: AppColors.calculateContrastRatio(Colors.black87, Colors.white),
      isCompliant: true,
      severity: IssueSeverity.none,
      recommendation: 'Already compliant',
      location: 'notifications_screen.dart:258',
    );
    
    // Splash Screen Issues
    issues['splash_subtitle'] = AccessibilityIssue(
      element: 'Splash screen subtitle',
      currentColors: 'Colors.white.withOpacity(0.9) on blue',
      contrastRatio: AppColors.calculateContrastRatio(
        Colors.white.withOpacity(0.9), 
        const Color(0xFF2196F3)
      ),
      isCompliant: false,
      severity: IssueSeverity.medium,
      recommendation: 'Use solid white or increase opacity to 1.0',
      location: 'splash_screen.dart:154',
    );
    
    return issues;
  }
  
  /// Calculate worst-case contrast ratio for category colors
  static double _calculateWorstCaseRatio() {
    final categoryColors = [
      const Color(0xFFFFB6C1), // Pink
      const Color(0xFFB8D4E6), // Baby blue
      const Color(0xFFB8E6B8), // Green
      Colors.grey[300]!,        // Default
    ];
    
    double worstRatio = double.infinity;
    for (final color in categoryColors) {
      final ratio = AppColors.calculateContrastRatio(Colors.black87, color);
      if (ratio < worstRatio) {
        worstRatio = ratio;
      }
    }
    return worstRatio;
  }
  
  /// Generate a summary report of all issues
  static AccessibilityReport generateReport() {
    final issues = auditCurrentImplementation();
    
    final highIssues = issues.values.where((i) => i.severity == IssueSeverity.high).length;
    final mediumIssues = issues.values.where((i) => i.severity == IssueSeverity.medium).length;
    final lowIssues = issues.values.where((i) => i.severity == IssueSeverity.low).length;
    final compliantElements = issues.values.where((i) => i.isCompliant).length;
    
    return AccessibilityReport(
      totalElements: issues.length,
      compliantElements: compliantElements,
      highSeverityIssues: highIssues,
      mediumSeverityIssues: mediumIssues,
      lowSeverityIssues: lowIssues,
      issues: issues,
    );
  }
  
  /// Print a detailed audit report
  static void printAuditReport() {
    final report = generateReport();
    
    print('\n=== ELDERA ACCESSIBILITY AUDIT REPORT ===');
    print('Total Elements Audited: ${report.totalElements}');
    print('Compliant Elements: ${report.compliantElements}');
    print('High Severity Issues: ${report.highSeverityIssues}');
    print('Medium Severity Issues: ${report.mediumSeverityIssues}');
    print('Low Severity Issues: ${report.lowSeverityIssues}');
    print('\nCompliance Rate: ${((report.compliantElements / report.totalElements) * 100).toStringAsFixed(1)}%');
    
    print('\n=== DETAILED ISSUES ===');
    report.issues.forEach((key, issue) {
      if (!issue.isCompliant) {
        print('\n${issue.severity.name.toUpperCase()}: ${issue.element}');
        print('  Current: ${issue.currentColors}');
        print('  Contrast: ${issue.contrastRatio.toStringAsFixed(2)}:1');
        print('  Location: ${issue.location}');
        print('  Fix: ${issue.recommendation}');
      }
    });
    
    print('\n=== COMPLIANT ELEMENTS ===');
    report.issues.forEach((key, issue) {
      if (issue.isCompliant) {
        print('✓ ${issue.element} (${issue.contrastRatio.toStringAsFixed(2)}:1)');
      }
    });
  }
}

/// Represents an accessibility issue found during audit
class AccessibilityIssue {
  final String element;
  final String currentColors;
  final double contrastRatio;
  final bool isCompliant;
  final IssueSeverity severity;
  final String recommendation;
  final String location;
  
  AccessibilityIssue({
    required this.element,
    required this.currentColors,
    required this.contrastRatio,
    required this.isCompliant,
    required this.severity,
    required this.recommendation,
    required this.location,
  });
}

/// Severity levels for accessibility issues
enum IssueSeverity {
  none,
  low,
  medium,
  high,
}

/// Complete accessibility audit report
class AccessibilityReport {
  final int totalElements;
  final int compliantElements;
  final int highSeverityIssues;
  final int mediumSeverityIssues;
  final int lowSeverityIssues;
  final Map<String, AccessibilityIssue> issues;
  
  AccessibilityReport({
    required this.totalElements,
    required this.compliantElements,
    required this.highSeverityIssues,
    required this.mediumSeverityIssues,
    required this.lowSeverityIssues,
    required this.issues,
  });
}
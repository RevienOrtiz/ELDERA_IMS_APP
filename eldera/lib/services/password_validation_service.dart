import 'package:flutter/material.dart';
import '../config/environment_config.dart';

/// Password Validation Service for Enhanced Security
///
/// Implements strong password policies including:
/// - Minimum length requirements
/// - Character complexity (uppercase, lowercase, numbers, special characters)
/// - Common password detection
/// - Security pattern validation
/// - Account lockout tracking
class PasswordValidationService {
  // Password complexity requirements
  static const int _minLength = 8;
  static const int _maxLength = 128;

  // Account lockout tracking
  static final Map<String, List<DateTime>> _failedAttempts = {};
  static final Map<String, DateTime> _lockedAccounts = {};

  /// Validates password complexity and security requirements
  static PasswordValidationResult validatePassword(String password) {
    final errors = <String>[];
    final warnings = <String>[];

    // Length validation
    if (password.isEmpty) {
      errors.add('Password is required');
      return PasswordValidationResult(isValid: false, errors: errors);
    }

    if (password.length < _minLength) {
      errors.add('Password must be at least $_minLength characters long');
    }

    if (password.length > _maxLength) {
      errors.add('Password must not exceed $_maxLength characters');
    }

    // Character complexity validation
    if (!_hasUppercase(password)) {
      errors.add('Password must contain at least one uppercase letter (A-Z)');
    }

    if (!_hasLowercase(password)) {
      errors.add('Password must contain at least one lowercase letter (a-z)');
    }

    if (!_hasNumber(password)) {
      errors.add('Password must contain at least one number (0-9)');
    }

    // Special characters are now optional for compatibility with backend
    if (!_hasSpecialCharacter(password)) {
      warnings.add(
          'For stronger security, consider adding a special character (!@#\$%^&*)');
    }

    // Security pattern validation
    if (_containsSuspiciousPatterns(password)) {
      errors.add('Password contains invalid or suspicious characters');
    }

    // Common password detection
    if (_isCommonPassword(password)) {
      errors.add(
          'This password is too common. Please choose a more unique password');
    }

    // Sequential character detection
    if (_hasSequentialCharacters(password)) {
      warnings.add('Avoid using sequential characters (e.g., 123, abc)');
    }

    // Repeated character detection
    if (_hasRepeatedCharacters(password)) {
      warnings.add('Avoid using repeated characters (e.g., aaa, 111)');
    }

    return PasswordValidationResult(
      isValid: errors.isEmpty,
      errors: errors,
      warnings: warnings,
      strength: _calculatePasswordStrength(password),
    );
  }

  /// Validates email format and security
  static EmailValidationResult validateEmail(String email) {
    final errors = <String>[];

    if (email.isEmpty) {
      errors.add('Email is required');
      return EmailValidationResult(isValid: false, errors: errors);
    }

    // Basic email format validation
    final emailRegex =
        RegExp(r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$');
    if (!emailRegex.hasMatch(email)) {
      errors.add('Please enter a valid email address');
    }

    // Security validation
    if (_containsSuspiciousPatterns(email)) {
      errors.add('Email contains invalid characters');
    }

    // Length validation
    if (email.length > 254) {
      errors.add('Email address is too long');
    }

    return EmailValidationResult(isValid: errors.isEmpty, errors: errors);
  }

  /// Tracks failed login attempts and implements account lockout
  static bool recordFailedAttempt(String email) {
    final now = DateTime.now();
    final attempts = _failedAttempts[email] ?? [];

    // Remove attempts older than lockout duration
    attempts.removeWhere((attempt) =>
        now.difference(attempt) > EnvironmentConfig.lockoutDuration);

    // Add current failed attempt
    attempts.add(now);
    _failedAttempts[email] = attempts;

    // Check if account should be locked
    if (attempts.length >= EnvironmentConfig.maxLoginAttempts) {
      _lockedAccounts[email] = now;
      return true; // Account is now locked
    }

    return false; // Account is not locked
  }

  /// Checks if an account is currently locked
  static bool isAccountLocked(String email) {
    final lockTime = _lockedAccounts[email];
    if (lockTime == null) return false;

    final now = DateTime.now();
    if (now.difference(lockTime) > EnvironmentConfig.lockoutDuration) {
      // Lockout period has expired
      _lockedAccounts.remove(email);
      _failedAttempts.remove(email);
      return false;
    }

    return true;
  }

  /// Gets remaining lockout time for an account
  static Duration? getRemainingLockoutTime(String email) {
    final lockTime = _lockedAccounts[email];
    if (lockTime == null) return null;

    final now = DateTime.now();
    final elapsed = now.difference(lockTime);
    final remaining = EnvironmentConfig.lockoutDuration - elapsed;

    return remaining.isNegative ? null : remaining;
  }

  /// Clears failed attempts for successful login
  static void clearFailedAttempts(String email) {
    _failedAttempts.remove(email);
    _lockedAccounts.remove(email);
  }

  // Private helper methods
  static bool _hasUppercase(String password) {
    return RegExp(r'[A-Z]').hasMatch(password);
  }

  static bool _hasLowercase(String password) {
    return RegExp(r'[a-z]').hasMatch(password);
  }

  static bool _hasNumber(String password) {
    return RegExp(r'[0-9]').hasMatch(password);
  }

  static bool _hasSpecialCharacter(String password) {
    return RegExp(r'[!@#\$%^&*(),.?":{}|<>]').hasMatch(password);
  }

  static bool _containsSuspiciousPatterns(String input) {
    final suspiciousPatterns = [
      RegExp(r'<script', caseSensitive: false),
      RegExp(r'javascript:', caseSensitive: false),
      RegExp(r'data:', caseSensitive: false),
      RegExp(r'vbscript:', caseSensitive: false),
      RegExp(r'onload=', caseSensitive: false),
      RegExp(r'onerror=', caseSensitive: false),
      RegExp(r'eval\(', caseSensitive: false),
    ];

    return suspiciousPatterns.any((pattern) => pattern.hasMatch(input));
  }

  static bool _isCommonPassword(String password) {
    final commonPasswords = [
      'password',
      '123456',
      '123456789',
      'qwerty',
      'abc123',
      'password123',
      'admin',
      'letmein',
      'welcome',
      'monkey',
      'dragon',
      'master',
      'shadow',
      'superman',
      'michael',
      'football',
      'baseball',
      'liverpool',
      'jordan',
      'princess',
    ];

    return commonPasswords.contains(password.toLowerCase());
  }

  static bool _hasSequentialCharacters(String password) {
    final sequences = [
      '123',
      '234',
      '345',
      '456',
      '567',
      '678',
      '789',
      'abc',
      'bcd',
      'cde',
      'def',
      'efg',
      'fgh',
      'ghi'
    ];

    return sequences.any((seq) =>
        password.toLowerCase().contains(seq) ||
        password.toLowerCase().contains(seq.split('').reversed.join()));
  }

  static bool _hasRepeatedCharacters(String password) {
    for (int i = 0; i < password.length - 2; i++) {
      if (password[i] == password[i + 1] && password[i] == password[i + 2]) {
        return true;
      }
    }
    return false;
  }

  static PasswordStrength _calculatePasswordStrength(String password) {
    int score = 0;

    // Length scoring
    if (password.length >= 8) score += 1;
    if (password.length >= 12) score += 1;
    if (password.length >= 16) score += 1;

    // Character variety scoring
    if (_hasUppercase(password)) score += 1;
    if (_hasLowercase(password)) score += 1;
    if (_hasNumber(password)) score += 1;
    if (_hasSpecialCharacter(password)) score += 1;

    // Complexity scoring
    if (!_hasSequentialCharacters(password)) score += 1;
    if (!_hasRepeatedCharacters(password)) score += 1;
    if (!_isCommonPassword(password)) score += 1;

    if (score <= 3) return PasswordStrength.weak;
    if (score <= 6) return PasswordStrength.medium;
    if (score <= 8) return PasswordStrength.strong;
    return PasswordStrength.veryStrong;
  }
}

/// Password validation result
class PasswordValidationResult {
  final bool isValid;
  final List<String> errors;
  final List<String> warnings;
  final PasswordStrength strength;

  const PasswordValidationResult({
    required this.isValid,
    required this.errors,
    this.warnings = const [],
    this.strength = PasswordStrength.weak,
  });
}

/// Email validation result
class EmailValidationResult {
  final bool isValid;
  final List<String> errors;

  const EmailValidationResult({
    required this.isValid,
    required this.errors,
  });
}

/// Password strength levels
enum PasswordStrength {
  weak,
  medium,
  strong,
  veryStrong,
}

/// Extension for password strength display
extension PasswordStrengthExtension on PasswordStrength {
  String get displayName {
    switch (this) {
      case PasswordStrength.weak:
        return 'Weak';
      case PasswordStrength.medium:
        return 'Medium';
      case PasswordStrength.strong:
        return 'Strong';
      case PasswordStrength.veryStrong:
        return 'Very Strong';
    }
  }

  Color get color {
    switch (this) {
      case PasswordStrength.weak:
        return const Color(0xFFE53E3E); // Red
      case PasswordStrength.medium:
        return const Color(0xFFED8936); // Orange
      case PasswordStrength.strong:
        return const Color(0xFF38A169); // Green
      case PasswordStrength.veryStrong:
        return const Color(0xFF2D3748); // Dark Green
    }
  }
}

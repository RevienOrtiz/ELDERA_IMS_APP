import 'package:flutter/material.dart';
import 'package:flutter/foundation.dart';
import '../services/auth_service.dart';
import '../services/font_size_service.dart';
import '../services/password_validation_service.dart';
import '../config/app_colors.dart';
import 'main_screen.dart';
import 'registration_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({Key? key}) : super(key: key);

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController _oscaIdController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  // Using SupabaseAuthService instead of AuthService
  late final FontSizeService _fontSizeService;
  bool _isLoading = false;
  bool _obscurePassword = true;

  @override
  void initState() {
    super.initState();
    _initializeServices();
  }

  Future<void> _initializeServices() async {
    _fontSizeService = FontSizeService.instance;
    await _fontSizeService.init();
    _checkLoginStatus();
  }

  double _getSafeScaledFontSize({
    double? baseSize,
    bool isTitle = false,
    bool isSubtitle = false,
    bool isCaption = false,
  }) {
    // Check if FontSizeService is properly initialized
    if (!_fontSizeService.isInitialized) {
      // Return default font size if service not initialized
      double defaultSize = 20.0;
      double scaleFactor = baseSize ?? 1.0;

      if (isTitle) {
        scaleFactor = 1.2;
      } else if (isSubtitle) {
        scaleFactor = 1.1;
      } else if (isCaption) {
        scaleFactor = 0.9;
      }

      return defaultSize * scaleFactor;
    }

    return _fontSizeService.getScaledFontSize(
      baseSize: baseSize ?? 1.0,
      isTitle: isTitle,
      isSubtitle: isSubtitle,
      isCaption: isCaption,
    );
  }

  Future<void> _checkLoginStatus() async {
    try {
      if (AuthService.isAuthenticated()) {
        // User is already logged in, navigate to main screen
        if (mounted) {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (context) => const MainScreen()),
          );
        }
      }
    } catch (e) {
      // Handle initialization error
      print('Error checking auth status: $e');
    }
  }

  String? _validateOscaId(String oscaId) {
    // Basic OSCA ID validation - accepts format like 2025-6248
    if (oscaId.isEmpty) {
      return 'OSCA ID is required';
    }
    
    // Simple format check for OSCA ID (can be customized based on actual format)
    final oscaIdRegex = RegExp(r'^\d{4}-\d{4}$');
    if (!oscaIdRegex.hasMatch(oscaId)) {
      return 'Please enter a valid OSCA ID (format: YYYY-NNNN)';
    }
    
    return null;
  }

  String? _validatePassword(String password) {
    // Simplified validation for login compatibility with backend
    if (password.isEmpty) {
      return 'Password is required';
    }
    if (password.length < 6) {
      return 'Password must be at least 6 characters';
    }
    return null;
  }

  String _sanitizeInput(String input) {
    return input
        .trim()
        .replaceAll(RegExp(r'[<>"/\\]'), '')
        .replaceAll(RegExp(r"[']"), '')
        .replaceAll(RegExp(r'\s+'), ' ');
  }

  Future<void> _login() async {
    final oscaId = _oscaIdController.text.trim();
    final password = _passwordController.text;

    // Check if account is locked
    if (PasswordValidationService.isAccountLocked(oscaId)) {
      final remainingTime =
          PasswordValidationService.getRemainingLockoutTime(oscaId);
      if (remainingTime != null) {
        _showMessage(
          'Account is locked. Please try again in ${remainingTime.inMinutes} minutes.',
          isError: true,
        );
        return;
      }
    }

    // Validate inputs
    final oscaIdError = _validateOscaId(oscaId);
    if (oscaIdError != null) {
      _showMessage(oscaIdError, isError: true);
      return;
    }

    final passwordError = _validatePassword(password);
    if (passwordError != null) {
      _showMessage(passwordError, isError: true);
      return;
    }

    setState(() {
      _isLoading = true;
    });

    try {
      // Sanitize inputs before sending to server
      final sanitizedOscaId = _sanitizeInput(oscaId);

      final result = await AuthService.signIn(
        oscaId: sanitizedOscaId,
        password:
            password, // Don't sanitize password as it might affect authentication
      );

      if (result['success']) {
        // Clear failed attempts on successful login
        PasswordValidationService.clearFailedAttempts(oscaId);

        _showMessage(result['message'], isError: false);
        // Navigate to main screen
        if (mounted) {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (context) => const MainScreen()),
          );
        }
      } else {
        // Record failed attempt and check for lockout
        final isLocked = PasswordValidationService.recordFailedAttempt(oscaId);

        if (isLocked) {
          _showMessage(
            'Too many failed attempts. Account locked for security.',
            isError: true,
          );
        } else {
          _showMessage(result['message'] ?? 'Invalid OSCA ID or password',
              isError: true);
        }
      }
    } catch (e) {
      // Record failed attempt for any authentication error
      final isLocked = PasswordValidationService.recordFailedAttempt(oscaId);

      // Sanitize error messages to prevent information disclosure
      String errorMessage = 'Login failed. Please try again.';
      if (isLocked) {
        errorMessage = 'Too many failed attempts. Account locked for security.';
      } else if (e.toString().contains('Authentication')) {
        errorMessage =
            'Invalid credentials. Please check your OSCA ID and password.';
      } else if (e.toString().contains('network') ||
          e.toString().contains('timeout')) {
        errorMessage =
            'Network error. Please check your connection and try again.';
      }
      _showMessage(errorMessage, isError: true);
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  void _showMessage(String message, {required bool isError}) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: isError ? Colors.red : Colors.green,
        duration: const Duration(seconds: 3),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF006662),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              const SizedBox(height: 60),
              // App Logo/Title
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Column(
                  children: [
                    Icon(
                      Icons.elderly,
                      size: 80,
                      color: Colors.white,
                    ),
                    const SizedBox(height: 16),
                    Text(
                      'ELDERA',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: _getSafeScaledFontSize(
                            isTitle: true, baseSize: 2.0),
                        fontWeight: FontWeight.bold,
                        letterSpacing: 2,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'Senior Citizen Information System',
                      style: TextStyle(
                        color: AppColors.textSecondaryOnPrimary,
                        fontSize: _getSafeScaledFontSize(),
                      ),
                      textAlign: TextAlign.center,
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 60),
              // Login Form
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(20),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.1),
                      blurRadius: 10,
                      offset: const Offset(0, 5),
                    ),
                  ],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    Text(
                      'Login to Your Account',
                      style: TextStyle(
                        fontSize: _getSafeScaledFontSize(isSubtitle: true),
                        fontWeight: FontWeight.bold,
                        color: AppColors.textOnWhite,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 32),
                    // OSCA ID Field
                    TextField(
                      controller: _oscaIdController,
                      keyboardType: TextInputType.text,
                      decoration: InputDecoration(
                        labelText: 'Enter your OSCA ID no.',
                        hintText: 'Enter your OSCA ID no.',
                        prefixIcon: const Icon(Icons.badge),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        focusedBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                          borderSide: const BorderSide(
                              color: Color(0xFF2D5A5A), width: 2),
                        ),
                      ),
                    ),
                    const SizedBox(height: 20),
                    // Password Field
                    TextField(
                      controller: _passwordController,
                      obscureText: _obscurePassword,
                      decoration: InputDecoration(
                        labelText: 'Password',
                        hintText: 'Enter your password',
                        prefixIcon: const Icon(Icons.lock),
                        suffixIcon: IconButton(
                          icon: Icon(
                            _obscurePassword
                                ? Icons.visibility
                                : Icons.visibility_off,
                          ),
                          onPressed: () {
                            setState(() {
                              _obscurePassword = !_obscurePassword;
                            });
                          },
                        ),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        focusedBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                          borderSide: const BorderSide(
                              color: Color(0xFF2D5A5A), width: 2),
                        ),
                      ),
                    ),
                    const SizedBox(height: 32),
                    // Login Button
                    ElevatedButton(
                      onPressed: _isLoading ? null : _login,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF2D5A5A),
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 16),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        elevation: 2,
                      ),
                      child: _isLoading
                          ? const SizedBox(
                              height: 20,
                              width: 20,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                valueColor:
                                    AlwaysStoppedAnimation<Color>(Colors.white),
                              ),
                            )
                          : Text(
                              'LOGIN',
                              style: TextStyle(
                                fontSize:
                                    _getSafeScaledFontSize(isSubtitle: true),
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 40),
              // Registration Link
              TextButton(
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => const RegistrationScreen()),
                  );
                },
                style: TextButton.styleFrom(
                  backgroundColor: Colors.white.withOpacity(0.1),
                  padding: const EdgeInsets.all(16),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      Icons.person_add,
                      color: Colors.white,
                      size: 24,
                    ),
                    const SizedBox(width: 8),
                    Text(
                      'Create New Account',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: _getSafeScaledFontSize(baseSize: 1.1),
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _oscaIdController.dispose();
    _passwordController.dispose();
    super.dispose();
  }
}

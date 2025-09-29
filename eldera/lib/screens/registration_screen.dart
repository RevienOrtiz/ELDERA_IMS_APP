import 'package:flutter/material.dart';
import '../services/auth_service.dart';
import '../services/font_size_service.dart';
import '../services/password_validation_service.dart';
import '../config/app_colors.dart';
import 'login_screen.dart';

class RegistrationScreen extends StatefulWidget {
  const RegistrationScreen({Key? key}) : super(key: key);

  @override
  State<RegistrationScreen> createState() => _RegistrationScreenState();
}

class _RegistrationScreenState extends State<RegistrationScreen> {
  final TextEditingController _oscaIdController = TextEditingController();
  final TextEditingController _firstNameController = TextEditingController();
  final TextEditingController _lastNameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _confirmPasswordController = TextEditingController();
  
  late final FontSizeService _fontSizeService;
  bool _isLoading = false;
  bool _obscurePassword = true;
  bool _obscureConfirmPassword = true;

  @override
  void initState() {
    super.initState();
    _fontSizeService = FontSizeService.instance;
  }

  double _getSafeScaledFontSize({
    bool isTitle = false,
    bool isSubtitle = false,
    double baseSize = 1.0,
  }) {
    double scaleFactor = _fontSizeService.getCurrentScaleFactor();
    double size = 16.0 * baseSize;
    
    if (isTitle) {
      size = 24.0;
    } else if (isSubtitle) {
      size = 18.0;
    }
    
    return size * scaleFactor;
  }

  String? _validateOscaId(String oscaId) {
    if (oscaId.isEmpty) {
      return 'OSCA ID is required';
    }
    if (oscaId.length < 4) {
      return 'OSCA ID must be at least 4 characters';
    }
    return null;
  }

  String? _validateName(String name, String fieldName) {
    if (name.isEmpty) {
      return '$fieldName is required';
    }
    return null;
  }

  String? _validateEmail(String email) {
    if (email.isEmpty) {
      return 'Email is required';
    }
    final emailRegex = RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$');
    if (!emailRegex.hasMatch(email)) {
      return 'Please enter a valid email address';
    }
    return null;
  }

  String? _validatePassword(String password) {
    if (password.isEmpty) {
      return 'Password is required';
    }
    if (password.length < 6) {
      return 'Password must be at least 6 characters';
    }
    return null;
  }

  String? _validateConfirmPassword(String password, String confirmPassword) {
    if (confirmPassword.isEmpty) {
      return 'Please confirm your password';
    }
    if (password != confirmPassword) {
      return 'Passwords do not match';
    }
    return null;
  }

  String _sanitizeInput(String input) {
    // Basic sanitization to prevent injection attacks
    return input.trim();
  }

  Future<void> _register() async {
    final oscaId = _oscaIdController.text.trim();
    final firstName = _firstNameController.text.trim();
    final lastName = _lastNameController.text.trim();
    final email = _emailController.text.trim();
    final password = _passwordController.text;
    final confirmPassword = _confirmPasswordController.text;

    // Validate inputs
    final oscaIdError = _validateOscaId(oscaId);
    if (oscaIdError != null) {
      _showMessage(oscaIdError, isError: true);
      return;
    }

    final firstNameError = _validateName(firstName, 'First name');
    if (firstNameError != null) {
      _showMessage(firstNameError, isError: true);
      return;
    }

    final lastNameError = _validateName(lastName, 'Last name');
    if (lastNameError != null) {
      _showMessage(lastNameError, isError: true);
      return;
    }

    final emailError = _validateEmail(email);
    if (emailError != null) {
      _showMessage(emailError, isError: true);
      return;
    }

    final passwordError = _validatePassword(password);
    if (passwordError != null) {
      _showMessage(passwordError, isError: true);
      return;
    }

    final confirmPasswordError = _validateConfirmPassword(password, confirmPassword);
    if (confirmPasswordError != null) {
      _showMessage(confirmPasswordError, isError: true);
      return;
    }

    setState(() {
      _isLoading = true;
    });

    try {
      // Sanitize inputs before sending to server
      final sanitizedOscaId = _sanitizeInput(oscaId);
      final sanitizedFirstName = _sanitizeInput(firstName);
      final sanitizedLastName = _sanitizeInput(lastName);
      final sanitizedEmail = _sanitizeInput(email);

      final result = await AuthService.register(
        oscaId: sanitizedOscaId,
        firstName: sanitizedFirstName,
        lastName: sanitizedLastName,
        email: sanitizedEmail,
        password: password, // Don't sanitize password as it might affect authentication
      );

      if (result['success']) {
        _showMessage('Registration successful!', isError: false);
        // Navigate to login screen
        if (mounted) {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (context) => const LoginScreen()),
          );
        }
      } else {
        _showMessage(result['message'] ?? 'Registration failed', isError: true);
      }
    } catch (e) {
      String errorMessage = 'Registration failed. Please try again.';
      if (e.toString().contains('network') || e.toString().contains('timeout')) {
        errorMessage = 'Network error. Please check your connection and try again.';
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
      appBar: AppBar(
        backgroundColor: const Color(0xFF006662),
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.white),
          onPressed: () {
            Navigator.of(context).pop();
          },
        ),
        title: Text(
          'Create Account',
          style: TextStyle(
            color: Colors.white,
            fontSize: _getSafeScaledFontSize(isSubtitle: true),
          ),
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              // Registration Form
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
                      'Register New Account',
                      style: TextStyle(
                        fontSize: _getSafeScaledFontSize(isSubtitle: true),
                        fontWeight: FontWeight.bold,
                        color: AppColors.textOnWhite,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 24),
                    
                    // OSCA ID Field
                    TextField(
                      controller: _oscaIdController,
                      keyboardType: TextInputType.text,
                      decoration: InputDecoration(
                        labelText: 'Enter your OSCA ID no.',
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
                    const SizedBox(height: 16),
                    
                    // First Name Field
                    TextField(
                      controller: _firstNameController,
                      keyboardType: TextInputType.name,
                      decoration: InputDecoration(
                        labelText: 'First Name',
                        prefixIcon: const Icon(Icons.person),
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
                    const SizedBox(height: 16),
                    
                    // Last Name Field
                    TextField(
                      controller: _lastNameController,
                      keyboardType: TextInputType.name,
                      decoration: InputDecoration(
                        labelText: 'Last Name',
                        prefixIcon: const Icon(Icons.person),
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
                    const SizedBox(height: 16),
                    
                    // Email Field
                    TextField(
                      controller: _emailController,
                      keyboardType: TextInputType.emailAddress,
                      decoration: InputDecoration(
                        labelText: 'Email',
                        prefixIcon: const Icon(Icons.email),
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
                    const SizedBox(height: 16),
                    
                    // Password Field
                    TextField(
                      controller: _passwordController,
                      obscureText: _obscurePassword,
                      decoration: InputDecoration(
                        labelText: 'Password',
                        prefixIcon: const Icon(Icons.lock),
                        suffixIcon: IconButton(
                          icon: Icon(
                            _obscurePassword
                                ? Icons.visibility_off
                                : Icons.visibility,
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
                    const SizedBox(height: 16),
                    
                    // Confirm Password Field
                    TextField(
                      controller: _confirmPasswordController,
                      obscureText: _obscureConfirmPassword,
                      decoration: InputDecoration(
                        labelText: 'Confirm Password',
                        prefixIcon: const Icon(Icons.lock),
                        suffixIcon: IconButton(
                          icon: Icon(
                            _obscureConfirmPassword
                                ? Icons.visibility_off
                                : Icons.visibility,
                          ),
                          onPressed: () {
                            setState(() {
                              _obscureConfirmPassword = !_obscureConfirmPassword;
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
                    
                    // Register Button
                    ElevatedButton(
                      onPressed: _isLoading ? null : _register,
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
                              'REGISTER',
                              style: TextStyle(
                                fontSize:
                                    _getSafeScaledFontSize(isSubtitle: true),
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                    ),
                    const SizedBox(height: 16),
                    
                    // Login Link
                    TextButton(
                      onPressed: () {
                        Navigator.pushReplacement(
                          context,
                          MaterialPageRoute(builder: (context) => const LoginScreen()),
                        );
                      },
                      child: Text(
                        'Already have an account? Login',
                        style: TextStyle(
                          color: const Color(0xFF2D5A5A),
                          fontSize: _getSafeScaledFontSize(),
                        ),
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
    _firstNameController.dispose();
    _lastNameController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }
}
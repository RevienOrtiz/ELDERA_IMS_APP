import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'dart:typed_data';
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../services/font_size_service.dart';
import '../services/user_service.dart';
import '../services/auth_service.dart';
import '../services/language_service.dart';
import '../models/user.dart' as app_user;
import 'admin_simulation_screen.dart';
import 'login_screen.dart';
import '../config/app_colors.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final FontSizeService _fontSizeService = FontSizeService.instance;
  final LanguageService _languageService = LanguageService.instance;
  final ImagePicker _picker = ImagePicker();
  // Using UserService and AuthService
  Uint8List? _selectedImage; // Use Uint8List for both web and mobile
  app_user.User? _currentUser;

  Future<void> _saveProfileImage(Uint8List imageBytes, String fileName) async {
    try {
      if (_currentUser?.id == null) {
        throw Exception('User not authenticated');
      }

      // Upload via UserService
      final result = await UserService.updateProfileImage(
        userId: _currentUser!.id,
        imageBytes: imageBytes,
        fileName: fileName,
      );

      if (result['success']) {
        // Update current user with new image URL
        if (_currentUser != null) {
          _currentUser = _currentUser!.copyWith(
            profileImageUrl: result['imageUrl'],
          );
        }

        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(result['message']),
            backgroundColor: Colors.green,
          ),
        );
      } else {
        throw Exception(result['message']);
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error saving profile image: $e'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  Future<void> _loadProfileImage() async {
    try {
      if (_currentUser?.profileImageUrl != null) {
        // Download image from storage
        final result = await UserService.downloadProfileImage(
          userId: _currentUser!.id,
          imageUrl: _currentUser!.profileImageUrl!,
        );

        if (result['success']) {
          setState(() {
            _selectedImage = result['imageData'];
          });
        }
      }
    } catch (e) {
      print('Error loading profile image: $e');
    }
  }

  Future<void> _pickImage() async {
    try {
      final XFile? image = await _picker.pickImage(
        source: ImageSource.gallery,
        maxWidth: 800,
        maxHeight: 800,
        imageQuality: 85,
      );

      if (image != null) {
        // Read as bytes for both web and mobile
        final bytes = await image.readAsBytes();
        await _saveProfileImage(bytes, image.name);
        setState(() {
          _selectedImage = bytes;
        });
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error picking image: $e'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  void _showImageSourceDialog() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(_getSafeText('select_image_source')),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              ListTile(
                leading: const Icon(Icons.photo_library),
                title: Text(_getSafeText('gallery')),
                onTap: () {
                  Navigator.of(context).pop();
                  _pickImage();
                },
              ),
              ListTile(
                leading: const Icon(Icons.camera_alt),
                title: Text(_getSafeText('camera')),
                onTap: () {
                  Navigator.of(context).pop();
                  _pickImageFromCamera();
                },
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: Text(_getSafeText('cancel')),
            ),
          ],
        );
      },
    );
  }

  Future<void> _pickImageFromCamera() async {
    try {
      final XFile? image = await _picker.pickImage(
        source: ImageSource.camera,
        maxWidth: 800,
        maxHeight: 800,
        imageQuality: 85,
      );

      if (image != null) {
        // Read as bytes for both web and mobile
        final bytes = await image.readAsBytes();
        await _saveProfileImage(bytes, image.name);
        setState(() {
          _selectedImage = bytes;
        });
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error taking photo: $e'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  void initState() {
    super.initState();
    _initializeUserData();
  }

  Future<void> _initializeUserData() async {
    await _fontSizeService.init();
    await _languageService.init();
    // Get current user from SupabaseUserService
    _currentUser = await UserService.getCurrentUser();
    setState(() {});
    await _loadProfileImage();
  }

  String _getSafeText(String key) {
    try {
      return _languageService.getText(key);
    } catch (e) {
      return key.toUpperCase();
    }
  }

  double _getSafeScaledFontSize({
    double? baseSize,
    bool isTitle = false,
    bool isSubtitle = false,
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
      }

      return defaultSize * scaleFactor;
    }

    return _fontSizeService.getScaledFontSize(
      baseSize: baseSize ?? 1.0,
      isTitle: isTitle,
      isSubtitle: isSubtitle,
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
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          _getSafeText('back'),
          style: TextStyle(
            color: Colors.white,
            fontSize: _getSafeScaledFontSize(isSubtitle: true),
            fontWeight: FontWeight.bold,
          ),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.admin_panel_settings, color: Colors.white),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => const AdminSimulationScreen(),
                ),
              ).then((_) {
                // Refresh user data when returning from admin screen
                _initializeUserData();
              });
            },
            tooltip: 'Admin Panel',
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // Profile Header Section
            Container(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  // Profile Avatar
                  GestureDetector(
                    onTap: _showImageSourceDialog,
                    child: Container(
                      width: 120,
                      height: 120,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        border: Border.all(color: Colors.white, width: 4),
                      ),
                      child: ClipOval(
                        child: _selectedImage != null
                            ? Image.memory(
                                _selectedImage!,
                                fit: BoxFit.cover,
                                width: 120,
                                height: 120,
                              )
                            : Container(
                                color: const Color(0xFF2D5A5A),
                                child: Stack(
                                  alignment: Alignment.center,
                                  children: [
                                    // Avatar illustration
                                    Container(
                                      width: 80,
                                      height: 80,
                                      decoration: const BoxDecoration(
                                        shape: BoxShape.circle,
                                        color: Color(0xFFE8B4A0), // Skin tone
                                      ),
                                    ),
                                    // Hair
                                    Positioned(
                                      top: 15,
                                      child: Container(
                                        width: 70,
                                        height: 40,
                                        decoration: const BoxDecoration(
                                          color: Color(0xFFD3D3D3), // Gray hair
                                          borderRadius: BorderRadius.only(
                                            topLeft: Radius.circular(35),
                                            topRight: Radius.circular(35),
                                          ),
                                        ),
                                      ),
                                    ),
                                    // Mustache
                                    Positioned(
                                      bottom: 25,
                                      child: Container(
                                        width: 30,
                                        height: 8,
                                        decoration: BoxDecoration(
                                          color: Colors.white,
                                          borderRadius:
                                              BorderRadius.circular(4),
                                        ),
                                      ),
                                    ),
                                    // Green shirt
                                    Positioned(
                                      bottom: 0,
                                      child: Container(
                                        width: 80,
                                        height: 30,
                                        decoration: const BoxDecoration(
                                          color: Color(0xFF4CAF50),
                                          borderRadius: BorderRadius.only(
                                            bottomLeft: Radius.circular(40),
                                            bottomRight: Radius.circular(40),
                                          ),
                                        ),
                                      ),
                                    ),
                                    // Camera icon overlay
                                    Positioned(
                                      bottom: 5,
                                      right: 5,
                                      child: Container(
                                        width: 24,
                                        height: 24,
                                        decoration: const BoxDecoration(
                                          color: Color(0xFF4CAF50),
                                          shape: BoxShape.circle,
                                        ),
                                        child: const Icon(
                                          Icons.camera_alt,
                                          color: Colors.white,
                                          size: 16,
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),
                  // User Name
                  Text(
                    _currentUser?.name ?? 'Loading...',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: _getSafeScaledFontSize(isTitle: true),
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 8),
                  // Age
                  Text(
                    _currentUser != null
                        ? '${_currentUser!.age} ${_getSafeText('years_old')}'
                        : _getSafeText('loading'),
                    style: TextStyle(
                      color: AppColors.textSecondaryOnPrimary,
                      fontSize: _getSafeScaledFontSize(),
                    ),
                  ),
                  const SizedBox(height: 4),
                  // Birth Date
                  Text(
                    _currentUser?.birthDate ?? _getSafeText('loading'),
                    style: TextStyle(
                      color: AppColors.textSecondaryOnPrimary,
                      fontSize: _getSafeScaledFontSize(),
                    ),
                  ),
                  const SizedBox(height: 4),
                  // Address
                  Text(
                    _currentUser?.address ?? _getSafeText('loading'),
                    style: TextStyle(
                      color: AppColors.textSecondaryOnPrimary,
                      fontSize: _getSafeScaledFontSize(baseSize: 0.9),
                    ),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: 16),
                  // Guardian Section
                  Text(
                    _getSafeText('guardian'),
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: _getSafeScaledFontSize(isSubtitle: true),
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    _currentUser?.guardianName ?? _getSafeText('loading'),
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: _getSafeScaledFontSize(),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    _currentUser?.phoneNumber ?? _getSafeText('loading'),
                    style: TextStyle(
                      color: AppColors.textSecondaryOnPrimary,
                      fontSize: _getSafeScaledFontSize(),
                    ),
                  ),
                  const SizedBox(height: 20),
                  // ID Status Section
                  _buildIdStatusCard(),
                ],
              ),
            ),
            // Benefits Section
            Container(
              width: double.infinity,
              constraints: BoxConstraints(
                minHeight: MediaQuery.of(context).size.height * 0.4,
              ),
              decoration: const BoxDecoration(
                color: Color(0xFFF5F5F5),
                borderRadius: BorderRadius.only(
                  topLeft: Radius.circular(30),
                  topRight: Radius.circular(30),
                ),
              ),
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  children: [
                    const SizedBox(height: 20),
                    Text(
                      _getSafeText('benefits'),
                      style: TextStyle(
                        color: Colors.black,
                        fontSize: _getSafeScaledFontSize(isSubtitle: true),
                        fontWeight: FontWeight.bold,
                        letterSpacing: 1.2,
                      ),
                    ),
                    const SizedBox(height: 30),
                    // DSWD Pension - now controlled by admin
                    _buildBenefitCard(
                      title: _getSafeText('dswd_pension'),
                      isActive: _currentUser?.isDswdPensionBeneficiary ?? false,
                    ),
                    const SizedBox(height: 20),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildIdStatusCard() {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Text(
            '${_getSafeText('id_status')} : ${_currentUser?.idStatus ?? _getSafeText('loading')}',
            style: TextStyle(
              fontSize: _getSafeScaledFontSize(baseSize: 0.8),
              fontWeight: FontWeight.w600,
              color: const Color.fromARGB(221, 247, 247, 247),
            ),
          ),
          const SizedBox(height: 12),
          Center(
            child: Container(
              margin: const EdgeInsets.symmetric(horizontal: 24),
              constraints: const BoxConstraints(maxWidth: 350),
              child: Container(
                width: double.infinity,
                height: 140,
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                    colors: [
                      Colors.white.withOpacity(0.25),
                      Colors.white.withOpacity(0.15),
                    ],
                  ),
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(
                    color: Colors.white.withOpacity(0.4),
                    width: 1.5,
                  ),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.15),
                      blurRadius: 15,
                      offset: const Offset(0, 5),
                    ),
                  ],
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(20),
                  child: Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        begin: Alignment.topLeft,
                        end: Alignment.bottomRight,
                        colors: [
                          Colors.white.withOpacity(0.1),
                          Colors.white.withOpacity(0.05),
                        ],
                      ),
                    ),
                    child: Row(
                      children: [
                        // Photo section
                        Container(
                          width: 60,
                          height: 80,
                          decoration: BoxDecoration(
                            gradient: LinearGradient(
                              begin: Alignment.topCenter,
                              end: Alignment.bottomCenter,
                              colors: [
                                Colors.white.withOpacity(0.4),
                                Colors.white.withOpacity(0.2),
                              ],
                            ),
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(
                              color: Colors.white.withOpacity(0.6),
                              width: 1.5,
                            ),
                          ),
                          child: Icon(
                            Icons.person_outline,
                            size: 30,
                            color: Colors.white.withOpacity(0.7),
                          ),
                        ),
                        const SizedBox(width: 20),
                        // Text section
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              // Header line
                              Container(
                                width: double.infinity * 0.9,
                                height: 14,
                                decoration: BoxDecoration(
                                  gradient: LinearGradient(
                                    colors: [
                                      Colors.white.withOpacity(0.6),
                                      Colors.white.withOpacity(0.4),
                                    ],
                                  ),
                                  borderRadius: BorderRadius.circular(7),
                                ),
                              ),
                              const SizedBox(height: 8),
                              Container(
                                width: double.infinity * 0.6,
                                height: 10,
                                decoration: BoxDecoration(
                                  gradient: LinearGradient(
                                    colors: [
                                      Colors.white.withOpacity(0.5),
                                      Colors.white.withOpacity(0.3),
                                    ],
                                  ),
                                  borderRadius: BorderRadius.circular(5),
                                ),
                              ),
                              const SizedBox(height: 16),
                              // Info rows
                              Row(
                                children: [
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      children: [
                                        Container(
                                          width: double.infinity * 0.8,
                                          height: 8,
                                          decoration: BoxDecoration(
                                            color:
                                                Colors.white.withOpacity(0.4),
                                            borderRadius:
                                                BorderRadius.circular(4),
                                          ),
                                        ),
                                        const SizedBox(height: 6),
                                        Container(
                                          width: double.infinity * 0.6,
                                          height: 8,
                                          decoration: BoxDecoration(
                                            color:
                                                Colors.white.withOpacity(0.4),
                                            borderRadius:
                                                BorderRadius.circular(4),
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                  const SizedBox(width: 16),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      children: [
                                        Container(
                                          width: double.infinity * 0.7,
                                          height: 8,
                                          decoration: BoxDecoration(
                                            color:
                                                Colors.white.withOpacity(0.4),
                                            borderRadius:
                                                BorderRadius.circular(4),
                                          ),
                                        ),
                                        const SizedBox(height: 6),
                                        Container(
                                          width: double.infinity * 0.5,
                                          height: 8,
                                          decoration: BoxDecoration(
                                            color:
                                                Colors.white.withOpacity(0.4),
                                            borderRadius:
                                                BorderRadius.circular(4),
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildBenefitCard({required String title, required bool isActive}) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(25),
        border: Border.all(
          color: isActive ? Colors.green : Colors.red,
          width: 2,
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.1),
            blurRadius: 4,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Row(
        children: [
          Icon(
            isActive ? Icons.check_circle : Icons.cancel,
            color: isActive ? Colors.green : Colors.red,
            size: 24,
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Text(
              title,
              style: TextStyle(
                color: Colors.black,
                fontSize: _getSafeScaledFontSize(),
                fontWeight: FontWeight.w500,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'screens/login_screen.dart';
import 'config/app_theme.dart';

class ElderaApp extends StatelessWidget {
  const ElderaApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Eldera Health',
      theme: AppTheme.lightTheme,
      darkTheme: AppTheme.darkTheme,
      themeMode:
          ThemeMode.light, // Use light theme by default for accessibility
      home: const LoginScreen(),
      debugShowCheckedModeBanner: false,
    );
  }
}

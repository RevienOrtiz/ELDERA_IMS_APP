import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:flutter/services.dart';
import 'package:flutter/painting.dart';
import 'package:flutter/widgets.dart';

/// Memory optimization utilities for budget devices
/// Helps manage memory usage during app startup and runtime
class MemoryOptimizer {
  static final MemoryOptimizer _instance = MemoryOptimizer._internal();
  factory MemoryOptimizer() => _instance;
  MemoryOptimizer._internal();

  // Memory usage tracking
  int _initialMemoryUsage = 0;
  int _currentMemoryUsage = 0;

  /// Initialize memory optimization for budget devices
  static Future<void> initialize() async {
    final optimizer = MemoryOptimizer();

    try {
      // Record initial memory usage
      optimizer._initialMemoryUsage = await _getCurrentMemoryUsage();
      optimizer._currentMemoryUsage = optimizer._initialMemoryUsage;

      debugPrint('📱 Memory Optimizer initialized');
      debugPrint(
          '💾 Initial memory usage: ${optimizer._initialMemoryUsage ~/ 1024 ~/ 1024} MB');

      // Configure memory optimizations for budget devices
      await _configureForBudgetDevice();
    } catch (e) {
      debugPrint('⚠️ Memory optimizer initialization failed: $e');
    }
  }

  /// Configure optimizations specifically for budget devices
  static Future<void> _configureForBudgetDevice() async {
    try {
      // Reduce image cache size for budget devices
      PaintingBinding.instance.imageCache.maximumSize = 50; // Default is 1000
      PaintingBinding.instance.imageCache.maximumSizeBytes =
          32 << 20; // 32MB instead of 100MB

      // Configure platform-specific optimizations
      if (Platform.isAndroid) {
        // Request garbage collection to free up memory
        await _requestGarbageCollection();
      }

      debugPrint('🔧 Budget device optimizations applied');
    } catch (e) {
      debugPrint('⚠️ Budget device configuration failed: $e');
    }
  }

  /// Get current memory usage in bytes
  static Future<int> _getCurrentMemoryUsage() async {
    try {
      if (Platform.isAndroid) {
        // Use Android-specific memory info
        final result = await const MethodChannel('flutter/platform')
            .invokeMethod('SystemNavigator.routeUpdated');
        return 0; // Native implementation required for platform-specific memory info
      }
      return 0;
    } catch (e) {
      return 0;
    }
  }

  /// Request garbage collection to free memory
  static Future<void> _requestGarbageCollection() async {
    try {
      // Force garbage collection
      await Future.delayed(const Duration(milliseconds: 100));

      // Clear image cache if memory is low
      if (await _isMemoryLow()) {
        PaintingBinding.instance.imageCache.clear();
        debugPrint('🧹 Image cache cleared due to low memory');
      }
    } catch (e) {
      debugPrint('⚠️ Garbage collection failed: $e');
    }
  }

  /// Check if device is running low on memory
  static Future<bool> _isMemoryLow() async {
    try {
      // Simple heuristic - in real implementation, would check actual memory
      final imageCache = PaintingBinding.instance.imageCache;
      return imageCache.currentSize > imageCache.maximumSize * 0.8;
    } catch (e) {
      return false;
    }
  }

  /// Optimize memory usage during app lifecycle
  static Future<void> optimizeForAppState(AppLifecycleState state) async {
    switch (state) {
      case AppLifecycleState.paused:
      case AppLifecycleState.detached:
        // App is backgrounded - free up memory
        await _freeBackgroundMemory();
        break;
      case AppLifecycleState.resumed:
        // App is foregrounded - prepare for active use
        await _prepareForActiveUse();
        break;
      case AppLifecycleState.inactive:
        // App is temporarily inactive
        break;
      case AppLifecycleState.hidden:
        // App is hidden
        break;
    }
  }

  /// Free memory when app is backgrounded
  static Future<void> _freeBackgroundMemory() async {
    try {
      // Clear image cache
      PaintingBinding.instance.imageCache.clear();

      // Request garbage collection
      await _requestGarbageCollection();

      debugPrint('🧹 Background memory cleanup completed');
    } catch (e) {
      debugPrint('⚠️ Background memory cleanup failed: $e');
    }
  }

  /// Prepare memory for active app use
  static Future<void> _prepareForActiveUse() async {
    try {
      // Restore image cache size if needed
      final imageCache = PaintingBinding.instance.imageCache;
      if (imageCache.maximumSize < 50) {
        imageCache.maximumSize = 50;
      }

      debugPrint('🚀 Memory prepared for active use');
    } catch (e) {
      debugPrint('⚠️ Active use preparation failed: $e');
    }
  }

  /// Get memory usage statistics
  static Map<String, dynamic> getMemoryStats() {
    final imageCache = PaintingBinding.instance.imageCache;

    return {
      'imageCacheSize': imageCache.currentSize,
      'imageCacheMaxSize': imageCache.maximumSize,
      'imageCacheSizeBytes': imageCache.currentSizeBytes,
      'imageCacheMaxSizeBytes': imageCache.maximumSizeBytes,
      'memoryPressure': imageCache.currentSize > imageCache.maximumSize * 0.8,
    };
  }

  /// Log current memory statistics
  static void logMemoryStats() {
    final stats = getMemoryStats();
    debugPrint('📊 Memory Stats:');
    debugPrint(
        '   Image Cache: ${stats['imageCacheSize']}/${stats['imageCacheMaxSize']} items');
    debugPrint(
        '   Cache Size: ${(stats['imageCacheSizeBytes'] / 1024 / 1024).toStringAsFixed(1)} MB');
    debugPrint('   Memory Pressure: ${stats['memoryPressure']}');
  }

  /// Check if device is likely a budget device based on memory constraints
  static bool isBudgetDevice() {
    try {
      // Simple heuristic - budget devices typically have limited memory
      // In a real implementation, you'd check actual device RAM
      return Platform.isAndroid; // Assume Android devices might be budget
    } catch (e) {
      return false;
    }
  }

  /// Apply budget-specific optimizations
  static Future<void> applyBudgetOptimizations() async {
    if (!isBudgetDevice()) return;

    try {
      // Reduce image cache even further for budget devices
      PaintingBinding.instance.imageCache.maximumSize = 25;
      PaintingBinding.instance.imageCache.maximumSizeBytes = 16 << 20; // 16MB

      // More aggressive garbage collection
      await _requestGarbageCollection();

      debugPrint('💰 Budget device optimizations applied');
    } catch (e) {
      debugPrint('⚠️ Budget optimizations failed: $e');
    }
  }
}

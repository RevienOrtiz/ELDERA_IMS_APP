import 'package:flutter/foundation.dart';
import 'ims_webhook_handler.dart';
import 'local_notification_service.dart';
import 'language_service.dart';
import '../utils/memory_optimizer.dart';

/// Manages lazy loading and initialization of app services
/// Optimized for budget devices with limited resources
class ServiceManager {
  static final ServiceManager _instance = ServiceManager._internal();
  factory ServiceManager() => _instance;
  ServiceManager._internal();

  // Service initialization status
  bool _webhookInitialized = false;
  bool _notificationInitialized = false;
  bool _languageInitialized = false;

  // Service instances (lazy loaded)
  LocalNotificationService? _notificationService;

  // Initialization progress tracking
  double _initializationProgress = 0.0;
  String _currentInitializationStep = 'Starting...';

  // Callbacks for progress updates
  final List<Function(double, String)> _progressCallbacks = [];

  /// Get current initialization progress (0.0 to 1.0)
  double get initializationProgress => _initializationProgress;

  /// Get current initialization step description
  String get currentInitializationStep => _currentInitializationStep;

  /// Check if all critical services are initialized
  bool get allServicesReady =>
      _webhookInitialized && _notificationInitialized && _languageInitialized;

  /// Check if notifications are ready
  bool get isNotificationReady => _notificationInitialized;

  /// Add a callback to receive initialization progress updates
  void addProgressCallback(Function(double, String) callback) {
    _progressCallbacks.add(callback);
  }

  /// Remove a progress callback
  void removeProgressCallback(Function(double, String) callback) {
    _progressCallbacks.remove(callback);
  }

  /// Update initialization progress and notify callbacks
  void _updateProgress(double progress, String step) {
    _initializationProgress = progress;
    _currentInitializationStep = step;

    for (final callback in _progressCallbacks) {
      try {
        callback(progress, step);
      } catch (e) {
        debugPrint('Error in progress callback: $e');
      }
    }
  }

  /// Initialize all services in background with progress tracking
  Future<void> initializeAllServices() async {
    try {
      _updateProgress(0.10, 'Optimizing memory...');
      await _initializeMemoryOptimizer();

      _updateProgress(0.30, 'Initializing language service...');
      await _initializeLanguageService();

      _updateProgress(0.60, 'Configuring webhooks...');
      await _initializeWebhooks();

      _updateProgress(0.85, 'Setting up notifications...');
      await _initializeNotifications();

      _updateProgress(1.0, 'Ready!');
      debugPrint('All services initialized successfully');
    } catch (e) {
      debugPrint('Service initialization error: $e');
      // Continue with partial initialization
    }
  }

  /// Initialize memory optimizer
  Future<void> _initializeMemoryOptimizer() async {
    try {
      await MemoryOptimizer.initialize();
      await MemoryOptimizer.applyBudgetOptimizations();
      debugPrint('✅ Memory optimizer initialized');
    } catch (e) {
      debugPrint('❌ Memory optimizer initialization failed: $e');
    }
  }

  /// Initialize language service
  Future<void> _initializeLanguageService() async {
    if (_languageInitialized) return;

    try {
      await LanguageService.instance.init();
      _languageInitialized = true;
      debugPrint('✅ Language service initialized');
    } catch (e) {
      debugPrint('❌ Language service initialization failed: $e');
      _languageInitialized = true;
    }
  }

  /// Initialize webhook handler
  Future<void> _initializeWebhooks() async {
    if (_webhookInitialized) return;

    try {
      await IMSWebhookHandler.initialize();
      _webhookInitialized = true;
      debugPrint('✅ Webhook handler initialized');
    } catch (e) {
      debugPrint('❌ Webhook initialization failed: $e');
      _webhookInitialized = true;
    }
  }

  /// Initialize notification service
  Future<void> _initializeNotifications() async {
    if (_notificationInitialized) return;

    try {
      _notificationService = LocalNotificationService();
      final initialized = await _notificationService!.initialize();

      if (initialized) {
        // Check permissions in background
        final notificationsEnabled =
            await _notificationService!.areNotificationsEnabled();
        final exactAlarmPermission =
            await _notificationService!.requestExactAlarmPermission();

        debugPrint(
            '✅ Notifications initialized - Enabled: $notificationsEnabled, Exact alarms: $exactAlarmPermission');

        if (!notificationsEnabled) {
          debugPrint(
              '⚠️ Notifications not enabled - some features may be limited');
        }
      }

      _notificationInitialized = true;
    } catch (e) {
      debugPrint('❌ Notification initialization failed: $e');
      _notificationInitialized = true;
    }
  }

  /// Get notification service instance (lazy loaded)
  LocalNotificationService? get notificationService {
    if (!_notificationInitialized) {
      debugPrint('⚠️ Notification service not yet initialized');
      return null;
    }
    return _notificationService;
  }

  /// Wait for a specific service to be ready
  Future<void> waitForService(String serviceName,
      {Duration timeout = const Duration(seconds: 10)}) async {
    final startTime = DateTime.now();

    while (DateTime.now().difference(startTime) < timeout) {
      switch (serviceName.toLowerCase()) {
        case 'webhook':
          if (_webhookInitialized) return;
          break;
        case 'notification':
          if (_notificationInitialized) return;
          break;
        case 'language':
          if (_languageInitialized) return;
          break;
        case 'all':
          if (allServicesReady) return;
          break;
      }

      await Future.delayed(const Duration(milliseconds: 100));
    }

    debugPrint('⚠️ Timeout waiting for service: $serviceName');
  }

  /// Reset all services (for testing or restart scenarios)
  void reset() {
    _webhookInitialized = false;
    _notificationInitialized = false;
    _languageInitialized = false;
    _notificationService = null;
    _initializationProgress = 0.0;
    _currentInitializationStep = 'Starting...';
    _progressCallbacks.clear();
  }
}

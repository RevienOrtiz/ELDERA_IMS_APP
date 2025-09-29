import 'package:flutter/material.dart';
import '../services/user_service.dart';
import '../models/user.dart' as app_user;

class AdminSimulationScreen extends StatefulWidget {
  const AdminSimulationScreen({Key? key}) : super(key: key);

  @override
  State<AdminSimulationScreen> createState() => _AdminSimulationScreenState();
}

class _AdminSimulationScreenState extends State<AdminSimulationScreen> {
  // Using SupabaseUserService instead of UserService
  app_user.User? _currentUser;
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _loadUserData();
  }

  Future<void> _loadUserData() async {
    _currentUser = await UserService.getCurrentUser();
    setState(() {});
  }

  Future<void> _updateBeneficiaryStatus({
    bool? isDswdPensionBeneficiary,
  }) async {
    setState(() {
      _isLoading = true;
    });

    try {
      if (_currentUser?.id == null) {
        throw Exception('No current user found');
      }

      await UserService.updateUserProfile(
        userId: _currentUser!.id,
        isDswdPensionBeneficiary: isDswdPensionBeneficiary,
      );

      await _loadUserData();

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Beneficiary status updated successfully'),
          backgroundColor: Colors.green,
        ),
      );
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error updating status: $e'),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Admin - Beneficiary Management'),
        backgroundColor: const Color(0xFF2D5A5A),
        foregroundColor: Colors.white,
      ),
      body: _currentUser == null
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'User Information',
                            style: Theme.of(context)
                                .textTheme
                                .headlineSmall
                                ?.copyWith(
                                  fontWeight: FontWeight.bold,
                                ),
                          ),
                          const SizedBox(height: 16),
                          Text('Name: ${_currentUser!.name}'),
                          Text('Age: ${_currentUser!.age}'),
                          Text('Phone: ${_currentUser!.phoneNumber}'),
                          Text('ID Status: ${_currentUser!.idStatus}'),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),
                  Text(
                    'Beneficiary Status Management',
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                          fontWeight: FontWeight.bold,
                        ),
                  ),
                  const SizedBox(height: 16),
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        children: [
                          _buildBeneficiaryToggle(
                            title: 'DSWD Pension Beneficiary',
                            subtitle:
                                'Determines if user receives DSWD pension benefits',
                            value: _currentUser!.isDswdPensionBeneficiary,
                            onChanged: (value) => _updateBeneficiaryStatus(
                              isDswdPensionBeneficiary: value,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.blue.shade50,
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(color: Colors.blue.shade200),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Icon(Icons.info, color: Colors.blue.shade700),
                            const SizedBox(width: 8),
                            Text(
                              'Admin Note',
                              style: TextStyle(
                                fontWeight: FontWeight.bold,
                                color: Colors.blue.shade700,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        const Text(
                          'In a real implementation, this would be part of an admin dashboard where authorized personnel can manage beneficiary status based on eligibility verification and documentation.',
                          style: TextStyle(fontSize: 14),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
    );
  }

  Widget _buildBeneficiaryToggle({
    required String title,
    required String subtitle,
    required bool value,
    required Function(bool) onChanged,
  }) {
    return ListTile(
      title: Text(
        title,
        style: const TextStyle(fontWeight: FontWeight.w600),
      ),
      subtitle: Text(
        subtitle,
        style: TextStyle(color: Colors.grey.shade600),
      ),
      trailing: _isLoading
          ? const SizedBox(
              width: 20,
              height: 20,
              child: CircularProgressIndicator(strokeWidth: 2),
            )
          : Switch(
              value: value,
              onChanged: onChanged,
              activeColor: const Color(0xFF2D5A5A),
            ),
    );
  }
}
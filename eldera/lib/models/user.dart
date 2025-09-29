/**
 * USER MODEL - SUPABASE DATA STRUCTURE
 * 
 * This model represents the user data structure used throughout the Eldera Health app.
 * Updated to work with Supabase backend instead of IMS API.
 * 
 * FIELD REQUIREMENTS:
 * - id: Unique user identifier (UUID string, required)
 * - name: Full name (string, required, 2-100 characters)
 * - age: User age (integer, required, 18-120 years)
 * - phoneNumber: Philippine mobile number (string, required, format: +63XXXXXXXXXX)
 * - profileImageUrl: Supabase Storage URL for profile image (string, optional)
 * - idStatus: User status (string, required, values: "Senior Citizen", "PWD", "Regular", "Pending")
 * - isDswdPensionBeneficiary: DSWD pension status (boolean, default: false)
 * - birthDate: User birth date (string, optional)
 * - address: User address (string, optional)
 * - guardianName: Guardian name (string, optional)
 * - createdAt: Account creation timestamp (ISO8601 datetime string)
 * - updatedAt: Last update timestamp (ISO8601 datetime string)
 * 
 * JSON MAPPING:
 * - phoneNumber ↔ phone_number (Supabase uses snake_case)
 * - profileImageUrl ↔ profile_image_url
 * - isDswdPensionBeneficiary ↔ is_dswd_pension_beneficiary
 * - birthDate ↔ birth_date
 * - guardianName ↔ guardian_name
 * - createdAt ↔ created_at
 * - updatedAt ↔ updated_at
 * 
 * VALIDATION NOTES:
 * - Phone numbers must be valid Philippine mobile format
 * - Profile images are stored in Supabase Storage with public URLs
 * - ID status should be validated against predefined values
 * - Timestamps should be in ISO8601 format for consistency
 * - User ID is a UUID that references auth.users(id)
 */
class User {
  final String id;
  final String name;
  final int age;
  final String phoneNumber;
  final String? profileImageUrl;
  final String idStatus;
  final bool isDswdPensionBeneficiary;
  final String? birthDate;
  final String? address;
  final String? guardianName;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  User({
    required this.id,
    required this.name,
    required this.age,
    required this.phoneNumber,
    this.profileImageUrl,
    this.idStatus = 'Pending',
    this.isDswdPensionBeneficiary = false,
    this.birthDate,
    this.address,
    this.guardianName,
    this.createdAt,
    this.updatedAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] ?? '',
      name: json['name'] ?? '',
      age: json['age'] ?? 0,
      phoneNumber: json['phone_number'] ?? '',
      profileImageUrl: json['profile_image_url'],
      idStatus: json['id_status'] ?? 'Pending',
      isDswdPensionBeneficiary: json['is_dswd_pension_beneficiary'] ?? false,
      birthDate: json['birth_date'],
      address: json['address'],
      guardianName: json['guardian_name'],
      createdAt: json['created_at'] != null ? DateTime.parse(json['created_at']) : null,
      updatedAt: json['updated_at'] != null ? DateTime.parse(json['updated_at']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'age': age,
      'phone_number': phoneNumber,
      'profile_image_url': profileImageUrl,
      'id_status': idStatus,
      'is_dswd_pension_beneficiary': isDswdPensionBeneficiary,
      'birth_date': birthDate,
      'address': address,
      'guardian_name': guardianName,
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }

  User copyWith({
    String? id,
    String? name,
    int? age,
    String? phoneNumber,
    String? profileImageUrl,
    String? idStatus,
    bool? isDswdPensionBeneficiary,
    String? birthDate,
    String? address,
    String? guardianName,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) {
    return User(
      id: id ?? this.id,
      name: name ?? this.name,
      age: age ?? this.age,
      phoneNumber: phoneNumber ?? this.phoneNumber,
      profileImageUrl: profileImageUrl ?? this.profileImageUrl,
      idStatus: idStatus ?? this.idStatus,
      isDswdPensionBeneficiary: isDswdPensionBeneficiary ?? this.isDswdPensionBeneficiary,
      birthDate: birthDate ?? this.birthDate,
      address: address ?? this.address,
      guardianName: guardianName ?? this.guardianName,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }
}
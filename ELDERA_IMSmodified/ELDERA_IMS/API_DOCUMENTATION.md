# ELDERA_IMS API Documentation

This document provides information about the API endpoints available for the Flutter mobile application to connect with the ELDERA_IMS Laravel backend.

## Base URL

```
http://your-domain.com/api
```

## Authentication

The API uses Laravel Sanctum for authentication. All protected routes require a valid Bearer token.

### Register

**Endpoint:** `POST /register`

**Description:** Register a new user account.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2023-11-15T12:00:00.000000Z",
    "updated_at": "2023-11-15T12:00:00.000000Z"
  },
  "access_token": "1|abcdefghijklmnopqrstuvwxyz",
  "token_type": "Bearer"
}
```

### Login

**Endpoint:** `POST /login`

**Description:** Authenticate a user and get an access token.

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2023-11-15T12:00:00.000000Z",
    "updated_at": "2023-11-15T12:00:00.000000Z"
  },
  "access_token": "1|abcdefghijklmnopqrstuvwxyz",
  "token_type": "Bearer"
}
```

### Logout

**Endpoint:** `POST /logout`

**Description:** Invalidate the current user's token.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "Logged out successfully"
}
```

### Get Current User

**Endpoint:** `GET /user`

**Description:** Get the authenticated user's information.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "created_at": "2023-11-15T12:00:00.000000Z",
  "updated_at": "2023-11-15T12:00:00.000000Z"
}
```

## Senior Citizens

### Get All Seniors

**Endpoint:** `GET /seniors`

**Description:** Get a list of all senior citizens.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Juan Dela Cruz",
      "age": 68,
      "gender": "Male",
      "address": "Brgy. San Antonio, Quezon City",
      "osca_id": "OSCA-2023-001",
      "contact_number": "09123456789",
      "status": "Active"
    },
    {
      "id": 2,
      "name": "Maria Santos",
      "age": 72,
      "gender": "Female",
      "address": "Brgy. Poblacion, Makati City",
      "osca_id": "OSCA-2023-002",
      "contact_number": "09187654321",
      "status": "Active"
    }
  ]
}
```

### Get Senior Details

**Endpoint:** `GET /seniors/{id}`

**Description:** Get detailed information about a specific senior citizen.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Juan Dela Cruz",
    "age": 68,
    "gender": "Male",
    "birthdate": "1955-05-15",
    "address": "Brgy. San Antonio, Quezon City",
    "osca_id": "OSCA-2023-001",
    "contact_number": "09123456789",
    "emergency_contact": "Ana Dela Cruz",
    "emergency_number": "09187654321",
    "status": "Active",
    "medical_conditions": "Hypertension, Diabetes",
    "benefits": {
      "social_pension": true,
      "medical_assistance": true,
      "burial_assistance": false
    }
  }
}
```

## Events

### Get All Events

**Endpoint:** `GET /events`

**Description:** Get a list of all events.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Health Checkup Camp",
      "description": "Free health checkup for senior citizens",
      "date": "2023-12-15",
      "time": "09:00:00",
      "location": "Barangay Hall, San Antonio",
      "status": "Upcoming"
    },
    {
      "id": 2,
      "title": "Christmas Celebration",
      "description": "Annual Christmas party for senior citizens",
      "date": "2023-12-20",
      "time": "14:00:00",
      "location": "Community Center, Poblacion",
      "status": "Upcoming"
    }
  ]
}
```

### Get Event Details

**Endpoint:** `GET /events/{id}`

**Description:** Get detailed information about a specific event.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Health Checkup Camp",
    "description": "Free health checkup for senior citizens including blood pressure monitoring, blood sugar testing, and basic medical consultation.",
    "date": "2023-12-15",
    "time": "09:00:00",
    "end_time": "16:00:00",
    "location": "Barangay Hall, San Antonio",
    "organizer": "Department of Health in partnership with OSCA",
    "contact_person": "Dr. Maria Santos",
    "contact_number": "09123456789",
    "status": "Upcoming",
    "max_participants": 100,
    "current_participants": 45,
    "requirements": "Bring OSCA ID and medical records if available"
  }
}
```

## Applications

### Submit ID Application

**Endpoint:** `POST /applications/id`

**Description:** Submit a new senior ID application.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body:**
```
full_name: "Juan Dela Cruz"
birthdate: "1955-05-15"
address: "Brgy. San Antonio, Quezon City"
contact_number: "09123456789"
photo: [file]
id_documents[0]: [file]
id_documents[1]: [file]
```

**Response:**
```json
{
  "success": true,
  "message": "Senior ID application submitted successfully",
  "application_id": 1234,
  "status": "Pending"
}
```

### Submit Pension Application

**Endpoint:** `POST /applications/pension`

**Description:** Submit a new pension application.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body:**
```
senior_id: 1
monthly_income: 5000
has_pension: false
pension_source: null
pension_amount: null
supporting_documents[0]: [file]
supporting_documents[1]: [file]
```

**Response:**
```json
{
  "success": true,
  "message": "Pension application submitted successfully",
  "application_id": 5678,
  "status": "Pending"
}
```

### Submit Benefits Application

**Endpoint:** `POST /applications/benefits`

**Description:** Submit a new benefits application.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body:**
```
senior_id: 1
benefit_type: "medical"
reason: "Need assistance for cataract surgery"
supporting_documents[0]: [file]
supporting_documents[1]: [file]
```

**Response:**
```json
{
  "success": true,
  "message": "Benefits application submitted successfully",
  "application_id": 9012,
  "status": "Pending"
}
```

### Check Application Status

**Endpoint:** `GET /applications/status/{id}`

**Description:** Check the status of a submitted application.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1234,
    "type": "Senior ID",
    "submitted_at": "2023-11-10 14:30:00",
    "status": "Under Review",
    "notes": "Your application is being processed. Please wait for further updates.",
    "estimated_completion": "2023-11-25"
  }
}
```

## Error Responses

### Validation Error

```json
{
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

### Authentication Error

```json
{
  "message": "Unauthenticated."
}
```

### Not Found Error

```json
{
  "message": "Resource not found."
}
```

## Integration with Flutter

To integrate this API with your Flutter application:

1. Store the access token securely after login/registration.
2. Include the token in the Authorization header for all protected requests.
3. Handle token expiration by implementing a refresh mechanism or redirecting to login.
4. Use appropriate Flutter packages for API communication (e.g., `http`, `dio`).
5. Implement proper error handling for different API response codes.

### Example Flutter Code for API Call

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

future<void> login(String email, String password) async {
  final response = await http.post(
    Uri.parse('http://your-domain.com/api/login'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'email': email,
      'password': password,
    }),
  );

  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    // Store token securely
    final token = data['access_token'];
    // Navigate to home screen
  } else {
    // Handle error
    final error = jsonDecode(response.body);
    print('Login failed: ${error['message']}');
  }
}

future<List<dynamic>> getSeniors(String token) async {
  final response = await http.get(
    Uri.parse('http://your-domain.com/api/seniors'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
  );

  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    return data['data'];
  } else {
    // Handle error
    throw Exception('Failed to load seniors');
  }
}
```
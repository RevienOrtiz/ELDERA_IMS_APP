# IMS API Integration Guide for Backend Developers

This document provides comprehensive guidance for implementing the IMS (Information Management System) API endpoints required by the Eldera Health mobile application.

## Base Configuration

- **Base URL**: `https://ims-api.eldera.gov.ph/api`
- **Protocol**: HTTPS only
- **Content-Type**: `application/json`
- **Timeout**: 30 seconds
- **Authentication**: To be implemented (consider JWT tokens)

## API Endpoints Overview

### 1. Authentication Endpoints

#### POST /api/auth/login
**Purpose**: Authenticate user and return user data

**Request Body**:
```json
{
  "email_or_id": "string",
  "password": "string"
}
```

**Success Response (200)**:
```json
{
  "user_id": "string",
  "user_data": {
    "id": "string",
    "name": "string",
    "age": 65,
    "phone_number": "+63912345678",
    "id_status": "Senior Citizen",
    "is_dswd_pension_beneficiary": true,
    "created_at": "2024-01-15T08:30:00Z",
    "updated_at": "2024-01-15T08:30:00Z"
  },
  "message": "Login successful"
}
```

**Error Responses**:
- 401: Invalid credentials
- 400: Bad request (missing fields)
- 429: Too many login attempts (rate limiting)

#### POST /api/auth/logout
**Purpose**: Log out user and invalidate session

**Request Body**:
```json
{
  "user_id": "string"
}
```

**Success Response (200)**:
```json
{
  "message": "Logout successful"
}
```

### 2. User Management Endpoints

#### GET /api/users/{user_id}
**Purpose**: Fetch complete user profile data

**Success Response (200)**:
```json
{
  "id": "string",
  "name": "string",
  "age": 65,
  "phone_number": "+63912345678",
  "id_status": "Senior Citizen",
  "is_dswd_pension_beneficiary": true,
  "profile_image_base64": "data:image/jpeg;base64,/9j/4AAQ...",
  "created_at": "2024-01-15T08:30:00Z",
  "updated_at": "2024-01-15T08:30:00Z"
}
```

**Error Responses**:
- 404: User not found
- 401: Unauthorized access

#### PUT /api/users/{user_id}
**Purpose**: Update user profile information

**Request Body** (partial updates allowed):
```json
{
  "name": "Updated Name",
  "phone_number": "+63987654321",
  "profile_image_base64": "data:image/jpeg;base64,/9j/4AAQ..."
}
```

**Success Response (200)**:
```json
{
  "id": "string",
  "name": "Updated Name",
  "age": 65,
  "phone_number": "+63987654321",
  "id_status": "Senior Citizen",
  "is_dswd_pension_beneficiary": true,
  "profile_image_base64": "data:image/jpeg;base64,/9j/4AAQ...",
  "created_at": "2024-01-15T08:30:00Z",
  "updated_at": "2024-01-20T10:15:00Z"
}
```

### 3. Announcement Endpoints

#### GET /api/announcements
**Purpose**: Fetch all announcements

**Success Response (200)**:
```json
[
  {
    "id": "ann_001",
    "title": "Health Screening Schedule",
    "posted_date": "2024-01-20",
    "what": "Free health screening for senior citizens",
    "when": "January 25, 2024 at 9:00 AM",
    "where": "Barangay Health Center",
    "category": "Health",
    "department": "Department of Health",
    "icon_type": "health",
    "has_reminder": true,
    "has_listen": true,
    "background_color": "#E3F2FD",
    "is_reminder_set": false,
    "reminder_time": null,
    "reminder_type": null
  }
]
```

#### GET /api/announcements?department={department}
**Purpose**: Filter announcements by department

**Query Parameters**:
- `department`: String (e.g., "Department of Health", "DSWD")

#### GET /api/announcements?category={category}
**Purpose**: Filter announcements by category

**Query Parameters**:
- `category`: String (e.g., "Health", "Social", "Emergency")

#### GET /api/announcements?date={YYYY-MM-DD}
**Purpose**: Get announcements for specific date

**Query Parameters**:
- `date`: ISO date format (e.g., "2024-01-25")

#### GET /api/announcements/{announcement_id}
**Purpose**: Get specific announcement by ID

**Success Response (200)**: Single announcement object
**Error Response**: 404 if announcement not found

## Data Validation Requirements

### User Data Validation
- **name**: Required, 2-100 characters
- **age**: Required, 18-120 years
- **phone_number**: Required, valid Philippine mobile format (+63XXXXXXXXXX)
- **id_status**: Required, predefined values ("Senior Citizen", "PWD", "Regular")
- **profile_image_base64**: Optional, max 2MB when decoded

### Announcement Data Validation
- **title**: Required, 5-200 characters
- **posted_date**: Required, valid date format (YYYY-MM-DD)
- **what, when, where**: Required, 10-500 characters each
- **category**: Required, predefined values
- **department**: Required, valid department name

## Security Implementation

### Required Security Measures
1. **HTTPS Only**: All endpoints must use HTTPS
2. **Password Security**: Use bcrypt or similar for password hashing
3. **Rate Limiting**: Implement rate limiting for login attempts
4. **Input Validation**: Sanitize and validate all input data
5. **SQL Injection Prevention**: Use parameterized queries
6. **CORS Configuration**: Configure appropriate CORS headers

### Recommended Security Enhancements
1. **JWT Tokens**: Implement JWT for session management
2. **API Key Authentication**: Consider API keys for additional security
3. **Request Logging**: Log all API requests for monitoring
4. **Error Handling**: Don't expose sensitive information in error messages

## Database Schema Suggestions

### Users Table
```sql
CREATE TABLE users (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INTEGER NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    id_status VARCHAR(50) NOT NULL,
    is_dswd_pension_beneficiary BOOLEAN DEFAULT FALSE,
    profile_image_base64 TEXT,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Announcements Table
```sql
CREATE TABLE announcements (
    id VARCHAR(50) PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    posted_date DATE NOT NULL,
    what TEXT NOT NULL,
    when VARCHAR(200) NOT NULL,
    where_location VARCHAR(200) NOT NULL,
    category VARCHAR(50) NOT NULL,
    department VARCHAR(100) NOT NULL,
    icon_type VARCHAR(50) NOT NULL,
    has_reminder BOOLEAN DEFAULT TRUE,
    has_listen BOOLEAN DEFAULT TRUE,
    background_color VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Error Handling Standards

### Standard Error Response Format
```json
{
  "error": true,
  "message": "Human-readable error message",
  "code": "ERROR_CODE",
  "details": {
    "field": "specific field that caused error",
    "value": "invalid value"
  }
}
```

### Common HTTP Status Codes
- **200**: Success
- **400**: Bad Request (validation errors)
- **401**: Unauthorized (authentication required)
- **403**: Forbidden (insufficient permissions)
- **404**: Not Found
- **429**: Too Many Requests (rate limiting)
- **500**: Internal Server Error

## Testing Recommendations

1. **Unit Tests**: Test each endpoint individually
2. **Integration Tests**: Test complete user flows
3. **Load Testing**: Test with multiple concurrent users
4. **Security Testing**: Test for common vulnerabilities
5. **API Documentation**: Use tools like Swagger/OpenAPI

## Deployment Checklist

- [ ] HTTPS certificate configured
- [ ] Database connections secured
- [ ] Environment variables configured
- [ ] Rate limiting implemented
- [ ] Logging configured
- [ ] Error monitoring setup
- [ ] Backup procedures in place
- [ ] API documentation published
- [ ] Load balancing configured (if needed)
- [ ] Security headers configured

## Contact Information

For questions about this API integration, please contact:
- **Mobile App Team**: [Your contact information]
- **IMS Development Team**: [Backend team contact]

---

**Note**: This guide should be updated as the API evolves. Always refer to the latest version for accurate implementation details.
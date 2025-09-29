# ELDERA IMS Backend Installation Guide

## Prerequisites

- PHP 8.2 or higher
- Composer
- PostgreSQL (or Supabase)
- Laravel 12.x

## Installation Steps

### 1. Install Dependencies
```bash
composer install
```

### 2. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Database Configuration
Update your `.env` file with your database credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=your-supabase-host
DB_PORT=5432
DB_DATABASE=your-database-name
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

### 4. Run Migrations
```bash
# Run all migrations
php artisan migrate

# Or run specific migrations
php artisan migrate --path=database/migrations/2024_01_01_000001_create_seniors_table.php
php artisan migrate --path=database/migrations/2024_01_01_000002_create_applications_table.php
# ... continue for each migration
```

### 5. Seed Sample Data
```bash
# Seed barangays
php artisan db:seed --class=BarangaySeeder

# Seed sample seniors
php artisan db:seed --class=SeniorSeeder
```

### 6. File Storage Setup
```bash
# Create storage link
php artisan storage:link

# Set proper permissions
chmod -R 755 storage/
chmod -R 755 public/storage/
```

### 7. Start Development Server
```bash
php artisan serve
```

## Configuration

### File Upload Settings
The system is configured to handle file uploads with the following settings:
- Maximum file size: 2MB
- Allowed file types: JPEG, PNG, PDF
- Storage disk: public
- Automatic thumbnail generation: Disabled (can be enabled later)

### API Rate Limiting
- Default: 60 requests per minute per user
- Configurable in `app/Http/Middleware/ApiRateLimit.php`

## Testing the Installation

### 1. Check Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

### 2. Test API Endpoints
```bash
# Test senior listing
curl http://localhost:8000/api/seniors

# Test authentication
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

### 3. Check File Upload
```bash
# Test file upload endpoint
curl -X POST http://localhost:8000/api/applications/id \
  -F "full_name=Test User" \
  -F "address=Test Address" \
  -F "gender=Male" \
  -F "date_of_birth=1960-01-01" \
  -F "birth_place=Test City" \
  -F "civil_status=Single" \
  -F "contact_number=09123456789" \
  -F "photo=@/path/to/test-image.jpg"
```

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check your `.env` file database credentials
   - Ensure PostgreSQL is running
   - Verify Supabase connection details

2. **File Upload Issues**
   - Check storage permissions
   - Ensure `storage:link` was run
   - Verify file size limits

3. **Migration Errors**
   - Check database user permissions
   - Ensure all required tables exist
   - Run migrations in order

4. **API Authentication Issues**
   - Check Sanctum configuration
   - Verify CORS settings
   - Check API routes

### Debug Commands

```bash
# Check Laravel configuration
php artisan config:cache
php artisan config:clear

# Check routes
php artisan route:list

# Check database
php artisan migrate:status

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Production Deployment

### 1. Environment Configuration
```bash
# Set production environment
APP_ENV=production
APP_DEBUG=false

# Configure database for production
DB_CONNECTION=pgsql
DB_HOST=your-production-host
# ... other production settings
```

### 2. Optimize Application
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### 3. Set Up File Storage
- Configure proper file storage (AWS S3, Google Cloud, etc.)
- Set up CDN for file delivery
- Configure backup strategies

## Support

If you encounter any issues during installation:

1. Check the Laravel logs in `storage/logs/`
2. Verify all dependencies are installed
3. Ensure database connection is working
4. Check file permissions

For additional help, refer to the Laravel documentation or create an issue in the project repository.

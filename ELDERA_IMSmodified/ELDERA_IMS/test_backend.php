<?php
/**
 * Simple test script to verify ELDERA IMS backend is working
 * Run this after setting up your database and running migrations
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Senior;
use App\Models\Barangay;
use App\Models\Application;

echo "🧪 Testing ELDERA IMS Backend...\n\n";

try {
    // Test 1: Database Connection
    echo "1. Testing database connection...\n";
    DB::connection()->getPdo();
    echo "✅ Database connection successful!\n\n";

    // Test 2: Check if tables exist
    echo "2. Checking database tables...\n";
    $tables = ['seniors', 'applications', 'events', 'barangays', 'documents', 'notifications'];
    foreach ($tables as $table) {
        if (DB::getSchemaBuilder()->hasTable($table)) {
            echo "✅ Table '{$table}' exists\n";
        } else {
            echo "❌ Table '{$table}' missing\n";
        }
    }
    echo "\n";

    // Test 3: Check seeded data
    echo "3. Checking seeded data...\n";
    $barangayCount = Barangay::count();
    $seniorCount = Senior::count();
    
    echo "✅ Barangays: {$barangayCount}\n";
    echo "✅ Seniors: {$seniorCount}\n\n";

    // Test 4: Test API endpoints (if server is running)
    echo "4. Testing API endpoints...\n";
    $baseUrl = 'http://localhost:8000';
    
    // Test seniors endpoint
    $seniorsResponse = @file_get_contents($baseUrl . '/api/seniors');
    if ($seniorsResponse) {
        $data = json_decode($seniorsResponse, true);
        if (isset($data['success']) && $data['success']) {
            echo "✅ API /api/seniors working\n";
        } else {
            echo "❌ API /api/seniors returned error\n";
        }
    } else {
        echo "⚠️  API server not running (start with: php artisan serve)\n";
    }

    // Test 5: Check file storage
    echo "\n5. Checking file storage...\n";
    if (is_dir('storage/app/public') && is_link('public/storage')) {
        echo "✅ File storage properly configured\n";
    } else {
        echo "❌ File storage not configured (run: php artisan storage:link)\n";
    }

    echo "\n🎉 Backend test completed!\n";
    echo "\nNext steps:\n";
    echo "- Start server: php artisan serve\n";
    echo "- Test API: curl http://localhost:8000/api/seniors\n";
    echo "- Check admin panel: http://localhost:8000\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "- Check your .env file database settings\n";
    echo "- Run: php artisan migrate\n";
    echo "- Run: php artisan db:seed --class=BarangaySeeder\n";
    echo "- Run: php artisan db:seed --class=SeniorSeeder\n";
}


























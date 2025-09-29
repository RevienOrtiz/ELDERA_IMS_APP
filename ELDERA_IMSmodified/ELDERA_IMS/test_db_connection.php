<?php

// This script tests the connection to the Supabase PostgreSQL database

// Load environment variables from .env file
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check if Supabase API credentials are set (optional)
$supabaseUrl = $_ENV['SUPABASE_URL'] ?? null;
$supabaseKey = $_ENV['SUPABASE_KEY'] ?? null;

// Get database connection details from environment variables
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$database = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$schema = $_ENV['DB_SCHEMA'] ?? 'laravel';

// Display connection information (without password)
echo "Attempting to connect to PostgreSQL database:\n";
echo "Host: {$host}\n";
echo "Port: {$port}\n";
echo "Database: {$database}\n";
echo "Username: {$username}\n";
echo "Schema: {$schema}\n";

// Display Supabase API information if available
if ($supabaseUrl && $supabaseKey) {
    echo "\nSupabase API Information:\n";
    echo "URL: {$supabaseUrl}\n";
    echo "API Key: " . substr($supabaseKey, 0, 10) . "..." . "\n";
    echo "(For API integration, see SUPABASE_API_GUIDE.md)\n";
}

echo "\n";

try {
    // Create a PDO connection
    // Try with IPv6 address if hostname resolution fails
    $ipv6 = "2406:da18:243:7400:b409:960:51a5:d91e";
    $dsn = "pgsql:host={$host};port={$port};dbname={$database}";
    
    // If connection fails with hostname, we'll try with IPv6 in the catch block
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Set the search path to the specified schema
    $pdo->exec("SET search_path TO {$schema}");
    
    echo "✅ Connection successful!\n";
    
    // Check if the schema exists
    $stmt = $pdo->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name = '{$schema}';");
    $schemaExists = $stmt->fetchColumn();
    
    if ($schemaExists) {
        echo "✅ Schema '{$schema}' exists.\n";
        
        // Check if migrations table exists
        try {
            $stmt = $pdo->query("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_schema = '{$schema}' AND table_name = 'migrations');");
            $migrationsExist = $stmt->fetchColumn();
            
            if ($migrationsExist) {
                echo "✅ Migrations table exists! Migrations have been run.\n";
                
                // Count migrations
                $stmt = $pdo->query("SELECT COUNT(*) FROM {$schema}.migrations;");
                $count = $stmt->fetchColumn();
                echo "   Number of migrations: {$count}\n";
            } else {
                echo "❌ Migrations table does not exist. Migrations have not been run.\n";
                echo "   Run: php artisan migrate\n";
            }
        } catch (PDOException $e) {
            echo "❌ Could not check migrations: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ Schema '{$schema}' does not exist. You need to create it first.\n";
        echo "Run this SQL in Supabase: CREATE SCHEMA {$schema};\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
    
    // Try with IPv6 address if hostname resolution failed
    if (strpos($e->getMessage(), 'could not translate host') !== false) {
        echo "\nAttempting connection with direct IPv6 address...\n";
        try {
            $dsn = "pgsql:host={$ipv6};port={$port};dbname={$database}";
            $pdo = new PDO($dsn, $username, $password, $options);
            
            // Set the search path to the specified schema
            $pdo->exec("SET search_path TO {$schema}");
            
            echo "✅ Connection successful using IPv6 address!\n";
            
            // Check if the schema exists
            $stmt = $pdo->query("SELECT schema_name FROM information_schema.schemata WHERE schema_name = '{$schema}';");
            $schemaExists = $stmt->fetchColumn();
            
            if ($schemaExists) {
                echo "✅ Schema '{$schema}' exists.\n";
                
                // Check if migrations table exists
                try {
                    $stmt = $pdo->query("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_schema = '{$schema}' AND table_name = 'migrations');");
                    $migrationsExist = $stmt->fetchColumn();
                    
                    if ($migrationsExist) {
                        echo "✅ Migrations table exists! Migrations have been run.\n";
                        
                        // Count migrations
                        $stmt = $pdo->query("SELECT COUNT(*) FROM {$schema}.migrations;");
                        $count = $stmt->fetchColumn();
                        echo "   Number of migrations: {$count}\n";
                    } else {
                        echo "❌ Migrations table does not exist. Migrations have not been run.\n";
                        echo "   Run: php artisan migrate\n";
                    }
                } catch (PDOException $e) {
                    echo "❌ Could not check migrations: " . $e->getMessage() . "\n";
                }
            } else {
                echo "❌ Schema '{$schema}' does not exist. You need to create it first.\n";
                echo "Run this SQL in Supabase: CREATE SCHEMA {$schema};\n";
            }
            
            exit(0); // Exit successfully if IPv6 connection works
        } catch (PDOException $e2) {
            echo "❌ IPv6 connection also failed: " . $e2->getMessage() . "\n";
        }
    }
    
    // Provide more specific error messages based on common issues
    if (strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "\nPossible causes:\n";
        echo "- Check if you're using the correct host and port\n";
        echo "- Ensure your IP address is allowed in Supabase's Database Settings\n";
    } elseif (strpos($e->getMessage(), 'password authentication failed') !== false) {
        echo "\nPossible causes:\n";
        echo "- Check if your username and password are correct\n";
    }
}
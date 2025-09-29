# Enabling PostgreSQL Support in XAMPP

## Step 1: Download the PostgreSQL Extension Files

1. First, determine your PHP version by running:
   ```
   php -v
   ```

2. Download the appropriate PostgreSQL extension files for your PHP version from:
   - [Windows PHP Extensions](https://windows.php.net/downloads/pecl/releases/)
   - Look for folders named `php_pdo_pgsql` and `php_pgsql`
   - Make sure to match your PHP version (e.g., 8.2) and architecture (x64 or x86)

## Step 2: Install the Extensions

1. Copy the downloaded DLL files (`php_pdo_pgsql.dll` and `php_pgsql.dll`) to your PHP extensions directory:
   ```
   C:\xampp\php\ext\
   ```

2. Open your PHP configuration file:
   ```
   C:\xampp\php\php.ini
   ```

3. Find the Extensions section and uncomment or add these lines:
   ```
   extension=pgsql
   extension=pdo_pgsql
   ```

4. Save the file and restart your Apache server from the XAMPP control panel.

## Step 3: Verify Installation

To verify that the PostgreSQL extensions are properly installed, run:

```
php -m | findstr pgsql
```

You should see both `pgsql` and `pdo_pgsql` in the output.

Alternatively, you can create a PHP file with the following content:

```php
<?php
phpinfo();
?>
```

Save it as `phpinfo.php` in your web root directory, then access it through your browser. Search for "pgsql" to confirm the extensions are loaded.

## Step 4: Test the Connection

After installing the extensions, run the test script again:

```
php test_db_connection.php
```

If the extensions are properly installed and your connection details are correct, you should be able to connect to your Supabase PostgreSQL database.
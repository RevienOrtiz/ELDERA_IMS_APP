# Installing PostgreSQL PDO Driver for PHP 8.2.12

Based on your environment check, you're running PHP 8.2.12 with XAMPP installed at `C:\xampp`. Here's a step-by-step guide to install the PostgreSQL PDO driver:

## Step 1: Download the Required DLL Files

1. Visit the Windows PHP Extensions page: https://windows.php.net/downloads/pecl/releases/

2. For PHP 8.2.12, navigate to these specific folders:
   - For `php_pgsql`: https://windows.php.net/downloads/pecl/releases/pgsql/8.2.1/php_pgsql-8.2.1-8.2-ts-vs16-x64.zip
   - For `php_pdo_pgsql`: https://windows.php.net/downloads/pecl/releases/pdo_pgsql/8.2.1/php_pdo_pgsql-8.2.1-8.2-ts-vs16-x64.zip

   If the above links don't work, try these direct download links:
   - For `php_pgsql`: https://windows.php.net/downloads/pecl/releases/pgsql/8.2.1/
   - For `php_pdo_pgsql`: https://windows.php.net/downloads/pecl/releases/pdo_pgsql/8.2.1/

3. Download both ZIP files

   **Note**: Make sure to download the TS (Thread Safe) version since you're using XAMPP with Apache.

## Step 2: Extract and Install the DLL Files

1. Extract both ZIP files
2. Locate the DLL files:
   - `php_pgsql.dll`
   - `php_pdo_pgsql.dll`

3. Copy these DLL files to your PHP extensions directory:
   ```
   C:\xampp\php\ext\
   ```

## Step 3: Update PHP Configuration

1. Open your PHP configuration file:
   ```
   C:\xampp\php\php.ini
   ```

2. Find the Extensions section and add or uncomment these lines:
   ```
   extension=pgsql
   extension=pdo_pgsql
   ```

3. Save the file

## Step 4: Restart Apache

1. Open XAMPP Control Panel
2. Stop Apache if it's running
3. Start Apache again

## Step 5: Verify Installation

Run this command to verify the extensions are loaded:

```
php -m | findstr pgsql
```

You should see both `pgsql` and `pdo_pgsql` in the output.

## Step 6: Test Database Connection

After installing the extensions, run the test script again:

```
php test_db_connection.php
```

If everything is set up correctly, you should see a successful connection message.

## Troubleshooting

If you encounter issues:

1. Make sure you downloaded the correct DLL files for your PHP version (8.2.12) and architecture (x64)
2. Verify that the DLL files are in the correct directory (`C:\xampp\php\ext\`)
3. Check that the extensions are properly enabled in `php.ini`
4. Restart Apache after making changes
5. Check for any error messages in the XAMPP error logs
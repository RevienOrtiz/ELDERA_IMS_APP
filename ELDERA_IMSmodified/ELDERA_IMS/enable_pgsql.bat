@echo off
echo Enabling PostgreSQL extensions in php.ini...

powershell -Command "(Get-Content 'C:\xampp\php\php.ini') -replace ';extension=pdo_pgsql', 'extension=pdo_pgsql' -replace ';extension=pgsql', 'extension=pgsql' | Set-Content 'C:\xampp\php\php.ini'"

echo.
echo PostgreSQL extensions have been enabled.
echo Please restart Apache for changes to take effect.
echo.
echo Press any key to exit...
pause > nul
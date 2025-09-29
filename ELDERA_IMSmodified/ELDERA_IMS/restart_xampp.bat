@echo off
echo Restarting XAMPP services...

echo Stopping Apache...
cd /d C:\xampp
xampp_stop.exe

echo Starting Apache...
xampp_start.exe

echo.
echo XAMPP services have been restarted.
echo.
echo Press any key to exit...
pause > nul
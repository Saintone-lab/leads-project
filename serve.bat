@echo off
set "PHP82=C:\Users\lemot\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe"
set "PATH=%PHP82%;%PATH%"
php artisan serve %*

@echo off
cd /d c:\laragon\www\web-portal\portal-backend
php artisan schedule:run >> storage\logs\scheduler.log 2>&1

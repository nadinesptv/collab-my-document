@echo off
cd /d "%~dp0"
echo Starting Laravel Reverb on ws://localhost:8080 ...
php artisan reverb:start

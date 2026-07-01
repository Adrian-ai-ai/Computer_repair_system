@echo off
cd /d "C:\Users\VSO\OneDrive\Documents\code_projects\inventory-repair-system"
php artisan schedule:run >> storage/logs/scheduler.log 2>&1

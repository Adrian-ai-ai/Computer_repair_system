# PowerShell script to create Windows Task Scheduler for Laravel
$taskName = "Laravel Inventory Repair Scheduler"
$scriptPath = "C:\Users\VSO\OneDrive\Documents\code_projects\inventory-repair-system\setup-scheduler.bat"
$description = "Runs Laravel scheduler for inventory repair system email reports"

# Check if running as administrator
if (-NOT ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")) {
    Write-Warning "Please run this script as Administrator to create the scheduled task."
    Write-Host "Right-click PowerShell and select 'Run as Administrator'"
    pause
    exit 1
}

# Remove existing task if it exists
try {
    Unregister-ScheduledTask -TaskName $taskName -ErrorAction SilentlyContinue
    Write-Host "Removed existing task: $taskName"
} catch {
    # Task doesn't exist, continue
}

# Create the scheduled task
$action = New-ScheduledTaskAction -Execute "cmd.exe" -Argument "/c `"$scriptPath`""
$trigger = New-ScheduledTaskTrigger -Daily -At 9am
$settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable
$principal = New-ScheduledTaskPrincipal -UserId "SYSTEM" -LogonType ServiceAccount

Register-ScheduledTask -TaskName $taskName -Action $action -Trigger $trigger -Settings $settings -Principal $principal -Description $description

Write-Host "✅ Scheduled task created successfully!"
Write-Host "Task: $taskName"
Write-Host "Runs: Daily at 9:00 AM"
Write-Host "Script: $scriptPath"
Write-Host ""
Write-Host "You can verify the task in Task Scheduler or run it manually:"
Write-Host "Start-ScheduledTask -TaskName `"$taskName`""

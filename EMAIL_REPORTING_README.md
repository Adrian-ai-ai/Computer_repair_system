<<<<<<< HEAD
# Email Reporting System

This system provides comprehensive email reporting for job status updates with both HTML emails and PDF attachments.

## Features

- **Multiple Recipients**: Send reports to clients, staff, and managers
- **Flexible Scheduling**: On-demand sending + automated daily/weekly reports
- **Dual Format Attachments**: Both PDF and Excel files with every email
- **Professional Format**: HTML emails with PDF and Excel attachments
- **User Tracking**: Shows who performed actions on jobs
- **Summary Statistics**: Job counts, completion rates, status breakdowns

## Report Types

### 1. Client Reports
- Personalized reports for individual clients
- Shows all their repair jobs with current status
- Includes job details, status history, and technician information

### 2. Staff Reports
- Sent to technicians and storekeepers
- Overview of all jobs in the system
- Helps staff stay informed about workload and progress

### 3. Manager Reports
- Detailed reports for administrators
- Complete system overview with statistics
- Includes all job data and performance metrics

## Access

The reports system is accessible from the main navigation bar:

1. **Navigation Bar**: Click "Reports" in the top navigation
2. **Reports Dashboard**: Overview of all available reports
3. **Email Reports**: Click "Send Email Reports" to access the email functionality

### On-Demand Reports

From the Reports Dashboard:

1. **Send to Client**: Select a client and optional date range
2. **Send to Staff**: Send to all technicians and storekeepers
3. **Send to Managers**: Send detailed reports to all administrators

### Automated Reports

The system automatically sends:
- **Daily Reports**: Every weekday at 9 AM (previous day's activity)
- **Weekly Reports**: Every Monday at 9 AM (previous week's summary)

To run scheduled reports manually:
```bash
php artisan reports:send-scheduled daily
php artisan reports:send-scheduled weekly
```

## Email Content

Each report includes:
- **HTML Email**: Professional layout with status badges
- **PDF Attachment**: Detailed printable report
- **Excel Attachment**: Spreadsheet for data analysis and filtering
- **Summary Statistics**: Job counts and completion rates
- **Job Details**: Individual job information with status history
- **User Attribution**: Shows who received jobs and changed statuses

## Configuration

### Mail Configuration
Update your `.env` file with mail settings:
```
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="Repair System"
```

### PDF Configuration
The PDF generation uses DomPDF. You can customize settings in `config/dompdf.php`.

## Technical Details

### Files Created/Modified
- `app/Mail/JobStatusReport.php` - Main mail class
- `app/Http/Controllers/ReportsController.php` - Extended with email methods
- `resources/views/emails/job-status-report.blade.php` - HTML email template
- `resources/views/emails/job-status-report-pdf.blade.php` - PDF template
- `resources/views/reports/dashboard.blade.php` - Reports dashboard
- `resources/views/reports/email.blade.php` - Email reports page
- `routes/web.php` - Added reporting routes
- `routes/console.php` - Added scheduled commands
- `app/Console/Commands/SendScheduledReports.php` - Console command

### Dependencies Added
- `barryvdh/laravel-dompdf` - For PDF generation
- `maatwebsite/excel` - For Excel export generation

### Excel Export Features
- **Formatted Spreadsheets**: Professional styling with headers and column widths
- **Complete Data**: Includes report metadata, summary statistics, and detailed job information
- **Status History**: Shows recent status changes with user attribution
- **Analysis Ready**: Perfect for data filtering, sorting, and pivot tables

## Security Notes

- Reports respect user roles and permissions
- Client reports only show their own jobs
- Staff reports show all jobs (for operational awareness)
- Manager reports show complete system data

## Troubleshooting

### Emails Not Sending
1. Check mail configuration in `.env`
2. Verify SMTP credentials
3. Check Laravel logs for errors

### PDF Generation Issues
1. Ensure DomPDF is properly installed
2. Check file permissions for storage
3. Verify PHP extensions (GD, etc.)

### Excel Generation Issues
1. Ensure maatwebsite/excel is properly installed
2. Check file permissions for storage
3. Verify PHP extensions (zip, xml)
4. Test with: `php artisan tinker` and try Excel export commands

### Scheduled Reports Not Running
1. Ensure cron jobs are set up for Laravel
2. Check that the schedule is running: `php artisan schedule:run`
=======
# Email Reporting System

This system provides comprehensive email reporting for job status updates with both HTML emails and PDF attachments.

## Features

- **Multiple Recipients**: Send reports to clients, staff, and managers
- **Flexible Scheduling**: On-demand sending + automated daily/weekly reports
- **Dual Format Attachments**: Both PDF and Excel files with every email
- **Professional Format**: HTML emails with PDF and Excel attachments
- **User Tracking**: Shows who performed actions on jobs
- **Summary Statistics**: Job counts, completion rates, status breakdowns

## Report Types

### 1. Client Reports
- Personalized reports for individual clients
- Shows all their repair jobs with current status
- Includes job details, status history, and technician information

### 2. Staff Reports
- Sent to technicians and storekeepers
- Overview of all jobs in the system
- Helps staff stay informed about workload and progress

### 3. Manager Reports
- Detailed reports for administrators
- Complete system overview with statistics
- Includes all job data and performance metrics

## Access

The reports system is accessible from the main navigation bar:

1. **Navigation Bar**: Click "Reports" in the top navigation
2. **Reports Dashboard**: Overview of all available reports
3. **Email Reports**: Click "Send Email Reports" to access the email functionality

### On-Demand Reports

From the Reports Dashboard:

1. **Send to Client**: Select a client and optional date range
2. **Send to Staff**: Send to all technicians and storekeepers
3. **Send to Managers**: Send detailed reports to all administrators

### Automated Reports

The system automatically sends:
- **Daily Reports**: Every weekday at 9 AM (previous day's activity)
- **Weekly Reports**: Every Monday at 9 AM (previous week's summary)

To run scheduled reports manually:
```bash
php artisan reports:send-scheduled daily
php artisan reports:send-scheduled weekly
```

## Email Content

Each report includes:
- **HTML Email**: Professional layout with status badges
- **PDF Attachment**: Detailed printable report
- **Excel Attachment**: Spreadsheet for data analysis and filtering
- **Summary Statistics**: Job counts and completion rates
- **Job Details**: Individual job information with status history
- **User Attribution**: Shows who received jobs and changed statuses

## Configuration

### Mail Configuration
Update your `.env` file with mail settings:
```
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="Repair System"
```

### PDF Configuration
The PDF generation uses DomPDF. You can customize settings in `config/dompdf.php`.

## Technical Details

### Files Created/Modified
- `app/Mail/JobStatusReport.php` - Main mail class
- `app/Http/Controllers/ReportsController.php` - Extended with email methods
- `resources/views/emails/job-status-report.blade.php` - HTML email template
- `resources/views/emails/job-status-report-pdf.blade.php` - PDF template
- `resources/views/reports/dashboard.blade.php` - Reports dashboard
- `resources/views/reports/email.blade.php` - Email reports page
- `routes/web.php` - Added reporting routes
- `routes/console.php` - Added scheduled commands
- `app/Console/Commands/SendScheduledReports.php` - Console command

### Dependencies Added
- `barryvdh/laravel-dompdf` - For PDF generation
- `maatwebsite/excel` - For Excel export generation

### Excel Export Features
- **Formatted Spreadsheets**: Professional styling with headers and column widths
- **Complete Data**: Includes report metadata, summary statistics, and detailed job information
- **Status History**: Shows recent status changes with user attribution
- **Analysis Ready**: Perfect for data filtering, sorting, and pivot tables

## Security Notes

- Reports respect user roles and permissions
- Client reports only show their own jobs
- Staff reports show all jobs (for operational awareness)
- Manager reports show complete system data

## Troubleshooting

### Emails Not Sending
1. Check mail configuration in `.env`
2. Verify SMTP credentials
3. Check Laravel logs for errors

### PDF Generation Issues
1. Ensure DomPDF is properly installed
2. Check file permissions for storage
3. Verify PHP extensions (GD, etc.)

### Excel Generation Issues
1. Ensure maatwebsite/excel is properly installed
2. Check file permissions for storage
3. Verify PHP extensions (zip, xml)
4. Test with: `php artisan tinker` and try Excel export commands

### Scheduled Reports Not Running
1. Ensure cron jobs are set up for Laravel
2. Check that the schedule is running: `php artisan schedule:run`
>>>>>>> 814c0e3a080a93b0a4c40958610f5493345a9fd8
3. Verify command permissions
# Employee Attendance & Payroll Application

A Laravel-based employee attendance and payroll management system for Indonesian companies. Employees can record attendance, view history, and print salary slips. Admins can manage employees, holidays, leaves, and payroll with progressive month visibility.

## Features

### Employee Features
- Login with email and password
- Record daily presence (check-in and check-out)
- View presence history (progressive, from first month used)
- See attendance status (Hadir, Terlambat, Tidak Hadir, Libur, Cuti)
- Print and view salary slips (slip gaji)
- See salary history (progressive, from first month used)
- Indonesian localization for UI and pagination

### Admin Features
- Manage employee data (CRUD)
- View and manage attendance (progressive, from first month used)
- Manage holidays (automatic detection & manual add)
- Manage leave requests (approve/reject)
- Manage and calculate payroll (gaji) with progressive month visibility
- Print and manage salary slips for all employees
- Dashboard with summary statistics
- Pagination and search on all major tables

## Requirements

- PHP >= 8.1
- Composer
- MySQL (XAMPP recommended)
- Node.js & NPM (for frontend assets)

## Installation

1. Clone the repository
2. Install PHP dependencies:
```bash
composer install
```
3. Copy `.env.example` to `.env` and configure your database and API keys:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=presence_db
DB_USERNAME=root
DB_PASSWORD=

# Add your holiday API key (get it from https://calendarific.com/)
CALENDARIFIC_API_KEY=your_calendarific_api_key
CALENDARIFIC_API_URL=https://calendarific.com/api/v2/holidays
```
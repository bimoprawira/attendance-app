# Employee Attendance & Payroll Application

A Laravel-based employee attendance and payroll management system for Indonesian companies. Employees can record attendance, view history, and print salary slips. Admins can manage employees, holidays, leaves, and payroll.

## Features

### Employee Features
- Login with email and password
- Record daily presence (check-in and check-out)
- View presence history 
- See attendance status (Hadir, Terlambat, Tidak Hadir, Libur, Cuti)
- Make leave requests
- Print and view salary slips (slip gaji)
- See salary history (progressive, from first month used)
- Indonesian localization for UI and pagination

### Admin Features
- Manage employee data (CRUD)
- View and manage attendance
- Manage holidays (automatic detection & manual add)
- Manage leave requests (approve/reject)
- Manage and calculate payroll (gaji)
- Print and manage salary slips for all employees
- Dashboard with summary statistics
- Pagination and search on all major tables

## Requirements

- PHP >= 8.1
- Composer
- MySQL (XAMPP recommended)
- Node.js & NPM (for frontend assets)

## Automatic Installation
1. Clone the repository
2. If you use git bash simply run this command
```bash
./setup.sh
```
## Manual Installation

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
```

Also add your holiday API key to `.env` (get it [here](https://calendarific.com/))
```
CALENDARIFIC_API_KEY=your_calendarific_api_key

```
4. Generate application key:
```bash
php artisan key:generate
```
5. Run database migrations and seeders:
```bash
php artisan migrate:fresh --seed
```
6. Install and build frontend assets
```bash
npm install
```
```bash
npm run build
```
7. Fetch holidays for the current year (optional, for best results):
```bash
php artisan holidays:fetch 2025
```
8. Start the development server:
```bash
php artisan serve
```

## Test Accounts

### Admin Account
- Username: admin
- Password: admin123

### Employee Account
- Email: john.doe@example.com
- Password: password

## Usage

1. Access the application at `http://localhost:8000`
2. Login as admin or employee
3. Admins can manage employees, holidays, leaves, and payroll
4. Employees can check-in/out, view attendance, and print salary slips

## Business Rules

- Presence open 30 minutes before work hour starts
- Employees are marked as "present" if they check in between 08:30 - 09:00 WIB
- Employees are marked as "late" if they check in after 09:00 WIB
- Employees are automatically marked as "absent" after 10:00 WIB
- Employees can only check in and out once per day
- Holidays and weekends are auto-detected and marked as "Libur"
- Salary (gaji) is calculated only for the current and past months (progressive visibility)
- Only admins can manage employee data, holidays, and payroll
- All major tables use pagination (10 items per page) and are localized in Indonesian

## Localization

- UI and pagination are in Bahasa Indonesia
- Salary and attendance history are shown progressively (from first month used)

---

For more details, see the code and comments in each controller and view.

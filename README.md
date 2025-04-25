# Employee Presence Application

A Laravel-based employee presence management system that allows employees to record their attendance and administrators to manage employee data.

## Features

### Employee Features
- Login with email and password
- Record daily presence (check-in and check-out)
- View presence history
- Status tracking (present/late)

### Admin Features
- Manage employee data (CRUD operations)
- View attendance statistics
- Dashboard with summary information

## Requirements

- PHP >= 8.1
- Composer
- MySQL (XAMPP)
- Node.js & NPM (for frontend assets)

## Installation

1. Clone the repository
2. Install PHP dependencies:
```bash
composer install
```

3. Copy `.env.example` to `.env` and configure your database:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=presence_db
DB_USERNAME=root
DB_PASSWORD=
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Run database migrations and seeders:
```bash
php artisan migrate:fresh --seed
```

6. Start the development server:
```bash
php artisan serve
```

## Test Accounts

### Admin Account
- Email: admin@example.com
- Password: password

### Employee Account
- Email: employee@example.com
- Password: password

## Usage

1. Access the application at `http://localhost:8000`
2. Login using either the admin or employee test accounts
3. Admin users can manage employees and view attendance statistics
4. Employees can check-in/check-out and view their presence history

## Business Rules

- Employees are marked as "late" if they check in after 9:00 AM
- Employees can only check in and out once per day
- Only admins can manage employee data

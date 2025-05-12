<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'Senior Developer' => [12000000, 15000000],
            'Project Manager' => [13000000, 17000000],
            'UI/UX Designer' => [9000000, 12000000],
            'Business Analyst' => [10000000, 13000000],
            'Quality Assurance' => [9000000, 11000000],
            'Junior Developer' => [7000000, 9000000],
            'HR Specialist' => [8000000, 11000000],
            'DevOps Engineer' => [12000000, 16000000],
            'Backend Developer' => [10000000, 13000000],
            'Frontend Developer' => [10000000, 13000000],
            'Mobile Developer' => [10000000, 13000000],
            'Scrum Master' => [11000000, 14000000],
            'System Analyst' => [10000000, 13000000],
            'Support Engineer' => [8000000, 10000000],
            'Finance Officer' => [9000000, 12000000],
            'Marketing Specialist' => [9000000, 12000000],
            'Content Writer' => [7000000, 9000000],
            'Product Owner' => [13000000, 17000000],
            'IT Security' => [12000000, 15000000],
            'Data Scientist' => [13000000, 17000000],
        ];

        $employees = [
            ['name' => 'John Doe', 'email' => 'john.doe@example.com', 'position' => 'Senior Developer'],
            ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com', 'position' => 'Project Manager'],
            ['name' => 'Mike Johnson', 'email' => 'mike.johnson@example.com', 'position' => 'UI/UX Designer'],
            ['name' => 'Sarah Wilson', 'email' => 'sarah.wilson@example.com', 'position' => 'Business Analyst'],
            ['name' => 'David Brown', 'email' => 'david.brown@example.com', 'position' => 'Quality Assurance'],
            ['name' => 'Emily Davis', 'email' => 'emily.davis@example.com', 'position' => 'Junior Developer'],
            ['name' => 'Kevin Lee', 'email' => 'kevin.lee@example.com', 'position' => 'HR Specialist'],
            ['name' => 'Linda Kim', 'email' => 'linda.kim@example.com', 'position' => 'DevOps Engineer'],
            ['name' => 'Chris Evans', 'email' => 'chris.evans@example.com', 'position' => 'Backend Developer'],
            ['name' => 'Anna Taylor', 'email' => 'anna.taylor@example.com', 'position' => 'Frontend Developer'],
            ['name' => 'Brian Clark', 'email' => 'brian.clark@example.com', 'position' => 'Mobile Developer'],
            ['name' => 'Olivia Martin', 'email' => 'olivia.martin@example.com', 'position' => 'Scrum Master'],
            ['name' => 'Sophia Turner', 'email' => 'sophia.turner@example.com', 'position' => 'System Analyst'],
            ['name' => 'James White', 'email' => 'james.white@example.com', 'position' => 'Support Engineer'],
            ['name' => 'Grace Hall', 'email' => 'grace.hall@example.com', 'position' => 'Finance Officer'],
            ['name' => 'Lucas Young', 'email' => 'lucas.young@example.com', 'position' => 'Marketing Specialist'],
            ['name' => 'Ella King', 'email' => 'ella.king@example.com', 'position' => 'Content Writer'],
            ['name' => 'Daniel Scott', 'email' => 'daniel.scott@example.com', 'position' => 'Product Owner'],
            ['name' => 'Megan Green', 'email' => 'megan.green@example.com', 'position' => 'IT Security'],
            ['name' => 'Matthew Adams', 'email' => 'matthew.adams@example.com', 'position' => 'Data Scientist'],
        ];

        foreach ($employees as $employeeData) {
            $range = $positions[$employeeData['position']];
            $gajiPokok = rand($range[0], $range[1]);
            Employee::create([
                'name' => $employeeData['name'],
                'email' => $employeeData['email'],
                'password' => Hash::make('password'),
                'role' => 'employee',
                'position' => $employeeData['position'],
                'gaji_pokok' => $gajiPokok,
                'date_joined' => now(),
                'annual_leave_quota' => 12,
                'sick_leave_quota' => 12,
                'emergency_leave_quota' => 6,
                'used_annual_leave' => 0,
                'used_sick_leave' => 0,
                'used_emergency_leave' => 0,
            ]);
        }
    }
} 
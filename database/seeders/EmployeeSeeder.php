<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'position' => 'Senior Developer',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'position' => 'Project Manager',
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike.johnson@example.com',
                'position' => 'UI/UX Designer',
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah.wilson@example.com',
                'position' => 'Business Analyst',
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@example.com',
                'position' => 'Quality Assurance',
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@example.com',
                'position' => 'Junior Developer',
            ],
        ];

        foreach ($employees as $employeeData) {
            Employee::create([
                'name' => $employeeData['name'],
                'email' => $employeeData['email'],
                'password' => Hash::make('password'),
                'role' => 'employee',
                'position' => $employeeData['position'],
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
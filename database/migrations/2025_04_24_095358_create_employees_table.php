<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('employee');
            $table->string('position');
            $table->date('date_joined');
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->integer('annual_leave_quota')->default(12);
            $table->integer('sick_leave_quota')->default(12);
            $table->integer('emergency_leave_quota')->default(6);
            $table->integer('used_annual_leave')->default(0);
            $table->integer('used_sick_leave')->default(0);
            $table->integer('used_emergency_leave')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

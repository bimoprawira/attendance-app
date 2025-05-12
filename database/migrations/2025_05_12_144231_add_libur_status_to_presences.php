<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE presences MODIFY COLUMN status ENUM('present', 'late', 'absent', 'on_leave', 'not_checked_in', 'libur')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change all 'libur' statuses to 'absent' before altering the column
        DB::table('presences')->where('status', 'libur')->update(['status' => 'absent']);
        DB::statement("ALTER TABLE presences MODIFY COLUMN status ENUM('present', 'late', 'absent', 'on_leave', 'not_checked_in')");
    }
};

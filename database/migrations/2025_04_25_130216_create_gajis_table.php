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
        Schema::create('gajis', function (Blueprint $table) {
            $table->id('id_gaji'); // Primary Key
            $table->unsignedBigInteger('employee_id'); // FK ke tabel employees
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('potongan', 15, 2)->nullable();
            $table->decimal('komponen_tambahan', 15, 2)->nullable();
            $table->string('status')->default('selesai');
            $table->string('periode_bayar'); // Contoh: 'April 2025'
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajis');
    }
};

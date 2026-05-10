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
        Schema::create('admin_table', function (Blueprint $table) {
            $table->id('admin_id'); // Matches Image #2
            // Added unique() to username to prevent duplicate accounts
            $table->string('username', 50)->unique(); 
            $table->string('password', 255);
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable(); // Matches 'Null: Yes'
            $table->string('last_name', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // FIXED: Must match the table name in the up() method
        Schema::dropIfExists('admin_table');
    }
};
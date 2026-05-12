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
        Schema::create('borrower_table', function (Blueprint $table) {
            $table->id('borrower_id'); 
            $table->string('first_name', 50); 
            $table->string('middle_name', 50)->nullable(); 
            $table->string('last_name', 50); 
            $table->string('contact_number', 20)->nullable(); 
            
            // Address Fields
            $table->string('house_no_bldg')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay');
            $table->string('city_municipality');
            $table->string('province');
            $table->string('zip_code')->nullable();

            $table->date('date_registered');
            
            // Foreign Key
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('admin_id')->on('admin_table')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // FIXED: Must match the table name in up()
        Schema::dropIfExists('borrower_table');
    }
};
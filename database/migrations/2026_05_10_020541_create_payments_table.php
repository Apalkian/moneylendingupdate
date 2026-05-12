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
       Schema::create('payment_table', function (Blueprint $table) {
            $table->id('payment_id'); 
            $table->unsignedBigInteger('loan_id'); 
            $table->date('payment_date'); 
            $table->decimal('amount_paid', 15, 2); 
            $table->decimal('interest_added', 15, 2)->default(0.00); 
            
            
            $table->unsignedBigInteger('admin_id')->nullable(); 

            // Foreign Key Definitions
            $table->foreign('loan_id')->references('loan_id')->on('loan_table')->onDelete('cascade');
            $table->foreign('admin_id')->references('admin_id')->on('admin_table')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // FIXED: Changed 'payments' to 'payment_table'
        Schema::dropIfExists('payment_table');
    }
};
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
        Schema::create('additional_table', function (Blueprint $table) {
            $table->id('capital_id'); 
            $table->unsignedBigInteger('loan_id');
            $table->decimal('amount_added', 15, 2); 
            $table->date('date_added'); 
            $table->text('remarks')->nullable(); 
            
            // Foreign Key Relationship
            $table->foreign('loan_id')->references('loan_id')->on('loan_table')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::dropIfExists('additional_table');
    }
};
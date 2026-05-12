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
       Schema::create('loan_table', function (Blueprint $table) {
            $table->id('loan_id'); 
            $table->unsignedBigInteger('borrower_id'); 
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2); 
            $table->date('release_date'); 
            $table->date('due_date'); 
            $table->string('status', 20)->default('Active'); 
            $table->unsignedBigInteger('admin_id')->nullable(); 
            
            // Defining Relationships
            $table->foreign('borrower_id')->references('borrower_id')->on('borrower_table')->onDelete('cascade');
            $table->foreign('admin_id')->references('admin_id')->on('admin_table')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
        Schema::dropIfExists('loan_table');
    }
};
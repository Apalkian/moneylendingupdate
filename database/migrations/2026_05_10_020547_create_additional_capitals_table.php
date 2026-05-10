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
            $table->id('capital_id'); // #1 capital_id
            $table->unsignedBigInteger('loan_id'); // #2 loan_id (Foreign Key)
            $table->decimal('amount_added', 15, 2); // #3 amount_added
            $table->date('date_added'); // #4 date_added
            $table->text('remarks')->nullable(); // #5 remarks (Null: Yes)
            
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
        // FIXED: Changed 'additional_capitals' to 'additional_table'
        Schema::dropIfExists('additional_table');
    }
};
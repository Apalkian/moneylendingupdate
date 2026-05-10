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
            $table->id('loan_id'); // #1 loan_id
            $table->unsignedBigInteger('borrower_id'); // #2 borrower_id (Foreign Key)
            $table->decimal('principal_amount', 15, 2); // #3
            $table->decimal('interest_rate', 5, 2); // #4
            $table->date('release_date'); // #5
            $table->date('due_date'); // #6
            $table->string('status', 20)->default('Active'); // #7 (Default: Active)
            $table->unsignedBigInteger('admin_id')->nullable(); // #8 admin_id (Foreign Key)
            
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
        // FIXED: Changed 'loans' to 'loan_table'
        Schema::dropIfExists('loan_table');
    }
};
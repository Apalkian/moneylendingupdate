<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('loans')) {
            // Production safety: ensure legacy tables have AUTO_INCREMENT on primary key.
            try {
                DB::statement('ALTER TABLE loans MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            } catch (\Throwable $e) {
                // Ignore here; dedicated fix migrations also handle this scenario.
            }

            return;
        }

        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrower_id')->constrained('borrowers')->cascadeOnDelete();
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->enum('interest_type', ['daily', 'weekly', 'monthly']);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'active', 'overdue', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};

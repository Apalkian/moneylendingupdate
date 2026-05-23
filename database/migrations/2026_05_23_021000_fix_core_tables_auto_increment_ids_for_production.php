<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE borrowers MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE loans MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE payments MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE payments MODIFY id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE loans MODIFY id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE borrowers MODIFY id BIGINT UNSIGNED NOT NULL');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = ['borrowers', 'loans', 'payments'];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            DB::statement("ALTER TABLE {$table} MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['payments', 'loans', 'borrowers'];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            DB::statement("ALTER TABLE {$table} MODIFY id BIGINT UNSIGNED NOT NULL");
        }
    }
};


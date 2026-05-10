<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // FIXED: Added this import

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // View 1: Loan Report
    // ADDED "OR REPLACE" here
    DB::statement("
        CREATE OR REPLACE VIEW vw_Loan_Report AS
        SELECT 
            l.loan_id, 
            CONCAT(b.first_name, ' ', b.last_name) AS Borrower_Name, 
            l.principal_amount AS Principal, 
            l.interest_rate AS Rate_Percent, 
            l.status AS Loan_Status, 
            l.release_date
        FROM loan_table l
        JOIN borrower_table b ON l.borrower_id = b.borrower_id
    ");

    // View 2: Payment History
    // ADDED "OR REPLACE" here
    DB::statement("
        CREATE OR REPLACE VIEW vw_Payment_History AS
        SELECT 
            p.payment_date, 
            CONCAT(b.first_name, ' ', b.last_name) AS Borrower, 
            p.amount_paid, 
            p.interest_added, 
            a.username AS Admin_Processor
        FROM payment_table p
        JOIN loan_table l ON p.loan_id = l.loan_id
        JOIN borrower_table b ON l.borrower_id = b.borrower_id
        JOIN admin_table a ON p.admin_id = a.admin_id
    ");

    // View 3: Outstanding Balances
    // ADDED "OR REPLACE" here
    DB::statement("
        CREATE OR REPLACE VIEW vw_Outstanding_Balances AS
        SELECT 
            l.loan_id, 
            CONCAT(b.first_name, ' ', b.last_name) AS Borrower,
            (l.principal_amount + 
             COALESCE((SELECT SUM(amount_added) FROM additional_table WHERE loan_id = l.loan_id), 0) +
             COALESCE((SELECT SUM(interest_added) FROM payment_table WHERE loan_id = l.loan_id), 0) - 
             COALESCE((SELECT SUM(amount_paid) FROM payment_table WHERE loan_id = l.loan_id), 0)
            ) AS Current_Balance, 
            l.status
        FROM loan_table l
        JOIN borrower_table b ON l.borrower_id = b.borrower_id
        WHERE l.status != 'Completed'
    ");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the views in reverse order
        DB::statement("DROP VIEW IF EXISTS vw_Outstanding_Balances");
        DB::statement("DROP VIEW IF EXISTS vw_Payment_History");
        DB::statement("DROP VIEW IF EXISTS vw_Loan_Report");
    }
};

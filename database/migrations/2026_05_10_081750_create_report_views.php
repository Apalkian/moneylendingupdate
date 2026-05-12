<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // View Loan Report
        DB::statement("
            CREATE OR REPLACE VIEW vw_Loan_Report AS
            SELECT 
                l.loan_id, 
                CONCAT(b.first_name, ' ', b.last_name) AS Borrower_Name, 
                l.principal_amount AS Principal, 
                l.interest_rate AS Rate_Percent, 
                CASE
                    WHEN
                        COALESCE((SELECT SUM(amount_paid) FROM payment_table WHERE loan_id = l.loan_id), 0)
                        >= (l.principal_amount + (l.principal_amount * (l.interest_rate / 100)))
                    THEN 'Completed'
                    ELSE 'Active'
                END AS Loan_Status,
                l.release_date,
                DATE_ADD(l.release_date, INTERVAL 1 MONTH) AS due_date,
                
                -- Rounded to 2 decimal places
                ROUND((l.principal_amount + (l.principal_amount * (l.interest_rate / 100))), 2) AS total_to_pay
            FROM loan_table l
            JOIN borrower_table b ON l.borrower_id = b.borrower_id
        ");

        // View Payment History
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

        // View Outstanding Balances
        DB::statement("
            CREATE OR REPLACE VIEW vw_Outstanding_Balances AS
            SELECT 
                l.loan_id, 
                CONCAT(b.first_name, ' ', b.last_name) AS Borrower,
                ROUND(
                    (l.principal_amount + (l.principal_amount * (l.interest_rate / 100)))
                    - COALESCE((SELECT SUM(amount_paid) FROM payment_table WHERE loan_id = l.loan_id), 0), 
                2) AS Current_Balance,
                CASE
                    WHEN COALESCE((SELECT SUM(amount_paid) FROM payment_table WHERE loan_id = l.loan_id), 0)
                         >= (l.principal_amount + (l.principal_amount * (l.interest_rate / 100)))
                    THEN 'Completed'
                    ELSE 'Active'
                END AS status
            FROM loan_table l
            JOIN borrower_table b ON l.borrower_id = b.borrower_id
            HAVING Current_Balance > 0
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_Outstanding_Balances");
        DB::statement("DROP VIEW IF EXISTS vw_Payment_History");
        DB::statement("DROP VIEW IF EXISTS vw_Loan_Report");
    }
};
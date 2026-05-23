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
        DB::statement('DROP VIEW IF EXISTS vw_loan_report');
        DB::statement('DROP VIEW IF EXISTS vw_outstanding_balances');
        DB::statement('DROP VIEW IF EXISTS vw_payment_history');

        DB::statement(<<<SQL
            CREATE SQL SECURITY INVOKER VIEW vw_loan_report AS
            SELECT
                l.id AS loan_id,
                l.borrower_id,
                COALESCE(u.name, CONCAT('Borrower #', b.id)) AS borrower_name,
                CAST(l.principal_amount AS DECIMAL(15,2)) AS principal_amount,
                CAST(l.interest_rate AS DECIMAL(5,2)) AS interest_rate,
                l.interest_type,
                l.start_date,
                l.end_date,
                l.status,
                CAST((
                    (l.principal_amount + (l.principal_amount * (l.interest_rate / 100)))
                    + COALESCE(ac.total_additional, 0)
                    - COALESCE(p.total_paid, 0)
                ) AS DECIMAL(15,2)) AS outstanding_balance
            FROM loans l
            INNER JOIN borrowers b ON b.id = l.borrower_id
            LEFT JOIN users u ON u.id = b.user_id
            LEFT JOIN (
                SELECT loan_id, SUM(amount_paid) AS total_paid
                FROM payments
                GROUP BY loan_id
            ) p ON p.loan_id = l.id
            LEFT JOIN (
                SELECT loan_id, SUM(amount_added) AS total_additional
                FROM additional_table
                GROUP BY loan_id
            ) ac ON ac.loan_id = l.id
        SQL);

        DB::statement(<<<SQL
            CREATE SQL SECURITY INVOKER VIEW vw_outstanding_balances AS
            SELECT
                l.id AS loan_id,
                l.borrower_id,
                COALESCE(u.name, CONCAT('Borrower #', b.id)) AS borrower_name,
                l.end_date AS due_date,
                ROUND((l.principal_amount + (l.principal_amount * (l.interest_rate / 100))), 2) AS base_total_due,
                ROUND(COALESCE(ac.total_additional, 0), 2) AS total_additional_payment,
                ROUND(COALESCE(p.total_paid, 0), 2) AS total_paid,
                ROUND((
                    (l.principal_amount + (l.principal_amount * (l.interest_rate / 100)))
                    + COALESCE(ac.total_additional, 0)
                    - COALESCE(p.total_paid, 0)
                ), 2) AS outstanding_balance,
                l.status
            FROM loans l
            INNER JOIN borrowers b ON b.id = l.borrower_id
            LEFT JOIN users u ON u.id = b.user_id
            LEFT JOIN (
                SELECT loan_id, SUM(amount_paid) AS total_paid
                FROM payments
                GROUP BY loan_id
            ) p ON p.loan_id = l.id
            LEFT JOIN (
                SELECT loan_id, SUM(amount_added) AS total_additional
                FROM additional_table
                GROUP BY loan_id
            ) ac ON ac.loan_id = l.id
        SQL);

        DB::statement(<<<SQL
            CREATE SQL SECURITY INVOKER VIEW vw_payment_history AS
            SELECT
                p.id AS payment_id,
                p.loan_id,
                p.payment_date,
                p.amount_paid,
                p.transaction_reference,
                p.notes,
                l.borrower_id,
                COALESCE(u.name, CONCAT('Borrower #', b.id)) AS borrower_name
            FROM payments p
            INNER JOIN loans l ON l.id = p.loan_id
            INNER JOIN borrowers b ON b.id = l.borrower_id
            LEFT JOIN users u ON u.id = b.user_id
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_payment_history');
        DB::statement('DROP VIEW IF EXISTS vw_outstanding_balances');
        DB::statement('DROP VIEW IF EXISTS vw_loan_report');
    }
};

<?php

namespace Database\Seeders;

use App\Models\Borrower;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => 'password123',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $borrowerUser = User::updateOrCreate(
            ['email' => 'borrower@example.com'],
            [
                'name' => 'Sample Borrower',
                'password' => 'password123',
                'role' => 'borrower',
                'email_verified_at' => now(),
            ]
        );

        $borrower = Borrower::updateOrCreate(
            ['user_id' => $borrowerUser->id],
            [
                'phone_number' => '09171234567',
                'address' => 'Davao City',
                'credit_status' => 'good',
            ]
        );

        $loan = Loan::updateOrCreate(
            ['borrower_id' => $borrower->id, 'status' => 'active'],
            [
                'principal_amount' => 5000.00,
                'interest_rate' => 10.00,
                'interest_type' => 'monthly',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
            ]
        );

        Payment::updateOrCreate(
            ['transaction_reference' => 'SEED-TXN-0001'],
            [
                'loan_id' => $loan->id,
                'amount_paid' => 500.00,
                'payment_date' => now()->toDateString(),
                'notes' => 'Seed payment record',
            ]
        );
    }
}

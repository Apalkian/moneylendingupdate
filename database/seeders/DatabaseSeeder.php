<?php

namespace Database\Seeders;
use App\Models\Admin;
use App\Models\Borrower;
use App\Models\Loan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {// 1. Create Admin
    $admin = Admin::create([
        'username' => 'admin_user',
        'password' => bcrypt('password123'),
        'first_name' => 'alfred',
        'last_name' => 'barua'
    ]);

    // 2. Create Borrower
    $borrower = Borrower::create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'barangay' => 'Brgy 1',
        'city_municipality' => 'Davao City',
        'province' => 'Davao del Sur',
        'date_registered' => now(),
        'admin_id' => $admin->admin_id
    ]);

    // 3. Create Loan
    $loan = Loan::create([
        'borrower_id' => $borrower->borrower_id,
        'principal_amount' => 5000.00,
        'interest_rate' => 5.00,
        'release_date' => now(),
        'due_date' => now()->addMonth(),
        'status' => 'Active',
        'admin_id' => $admin->admin_id
    ]);
}
}

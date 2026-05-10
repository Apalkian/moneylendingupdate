<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Borrower; // THIS WAS MISSING - MUST BE ADDED
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Show the form for creating a new loan.
     */
    public function create()
    {
        // Fetch all borrowers to populate the dropdown in the form
        $borrowers = Borrower::orderBy('last_name', 'asc')->get();
        return view('create_loan', compact('borrowers'));
    }

    /**
     * Store a newly created loan in the database.
     */
    public function store(Request $request)
    {
        // 1. Validation Logic
        $request->validate([
            'borrower_id'      => 'required|exists:borrower_table,borrower_id',
            'principal_amount' => 'required|numeric|min:0.01',
            'interest_rate'    => 'required|numeric|min:0',
            'release_date'     => 'required|date',
            // Ensures the due date is logically after the release date
            'due_date'         => 'required|date|after:release_date',
        ]);

        // 2. Create the Loan record
        Loan::create([
            'borrower_id'      => $request->borrower_id,
            'principal_amount' => $request->principal_amount,
            'interest_rate'    => $request->interest_rate,
            'release_date'     => $request->release_date,
            'due_date'         => $request->due_date,
            'status'           => 'Active', // Initial status from your Image #4
            'admin_id'         => session('admin_id') ?? 1, // Uses logged-in admin or default to 1
        ]);

        // 3. Redirect to dashboard with success message
        return redirect('/')->with('success', 'Loan has been released successfully!');
    }
}
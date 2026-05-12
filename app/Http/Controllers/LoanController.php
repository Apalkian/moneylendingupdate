<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Borrower;
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
        
        $request->validate([
            'borrower_id'      => 'required|exists:borrower_table,borrower_id',
            'principal_amount' => 'required|numeric|min:0.01',
            'interest_rate'    => 'required|in:10,12,15',
            'release_date'     => 'required|date',
        ]);

        $releaseDate = \Carbon\Carbon::parse($request->release_date);
        $dueDate = $releaseDate->copy()->addMonth(); // term is Monthly

        // Create the Loan record
        Loan::create([
            'borrower_id'      => $request->borrower_id,
            'principal_amount' => $request->principal_amount,
            'interest_rate'    => $request->interest_rate,
            'release_date'     => $request->release_date,
            'due_date'         => $dueDate->format('Y-m-d'),
            'status'           => 'Active', 
            'admin_id'         => session('admin_id') ?? 1, // Uses logged-in admin or default to 1
        ]);

      
        return redirect('/')->with('success', 'Loan has been released successfully!');
    }
}

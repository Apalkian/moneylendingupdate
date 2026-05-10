<?php

namespace App\Http\Controllers;

use App\Models\AdditionalCapital;
use App\Models\Loan;
use Illuminate\Http\Request;

class AdditionalCapitalController extends Controller
{
    public function create() 
    {
        // Added .with('borrower') so the names show up quickly in your dropdown
        $loans = Loan::with('borrower')->where('status', 'Active')->get();
        return view('create_capital', compact('loans'));
    }

    public function store(Request $request) 
    {
        // 1. Validation: This prevents empty or wrong data from breaking your database
        $request->validate([
            'loan_id' => 'required|exists:loan_table,loan_id',
            'amount_added' => 'required|numeric|min:0.01',
            'date_added' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        // 2. The Logic: This satisfies your "Check_Loan_Status_Before_Capital" requirement
        if ($loan->status == 'Completed') {
            return redirect()->back()
                ->withInput() // Keeps the data typed in the boxes
                ->withErrors(['loan_id' => 'Error: You cannot add capital/penalties to a loan that is already Completed.']);
        }

        // 3. Create the record
        AdditionalCapital::create([
            'loan_id' => $request->loan_id,
            'amount_added' => $request->amount_added, // Make sure this matches your input name
            'date_added' => $request->date_added,
            'remarks' => $request->remarks,
        ]);

        return redirect('/')->with('success', 'Additional capital/penalty has been added successfully.');
    }
}
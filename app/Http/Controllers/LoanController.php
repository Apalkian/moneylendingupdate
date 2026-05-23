<?php

namespace App\Http\Controllers;

use App\Models\AdditionalCapital;
use App\Models\Borrower;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrower_id' => ['required', 'integer', 'exists:borrowers,id'],
            'principal_amount' => ['required', 'numeric', 'min:0.01'],
            'interest_rate' => ['required', 'numeric', 'min:0'],
            'release_date' => ['required', 'date'],
            'interest_type' => ['nullable', 'in:daily,weekly,monthly'],
        ]);

        $principal = (float) $validated['principal_amount'];
        $rate = (float) $validated['interest_rate'];

        $releaseDate = Carbon::parse($validated['release_date']);
        $dueDate = $releaseDate->copy()->addMonth();

        Loan::create([
            'borrower_id' => (int) $validated['borrower_id'],
            'principal_amount' => $principal,
            'interest_rate' => $rate,
            'interest_type' => $validated['interest_type'] ?? 'monthly',
            'start_date' => $releaseDate->format('Y-m-d'),
            'end_date' => $dueDate->format('Y-m-d'),
            'status' => 'active',
        ]);

        return redirect()->route('dashboard')->with('success', 'Loan released successfully.');
    }

    // Spec-style entrypoint: add additional capital (trigger syncs outstanding).
    public function addCapital(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => ['required', 'integer', 'exists:loans,id'],
            'amount_added' => ['required', 'numeric', 'min:0.01'],
            'date_added' => ['required', 'date'],
            'remarks' => ['nullable', 'string'],
        ]);

        AdditionalCapital::create([
            'loan_id' => (int) $validated['loan_id'],
            'amount_added' => (float) $validated['amount_added'],
            'date_added' => Carbon::parse($validated['date_added'])->format('Y-m-d'),
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Additional capital recorded successfully.');
    }

    // Keep existing UI helper if you still use it.
    public function create()
    {
        $borrowers = Borrower::orderBy('id', 'asc')->get();

        return view('create_loan', compact('borrowers'));
    }
}

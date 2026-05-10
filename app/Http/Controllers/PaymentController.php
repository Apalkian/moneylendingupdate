<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Loan;
use App\Models\AdditionalCapital;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show the form to record a new payment.
     */
    public function create()
    {
        // Only show loans that are still 'Active'
        $loans = Loan::with('borrower')->where('status', 'Active')->get();
        return view('create_payment', compact('loans'));
    }

    /**
     * Store the payment and run the 'Trigger' logic.
     */
    public function store(Request $request) 
    {
        // 1. Validation: Prevent empty or invalid data
        $request->validate([
            'loan_id'      => 'required|exists:loan_table,loan_id',
            'amount_paid'  => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        // 2. Logic: Calculate Interest Added (Trigger: Calculate_Interest_Before_Insert)
        // Example: calculating 5% of the payment as interest toward the profit
        $interestAmount = $request->amount_paid * ($loan->interest_rate / 100);

        // 3. Save the Payment Record
        Payment::create([
            'loan_id'        => $request->loan_id,
            'payment_date'   => $request->payment_date,
            'amount_paid'    => $request->amount_paid,
            'interest_added' => $interestAmount,
            'admin_id'       => session('admin_id') ?? 1 // Use logged-in admin or default to 1
        ]);

        // 4. Logic: Auto-Complete Check (Trigger: Generate_Completed_Status_After_Payment)
        
        // Sum of all payments made so far
        $totalPaid = Payment::where('loan_id', $loan->loan_id)->sum('amount_paid');
        
        // Sum of any additional capital/penalties added (from Additional_table)
        $totalAdditional = AdditionalCapital::where('loan_id', $loan->loan_id)->sum('amount_added');

        // Total amount the borrower actually owes
        $totalDue = $loan->principal_amount + $totalAdditional;

        if ($totalPaid >= $totalDue) {
            $loan->update(['status' => 'Completed']);
        }

        return redirect('/')->with('success', 'Payment recorded successfully! Loan status updated.');
    }
}
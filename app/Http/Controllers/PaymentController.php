<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Loan;
use App\Models\AdditionalCapital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Show the form to record a new payment.
     */
    public function create()
    {
        // Use the database view to decide what still has an outstanding balance.
        // This keeps Post Payment dropdown consistent with Dashboard "Outstanding Balances".
        $loanIds = \DB::table('vw_Outstanding_Balances')
            ->where('Current_Balance', '>', 0)
            ->pluck('loan_id')
            ->all();

        $loans = Loan::with('borrower')
            ->whereIn('loan_id', $loanIds)
            ->get();

        return view('create_payment', compact('loans'));
    }

    /**
     * Store the payment and run the 'Trigger' logic.
     */
    public function store(Request $request) 
    {
        // Validation: Prevent empty or invalid data
        $request->validate([
            'loan_id'      => 'required|exists:loan_table,loan_id',
            'amount_paid'  => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        // Calculate Interest Added Trigger: Calculate_Interest_Before_Insert
        
        $interestAmount = $request->amount_paid * ($loan->interest_rate / 100);

        // Save the Payment Record
        Payment::create([
            'loan_id'        => $request->loan_id,
            'payment_date'   => $request->payment_date,
            'amount_paid'    => $request->amount_paid,
            'interest_added' => $interestAmount,
            'admin_id'       => session('admin_id') ?? 1 // Use logged-in admin or default to 1
        ]);

        //  Auto-Complete Check Trigger will Generate_Completed_Status_After_Payment
        
        // Sum of all payments made so in this line
        $totalPaid = (float) Payment::where('loan_id', $loan->loan_id)->sum('amount_paid');

        $principal = (float) $loan->principal_amount;
        $rate = (float) $loan->interest_rate;

        // Total amount the borrower actually owes (principal + interest only)
        $interestDue = $principal * ($rate / 100);
        $totalDue = $principal + $interestDue;

        // Epsilon to prevent float/string rounding surprises
        $epsilon = 0.0001;

        Log::info('PaymentController completion check', [
            'loan_id' => $loan->loan_id,
            'principal_amount' => $loan->principal_amount,
            'interest_rate' => $loan->interest_rate,
            'interest_due' => $interestDue,
            'totalDue' => $totalDue,
            'totalPaid' => $totalPaid,
        ]);

        $newStatus = ($totalPaid + $epsilon) >= $totalDue ? 'Completed' : 'Active';

        Log::info('PaymentController status update', [
            'loan_id' => $loan->loan_id,
            'totalPaid' => $totalPaid,
            'totalDue' => $totalDue,
            'newStatus' => $newStatus,
        ]);

        if ($loan->status !== $newStatus) {
            $loan->update(['status' => $newStatus]);
        }

        return redirect('/')->with('success', 'Payment recorded successfully! Loan status updated.');
    }
}

<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Loan;
use App\Models\AdditionalCapital; 

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        $this->updateLoanStatus($payment->loan_id);
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        $this->updateLoanStatus($payment->loan_id);
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        $this->updateLoanStatus($payment->loan_id);
    }

    /**
     * Private helper function to handle the status logic
     */
    private function updateLoanStatus($loanId)
    {
        $loan = Loan::find($loanId);
        if (!$loan) return;

        // 1. Calculate total paid
        $totalPaid = Payment::where('loan_id', $loanId)->sum('amount_paid');

        // 2. Calculate total additional capital
        $totalCapital = AdditionalCapital::where('loan_id', $loanId)->sum('amount_added');

        // 3. Compare Total Due  Total Paid
        $totalDue = $loan->principal_amount + $totalCapital;

        if ($totalPaid >= $totalDue) {
            $loan->update(['status' => 'Completed']);
        } else {
            // If they deleted a payment and it's no longer fully paid set back to Active
            $loan->update(['status' => 'Active']);
        }
    }
}
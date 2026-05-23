<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => ['required', 'integer', 'exists:loans,id'],
            'payment_date' => ['required', 'date'],
            'amount_paid' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ]);

        $loan = Loan::findOrFail((int) $validated['loan_id']);

        Payment::create([
            'loan_id' => (int) $loan->id,
            'payment_date' => $validated['payment_date'],
            'amount_paid' => (float) $validated['amount_paid'],
            'transaction_reference' => 'TRX-'.now()->format('YmdHis').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT),
            'notes' => $validated['notes'] ?? null,
        ]);

        $principal = (float) $loan->principal_amount;
        $interest = $principal * ((float) $loan->interest_rate / 100);
        $baseDue = $principal + $interest;
        $additional = (float) $loan->additionalCapitals()->sum('amount_added');
        $paid = (float) $loan->payments()->sum('amount_paid');
        $remaining = max(0, ($baseDue + $additional) - $paid);

        if ($remaining <= 0.00001) {
            $loan->status = 'completed';
        } else {
            $loan->status = Carbon::parse($loan->end_date)->isPast() ? 'overdue' : 'active';
        }

        $loan->save();

        return redirect()->route('dashboard')->with('success', 'Payment recorded successfully.');
    }
}

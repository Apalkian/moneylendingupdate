<?php

namespace App\Http\Controllers;

use App\Models\AdditionalCapital;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdditionalCapitalController extends Controller
{
    public function create()
    {
        $loans = Loan::with(['borrower.user'])
            ->whereIn('status', ['active', 'overdue', 'pending'])
            ->orderByDesc('id')
            ->get();

        $paymentTotals = DB::table('payments')
            ->select('loan_id', DB::raw('COALESCE(SUM(amount_paid), 0) as total_paid'))
            ->groupBy('loan_id')
            ->pluck('total_paid', 'loan_id');

        $capitalTotals = DB::table('additional_table')
            ->select('loan_id', DB::raw('COALESCE(SUM(amount_added), 0) as total_added'))
            ->groupBy('loan_id')
            ->pluck('total_added', 'loan_id');

        $loans = $loans->map(function (Loan $loan) use ($paymentTotals, $capitalTotals) {
            $principal = (float) $loan->principal_amount;
            $interest = $principal * ((float) $loan->interest_rate / 100);
            $baseDue = $principal + $interest;
            $added = (float) ($capitalTotals[$loan->id] ?? 0);
            $paid = (float) ($paymentTotals[$loan->id] ?? 0);
            $loan->computed_outstanding = max(0, ($baseDue + $added) - $paid);

            return $loan;
        })->filter(fn (Loan $loan) => $loan->computed_outstanding > 0)->values();

        return view('create_capital', compact('loans'));
    }

    /**
     * Handle POST /additional-capital
     */
    public function store(Request $request)
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
}

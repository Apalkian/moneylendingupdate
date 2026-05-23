<?php

namespace App\Http\Controllers;

use App\Models\AdditionalCapital;
use App\Models\Borrower;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WebController extends Controller
{
    public function index()
    {
        $totalCapitalOut = (float) DB::table('loans')->sum('principal_amount');
        $totalInterest = (float) DB::table('loans')->selectRaw('COALESCE(SUM(principal_amount * (interest_rate / 100)), 0) as total_interest', [])
            ->value('total_interest');
        $activeLoans = (int) DB::table('loans')
            ->whereRaw("status IN ('active', 'overdue')")
            ->count('*');
        $overdueLoans = (int) DB::table('loans')->whereRaw("status = 'overdue'")->count('*');
        $totalPaid = (float) DB::table('payments')->sum('amount_paid');
        $activeBorrowers = (int) DB::table('borrowers')->count('*');

        return view('dashboard', compact(
            'totalCapitalOut',
            'totalInterest',
            'activeLoans',
            'overdueLoans',
            'totalPaid',
            'activeBorrowers'
        ));
    }

    public function borrowerPortal()
    {
        $user = Auth::user();

        $borrower = Borrower::query()
            ->with('user')
            ->where('user_id', $user?->id)
            ->first();

        $loans = Loan::with(['payments', 'additionalCapitals', 'borrower'])
            ->when($borrower, fn ($query) => $query->where('borrower_id', $borrower->id))
            ->orderByDesc('id')
            ->get();

        $loans = $loans->map(function (Loan $loan) {
            $principal = (float) $loan->principal_amount;
            $interest = $principal * ((float) $loan->interest_rate / 100);
            $baseDue = $principal + $interest;
            $totalPaid = (float) $loan->payments->sum('amount_paid');
            $totalAdded = (float) $loan->additionalCapitals->sum('amount_added');
            $computedOutstanding = max(0, ($baseDue + $totalAdded) - $totalPaid);

            $loan->computed_total_due = $baseDue + $totalAdded;
            $loan->computed_outstanding = $computedOutstanding;
            $loan->computed_paid = $totalPaid;
            $loan->computed_status = $computedOutstanding <= 0 ? 'paid' : ((string) $loan->status ?: 'active');

            $paymentEvents = $loan->payments->map(function ($payment) use ($loan) {
                return [
                    'event_type' => 'payment',
                    'loan_id' => $loan->id,
                    'event_date' => $payment->payment_date,
                    'amount' => (float) $payment->amount_paid,
                    'notes' => $payment->notes,
                ];
            });

            $capitalEvents = $loan->additionalCapitals->map(function ($capital) use ($loan) {
                return [
                    'event_type' => 'additional_payment',
                    'loan_id' => $loan->id,
                    'event_date' => $capital->date_added,
                    'amount' => (float) $capital->amount_added,
                    'notes' => $capital->remarks,
                ];
            });

            $loan->timeline_events = $paymentEvents
                ->concat($capitalEvents)
                ->sortByDesc('event_date')
                ->values();

            return $loan;
        });

        return view('borrower_portal', [
            'user' => $user,
            'borrower' => $borrower,
            'loans' => $loans,
        ]);
    }

    public function ledger()
    {
        $transactions = Payment::with(['loan.borrower.user'])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();

        $loans = Loan::with(['borrower.user'])
            ->whereIn('status', ['active', 'overdue', 'pending'])
            ->orderByDesc('id')
            ->get();

        $paymentTotals = Payment::query()
            ->select('loan_id', DB::raw('COALESCE(SUM(amount_paid), 0) as total_paid'))
            ->groupBy('loan_id')
            ->pluck('total_paid', 'loan_id');

        $capitalTotals = AdditionalCapital::query()
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

        return view('ledger', compact('transactions', 'loans'));
    }

    public function showBorrowers(Request $request)
    {
        $query = Borrower::query();

        if ($request->filled('search')) {
            $search = (string) $request->input('search');

            $query->where(function ($inner) use ($search) {
                $inner->where('last_name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%");
            });
        }

        $borrowers = $query->orderBy('last_name', 'asc')->get();

        return view('borrowers.index', compact('borrowers'));
    }

    public function createBorrower()
    {
        return view('borrowers.create_borrower');
    }

    public function createLoan()
    {
        $borrowers = Borrower::with('user')->orderBy('id', 'desc')->get();

        return view('create_loan', compact('borrowers'));
    }

    public function createPayment()
    {
        $loans = Loan::with(['borrower.user'])
            ->whereIn('status', ['active', 'overdue', 'pending'])
            ->orderByDesc('id')
            ->get();

        $paymentTotals = Payment::query()
            ->select('loan_id', DB::raw('COALESCE(SUM(amount_paid), 0) as total_paid'))
            ->groupBy('loan_id')
            ->pluck('total_paid', 'loan_id');

        $capitalTotals = AdditionalCapital::query()
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

        return view('create_payment', compact('loans'));
    }
}

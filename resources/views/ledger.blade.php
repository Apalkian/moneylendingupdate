@extends('layout')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-dark">
            <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <div class="text-uppercase small fw-bold text-muted">System</div>
                    <h2 class="mb-1">Digital Ledger</h2>
                    <p class="mb-0 text-muted">Track verified payments and active loan balances in one place.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('payments.create') }}" class="btn btn-success fw-bold">Record New Payment</a>
                    <a href="{{ route('additional-capital.create') }}" class="btn btn-warning fw-bold">Add Capital</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-dark shadow-sm h-100">
            <div class="card-body">
                <div class="text-uppercase small text-muted fw-bold">Transactions</div>
                <div class="fs-2 fw-bold">{{ $transactions->count() }}</div>
                <div class="text-muted small">Verified payment entries</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-dark shadow-sm h-100">
            <div class="card-body">
                <div class="text-uppercase small text-muted fw-bold">Active Loans</div>
                <div class="fs-2 fw-bold">{{ $loans->count() }}</div>
                <div class="text-muted small">Loans with remaining balance</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-dark shadow-sm h-100">
            <div class="card-body">
                <div class="text-uppercase small text-muted fw-bold">Outstanding Balance</div>
                <div class="fs-2 fw-bold text-danger">
                    PHP {{ number_format($loans->sum('computed_outstanding'), 2) }}
                </div>
                <div class="text-muted small">Current open receivables</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-dark mb-4">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Transaction Stream</h5>
        <span class="badge bg-light text-dark">{{ $transactions->count() }} records</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date-Time</th>
                        <th>Borrower</th>
                        <th>Amount Paid</th>
                        <th>Reference</th>
                        <th>Remaining Balance</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        @php
                            $loan = $transaction->loan;
                            $borrowerName = $loan?->borrower?->user?->name ?? '—';
                            $principal = (float) ($loan->principal_amount ?? 0);
                            $interestDue = $principal * ((float) ($loan->interest_rate ?? 0) / 100);
                            $baseDue = $principal + $interestDue;
                            $totalPaid = (float) ($loan?->payments()->sum('amount_paid') ?? 0);
                            $totalAdditional = (float) ($loan?->additionalCapitals()->sum('amount_added') ?? 0);
                            $remaining = max(0, ($baseDue + $totalAdditional) - $totalPaid);
                        @endphp
                        <tr>
                            <td>{{ $transaction->payment_date }}</td>
                            <td>{{ $borrowerName }}</td>
                            <td class="fw-bold">PHP {{ number_format($transaction->amount_paid, 2) }}</td>
                            <td>{{ $transaction->transaction_reference ?? '—' }}</td>
                            <td>PHP {{ number_format($remaining, 2) }}</td>
                            <td>{{ $transaction->notes ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No transactions recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm border-dark">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Active Loans</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Loan ID</th>
                        <th>Borrower</th>
                        <th>Principal</th>
                        <th>Interest Rate</th>
                        <th>Due Date</th>
                        <th>Outstanding Balance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        <tr>
                            <td>#{{ $loan->id }}</td>
                            <td>{{ $loan->borrower->user->name ?? ('Borrower #'.$loan->borrower_id) }}</td>
                            <td>PHP {{ number_format($loan->principal_amount, 2) }}</td>
                            <td>{{ number_format($loan->interest_rate, 2) }}%</td>
                            <td>{{ $loan->end_date }}</td>
                            <td class="fw-bold text-danger">PHP {{ number_format($loan->computed_outstanding ?? 0, 2) }}</td>
                            <td>
                                <a href="{{ route('payments.create') }}" class="btn btn-sm btn-success">Record Payment</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No active loans available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

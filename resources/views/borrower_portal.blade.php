@extends('layout')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h2 class="mb-1">Borrower Portal</h2>
                    <p class="mb-0 text-muted">
                        View your active loan status, balances, and transaction history.
                    </p>
                </div>
                <div class="text-md-end">
                    <div class="fw-bold">{{ $user?->name ?? 'Borrower' }}</div>
                    <div class="text-muted small">{{ $user?->email ?? '' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (! $borrower)
    <div class="alert alert-warning shadow-sm">
        No borrower profile is linked to this account yet.
    </div>
@else
    <div class="row g-4 mb-4">
        @php
            $activeLoans = $loans->filter(fn ($loan) => ($loan->computed_outstanding ?? 0) > 0);
            $completedLoans = $loans->filter(fn ($loan) => ($loan->computed_outstanding ?? 0) <= 0);
            $totalOutstanding = $loans->sum(fn ($loan) => (float) ($loan->computed_outstanding ?? 0));
            $totalPaid = $loans->sum(fn ($loan) => (float) ($loan->computed_paid ?? 0));
        @endphp

        <div class="col-md-3">
            <div class="card border-dark shadow-sm h-100">
                <div class="card-body">
                    <div class="text-uppercase text-muted small fw-bold">Borrower</div>
                    <div class="fs-4 fw-bold">{{ $borrower->user?->name ?? 'Borrower' }}</div>
                    <div class="text-muted small">{{ $borrower->phone_number ?? 'No contact number' }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-dark shadow-sm h-100">
                <div class="card-body">
                    <div class="text-uppercase text-muted small fw-bold">Active Loans</div>
                    <div class="fs-2 fw-bold">{{ $activeLoans->count() }}</div>
                    <div class="text-muted small">Currently open loan accounts</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-dark shadow-sm h-100">
                <div class="card-body">
                    <div class="text-uppercase text-muted small fw-bold">Outstanding Balance</div>
                    <div class="fs-2 fw-bold text-danger">PHP {{ number_format($totalOutstanding, 2) }}</div>
                    <div class="text-muted small">Remaining amount due</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-dark shadow-sm h-100">
                <div class="card-body">
                    <div class="text-uppercase text-muted small fw-bold">Paid So Far</div>
                    <div class="fs-2 fw-bold text-success">PHP {{ number_format($totalPaid, 2) }}</div>
                    <div class="text-muted small">Verified payments recorded</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Active Loan Status</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Loan ID</th>
                            <th>Principal</th>
                            <th>Interest Rate</th>
                            <th>Due Date</th>
                            <th>Total Due</th>
                            <th>Outstanding Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activeLoans as $loan)
                            <tr>
                                <td>#{{ $loan->id }}</td>
                                <td>PHP {{ number_format($loan->principal_amount, 2) }}</td>
                                <td>{{ number_format($loan->interest_rate, 2) }}%</td>
                                <td>{{ $loan->end_date }}</td>
                                <td>PHP {{ number_format($loan->computed_total_due ?? 0, 2) }}</td>
                                <td class="fw-bold text-danger">PHP {{ number_format($loan->computed_outstanding ?? 0, 2) }}</td>
                                <td>
                                    @if (($loan->computed_outstanding ?? 0) <= 0)
                                        <span class="badge bg-success">Paid</span>
                                    @elseif (($loan->status ?? '') === 'overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Active</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No active loans found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">My Transaction History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="max-height: 420px;">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Loan ID</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans->flatMap(fn ($loan) => $loan->timeline_events->map(fn ($event) => [
                            'loan_id' => $loan->id,
                            'event_date' => $event['event_date'] ?? null,
                            'event_type' => $event['event_type'] ?? 'payment',
                            'amount' => $event['amount'] ?? 0,
                            'notes' => $event['notes'] ?? null,
                        ]))->sortByDesc('event_date') as $entry)
                            <tr>
                                <td>#{{ $entry['loan_id'] }}</td>
                                <td>{{ $entry['event_date'] }}</td>
                                <td>
                                    @if (($entry['event_type'] ?? '') === 'additional_payment')
                                        <span class="badge bg-info text-dark">Additional Payment</span>
                                    @else
                                        <span class="badge bg-primary">Payment</span>
                                    @endif
                                </td>
                                <td>PHP {{ number_format((float) ($entry['amount'] ?? 0), 2) }}</td>
                                <td>{{ $entry['notes'] ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No payment history available yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($completedLoans->isNotEmpty())
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Completed Loans</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Loan ID</th>
                                <th>Due Date</th>
                                <th>Principal</th>
                                <th>Total Paid</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($completedLoans as $loan)
                                <tr>
                                    <td>#{{ $loan->id }}</td>
                                    <td>{{ $loan->end_date }}</td>
                                    <td>PHP {{ number_format($loan->principal_amount, 2) }}</td>
                                    <td>PHP {{ number_format($loan->computed_paid ?? 0, 2) }}</td>
                                    <td><span class="badge bg-success">Paid</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endif
@endsection

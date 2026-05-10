@extends('layout')

@section('content')
    <h2 class="mb-4">Dashboard & Reports</h2>

    <!-- 1. Statistics Summary Cards (Moved to the top) -->
    <div class="row mb-4 text-center">
        <div class="col-md-4">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h5>Total Principal</h5>
                    <h3>PHP {{ number_format(\App\Models\Loan::sum('principal_amount'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5>Total Collected</h5>
                    <h3>PHP {{ number_format(\App\Models\Payment::sum('amount_paid'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h5>Active Loans</h5>
                    <h3>{{ \App\Models\Loan::where('status', 'Active')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Loan Report (Matches your Image #7) -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Loan Report (vw_Loan_Report)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Loan ID</th>
                            <th>Borrower Name</th>
                            <th>Principal</th>
                            <th>Rate %</th>
                            <th>Status</th>
                            <th>Release Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loanReport as $row)
                        <tr>
                            <td>{{ $row->loan_id }}</td>
                            <td>{{ $row->Borrower_Name }}</td>
                            <td>{{ number_format($row->Principal, 2) }}</td>
                            <td>{{ $row->Rate_Percent }}%</td>
                            <td>
                                <span class="badge {{ $row->Loan_Status == 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $row->Loan_Status }}
                                </span>
                            </td>
                            <td>{{ $row->release_date }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. Outstanding Balances (Matches your Image #8 Bottom) -->
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Outstanding Balances</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Borrower</th>
                            <th>Current Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outstanding as $row)
                        <tr>
                            <td>{{ $row->Borrower }}</td>
                            <td class="fw-bold text-danger">PHP {{ number_format($row->Current_Balance, 2) }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $row->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
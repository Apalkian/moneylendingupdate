@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Post a Payment</h5>
                <a href="/" class="btn btn-sm btn-outline-light">Back to Dashboard</a>
            </div>
            <div class="card-body">
                <form action="/payments" method="POST">
                    @csrf
                    
                    <!-- 1. Select Loan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Active Loan</label>
                        <select name="loan_id" class="form-select" required>
                            <option value="" selected disabled>-- Choose a Loan --</option>
                            @foreach($loans as $loan)
                                <option value="{{ $loan->loan_id }}">
                                    ID: {{ $loan->loan_id }} | 
                                    Borrower: {{ $loan->borrower->last_name }}, {{ $loan->borrower->first_name }} | 
                                    Original Principal: PHP {{ number_format($loan->principal_amount, 2) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted">Only loans with "Active" status are shown here.</div>
                    </div>

                    <div class="row">
                        <!-- 2. Amount to Pay -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Amount to Pay</label>
                            <div class="input-group">
                                <span class="input-group-text">PHP</span>
                                <input type="number" step="0.01" name="amount_paid" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>

                        <!-- 3. Payment Date -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <hr>

                    <!-- Submit Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="/" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5">Submit Payment</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="alert alert-info mt-3 shadow-sm">
            <strong>Note:</strong> The system will automatically calculate the interest added and update the Loan Status to <strong>'Completed'</strong> if the full balance is paid.
        </div>
    </div>
</div>
@endsection
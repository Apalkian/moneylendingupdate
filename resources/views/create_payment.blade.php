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
                                @php $remainingTotal = (float) ($loan->computed_outstanding ?? 0); @endphp
                                <option
                                    value="{{ $loan->id }}"
                                    data-due-date="{{ $loan->end_date }}"
                                    data-total-to-pay="{{ number_format($remainingTotal, 2, '.', '') }}">
                                    ID: {{ $loan->id }} |
                                    Borrower: {{ $loan->borrower->user->name ?? ('Borrower #'.$loan->borrower_id) }} |
                                    Due: {{ $loan->end_date }} |
                                    Total to Pay: PHP {{ number_format($remainingTotal, 2) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted"></div>

                        <div class="mt-3">
                            <label class="form-label fw-bold">Due Date (Monthly)</label>
                            <div class="border rounded p-2 bg-light">
                                <div id="due_date_text" class="fw-bold">Due Date: —</div>
                                <div id="total_to_pay_text" class="fw-bold">Total to Pay: —</div>
                            </div>
                        </div>
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

                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Optional note for this payment...">{{ old('notes') }}</textarea>
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



<script>
(function () {
    const select = document.querySelector('select[name="loan_id"]');
    const dueText = document.getElementById('due_date_text');
    const totalText = document.getElementById('total_to_pay_text');

    if (!select || !dueText || !totalText) return;

    function sync() {
        const opt = select.options[select.selectedIndex];
        const due = opt?.dataset?.dueDate;
        const total = opt?.dataset?.totalToPay;

        dueText.textContent = due ? ('Due Date: ' + due) : 'Due Date: —';
        totalText.textContent = total ? ('Total to Pay: PHP ' + Number(total).toFixed(2)) : 'Total to Pay: —';
    }

    select.addEventListener('change', sync);
    sync();
})();
</script>
@endsection

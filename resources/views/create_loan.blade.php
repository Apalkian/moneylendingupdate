@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create New Loan</h5>
                <a href="/" class="btn btn-sm btn-outline-light">Back to Dashboard</a>
            </div>
            <div class="card-body">
                <form action="/loans" method="POST">
                    @csrf

                    <!-- 1. Select Borrower -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Select Borrower</label>
                        <select name="borrower_id" class="form-select @error('borrower_id') is-invalid @enderror" required>
                            <option value="">-- Choose Borrower --</option>
                            @foreach($borrowers as $b)
                                <option value="{{ $b->borrower_id }}" {{ old('borrower_id') == $b->borrower_id ? 'selected' : '' }}>
                                    {{ $b->last_name }}, {{ $b->first_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('borrower_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <!-- 2. Principal Amount -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Principal Amount</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text">PHP</span>
                                <input
                                    id="principal_amount"
                                    type="number"
                                    step="0.01"
                                    name="principal_amount"
                                    class="form-control @error('principal_amount') is-invalid @enderror"
                                    value="{{ old('principal_amount') }}"
                                    placeholder="0.00"
                                    required>
                                @error('principal_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- 3. Interest Rate -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Interest Rate (%)</label>
                            <select
                                id="interest_rate"
                                name="interest_rate"
                                class="form-select @error('interest_rate') is-invalid @enderror"
                                required>
                                <option value="">-- Choose Interest --</option>
                                <option value="10" {{ old('interest_rate') == '10' ? 'selected' : '' }}>10%</option>
                                <option value="12" {{ old('interest_rate') == '12' ? 'selected' : '' }}>12%</option>
                                <option value="15" {{ old('interest_rate') == '15' ? 'selected' : '' }}>15%</option>
                            </select>
                            @error('interest_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Total (Principal + Interest) -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Total Payable Amount</label>
                            <input
                                id="total_principal_interest"
                                type="text"
                                class="form-control bg-light"
                                value="0.00"
                                readonly>
                        </div>
                    </div>

                    <!-- Term (fixed to Monthly) -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Term of loan</label>
                            <input type="text" class="form-control bg-light" value="Monthly" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <!-- 4. Release Date -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Release Date</label>
                            <input type="date" name="release_date" id="release_date"
                                   class="form-control @error('release_date') is-invalid @enderror"
                                   value="{{ old('release_date', date('Y-m-d')) }}" required>
                            @error('release_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- 5. Due Date (Automatically calculated and shown) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Due Date</label>
                            <input 
                                type="date" 
                                name="due_date" 
                                id="due_date" 
                                class="form-control bg-light" 
                                readonly 
                                required>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="/" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5">Release Loan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const principal = document.getElementById('principal_amount');
    const interest = document.getElementById('interest_rate');
    const releaseDate = document.getElementById('release_date');
    const dueDate = document.getElementById('due_date');
    const totalOut = document.getElementById('total_principal_interest');

    function calc() {
        if (!principal || !interest || !totalOut) return;

        // --- Calculate Total Money ---
        const p = parseFloat(principal.value || '0');
        const r = parseFloat(interest.value || '0');

        if (!p || !r) {
            totalOut.value = '0.00';
        } else {
            const total = p + (p * r / 100);
            totalOut.value = total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        // --- Calculate Due Date (+1 Month) ---
        if (releaseDate.value) {
            let date = new Date(releaseDate.value);
            // Add exactly one month
            date.setMonth(date.getMonth() + 1);
            
            // Format to YYYY-MM-DD so the date input can read it
            let y = date.getFullYear();
            let m = ("0" + (date.getMonth() + 1)).slice(-2);
            let d = ("0" + date.getDate()).slice(-2);
            
            dueDate.value = `${y}-${m}-${d}`;
        }
    }

    // Listen for changes on all relevant fields
    if (principal) principal.addEventListener('input', calc);
    if (interest) interest.addEventListener('change', calc);
    if (releaseDate) releaseDate.addEventListener('change', calc);

    // Run once on page load to set initial Due Date
    calc();
})();
</script>
@endsection
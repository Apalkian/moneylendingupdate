@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-dark">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">Add Additional Capital / Remarks</h5>
                    <div class="small">Record loan adjustments and penalties.</div>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-dark">Back</a>
            </div>

            <div class="card-body">
                <form action="{{ route('additional-capital.store') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label for="loan_id" class="form-label fw-bold">Select Active Loan</label>
                        <select name="loan_id" id="loan_id" class="form-select @error('loan_id') is-invalid @enderror" required>
                            <option value="" selected disabled>-- Choose Loan --</option>
                            @foreach ($loans as $loan)
                                <option value="{{ $loan->id }}" {{ old('loan_id') == $loan->id ? 'selected' : '' }}>
                                    #{{ $loan->id }} - {{ $loan->borrower->user->name ?? ('Borrower #'.$loan->borrower_id) }} - PHP {{ number_format($loan->computed_outstanding ?? 0, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('loan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="amount_added" class="form-label fw-bold">Amount to Add</label>
                        <div class="input-group">
                            <span class="input-group-text">PHP</span>
                            <input
                                type="number"
                                step="0.01"
                                name="amount_added"
                                id="amount_added"
                                class="form-control @error('amount_added') is-invalid @enderror"
                                value="{{ old('amount_added') }}"
                                placeholder="0.00"
                                required
                            >
                            @error('amount_added')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="date_added" class="form-label fw-bold">Date Added</label>
                        <input
                            type="date"
                            name="date_added"
                            id="date_added"
                            class="form-control @error('date_added') is-invalid @enderror"
                            value="{{ old('date_added', date('Y-m-d')) }}"
                            required
                        >
                        @error('date_added')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="remarks" class="form-label fw-bold">Remarks / Reason</label>
                        <textarea
                            name="remarks"
                            id="remarks"
                            rows="4"
                            class="form-control @error('remarks') is-invalid @enderror"
                            placeholder="Explain why this capital or penalty is being added..."
                        >{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-warning fw-bold">Save Record</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <!-- Header matches the "Warning" style for additional capital/penalties -->
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Add Additional Capital / Remarks</h5>
                <a href="/" class="btn btn-sm btn-outline-dark">Back</a>
            </div>
            
            <div class="card-body">
                <form action="/additional-capital" method="POST">
                    @csrf
                    
                    <!-- 1. Select Loan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Active Loan</label>
                        <select name="loan_id" class="form-select @error('loan_id') is-invalid @enderror" required>
                            <option value="" selected disabled>-- Choose Loan --</option>
                            @foreach($loans as $loan)
                                <option value="{{ $loan->loan_id }}" {{ old('loan_id') == $loan->loan_id ? 'selected' : '' }}>
                                    ID: {{ $loan->loan_id }} - {{ $loan->borrower->last_name }}, {{ $loan->borrower->first_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('loan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <!-- 2. Amount to Add -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Amount to Add</label>
                            <div class="input-group">
                                <span class="input-group-text">PHP</span>
                                <input type="number" step="0.01" name="amount_added" 
                                       class="form-control @error('amount_added') is-invalid @enderror" 
                                       value="{{ old('amount_added') }}" placeholder="0.00" required>
                                @error('amount_added') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- 3. Date Added -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date Added</label>
                            <input type="date" name="date_added" 
                                   class="form-control @error('date_added') is-invalid @enderror" 
                                   value="{{ old('date_added', date('Y-m-d')) }}" required>
                            @error('date_added') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- 4. Remarks (Textarea) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Remarks / Reason</label>
                        <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" 
                                  rows="3" placeholder="Explain why this capital/penalty is being added...">{{ old('remarks') }}</textarea>
                        @error('remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="/" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-warning px-5 fw-bold">Save Record</button>
                    </div>
                </form>
            </div>
        </div>

     
@endsection
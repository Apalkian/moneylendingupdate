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
                                <input type="number" step="0.01" name="principal_amount" 
                                       class="form-control @error('principal_amount') is-invalid @enderror" 
                                       value="{{ old('principal_amount') }}" placeholder="0.00" required>
                                @error('principal_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- 3. Interest Rate -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Interest Rate (%)</label>
                            <input type="number" step="0.01" name="interest_rate" 
                                   class="form-control @error('interest_rate') is-invalid @enderror" 
                                   value="{{ old('interest_rate') }}" placeholder="e.g. 5" required>
                            @error('interest_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- 4. Release Date -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Release Date</label>
                            <input type="date" name="release_date" 
                                   class="form-control @error('release_date') is-invalid @enderror" 
                                   value="{{ old('release_date', date('Y-m-d')) }}" required>
                            @error('release_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- 5. Due Date -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Due Date</label>
                            <input type="date" name="due_date" 
                                   class="form-control @error('due_date') is-invalid @enderror" 
                                   value="{{ old('due_date') }}" required>
                            @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
@endsection
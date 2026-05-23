@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Register New Borrower</h5>
                <a href="/" class="btn btn-sm btn-outline-light">Back to Dashboard</a>
            </div>
            <div class="card-body">
                <form action="/borrowers" method="POST">
                    @csrf

                    <!-- 1. Personal Information -->
                    <h6 class="text-success fw-bold mb-3">Borrower Account</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" placeholder="09xxxxxxxxx">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Credit Status</label>
                            <select name="credit_status" class="form-select @error('credit_status') is-invalid @enderror" required>
                                <option value="good" {{ old('credit_status') === 'good' ? 'selected' : '' }}>Good</option>
                                <option value="delinquent" {{ old('credit_status') === 'delinquent' ? 'selected' : '' }}>Delinquent</option>
                            </select>
                            @error('credit_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr>

                    <h6 class="text-success fw-bold mb-3">Address</h6>
                    <div class="mb-3">
                        <label class="form-label">Complete Address</label>
                        <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror" placeholder="House/Street/Barangay/City/Province">{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-4 d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="/" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-success px-5">Save Borrower</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

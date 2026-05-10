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
                    <h6 class="text-success fw-bold mb-3">Personal Information</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}" placeholder="09xxxxxxxxx">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date Registered</label>
                            <input type="date" name="date_registered" class="form-control" value="{{ old('date_registered', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <hr>

                    <!-- 2. Address Details (Matches your Image #10) -->
                    <h6 class="text-success fw-bold mb-3">Address Details</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">House No. / Bldg</label>
                            <input type="text" name="house_no_bldg" class="form-control" value="{{ old('house_no_bldg') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Street</label>
                            <input type="text" name="street" class="form-control" value="{{ old('street') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Barangay</label>
                            <input type="text" name="barangay" class="form-control @error('barangay') is-invalid @enderror" value="{{ old('barangay') }}" required>
                            @error('barangay') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">City/Municipality</label>
                            <input type="text" name="city_municipality" class="form-control @error('city_municipality') is-invalid @enderror" value="{{ old('city_municipality') }}" required>
                            @error('city_municipality') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Province</label>
                            <input type="text" name="province" class="form-control @error('province') is-invalid @enderror" value="{{ old('province') }}" required>
                            @error('province') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Zip Code</label>
                            <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code') }}">
                        </div>
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
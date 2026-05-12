@extends('layout')

@section('content')
<div class="card shadow">
    <div class="card-header bg-warning">Edit Borrower: {{ $borrower->first_name }}</div>
    <div class="card-body">
        <form action="/borrowers/{{ $borrower->borrower_id }}" method="POST">
            @csrf
            @method('PUT') 
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" value="{{ $borrower->first_name }}" required>
                </div>
                <div class="col-md-4">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" value="{{ $borrower->middle_name }}">
                </div>
                <div class="col-md-4">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="{{ $borrower->last_name }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Contact</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ $borrower->contact_number }}">
                </div>
                <div class="col-md-6">
                    <label>Date Registered</label>
                    <input type="date" name="date_registered" class="form-control" value="{{ $borrower->date_registered ?? '' }}" required>
                </div>
            </div>

            <h5 class="mt-4">Update Address</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>House No. / Bldg</label>
                    <input type="text" name="house_no_bldg" class="form-control" value="{{ $borrower->house_no_bldg }}">
                </div>
                <div class="col-md-4">
                    <label>Street</label>
                    <input type="text" name="street" class="form-control" value="{{ $borrower->street }}">
                </div>
                <div class="col-md-4">
                    <label>Zip Code</label>
                    <input type="text" name="zip_code" class="form-control" value="{{ $borrower->zip_code }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Barangay</label>
                    <input type="text" name="barangay" class="form-control" value="{{ $borrower->barangay }}" required>
                </div>
                <div class="col-md-4">
                    <label>City</label>
                    <input type="text" name="city_municipality" class="form-control" value="{{ $borrower->city_municipality }}" required>
                </div>
                <div class="col-md-4">
                    <label>Province</label>
                    <input type="text" name="province" class="form-control" value="{{ $borrower->province }}" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Borrower Details</button>
            <a href="/borrowers" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

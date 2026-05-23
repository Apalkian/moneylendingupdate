@extends('layout')

@section('content')
<div class="card shadow">
    <div class="card-header bg-warning">Edit Borrower: {{ $borrower->user->name ?? 'Borrower' }}</div>
    <div class="card-body">
        <form action="/borrowers/{{ $borrower->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $borrower->user->name ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $borrower->user->email ?? '') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Contact</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $borrower->phone_number) }}">
                </div>
                <div class="col-md-6">
                    <label>Credit Status</label>
                    <select name="credit_status" class="form-select" required>
                        <option value="good" {{ old('credit_status', $borrower->credit_status) === 'good' ? 'selected' : '' }}>Good</option>
                        <option value="delinquent" {{ old('credit_status', $borrower->credit_status) === 'delinquent' ? 'selected' : '' }}>Delinquent</option>
                    </select>
                </div>
            </div>

            <h5 class="mt-4">Update Address</h5>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" rows="3" class="form-control">{{ old('address', $borrower->address) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Borrower Details</button>
            <a href="/borrowers" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

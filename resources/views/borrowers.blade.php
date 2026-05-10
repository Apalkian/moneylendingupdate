@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Borrower Registry</h2>
    <a href="/borrowers/create" class="btn btn-success">+ Register New</a>
</div>

<!-- Search Bar -->
<form action="/borrowers" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit">Search</button>
        <a href="/borrowers" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Contact</th>
                    <th>Address (Barangay, City, Province)</th>
                    <th>Date Registered</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowers as $b)
                <tr>
                    <td>{{ $b->borrower_id }}</td>
                    <td><strong>{{ $b->last_name }}, {{ $b->first_name }} {{ $b->middle_name }}</strong></td>
                    <td>{{ $b->contact_number ?? 'N/A' }}</td>
                    <td>{{ $b->barangay }}, {{ $b->city_municipality }}, {{ $b->province }}</td>
                    <td>{{ $b->date_registered }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No borrowers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
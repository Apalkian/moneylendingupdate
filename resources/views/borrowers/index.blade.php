@extends('layout')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Borrower Registry</h3>
    <a href="/borrowers/create" class="btn btn-success">+ Add New</a>
</div>

<form action="/borrowers" method="GET" class="mb-3 d-flex">
    <input type="text" name="search" class="form-control me-2" placeholder="Search by name..." value="{{ request('search') }}">
    <button type="submit" class="btn btn-primary">Search</button>
</form>

<table class="table table-bordered bg-white shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($borrowers as $b)
        <tr>
            <td>{{ $b->last_name }}, {{ $b->first_name }} {{ $b->middle_name }}</td>
            <td>{{ $b->contact_number }}</td>
            <td>
                {{ $b->house_no_bldg }} {{ $b->street }}<br>
                {{ $b->barangay }}, {{ $b->city_municipality }}<br>
                {{ $b->province }} {{ $b->zip_code }}
            </td>
            <td>
                <!-- Edit Button -->
                <a href="/borrowers/{{ $b->borrower_id }}/edit" class="btn btn-sm btn-warning">Edit</a>

                <!-- Delete Button (Requires a small form) -->
                <form action="/borrowers/{{ $b->borrower_id }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this borrower?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

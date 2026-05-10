<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    public function index()
    {
        // Added pagination or ordering is usually better for long lists
        $borrowers = Borrower::orderBy('last_name', 'asc')->get();
        return view('borrowers', compact('borrowers'));
    }

    public function create()
    {
        return view('create_borrower');
    }

    public function store(Request $request)
    {
        // Updated Validation to include ALL your split-address fields
        $request->validate([
            'first_name'        => 'required|string|max:50',
            'last_name'         => 'required|string|max:50',
            'contact_number'    => 'nullable|string|max:20',
            'house_no_bldg'     => 'nullable|string|max:100',
            'street'            => 'nullable|string|max:100',
            'barangay'          => 'required|string|max:100',
            'city_municipality' => 'required|string|max:100',
            'province'          => 'required|string|max:100',
            'zip_code'          => 'nullable|string|max:10',
            'date_registered'   => 'required|date',
        ]);

        // Saves everything in one go
        Borrower::create($request->all());

        return redirect('/')->with('success', 'Borrower registered successfully!');
    }

    public function show(Borrower $borrower)
    {
        return view('borrower_details', compact('borrower'));
    }

    public function edit(Borrower $borrower)
    {
        return view('edit_borrower', compact('borrower'));
    }

    public function update(Request $request, Borrower $borrower)
    {
        // Best practice: Validate before update as well
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'barangay'   => 'required|string',
        ]);

        $borrower->update($request->all());
        return redirect('/')->with('success', 'Borrower updated successfully!');
    }

    public function destroy(Borrower $borrower)
    {
        $borrower->delete();
        return redirect('/')->with('success', 'Borrower deleted successfully!');
    }
}
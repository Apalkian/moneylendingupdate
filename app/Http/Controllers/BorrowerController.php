<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    // READ: List all borrowers (with search)
    public function index(Request $request)
    {
        $query = Borrower::query();

        if ($request->has('search')) {
            $query->where('last_name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('first_name', 'LIKE', '%' . $request->search . '%');
        }

        $borrowers = $query->orderBy('last_name', 'asc')->get();
        return view('borrowers.index', compact('borrowers'));
    }

    // CREATE: Show the form
    public function create()
    {
        return view('borrowers.create_borrower');
    }

    // CREATE: Save to database
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'barangay'   => 'required',
            'city_municipality' => 'required',
            'province'   => 'required',
            'date_registered'   => 'required|date',
        ]);

        Borrower::create($request->all());

        return redirect('/borrowers')->with('success', 'Borrower added successfully!');
    }

    // UPDATE: Show the pre-filled form
    public function edit($id)
    {
        $borrower = Borrower::findOrFail($id);
        return view('borrowers.edit', compact('borrower'));
    }

    // UPDATE: Save changes
    public function update(Request $request, $id)
    {
        $borrower = Borrower::findOrFail($id);
        
        $request->validate([
            'first_name' => 'required',
            'middle_name' => 'nullable|string|max:50',
            'last_name'  => 'required',

            'contact_number' => 'nullable|string|max:20',
            'date_registered' => 'required|date',

            'house_no_bldg' => 'nullable|string',
            'street' => 'nullable|string',
            'zip_code' => 'nullable|string',

            'barangay' => 'required|string',
            'city_municipality' => 'required|string',
            'province' => 'required|string',
        ]);

        $borrower->update($request->all());

        return redirect('/borrowers')->with('success', 'Borrower updated successfully!');
    }

    // DELETE: Remove from database
    public function destroy($id)
    {
        $borrower = Borrower::findOrFail($id);
        $borrower->delete();

        return redirect('/borrowers')->with('success', 'Borrower has been deleted.');
    }
}

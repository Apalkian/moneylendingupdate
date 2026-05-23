<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BorrowerController extends Controller
{
    // --- Resource methods required by Route::resource('borrowers', ...) ---

    public function index(Request $request)
    {
        $query = Borrower::with('user');

        if ($request->has('search')) {
            $search = (string) $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('address', 'LIKE', "%{$search}%")
                    ->orWhere('phone_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        $borrowers = $query->orderBy('id', 'asc')->get();

        return view('borrowers.index', compact('borrowers'));
    }

    public function create()
    {
        return view('borrowers.create_borrower');
    }

    public function edit(int $id)
    {
        $borrower = Borrower::findOrFail($id);

        return view('borrowers.edit', compact('borrower'));
    }

    public function show(int $id)
    {
        return redirect()->route('borrowers.edit', ['borrower' => $id]);
    }

    public function destroy(int $id)
    {
        Borrower::findOrFail($id)->delete();

        return redirect()->route('borrowers.index')->with('success', 'Borrower deleted successfully.');
    }

    // --- Form submit methods (POST/PUT) ---

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'credit_status' => ['required', 'in:good,delinquent'],
        ]);

        $generatedPassword = Str::random(10);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($generatedPassword),
            'role' => 'borrower',
        ]);

        Borrower::create([
            'user_id' => $user->id,
            'phone_number' => $validated['phone_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'credit_status' => $validated['credit_status'],
        ]);

        return redirect()->route('borrowers.index')->with('success', 'Borrower registered successfully. Temporary password: '.$generatedPassword);
    }

    public function update(Request $request, int $id)
    {
        $borrower = Borrower::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.($borrower->user_id ?? 0)],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'credit_status' => ['required', 'in:good,delinquent'],
        ]);

        if ($borrower->user) {
            $borrower->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
        }

        $borrower->update([
            'phone_number' => $validated['phone_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'credit_status' => $validated['credit_status'],
        ]);

        return redirect()->route('borrowers.index')->with('success', 'Borrower profile updated successfully.');
    }
}

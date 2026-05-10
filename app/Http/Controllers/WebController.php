<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // THIS WAS MISSING - ADDED
use App\Models\Borrower;
use App\Models\Loan;

class WebController extends Controller
{
    /**
     * Show the Dashboard with Reports
     */
    public function index()
    {
        // Load the reports from the SQL Views
        $loanReport = DB::table('vw_Loan_Report')->get();
        $outstanding = DB::table('vw_Outstanding_Balances')->get();
        
        return view('dashboard', compact('loanReport', 'outstanding'));
    }

    /**
     * Show the list of Borrowers with Search functionality
     */
    public function showBorrowers(Request $request)
    {
        $query = Borrower::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('last_name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('first_name', 'LIKE', '%' . $request->search . '%');
        }

        $borrowers = $query->orderBy('last_name', 'asc')->get();
        return view('borrowers', compact('borrowers'));
    }

    /**
     * Show the form to register a new borrower
     */
    public function createBorrower() 
    {
        return view('create_borrower');
    }

    /**
     * Show the form to create a new loan
     */
    public function createLoan() 
    {
        $borrowers = Borrower::orderBy('last_name', 'asc')->get();
        return view('create_loan', compact('borrowers'));
    }

    /**
     * Show the form to record a payment
     */
    public function createPayment() 
    {
        // Added .with('borrower') so you can show names in the dropdown
        $loans = Loan::with('borrower')
                     ->where('status', 'Active')
                     ->get();
                     
        return view('create_payment', compact('loans'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // THIS WAS MISSING
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Get the Loan Report (Matches Image #7)
     */
    public function getLoanReport() 
    {
        // This fetches data from the SQL View 'vw_Loan_Report'
        $data = DB::table('vw_Loan_Report')->get();
        return response()->json($data);
    }

    /**
     * Get Payment History (Matches Image #8 Top)
     */
    public function getPaymentHistory() 
    {
        // This fetches data from the SQL View 'vw_Payment_History'
        $data = DB::table('vw_Payment_History')->get();
        return response()->json($data);
    }

    /**
     * Get Outstanding Balances (Matches Image #8 Bottom)
     */
    public function getOutstandingBalances() 
    {
        // This fetches data from the SQL View 'vw_Outstanding_Balances'
        $data = DB::table('vw_Outstanding_Balances')->get();
        return response()->json($data);
    }
}
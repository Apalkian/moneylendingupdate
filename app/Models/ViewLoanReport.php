<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewLoanReport extends Model
{
    // 1. Matches the view name created in your migration
    protected $table = 'vw_Loan_Report'; 

    // 2. Views are read-only, so we disable timestamps
    public $timestamps = false;

    // 3. Tell Laravel which column is the unique ID 
    // This allows you to use ViewLoanReport::find($id)
    protected $primaryKey = 'loan_id';

    // 4. Since it's a view, the ID isn't "auto-incrementing" in this specific object
    public $incrementing = false;
}
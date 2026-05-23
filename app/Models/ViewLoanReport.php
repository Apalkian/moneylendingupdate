<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewLoanReport extends Model
{
    // view
    protected $table = 'vw_Loan_Report';

    //
    public $timestamps = false;

    //
    protected $primaryKey = 'loan_id';


    public $incrementing = false;
}

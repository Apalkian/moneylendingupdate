<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewOutstandingBalance extends Model
{
    //view
    protected $table = 'vw_Outstanding_Balances';

    //  Views are read-only so disable timestamps
    public $timestamps = false;

    // Define the primary key to can find specific records
    protected $primaryKey = 'loan_id';

    // Since it's a view the ID is not autoincrementing in this object
    public $incrementing = false;
}
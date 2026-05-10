<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewOutstandingBalance extends Model
{
    // 1. Point to the specific SQL View created in your migration
    protected $table = 'vw_Outstanding_Balances';

    // 2. Views are read-only, so we disable timestamps
    public $timestamps = false;

    // 3. Define the primary key so you can find specific records
    protected $primaryKey = 'loan_id';

    // 4. Since it's a view, the ID is not auto-incrementing in this object
    public $incrementing = false;
}
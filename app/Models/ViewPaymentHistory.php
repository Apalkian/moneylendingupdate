<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewPaymentHistory extends Model
{
    protected $table = 'vw_Payment_History'; // Matches Image #8 Top
    public $timestamps = false;
    public $incrementing = false;
}
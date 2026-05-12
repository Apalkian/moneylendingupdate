<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment_table';
    protected $primaryKey = 'payment_id';

    
    public $timestamps = true; 

    protected $fillable = [
        'loan_id', 
        'payment_date', 
        'amount_paid', 
        'interest_added', 
        'admin_id'
    ];

    /**
     * Relationship: A Payment belongs to a Loan.
     * Allows you to do: $payment->loan->principal_amount
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'loan_id');
    }

 
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }
}
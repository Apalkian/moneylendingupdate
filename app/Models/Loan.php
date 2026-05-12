<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loan_table';
    protected $primaryKey = 'loan_id';

   
    public $timestamps = true; 


    protected $fillable = [
        'borrower_id', 
        'principal_amount', 
        'interest_rate', 
        'release_date', 
        'due_date', 
        'status', 
        'admin_id'
    ];

    /**
     * Relationship: A Loan belongs to a Borrower.
     * This allows you to do: $loan->borrower->last_name
     */
    public function borrower()
    {
        return $this->belongsTo(Borrower::class, 'borrower_id', 'borrower_id');
    }


    //Relationship A Loan can have many Payments.
   
    public function payments()
    {
        return $this->hasMany(Payment::class, 'loan_id', 'loan_id');
    }

    /**
     * Relationship: A Loan can have many Additional Capital/Penalty entries.
     */
    public function additional_capitals()
    {
        return $this->hasMany(AdditionalCapital::class, 'loan_id', 'loan_id');
    }

    /**
     * Relationship: A Loan is managed by an Admin.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }
}
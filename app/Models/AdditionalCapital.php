<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdditionalCapital extends Model
{
    use HasFactory;

    protected $table = 'additional_table';
    protected $primaryKey = 'capital_id';

   
    public $timestamps = true; 

    protected $fillable = [
        'loan_id', 
        'amount_added', 
        'date_added', 
        'remarks'
    ];

    /**
     * Relationship: An Additional Capital entry belongs to a Loan.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'loan_id');
    }
}
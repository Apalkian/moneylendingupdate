<?php

namespace App\Http\Controllers; // Check your namespace, usually App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrower extends Model
{
    use HasFactory;

    protected $table = 'borrower_table';
    protected $primaryKey = 'borrower_id';

    public $timestamps = true; 


    protected $fillable = [
        'first_name', 
        'middle_name', 
        'last_name', 
        'contact_number', 
        'house_no_bldg', 
        'street', 
        'barangay', 
        'city_municipality', 
        'province', 
        'zip_code', 
        'date_registered', 
        'admin_id'
    ];

    // A Borrower can have many Loans.
     
    public function loans()
    {
        return $this->hasMany(Loan::class, 'borrower_id', 'borrower_id');
    }

        // A Borrower was registered by an Admin.
     
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }
}
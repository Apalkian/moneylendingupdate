<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loans';

    protected $fillable = [
        'borrower_id',
        'principal_amount',
        'interest_rate',
        'interest_type',
        'start_date',
        'end_date',
        'status',
    ];

    public function borrower(): BelongsTo
    {
        return $this->belongsTo(Borrower::class, 'borrower_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'loan_id');
    }

    public function additionalCapitals(): HasMany
    {
        return $this->hasMany(AdditionalCapital::class, 'loan_id');
    }
}

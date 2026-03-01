<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'colocation_member_id',
        'amount',
        'status',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function colocationMember()
    {
        return $this->belongsTo(ColocationMember::class);
    }
}
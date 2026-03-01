<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'colocation_member_id',
        'category_id',
        'title',
        'amount',
    ];

    public function colocationMember()
    {
        return $this->belongsTo(ColocationMember::class);
    }

    public function expenseDetails()
    {
        return $this->hasMany(ExpenseDetail::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
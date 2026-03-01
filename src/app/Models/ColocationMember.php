<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColocationMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'colocation_id',
        'role',
        'left_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function expenseDetails()
    {
        return $this->hasMany(ExpenseDetail::class);
    }
}
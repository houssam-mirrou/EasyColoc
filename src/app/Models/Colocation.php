<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'status',
        'cancelled_at',
    ];


    public function owner()
    {
        return $this->belongsTo(User::class , 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('left_at')->withTimestamps();
    }

    public function activeMembers()
    {
        return $this->members()->whereNull('left_at');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class , 'colocation_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class , 'colocation_id');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class , 'colocation_id');
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];


    public function colocationMembers()
    {
        return $this->hasMany(ColocationMember::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class , 'colocation_members')->withPivot('role', 'left_at')->withTimestamps();
    }

    public function activeMembers()
    {
        return $this->members()->wherePivotNull('left_at');
    }

    public function expenses()
    {
        return $this->hasManyThrough(Expense::class , ColocationMember::class);
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
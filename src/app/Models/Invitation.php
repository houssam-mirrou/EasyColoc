<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'colocation_id',
        'sender_id',
        'receiver_email',
        'token',
        'status',
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class , 'sender_id');
    }
}
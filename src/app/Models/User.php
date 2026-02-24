<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'reputation_score',
        'is_banned',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ownedColocations()
    {
        return $this->hasMany(Colocation::class , 'owner_id');
    }

    public function colocations()
    {
        return $this->belongsToMany(Colocation::class , 'colocation_user')->withPivot('left_at')->withTimestamps();
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class , 'payer_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class , 'payer_id');
    }

    public function currentColocation()
    {
        return $this->colocations()->wherePivot('left_at', null)->first()
            ?? $this->ownedColocations()->where('status', 'active')->first();
    }

}
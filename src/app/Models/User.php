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

    public function colocationMembers()
    {
        return $this->hasMany(ColocationMember::class);
    }

    public function colocations()
    {
        return $this->belongsToMany(Colocation::class , 'colocation_members')->withPivot('role', 'left_at')->withTimestamps();
    }

    // Removed expenses() and payments() directly from User, as they route through ColocationMember now.

    public function currentColocation()
    {
        return $this->colocations()->where([['status', '=', 'active']])->wherePivotNull('left_at')->first();
    }

    /**
     * Calculate the user's current net balance in their active colocation.
     * Positive = They are owed money. Negative = They owe money (debt).
     */
    public function getColocationBalance($colocation_id = null)
    {
        $colocation = $colocation_id ?Colocation::find($colocation_id) : $this->currentColocation();
        if (!$colocation) {
            return 0;
        }

        $myMemberId = $this->colocationMembers()->where('colocation_id', $colocation->id)->value('id');
        if (!$myMemberId) {
            return 0;
        }

        $myDebt = ExpenseDetail::where('colocation_member_id', $myMemberId)
            ->where('status', 'pending')
            ->sum('amount');

        $owedToMe = ExpenseDetail::whereHas('expense', function ($q) use ($myMemberId) {
            $q->where('colocation_member_id', $myMemberId);
        })
            ->where('status', 'pending')
            ->sum('amount');

        $netBalance = $owedToMe - $myDebt;
        return round($netBalance, 2);
    }

    /**
     * Check if the user currently has any debt in their active colocation.
     */
    public function hasDebt($colocation_id = null)
    {
        return $this->getColocationBalance($colocation_id) < -0.01;
    }
}
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
        return $this->colocations()->where('colocations.status', 'active')->wherePivotNull('left_at')->first()
            ?? Colocation::where('owner_id', $this->id)->where('status', 'active')->first();
    }

    /**
     * Calculate the user's current net balance in their active colocation.
     * Positive = They are owed money. Negative = They owe money (debt).
     */
    public function getColocationBalance()
    {
        $colocation = $this->currentColocation();
        if (!$colocation) {
            return 0;
        }

        $members = $colocation->members()->wherePivotNull('left_at')->get();
        $owner = User::find($colocation->owner_id);
        if ($owner && !$members->contains($owner)) {
            $members->push($owner);
        }

        $totalExpenses = Expense::where('colocation_id', $colocation->id)->sum('amount');
        $memberCount = $members->count();
        $sharePerPerson = $memberCount > 0 ? $totalExpenses / $memberCount : 0;

        $paidExpenses = Expense::where('colocation_id', $colocation->id)
            ->where('payer_id', $this->id)
            ->sum('amount');

        $sentPayments = Payment::where('colocation_id', $colocation->id)
            ->where('payer_id', $this->id)
            ->sum('amount');

        $receivedPayments = Payment::where('colocation_id', $colocation->id)
            ->where('receiver_id', $this->id)
            ->sum('amount');

        $netBalance = ($paidExpenses - $sharePerPerson) + $sentPayments - $receivedPayments;

        return round($netBalance, 2);
    }

    /**
     * Check if the user currently has any debt in their active colocation.
     */
    public function hasDebt()
    {
        return $this->getColocationBalance() < -0.01;
    }
}
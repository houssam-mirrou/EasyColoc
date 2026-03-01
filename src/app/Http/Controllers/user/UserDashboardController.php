<?php

namespace App\Http\Controllers\user;

use App\Models\Colocation;
use App\Models\ColocationMember;
use App\Models\Expense;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController
{
    public function index()
    {
        $invitation = Invitation::where('receiver_email', Auth::user()->email)->where('status', 'pending')->get();
        $colocation = Auth::user()->currentColocation();
        if ($colocation) {
            $memberId = ColocationMember::where('colocation_id', $colocation->id)
                ->where('user_id', Auth::id())
                ->value('id');
            $personal_spent = Expense::where('colocation_member_id', $memberId)->sum('amount');
        }
        else {
            $colocation = null;
            $personal_spent = 0;
        }

        return view('pages.user.dashboard', compact('invitation', 'colocation', 'personal_spent'));
    }

    public function profile()
    {
        $user = Auth::user();
        $totalColocations = $user->colocations()->where('colocations.status', 'active')->wherePivotNull('left_at')->count();

        $totalPaid = Expense::whereHas('colocationMember', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->sum('amount');

        return view('pages.user.profile', compact('user', 'totalColocations', 'totalPaid'));
    }
}
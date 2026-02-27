<?php

namespace App\Http\Controllers\user;

use App\Models\Colocation;
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
            $personal_spent = Expense::where('colocation_id', $colocation->id)
                ->where('payer_id', Auth::id())
                ->sum('amount');
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
        return view('pages.user.profile', compact('user'));
    }
}
<?php

namespace App\Http\Controllers\user;

use App\Models\Colocation;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController
{
    public function index()
    {
        $invitation = Invitation::where('receiver_email', Auth::user()->email)->where('status', 'pending')->get();
        $colocation_id = DB::table('colocation_user')->select('colocation_id')->where('user_id', Auth::user()->id)->first();
        if ($colocation_id) {
            $colocation = Colocation::find($colocation_id->colocation_id);
        }
        else {
            $colocation = null;
        }
        return view('pages.user.dashboard', compact('invitation', 'colocation'));
    }

}
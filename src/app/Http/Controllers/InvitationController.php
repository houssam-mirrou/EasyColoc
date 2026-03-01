<?php

namespace App\Http\Controllers;

use App\Mail\InvitationMail;
use App\Models\Colocation;
use App\Models\ColocationMember;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController
{
    public function store(Request $request)
    {
        $request->validate([
            'colocation_id' => 'required|exists:colocations,id',
            'receiver_email' => 'required|email',
        ]);

        $colocation = Colocation::findOrFail($request->colocation_id);
        if ($colocation->status === 'desactive') {
            return redirect()->back()->with('error', 'Vous ne pouvez pas inviter de membres dans une colocation annulée.');
        }
        $token = Str::random(32);
        $invitation = Invitation::create([
            'sender_id' => Auth::user()->id,
            'colocation_id' => $request->colocation_id,
            'receiver_email' => $request->receiver_email,
            'token' => $token,
        ]);
        $user_exist = User::where('email', $request->receiver_email)->first();
        Mail::to($request->receiver_email)->send(new InvitationMail($colocation, $invitation, $user_exist));
        $message = "L'invitation a été envoyée à {$request->receiver_email}. Le token à lui transmettre est : {$token}";

        return redirect()->back()->with('success', $message);
    }

    public function accept(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);
        $invitation = Invitation::where('token', $request->token)->where('status', 'pending')->first();
        if (!$invitation) {
            return redirect()->back()->with('error', 'Invitation non trouvée');
        }

        if (Auth::user()->email !== $invitation->receiver_email) {
            return redirect()->route('user.dashboard')->with('error', 'Cette invitation ne vous est pas destinée.');
        }

        if (Auth::user()->currentColocation()) {
            return redirect()->route('user.dashboard')->with('error', 'Vous avez déjà une colocation active. Vous ne pouvez pas en rejoindre une autre.');
        }

        $invitation->status = 'accepted';
        $invitation->save();

        ColocationMember::create([
            'user_id' => Auth::id(),
            'colocation_id' => $invitation->colocation_id,
            'role' => 'member',
        ]);
        return redirect()->route('user.dashboard')->with('success', 'Invitation acceptée avec succès');
    }
    public function decline(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);
        $invitation = Invitation::where('token', $request->token)->where('status', 'pending')->first();
        if (!$invitation) {
            return redirect()->back()->with('error', 'Invitation non trouvée');
        }
        $invitation->status = 'rejected';
        $invitation->save();
        return redirect()->route('user.dashboard')->with('success', 'Invitation déclinée avec succès');
    }
}
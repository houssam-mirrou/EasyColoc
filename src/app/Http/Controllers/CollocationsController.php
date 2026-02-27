<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\User;
use \App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollocationsController
{
    public function index()
    {
        $user = Auth::user();

        $owned = $user->ownedColocations;

        $member = $user->colocations()->withPivot('left_at')->get();

        $allColocations = $owned->merge($member)->sortByDesc('created_at');

        return view('pages.colocations.index', compact('allColocations'));
    }

    public function create()
    {
        return view('pages.colocations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        if (Auth::user()->currentColocation()) {
            return redirect()->route('user.dashboard')->with('error', 'Vous avez déjà une colocation active. Vous ne pouvez pas en créer une nouvelle.');
        }

        $colocation = Colocation::create([
            'name' => $request->name,
            'owner_id' => Auth::id(),
            'status' => 'active',
        ]);

        DB::table('colocation_user')->insert([
            'user_id' => Auth::id(),
            'colocation_id' => $colocation->id,
        ]);

        if (Auth::user()->role === 'user') {
            return redirect()->route('user.dashboard')->with('success', 'Colocation créée avec succès');
        }

        return redirect()->route('admin.colocations.index')->with('success', 'Colocation créée avec succès');
    }

    public function show(Colocation $colocation)
    {
        $expenses = $colocation->expenses;
        return view('pages.colocations.show', compact('colocation', 'expenses'));
    }

    public function edit(Colocation $colocation)
    {
        return view('pages.colocations.edit', compact('colocation'));
    }

    public function update(Request $request, Colocation $colocation)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $colocation->update($request->all());

        return redirect()->route('colocations.index');
    }

    public function destroy(string $colocation_id)
    {
        $hasDebt = Auth::user()->hasDebt($colocation_id);
        if ($hasDebt) {
            return redirect()->back()->with('error', 'Vous devez payer votre dette avant de quitter la colocation');
        }
        $colocation = Colocation::find($colocation_id);
        $colocation->status = 'cancelled';
        $colocation->cancelled_at = now();
        $colocation->save();

        return redirect()->route('colocations.index')->with('success', 'Colocation supprimée avec succès');
    }

    public function settings()
    {
        $colocation = Auth::user()->currentColocation();
        return view('pages.colocations.settings', compact('colocation'));
    }

    public function category_settings(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:4',
        ]);
        $colocation = Auth::user()->currentColocation();
        if ($colocation->status === 'cancelled') {
            return redirect()->back()->with('error', 'Vous ne pouvez pas ajouter de catégories à une colocation annulée.');
        }
        Category::create([
            'name' => $request->name,
            'colocation_id' => $colocation->id,
        ]);
        return redirect()->back()->with('success', 'Catégorie créée avec succès');
    }

    public function transfer_ownership(Request $request, string $colocation_id, string $user_id)
    {
        $colocation = Colocation::find($colocation_id);
        $user = User::find($user_id);
        $colocation->owner_id = $user->id;
        $colocation->save();
        return redirect()->route('colocations.show', $colocation_id)->with('success', 'Propriétaire transféré avec succès');
    }

    public function leave(string $colocation_id)
    {
        $user = User::find(Auth::user()->id);
        $balance = $this->getUserBalance($colocation_id);
        $colocation = Colocation::find($colocation_id); 
        if ($balance < 0) {
            $user->reputation_score -= 1;
            $user->save();
            Payment::create([
                'colocation_id' => $colocation_id,
                'payer_id' => Auth::user()->id,
                'receiver_id' => $colocation->owner_id,
                'amount' => $balance,
            ]);
        }
        else if ($balance > 0) {
            $user->reputation_score += 1;
            Payment::create([
                'colocation_id' => $colocation_id,
                'payer_id' => $colocation->owner_id,
                'receiver_id' => Auth::user()->id,
                'amount' => $balance,
            ]);
        }
        DB::table('colocation_user')->where('colocation_id', $colocation_id)->where('user_id', Auth::user()->id)->update([
            'left_at' => now(),
        ]);
        return redirect()->route('colocations.show', $colocation_id)->with('success', 'Vous avez quitté la colocation');
    }


    private function getUserBalance($colocation_id)
    {
        $totalExpenses = Expense::where('colocation_id', $colocation_id)->sum('amount');
        $membersCount = DB::table('colocation_user')->where('colocation_id', $colocation_id)->count();
        if ($membersCount === 0) {
            return;
        }
        $fairshare = $totalExpenses / $membersCount;
        $paidExpenses = Expense::where('colocation_id', $colocation_id)->where('payer_id', Auth::user()->id)->sum('amount');
        $sentPayments = Payment::where('colocation_id', $colocation_id)->where('payer_id', Auth::user()->id)->sum('amount');
        $receivedPayments = Payment::where('colocation_id', $colocation_id)->where('receiver_id', Auth::user()->id)->sum('amount');
        $netBalance = ($paidExpenses - $fairshare) + $sentPayments - $receivedPayments;
        return round($netBalance, 2);
    }
    
}
<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalenceController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $colocation = $user->currentColocation();

        if (!$colocation) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas de colocation active.');
        }

        // 1. On ne récupère QUE les membres ACTIFS pour le calcul des parts
        // On utilise la relation activeMembers ou on filtre manuellement
        $members = $colocation->members()->wherePivotNull('left_at')->get();

        // On s'assure que l'Owner est dans la liste (même s'il n'est pas dans le pivot)
        $owner = User::find($colocation->owner_id);
        if (!$members->contains($owner)) {
            $members->push($owner);
        }

        // 2. Calcul de la Part Équitable pour les membres actuels
        $totalExpenses = Expense::where('colocation_id', $colocation->id)->sum('amount');
        $memberCount = $members->count();

        // IMPORTANT : La part individuelle est basée sur le nombre de personnes qui vont payer MAINTENANT
        $sharePerPerson = $memberCount > 0 ? $totalExpenses / $memberCount : 0;

        $balances = [];
        foreach ($members as $member) {
            // On prend TOUTES les dépenses et paiements de l'histoire, 
            // car la dette du membre parti a été transférée à l'Owner via la table Payment
            $paidExpenses = Expense::where('colocation_id', $colocation->id)
                ->where('payer_id', $member->id)
                ->sum('amount');

            $sentPayments = Payment::where('colocation_id', $colocation->id)
                ->where('payer_id', $member->id)
                ->sum('amount');

            $receivedPayments = Payment::where('colocation_id', $colocation->id)
                ->where('receiver_id', $member->id)
                ->sum('amount');

            // Formule de balance nette
            $netBalance = ($paidExpenses - $sharePerPerson) + $sentPayments - $receivedPayments;

            $balances[$member->id] = [
                'user' => $member,
                'balance' => round($netBalance, 2)
            ];
        }

        // 3. Algorithme "Qui doit à qui"
        $debtors = array_filter($balances, fn($b) => $b['balance'] < -0.01);
        $creditors = array_filter($balances, fn($b) => $b['balance'] > 0.01);

        usort($debtors, fn($a, $b) => $a['balance'] <=> $b['balance']);
        usort($creditors, fn($a, $b) => $b['balance'] <=> $a['balance']);

        $suggestedPayments = [];
        $debtorsList = array_values($debtors);
        $creditorsList = array_values($creditors);
        $i = $j = 0;

        while ($i < count($debtorsList) && $j < count($creditorsList)) {
            $amount = min(abs($debtorsList[$i]['balance']), $creditorsList[$j]['balance']);
            if ($amount > 0.01) {
                $suggestedPayments[] = [
                    'from' => $debtorsList[$i]['user'],
                    'to' => $creditorsList[$j]['user'],
                    'amount' => round($amount, 2)
                ];
            }
            $debtorsList[$i]['balance'] += $amount;
            $creditorsList[$j]['balance'] -= $amount;
            if (abs($debtorsList[$i]['balance']) < 0.01)
                $i++;
            if ($creditorsList[$j]['balance'] < 0.01)
                $j++;
        }

        $pastPayments = Payment::where('colocation_id', $colocation->id)->orderBy('created_at', 'desc')->get();

        return view('pages.balences.index', compact('balances', 'suggestedPayments', 'pastPayments', 'members'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($request->receiver_id == Auth::id()) {
            return redirect()->back()->with('error', 'You cannot pay yourself!');
        }

        $colocation = Auth::user()->currentColocation();

        Payment::create([
            'colocation_id' => $colocation->id,
            'payer_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'amount' => $request->amount,
        ]);

        return redirect()->route('balances.index')->with('success', 'Reimbursement recorded successfully! Balances have been updated.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    //
    }
}
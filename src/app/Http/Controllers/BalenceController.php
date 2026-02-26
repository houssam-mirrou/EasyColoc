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

        // 1. Get all members (Owner + Pivot members)
        $members = $colocation->members;
        $owner = User::find($colocation->owner_id);
        if (!$members->contains($owner)) {
            $members->push($owner);
        }

        // 2. Calculate the Equal Share
        $totalExpenses = Expense::where('colocation_id', $colocation->id)->sum('amount');
        $memberCount = $members->count();
        $sharePerPerson = $memberCount > 0 ? $totalExpenses / $memberCount : 0;

        // 3. Calculate Individual Net Balances
        $balances = [];
        foreach ($members as $member) {
            $paidExpenses = Expense::where('colocation_id', $colocation->id)
                ->where('payer_id', $member->id)
                ->sum('amount');

            $sentPayments = Payment::where('colocation_id', $colocation->id)
                ->where('payer_id', $member->id)
                ->sum('amount');

            $receivedPayments = Payment::where('colocation_id', $colocation->id)
                ->where('receiver_id', $member->id)
                ->sum('amount');

            // Net Balance = (What they bought - What they should have paid) + What they reimbursed others - What others reimbursed them
            $netBalance = ($paidExpenses - $sharePerPerson) + $sentPayments - $receivedPayments;

            $balances[$member->id] = [
                'user' => $member,
                'balance' => round($netBalance, 2)
            ];
        }

        // 4. Algorithm to calculate "Who owes whom" (Suggested Debts)
        $debtors = array_filter($balances, fn($b) => $b['balance'] < -0.01);
        $creditors = array_filter($balances, fn($b) => $b['balance'] > 0.01);

        usort($debtors, fn($a, $b) => $a['balance'] <=> $b['balance']); // Most negative first
        usort($creditors, fn($a, $b) => $b['balance'] <=> $a['balance']); // Most positive first

        $suggestedPayments = [];
        $debtorsList = array_values($debtors);
        $creditorsList = array_values($creditors);
        $i = 0;
        $j = 0;

        while ($i < count($debtorsList) && $j < count($creditorsList)) {
            $debtor = $debtorsList[$i];
            $creditor = $creditorsList[$j];

            $amountToSettle = min(abs($debtor['balance']), $creditor['balance']);

            if ($amountToSettle > 0.01) {
                $suggestedPayments[] = [
                    'from' => $debtor['user'],
                    'to' => $creditor['user'],
                    'amount' => round($amountToSettle, 2)
                ];
            }

            $debtorsList[$i]['balance'] += $amountToSettle;
            $creditorsList[$j]['balance'] -= $amountToSettle;

            if (abs($debtorsList[$i]['balance']) < 0.01)
                $i++;
            if ($creditorsList[$j]['balance'] < 0.01)
                $j++;
        }

        // 5. Get History of Payments
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
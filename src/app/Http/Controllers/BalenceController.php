<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use App\Models\ExpenseDetail;
use App\Models\ColocationMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalenceController
{
    /**
     * Display a listing of the balances and suggested payments.
     */
    public function index($colocation_id)
    {
        $user = Auth::user();
        $colocation = Colocation::find($colocation_id);

        if (!$colocation) {
            return redirect()->route('user.dashboard')->with('error', 'Colocation introuvable.');
        }

        $members = $colocation->members()->wherePivotNull('left_at')->get();
        $owner = User::find($colocation->owner_id);

        if ($owner && !$members->contains($owner)) {
            $members->push($owner);
        }

        $balances = [];
        foreach ($members as $member) {
            $balances[$member->id] = [
                'user' => $member,
                'balance' => $member->getColocationBalance($colocation->id)
            ];
        }

        $suggestedPayments = $this->calculateSuggestedPayments($balances);

        $pastPayments = ExpenseDetail::whereHas('colocationMember', function ($query) use ($colocation) {
            $query->where('colocation_id', $colocation->id);
        })
            ->where('status', 'paid')
            ->latest('updated_at')
            ->get();

        return view('pages.balences.index', compact('colocation', 'balances', 'suggestedPayments', 'pastPayments', 'members'));
    }

    public function store(Request $request, $colocation_id)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($request->receiver_id == Auth::id()) {
            return redirect()->back()->with('error', 'You cannot pay yourself!');
        }

        $colocation = Colocation::find($colocation_id);
        if (!$colocation || $colocation->status === 'desactive') {
            return redirect()->back()->with('error', 'Vous ne pouvez pas effectuer de remboursements dans une colocation annulée.');
        }

        $myMemberId = ColocationMember::where('colocation_id', $colocation->id)->where('user_id', Auth::id())->value('id');
        $receiverMemberId = ColocationMember::where('colocation_id', $colocation->id)->where('user_id', $request->receiver_id)->value('id');

        $detailsToPay = ExpenseDetail::where('colocation_member_id', $myMemberId)
            ->where('status', 'pending')
            ->whereHas('expense', function ($query) use ($receiverMemberId) {
            $query->where('colocation_member_id', $receiverMemberId);
        })
            ->get();

        $remainingToPay = $request->amount;

        foreach ($detailsToPay as $detail) {
            if ($remainingToPay >= $detail->amount - 0.01) {
                $detail->status = 'paid';
                $detail->save();
                $remainingToPay -= $detail->amount;
            }
            elseif ($remainingToPay > 0) {
                $detail->status = 'paid';
                $detail->save();
                $remainingToPay = 0;
            }
        }

        return redirect()->route('balances.index', $colocation_id)->with('success', 'Reimbursement recorded successfully! Balances have been updated.');
    }

    private function calculateSuggestedPayments(array $balances): array
    {
        $suggestedPayments = [];

        $debtors = [];
        $creditors = [];

        foreach ($balances as $b) {
            if ($b['balance'] < -0.01) {
                $debtors[] = $b;
            }
            elseif ($b['balance'] > 0.01) {
                $creditors[] = $b;
            }
        }

        foreach ($debtors as &$debtor) {
            foreach ($creditors as &$creditor) {
                if ($debtor['balance'] < -0.01 && $creditor['balance'] > 0.01) {
                    $amount = min(abs($debtor['balance']), $creditor['balance']);

                    if ($amount > 0.01) {
                        $suggestedPayments[] = [
                            'from' => $debtor['user'],
                            'to' => $creditor['user'],
                            'amount' => round($amount, 2)
                        ];

                        $debtor['balance'] += $amount;
                        $creditor['balance'] -= $amount;
                    }
                }
            }
        }

        return $suggestedPayments;
    }


}
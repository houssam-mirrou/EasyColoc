<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ColocationMember;
use App\Models\Expense;
use App\Models\ExpenseDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpensesController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('pages.expences.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($colocation_id)
    {
        $colocation = \App\Models\Colocation::find($colocation_id);
        if (!$colocation || $colocation->status === 'desactive') {
            return redirect()->back()->with('error', 'Vous ne pouvez pas ajouter de dépenses à une colocation annulée.');
        }
        $members = $colocation->members()->get();
        $categories = Category::all();
        return view('pages.expences.create', compact('categories', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $colocation_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'payer_id' => 'required|exists:users,id',
        ]);

        $colocation = \App\Models\Colocation::find($colocation_id);
        if (!$colocation || $colocation->status === 'desactive') {
            return redirect()->back()->with('error', 'Vous ne pouvez pas ajouter de dépenses à une colocation annulée.');
        }

        /** @var \App\Models\ColocationMember $payerMember */
        $payerMember = ColocationMember::where('colocation_id', $colocation->id)
            ->where('user_id', $request->payer_id)
            ->firstOrFail();

        /** @var \App\Models\Expense $expense */
        $expense = Expense::create([
            'colocation_member_id' => $payerMember->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'amount' => $request->amount,
        ]);

        $activeMembers = ColocationMember::where('colocation_id', $colocation->id)->whereNull('left_at')->get();
        $memberCount = $activeMembers->count();

        if ($memberCount > 0) {
            $fairShare = $request->amount / $memberCount;
            foreach ($activeMembers as $member) {
                // Ignore the payer
                if ($member->id === $payerMember->id) {
                    continue;
                }

                ExpenseDetail::create([
                    'expense_id' => $expense->id,
                    'colocation_member_id' => $member->id,
                    'amount' => $fairShare,
                    'status' => 'pending',
                ]);
            }
        }

        return redirect()->route('balances.index', $colocation->id)->with('success', 'Expense created successfully');
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
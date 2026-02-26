<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
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
    public function create()
    {
        $colocation = Auth::user()->currentColocation();
        $members = $colocation->members()->get();
        $categories = Category::all();
        return view('pages.expences.create', compact('categories', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'payer_id' => 'required|exists:users,id',
        ]);

        $expense = Expense::create([
            'colocation_id' => Auth::user()->currentColocation()->id,
            'payer_id' => $request->payer_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
        ]);

        DB::table('payments')->insert([
            'colocation_id' => Auth::user()->currentColocation()->id,
            'payer_id' => $request->payer_id,
            'receiver_id' => Auth::id(),
            'amount' => $request->amount,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('balances.index')->with('success', 'Expense created successfully');
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
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollocationsController
{
    public function index()
    {
        return view('pages.colocations.index');
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
        Colocation::create([
            'name' => $request->name,
            'owner_id' => Auth::user()->id,
            'status' => 'active',
        ]);
        DB::table('colocation_user')->insert([
            'user_id' => Auth::user()->id,
            'colocation_id' => Colocation::where('owner_id', Auth::user()->id)->first()->id,
        ]);
        if (Auth::user()->role === 'user') {
            return redirect()->route('user.dashboard')->with('success', 'Colocation créée avec succès');
        }

        return redirect()->route('admin.colocations.index')->with('success', 'Colocation créée avec succès');
    }

    public function show(Colocation $colocation)
    {
        return view('pages.colocations.show', compact('colocation'));
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

    public function destroy(Colocation $colocation)
    {
        $colocation->status = 'cancelled';
        $colocation->cancelled_at = now();
        $colocation->save();

        return redirect()->route('colocations.index');
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
        Category::create([
            'name' => $request->name,
            'colocation_id' => Auth::user()->currentColocation()->id,
        ]);
        return redirect()->back()->with('success', 'Catégorie créée avec succès');
    }
}
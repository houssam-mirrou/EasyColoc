<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;

class CollocationsController
{
    public function index()
    {
        return view('colocations.index');
    }

    public function create()
    {
        return view('colocations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'country' => 'required',
        ]);

        $colocation = Colocation::create($request->all());

        return redirect()->route('colocations.index');
    }

    public function show(Colocation $colocation)
    {
        return view('colocations.show', compact('colocation'));
    }

    public function edit(Colocation $colocation)
    {
        return view('colocations.edit', compact('colocation'));
    }

    public function update(Request $request, Colocation $colocation)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'country' => 'required',
        ]);

        $colocation->update($request->all());

        return redirect()->route('colocations.index');
    }

    public function destroy(Colocation $colocation)
    {
        $colocation->delete();

        return redirect()->route('colocations.index');
    }
}
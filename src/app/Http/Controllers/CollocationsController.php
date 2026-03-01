<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colocation;
use App\Models\ColocationMember;
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

        $allColocations = $user->colocations()->withPivot('left_at', 'role')->orderByDesc('colocations.created_at')->get();

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
            'status' => 'active',
        ]);

        ColocationMember::create([
            'user_id' => Auth::id(),
            'colocation_id' => $colocation->id,
            'role' => 'owner',
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
        $colocation->status = 'desactive';
        $colocation->save();

        return redirect()->route('colocations.index')->with('success', 'Colocation supprimée avec succès');
    }

    public function settings($id)
    {
        $colocation = Colocation::find($id);
        if (!$colocation) {
             return redirect()->route('user.dashboard')->with('error', 'Colocation introuvable.');
        }
        return view('pages.colocations.settings', compact('colocation'));
    }

    public function category_settings(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|min:4',
        ]);
        $colocation = Colocation::find($id);
        if (!$colocation || $colocation->status === 'desactive') {
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
        $oldOwner = ColocationMember::where('colocation_id', $colocation_id)->where('role', 'owner')->first();
        if ($oldOwner) {
            $oldOwner->update(['role' => 'member']);
        }
        
        $newOwner = ColocationMember::where('colocation_id', $colocation_id)->where('user_id', $user_id)->first();
        if ($newOwner) {
            $newOwner->update(['role' => 'owner']);
        }
        return redirect()->route('colocations.show', $colocation_id)->with('success', 'Propriétaire transféré avec succès');
    }

    public function leave(string $colocation_id)
    {
        $user = User::find(Auth::user()->id);
        $balance = $user->getColocationBalance($colocation_id);
        
        if ($balance < 0) {
            $user->reputation_score -= 1;
            $user->save();
        }

        ColocationMember::where('colocation_id', $colocation_id)->where('user_id', Auth::user()->id)->update([
            'left_at' => now(),
        ]);
        
        return redirect()->route('user.dashboard')->with('success', 'Vous avez quitté la colocation');
    }

    public function remove_member(string $colocation_id, string $member_id)
    {
        $member = ColocationMember::where('colocation_id', $colocation_id)->where('user_id', $member_id)->first();
        if ($member) {
            $member->left_at = now();
            $member->save();
        }
        return redirect()->route('colocations.show', $colocation_id)->with('success', 'Member removed successfully!');
    }
    
}
<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;

class AdminDashboardController
{
    public function index()
    {
        $users = User::paginate(10);
        $totalUsers = User::count();
        $totalColocations = Colocation::count();
        $totalExpenses = Expense::sum('amount');
        $bannedUsers = User::where('is_banned', true)->count();

        return view('pages.admin.dashboard', compact('users', 'totalUsers', 'totalColocations', 'totalExpenses', 'bannedUsers'));
    }

    public function toggleBan(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous bannir vous-même.');
        }

        $user->update([
            'is_banned' => !$user->is_banned
        ]);

        $status = $user->is_banned ? 'banni' : 'débanni';
        return back()->with('success', "L'utilisateur {$user->name} a été {$status} avec succès.");
    }
}
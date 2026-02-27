@extends('layout')

@section('title', 'Administration Globale - EasyColoc')

@section('content')
<div class="max-w-7xl mx-auto mt-10 px-4">

    <div class="bg-gray-900 text-white p-6 rounded-xl shadow-sm mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Administration Globale 🛡️</h1>
            <p class="text-gray-400 mt-1">Supervision de la plateforme EasyColoc</p>
        </div>
        <div class="bg-gray-800 px-4 py-2 rounded-lg border border-gray-700">
            <span class="text-sm text-gray-400">Connecté en tant que : </span>
            <span class="font-bold text-white">{{ Auth::user()->name }}</span>
        </div>
    </div>

    @if(session('success'))
    <div
        class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm mb-6 flex items-center gap-3">
        <i class="ph-fill ph-check-circle text-xl text-green-500"></i>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div
        class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm mb-6 flex items-center gap-3">
        <i class="ph-fill ph-warning-circle text-xl text-red-500"></i>
        <p class="font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg text-2xl">👥</div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Utilisateurs</p>
                <p class="text-2xl font-black text-gray-900">{{ $totalUsers ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-green-100 text-green-600 rounded-lg text-2xl">🏠</div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Colocations</p>
                <p class="text-2xl font-black text-gray-900">{{ $totalColocations ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-purple-100 text-purple-600 rounded-lg text-2xl">💰</div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Dépenses totales</p>
                <p class="text-2xl font-black text-gray-900">{{ $totalExpenses ?? '0.00' }} <span
                        class="text-sm">MAD</span></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-red-100 text-red-600 rounded-lg text-2xl">🚫</div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Comptes bannis</p>
                <p class="text-2xl font-black text-gray-900">{{ $bannedUsers ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 p-6 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900">Gestion des Utilisateurs</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wide">
                        <th class="p-4 border-b border-gray-100 font-medium">ID</th>
                        <th class="p-4 border-b border-gray-100 font-medium">Utilisateur</th>
                        <th class="p-4 border-b border-gray-100 font-medium">Rôle global</th>
                        <th class="p-4 border-b border-gray-100 font-medium text-center">Réputation</th>
                        <th class="p-4 border-b border-gray-100 font-medium text-center">Statut</th>
                        <th class="p-4 border-b border-gray-100 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">

                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition {{ $user->is_banned ? 'bg-red-50/30' : '' }}">
                        <td class="p-4 text-gray-500 text-sm">#{{ $user->id }}</td>
                        <td class="p-4">
                            <p
                                class="font-bold {{ $user->is_banned ? 'text-gray-900 line-through text-gray-500' : 'text-gray-900' }}">
                                {{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </td>
                        <td class="p-4">
                            @if($user->role === 'admin')
                            <span
                                class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full">Admin</span>
                            @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-3 py-1 rounded-full">User</span>
                            @endif
                        </td>
                        <td
                            class="p-4 text-center font-bold {{ $user->reputation_score >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->reputation_score > 0 ? '+' : '' }}{{ $user->reputation_score }}
                        </td>
                        <td class="p-4 text-center">
                            @if($user->is_banned)
                            <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full">Banni</span>
                            @else
                            <span
                                class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">Actif</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            @if($user->id === Auth::id())
                            <span class="text-gray-400 text-sm italic">Intouchable</span>
                            @else
                            <form action="{{ route('admin.users.toggle_ban', $user->id) }}" method="POST"
                                class="inline">
                                @csrf
                                @if($user->is_banned)
                                <button type="submit"
                                    class="bg-green-50 hover:bg-green-100 text-green-600 font-medium px-4 py-2 rounded-lg border border-green-200 transition">
                                    Débannir
                                </button>
                                @else
                                <button type="submit"
                                    class="bg-red-50 hover:bg-red-100 text-red-600 font-medium px-4 py-2 rounded-lg border border-red-200 transition">
                                    Bannir
                                </button>
                                @endif
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 text-gray-500 text-sm text-center">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
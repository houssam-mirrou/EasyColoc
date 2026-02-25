@extends('layout')

@section('title', 'Ma Colocation - EasyColoc')

@section('content')
<div class="max-w-7xl mx-auto mt-10 px-4">

    <div
        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-blue-600">{{ $colocation->name }}</h1>
            <p class="text-gray-500 mt-1">Gérez vos dépenses communes et vos colocataires.</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ url('/balances') }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg transition">
                📊 Qui doit quoi à qui ?
            </a>

            @if(Auth::id() === $colocation->owner_id)
            <a href="{{ url('/colocations/settings') }}"
                class="bg-gray-900 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition">
                ⚙️ Paramètres (Gérer)
            </a>
            @else
            <form action="{{ url('/colocations/leave') }}" method="POST"
                onsubmit="return confirm('Voulez-vous vraiment quitter cette colocation ?');">
                @csrf
                <button type="submit"
                    class="bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 px-4 rounded-lg transition">
                    🚪 Quitter
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">👥 Membres Actifs</h2>

                <ul class="divide-y divide-gray-100">
                    @foreach($colocation->activeMembers as $member)
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $member->name }}
                                @if($member->id === Auth::id()) <span class="text-gray-400 text-sm">(Moi)</span> @endif
                            </p>
                            <p class="text-xs text-gray-500">
                                Réputation: <span
                                    class="{{ $member->reputation_score >= 0 ? 'text-green-600' : 'text-red-600' }} font-bold">{{
                                    $member->reputation_score }}</span>
                            </p>
                        </div>
                        <span
                            class="text-xs font-bold px-2 py-1 rounded-full uppercase tracking-wide {{ $member->id === $colocation->owner_id ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $member->id === $colocation->owner_id ? 'Owner' : 'Member' }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <h2 class="text-xl font-bold text-gray-900">💸 Historique des Dépenses</h2>

                    <div class="flex items-center gap-3">
                        <form action="{{ url('/colocations/show') }}" method="GET" class="flex gap-2">
                            <select name="month"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 bg-white">
                                <option value="">Tous les mois</option>
                                <option value="01" {{ request('month')=='01' ? 'selected' : '' }}>Janvier</option>
                                <option value="02" {{ request('month')=='02' ? 'selected' : '' }}>Février</option>
                                <option value="03" {{ request('month')=='03' ? 'selected' : '' }}>Mars</option>
                            </select>
                            <button type="submit"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-lg text-sm font-medium transition">Filtrer</button>
                        </form>

                        <a href="{{ url('/expenses/create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition text-sm">
                            + Ajouter
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm">
                                <th class="p-3 border-b border-gray-100">Date</th>
                                <th class="p-3 border-b border-gray-100">Titre</th>
                                <th class="p-3 border-b border-gray-100">Catégorie</th>
                                <th class="p-3 border-b border-gray-100">Payé par</th>
                                <th class="p-3 border-b border-gray-100 text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">

                            @forelse($expenses as $expense)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 text-sm text-gray-500">{{
                                    \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                                <td class="p-3 font-medium text-gray-900">{{ $expense->title }}</td>
                                <td class="p-3">
                                    <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-md">{{
                                        $expense->category->name }}</span>
                                </td>
                                <td class="p-3 text-sm text-gray-600">{{ $expense->payer->name }}</td>
                                <td class="p-3 text-right font-bold text-gray-900">{{ number_format($expense->amount, 2)
                                    }} MAD</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5"
                                    class="p-8 text-center text-gray-500 border border-dashed border-gray-200 rounded-lg">
                                    Aucune dépense trouvée pour ce mois.
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
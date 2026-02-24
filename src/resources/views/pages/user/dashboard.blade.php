@extends('layout')

@section('title', 'Tableau de bord - EasyColoc')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-600 mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Bienvenue, {{ Auth::user()->name }} 👋</h1>
            <p class="text-gray-600 mt-2">Votre score de réputation :
                <span class="font-bold {{ Auth::user()->reputation_score >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ Auth::user()->reputation_score > 0 ? '+' : '' }}{{ Auth::user()->reputation_score }}
                </span>
            </p>
        </div>
    </div>

    @if(!Auth::user()->currentColocation())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
            <div class="text-5xl mb-4">🏠</div>
            <h2 class="text-xl font-bold mb-2">Créer une colocation</h2>
            <p class="text-gray-500 mb-6 text-sm">Devenez l'administrateur (Owner) de votre groupe et invitez vos
                colocataires.</p>
            <a href="{{ route('colocations.create') }}"
                class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium p-3 rounded-lg transition">
                Créer ma colocation
            </a>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
            <div class="text-5xl mb-4">🤝</div>
            <h2 class="text-xl font-bold mb-2">Rejoindre une colocation</h2>
            <p class="text-gray-500 mb-6 text-sm">Vous avez reçu un code d'invitation ? Entrez-le ci-dessous.</p>
            <form action="{{ route('invitations.accept') }}" method="POST" class="flex flex-col gap-3">
                @csrf
                <input type="text" name="token" placeholder="Collez le token ici..."
                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    required>
                <button type="submit"
                    class="w-full bg-gray-900 hover:bg-gray-800 text-white font-medium p-3 rounded-lg transition">
                    Rejoindre la colocation
                </button>
            </form>
        </div>
    </div>

    @else
    @php
    $colocation = Auth::user()->currentColocation();
    @endphp

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 p-6 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900">
                Colocation : <span class="text-blue-600">{{ $colocation->name }}</span>
            </h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                {{ $colocation->owner_id === Auth::id() ? 'Owner' : 'Member' }}
            </span>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-center">

                <div class="p-4 border border-gray-100 rounded-lg bg-gray-50 flex flex-col justify-center">
                    <span class="block text-gray-500 mb-1 text-sm font-medium">Mon Solde Actuel</span>
                    <span class="text-3xl font-black text-gray-900">0.00 MAD</span>
                </div>

                <div class="p-4 flex flex-col justify-center">
                    <a href="{{ route('expenses.create') }}"
                        class="w-full p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-sm transition flex items-center justify-center gap-2">
                        <span>➕</span> Ajouter une dépense
                    </a>
                </div>

                <div class="p-4 flex flex-col justify-center">
                    <a href="{{ route('balances.index') }}"
                        class="w-full p-4 border-2 border-gray-200 hover:border-blue-500 hover:text-blue-600 text-gray-700 rounded-lg font-bold transition flex items-center justify-center gap-2">
                        <span>👁️</span> Qui doit à qui ?
                    </a>
                </div>
            </div>

            <h3 class="font-bold text-lg mb-4 text-gray-800">Dernières dépenses</h3>
            <div class="border border-gray-100 rounded-lg overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm">
                            <th class="p-4 border-b border-gray-100 font-medium">Date</th>
                            <th class="p-4 border-b border-gray-100 font-medium">Titre</th>
                            <th class="p-4 border-b border-gray-100 font-medium">Payé par</th>
                            <th class="p-4 border-b border-gray-100 font-bold text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 border-b border-gray-50 text-gray-500 text-sm">Aujourd'hui</td>
                            <td class="p-4 border-b border-gray-50 font-medium text-gray-900">Facture Internet</td>
                            <td class="p-4 border-b border-gray-50">Moi</td>
                            <td class="p-4 border-b border-gray-50 text-right font-bold text-gray-900">200.00 MAD</td>
                        </tr>
                        <tr>
                            <td class="p-4 text-center text-gray-500 text-sm" colspan="4">
                                <a href="{{ route('expenses.index') }}" class="text-blue-600 hover:underline">Voir tout
                                    l'historique</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
@extends('layout')

@section('title', 'Tableau de bord - EasyColoc')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md shadow-sm">
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm">
        <p class="text-red-700 font-medium">{{ session('error') }}</p>
    </div>
    @endif

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
    @if(isset($invitation) && $invitation->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">🔔 Vos invitations en attente</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($invitation as $inv)
            <div
                class="bg-blue-50 border border-blue-100 p-5 rounded-xl shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500">Invitation à rejoindre :</p>
                    <p class="text-lg font-bold text-blue-900">{{ $inv->colocation->name }}</p>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('invitations.decline') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $inv->token }}">
                        <button type="submit"
                            class="bg-white border border-red-200 text-red-600 hover:bg-red-50 font-medium py-2 px-4 rounded-lg transition">
                            Refuser
                        </button>
                    </form>
                    <form action="{{ route('invitations.accept') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $inv->token }}">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Accepter
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!isset($colocation) || !$colocation)
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
            <p class="text-gray-500 mb-6 text-sm">Vous avez reçu un code d'invitation manuellement ? Entrez-le
                ci-dessous.</p>
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

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div
            class="bg-gray-50 border-b border-gray-100 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-xl font-bold text-gray-900">
                Colocation : <span class="text-blue-600">{{ $colocation->name }}</span>
            </h2>

            <div class="flex items-center gap-3">
                <span
                    class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                    {{ $colocation->owner_id === Auth::id() ? 'Owner' : 'Member' }}
                </span>

                @if(Auth::id() === $colocation->owner_id)
                <a href="{{ url('/colocations/' . $colocation->id . '/settings') }}"
                    class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium py-1.5 px-3 rounded-lg transition flex items-center gap-1">
                    ⚙️ Paramètres
                </a>
                @endif
            </div>
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
        </div>
    </div>
    @endif
</div>
@endsection
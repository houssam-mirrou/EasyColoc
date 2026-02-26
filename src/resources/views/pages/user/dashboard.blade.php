@extends('layout')

@section('title', 'Tableau de bord - EasyColoc')

@section('content')
<div class="max-w-6xl mx-auto mt-6 px-4">

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

    <div
        class="bg-white p-6 rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Bienvenue, {{ Auth::user()->name }} 👋</h1>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-gray-500 font-medium text-sm">Score de réputation :</span>
                <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-sm font-bold {{ Auth::user()->reputation_score >= 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                    <i
                        class="{{ Auth::user()->reputation_score >= 0 ? 'ph-bold ph-trend-up' : 'ph-bold ph-trend-down' }}"></i>
                    {{ Auth::user()->reputation_score > 0 ? '+' : '' }}{{ Auth::user()->reputation_score }}
                </span>
            </div>
        </div>
    </div>

    @if(isset($invitation) && $invitation->count() > 0)
    <div class="mb-10">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="ph-fill ph-bell-ringing text-blue-600"></i>
            Vos invitations en attente
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($invitation as $inv)
            <div
                class="bg-white border-2 border-blue-100 p-5 rounded-2xl shadow-sm flex flex-col sm:flex-row justify-between items-center gap-5 hover:border-blue-200 transition-colors">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Invitation à rejoindre :</p>
                    <p class="text-xl font-black text-gray-900 mt-0.5">{{ $inv->colocation->name }}</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <form action="{{ route('invitations.decline') }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="token" value="{{ $inv->token }}">
                        <button type="submit"
                            class="w-full bg-white border border-gray-200 text-gray-600 hover:text-red-600 hover:border-red-200 hover:bg-red-50 font-bold py-2.5 px-4 rounded-xl transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-x"></i> Refuser
                        </button>
                    </form>
                    <form action="{{ route('invitations.accept') }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="token" value="{{ $inv->token }}">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-check"></i> Accepter
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
        <div
            class="bg-white p-8 rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100 text-center hover:shadow-lg transition-all group">
            <div
                class="w-20 h-20 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                <i class="ph-fill ph-house-line text-4xl"></i>
            </div>
            <h2 class="text-xl font-black text-gray-900 mb-2">Créer une colocation</h2>
            <p class="text-gray-500 mb-8 text-sm leading-relaxed">Devenez l'administrateur de votre groupe, configurez
                les règles et invitez vos colocataires.</p>
            <a href="{{ route('colocations.create') }}"
                class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold p-3.5 rounded-xl transition-colors shadow-sm">
                <i class="ph-bold ph-plus"></i> Créer ma colocation
            </a>
        </div>

        <div
            class="bg-white p-8 rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100 text-center hover:shadow-lg transition-all group">
            <div
                class="w-20 h-20 mx-auto bg-gray-50 text-gray-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                <i class="ph-fill ph-handshake text-4xl"></i>
            </div>
            <h2 class="text-xl font-black text-gray-900 mb-2">Rejoindre une colocation</h2>
            <p class="text-gray-500 mb-6 text-sm leading-relaxed">Vous avez reçu un code d'invitation manuellement ?
                Entrez-le ci-dessous pour intégrer le groupe.</p>
            <form action="{{ route('invitations.accept') }}" method="POST" class="flex flex-col gap-3">
                @csrf
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="ph ph-key text-gray-400 text-lg"></i>
                    </div>
                    <input type="text" name="token" placeholder="Collez le token ici..."
                        class="w-full pl-11 border border-gray-200 rounded-xl p-3.5 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all"
                        required>
                </div>
                <button type="submit"
                    class="flex items-center justify-center gap-2 w-full bg-gray-900 hover:bg-gray-800 text-white font-bold p-3.5 rounded-xl transition-colors">
                    <i class="ph-bold ph-arrow-right"></i> Rejoindre la colocation
                </button>
            </form>
        </div>
    </div>

    @else
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100 overflow-hidden">

        <div
            class="bg-gray-900 p-6 sm:px-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center text-blue-500 border border-gray-700">
                    <i class="ph-fill ph-house text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white">
                        {{ $colocation->name }}
                    </h2>
                    <p class="text-gray-400 text-sm font-medium">Tableau de bord du groupe</p>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide {{ $colocation->owner_id === Auth::id() ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 'bg-gray-800 text-gray-300 border border-gray-700' }}">
                    <i class="{{ $colocation->owner_id === Auth::id() ? 'ph-bold ph-crown' : 'ph-bold ph-user' }}"></i>
                    {{ $colocation->owner_id === Auth::id() ? 'Owner' : 'Member' }}
                </span>

                @if(Auth::id() === $colocation->owner_id)
                <a href="{{ url('/colocations/' . $colocation->id . '/settings') }}"
                    class="ml-auto sm:ml-0 bg-white/10 hover:bg-white/20 text-white text-sm font-bold py-2 px-3.5 rounded-lg transition-colors flex items-center gap-2">
                    <i class="ph-bold ph-gear"></i> Paramètres
                </a>
                @endif
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div
                    class="p-6 border border-gray-100 rounded-2xl bg-gray-50 flex flex-col justify-center items-center text-center">
                    <div
                        class="w-10 h-10 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-gray-400 mb-3">
                        <i class="ph-bold ph-wallet"></i>
                    </div>
                    <span class="block text-gray-500 mb-1 text-sm font-semibold uppercase tracking-wider">Mon Solde
                        Actuel</span>
                    <span class="text-4xl font-black text-gray-900 tracking-tight">0.00 <span
                            class="text-xl text-gray-400">MAD</span></span>
                </div>

                <div class="flex flex-col justify-center">
                    <a href="{{ route('expenses.create') }}"
                        class="group h-full flex flex-col items-center justify-center p-6 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                        <i class="ph-bold ph-receipt text-3xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <span class="font-bold text-lg">Ajouter une dépense</span>
                        <span class="text-blue-200 text-sm mt-1">Factures, courses, loyer...</span>
                    </a>
                </div>

                <div class="flex flex-col justify-center">
                    <a href="{{ route('balances.index') }}"
                        class="group h-full flex flex-col items-center justify-center p-6 border-2 border-gray-200 hover:border-blue-500 hover:bg-blue-50 text-gray-700 hover:text-blue-700 rounded-2xl transition-all">
                        <i
                            class="ph-bold ph-scales text-3xl mb-2 text-gray-400 group-hover:text-blue-500 group-hover:scale-110 transition-all"></i>
                        <span class="font-bold text-lg">Qui doit à qui ?</span>
                        <span class="text-gray-500 group-hover:text-blue-600 text-sm mt-1">Équilibrez les comptes</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
    @endif
</div>
@endsection
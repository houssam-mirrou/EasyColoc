@extends('layout')

@section('title', 'Tableau de bord - EasyColoc')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">

    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-600 mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Bienvenue, {{ Auth::user()->name ?? 'Utilisateur' }} 👋</h1>
        <p class="text-gray-600 mt-2">Que souhaitez-vous faire aujourd'hui ?</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
            <h2 class="text-xl font-bold mb-2">Créer une colocation</h2>
            <p class="text-gray-500 mb-6 text-sm">Devenez l'administrateur (Owner) de votre groupe et invitez vos
                colocataires.</p>
            <a href="{{ url('/colocations/create') }}"
                class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium p-3 rounded-lg">
                Créer une colocation
            </a>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
            <h2 class="text-xl font-bold mb-2">Rejoindre une colocation</h2>
            <p class="text-gray-500 mb-6 text-sm">Vous avez reçu un code d'invitation ? Entrez-le ci-dessous.</p>
            <form action="{{ url('/invitations/accept') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="token" placeholder="Token d'invitation"
                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    required>
                <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white font-medium px-4 py-3 rounded-lg">
                    Rejoindre
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
@extends('layout')

@section('title', 'Paramètres - ' . $colocation->name)

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">

    <div class="mb-8 flex items-center gap-4">
        <a href="{{ url('/colocations/' . $colocation->id) }}" class="text-gray-500 hover:text-blue-600 transition">
            &larr; Retour à la colocation
        </a>
        <h1 class="text-3xl font-black text-gray-900">Paramètres de la Colocation</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">💌 Inviter un membre</h2>
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="text-green-500 text-xl">✓</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
            <p class="text-sm text-gray-500 mb-4">Générez un token d'invitation pour permettre à un colocataire de vous
                rejoindre.</p>

            <form action="{{ route('invitations.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="colocation_id" value="{{ $colocation->id }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email du futur colocataire</label>
                    <input type="email" name="receiver_email"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none"
                        required>
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    Générer & Envoyer l'invitation
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">🏷️ Catégories de dépenses</h2>

            <form action="{{ route('colocations.category_settings', $colocation->id) }}" method="POST"
                class="flex gap-2 mb-4">
                @csrf
                <input type="text" name="name" placeholder="Nouvelle catégorie..."
                    class="flex-grow border border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none"
                    required>
                <button type="submit"
                    class="bg-gray-900 hover:bg-gray-800 text-white font-medium px-4 py-2 rounded-lg">Ajouter</button>
            </form>

            <ul class="divide-y divide-gray-100">
                @foreach($colocation->categories as $category)
                <li class="py-2 flex justify-between items-center text-sm">
                    <span class="font-medium text-gray-700">{{ $category->name }}</span>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 md:col-span-2">
            <h2 class="text-xl font-bold text-gray-900 mb-4">👥 Gestion des Membres</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-100">
                        @foreach($colocation->activeMembers as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 font-medium">{{ $member->name }}</td>
                            <td class="py-3 text-right">
                                @if($member->id !== Auth::id())
                                <form
                                    action="{{ url('/colocations/' . $colocation->id . '/remove-member/' . $member->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Attention : Si ce membre a des dettes, elles vous seront imputées selon les règles. Continuer ?');">
                                    @csrf
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition">
                                        Retirer de la colocation
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400 text-sm italic">Vous (Owner)</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-red-50 p-6 rounded-xl border border-red-200 md:col-span-2">
            <h2 class="text-xl font-bold text-red-800 mb-2">Zone de Danger</h2>
            <p class="text-sm text-red-600 mb-4">L'annulation de la colocation est irréversible. Toutes les dettes
                seront archivées.</p>
            <form action="{{ url('/colocations/' . $colocation->id . '/cancel') }}" method="POST"
                onsubmit="return confirm('Êtes-vous absolument sûr de vouloir annuler cette colocation ?');">
                @csrf
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    Annuler la Colocation
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
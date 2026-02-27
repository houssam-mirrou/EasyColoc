@extends('layout')

@section('title', 'Paramètres - ' . $colocation->name)

@section('content')
<div class="max-w-4xl mx-auto mt-6 px-4">

    <div class="mb-8">
        <a href="{{ route('user.dashboard') }}"
            class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm hover:shadow-md mb-6">
            <i class="ph-bold ph-arrow-left text-lg"></i>
            Retour au tableau de bord
        </a>
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center text-white shadow-sm">
                <i class="ph-fill ph-gear text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Paramètres</h1>
                <p class="text-gray-500 font-medium mt-1">Gérez votre colocation <span
                        class="text-blue-600 font-bold">{{ $colocation->name }}</span></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        @if(session('error'))
        <div class="md:col-span-2 bg-red-50 border border-red-200 p-4 rounded-xl flex items-start gap-3">
            <i class="ph-fill ph-warning-circle text-xl text-red-500 mt-0.5"></i>
            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
        </div>
        @endif

        <div
            class="bg-white p-6 sm:p-8 rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100 flex flex-col">
            <div class="flex items-center gap-2 mb-2">
                <i class="ph-fill ph-envelope-simple-open text-xl text-blue-500"></i>
                <h2 class="text-xl font-bold text-gray-900">Inviter un membre</h2>
            </div>
            <p class="text-sm text-gray-500 mb-6 leading-relaxed">Générez un token d'invitation et envoyez-le par email
                pour permettre à un colocataire de vous rejoindre.</p>

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 p-4 mb-6 rounded-xl flex items-start gap-3">
                <i class="ph-fill ph-check-circle text-xl text-green-500 mt-0.5"></i>
                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
            @endif

            <form action="{{ route('invitations.store') }}" method="POST" class="space-y-4 mt-auto">
                @csrf
                <input type="hidden" name="colocation_id" value="{{ $colocation->id }}">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email du futur colocataire</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph ph-at text-gray-400 text-lg"></i>
                        </div>
                        <input type="email" name="receiver_email" placeholder="nouveau@coloc.com"
                            class="w-full pl-11 border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                            required>
                    </div>
                </div>

                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <i class="ph-bold ph-paper-plane-right"></i> Envoyer l'invitation
                </button>
            </form>
        </div>

        <div
            class="bg-white p-6 sm:p-8 rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100 flex flex-col">
            <div class="flex items-center gap-2 mb-2">
                <i class="ph-fill ph-tags text-xl text-blue-500"></i>
                <h2 class="text-xl font-bold text-gray-900">Catégories de dépenses</h2>
            </div>
            <p class="text-sm text-gray-500 mb-6 leading-relaxed">Personnalisez les étiquettes pour mieux organiser vos
                dépenses de groupe.</p>

            <form action="{{ route('colocations.category_settings', $colocation->id) }}" method="POST"
                class="flex gap-2 mb-6">
                @csrf
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="ph ph-tag text-gray-400 text-lg"></i>
                    </div>
                    <input type="text" name="name" placeholder="Ex: Internet, Loyer..."
                        class="w-full pl-11 border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                        required>
                </div>
                <button type="submit"
                    class="bg-gray-900 hover:bg-gray-800 text-white font-bold px-4 py-3 rounded-xl transition-colors flex items-center gap-2 whitespace-nowrap shadow-sm">
                    <i class="ph-bold ph-plus"></i> Ajouter
                </button>
            </form>

            <div class="bg-gray-50 border border-gray-100 rounded-xl overflow-hidden mt-auto">
                <ul class="divide-y divide-gray-200 max-h-40 overflow-y-auto">
                    @foreach($colocation->categories as $category)
                    <li class="p-3 flex items-center gap-3 hover:bg-white transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="ph-fill ph-tag text-sm"></i>
                        </div>
                        <span class="font-semibold text-gray-700">{{ $category->name }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div
            class="bg-white p-6 sm:p-8 rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100 md:col-span-2">
            <div class="flex items-center gap-2 mb-6">
                <i class="ph-fill ph-users text-xl text-blue-500"></i>
                <h2 class="text-xl font-bold text-gray-900">Gestion des Membres</h2>
            </div>

            <div class="border border-gray-100 rounded-xl overflow-hidden bg-white">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4">Utilisateur</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($colocation->activeMembers as $member)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold border border-blue-200">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $member->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right align-middle">
                                <div class="flex justify-end items-center gap-2">
                                    @if($member->id !== Auth::id())
                                    <form
                                        action="{{ url('/colocations/' . $colocation->id . '/transfer-ownership/' . $member->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Êtes-vous sûr ? Vous ne serez plus l\'administrateur de cette colocation.');">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 text-sm font-bold bg-blue-50 hover:bg-blue-100 border border-blue-100 px-4 py-2 rounded-lg transition-colors">
                                            <i class="ph-bold ph-crown"></i> Nommer Owner
                                        </button>
                                    </form>

                                    <form
                                        action="{{ url('/colocations/' . $colocation->id . '/remove-member/' . $member->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Attention : Si ce membre a des dettes, elles vous seront imputées selon les règles. Continuer ?');">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 text-red-600 hover:text-red-800 text-sm font-bold bg-red-50 hover:bg-red-100 border border-red-100 px-4 py-2 rounded-lg transition-colors">
                                            <i class="ph-bold ph-trash"></i> Retirer
                                        </button>
                                    </form>
                                    @else
                                    <span
                                        class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1.5 rounded-lg text-sm font-bold">
                                        <i class="ph-bold ph-crown"></i> Vous (Owner)
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-[#FFF5F5] p-6 sm:p-8 rounded-2xl border border-red-200 md:col-span-2 relative overflow-hidden">
            <i
                class="ph-fill ph-warning absolute -right-4 -bottom-4 text-9xl text-red-500 opacity-5 pointer-events-none"></i>

            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex-grow">
                    <h2 class="text-xl font-black text-red-800 mb-1 flex items-center gap-2">
                        <i class="ph-bold ph-warning-circle text-2xl"></i> Zone de Danger
                    </h2>
                    <p class="text-sm font-medium text-red-600/80 mb-2">L'annulation de la colocation est irréversible.
                        Le
                        groupe sera dissous et toutes les dettes seront archivées.</p>

                    @if(Auth::user()->hasDebt())
                    <div class="bg-red-100 border border-red-300 p-3 rounded-xl flex items-start gap-2">
                        <i class="ph-fill ph-warning-circle text-lg text-red-600 mt-0.5"></i>
                        <p class="text-sm text-red-800 font-bold">Action impossible : Vous devez payer votre dette avant
                            de pouvoir annuler la colocation.</p>
                    </div>
                    @endif
                </div>

                <form action="{{ url('/colocations/' . $colocation->id . '/cancel') }}" method="POST"
                    onsubmit="return confirm('Êtes-vous absolument sûr de vouloir annuler cette colocation ? Cette action est définitive.');"
                    class="w-full sm:w-auto shrink-0">
                    @csrf
                    <button type="submit" @if(Auth::user()->hasDebt()) disabled @endif
                        class="w-full sm:w-auto flex items-center justify-center gap-2 {{ Auth::user()->hasDebt() ?
                        'bg-gray-400 cursor-not-allowed border-gray-400' : 'bg-red-600 hover:bg-red-700 border-red-700'
                        }} text-white font-bold py-3 px-6 rounded-xl shadow-sm hover:shadow-md transition-all border">
                        <i class="ph-bold ph-trash-simple"></i> Annuler la Colocation
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
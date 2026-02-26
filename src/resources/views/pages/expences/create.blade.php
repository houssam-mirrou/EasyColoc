@extends('layout')

@section('title', 'Ajouter une dépense - EasyColoc')

@section('content')
<div class="max-w-2xl mx-auto mt-6 px-4 pb-12">

    <div class="mb-8">
        <a href="{{ route('user.dashboard') }}"
            class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm hover:shadow-md mb-6">
            <i class="ph-bold ph-arrow-left text-lg"></i>
            Retour au tableau de bord
        </a>
        <div class="flex items-center gap-3">
            <div
                class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shadow-sm border border-blue-100">
                <i class="ph-fill ph-receipt text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Ajouter une dépense</h1>
                <p class="text-gray-500 font-medium mt-1">Enregistrez un nouvel achat pour la colocation.</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100">
        <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="title" class="block mb-2 text-sm font-semibold text-gray-700">Qu'avez-vous acheté ?</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="ph ph-shopping-cart text-gray-400 text-lg"></i>
                    </div>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        placeholder="Ex: Courses Carrefour, Facture d'eau, Netflix..."
                        class="w-full pl-11 border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                        required>
                </div>
                @error('title')
                <span class="flex items-center gap-1 text-red-500 text-sm mt-1.5 font-medium">
                    <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                </span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="amount" class="block mb-2 text-sm font-semibold text-gray-700">Montant (MAD)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph ph-coins text-gray-400 text-lg"></i>
                        </div>
                        <input type="number" step="0.01" min="0.1" name="amount" id="amount" value="{{ old('amount') }}"
                            placeholder="0.00"
                            class="w-full pl-11 border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                            required>
                    </div>
                    @error('amount')
                    <span class="flex items-center gap-1 text-red-500 text-sm mt-1.5 font-medium">
                        <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                    </span>
                    @enderror
                </div>

                <div>
                    <label for="expense_date" class="block mb-2 text-sm font-semibold text-gray-700">Date de
                        l'achat</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph ph-calendar-blank text-gray-400 text-lg"></i>
                        </div>
                        <input type="date" name="expense_date" id="expense_date"
                            value="{{ old('expense_date', date('Y-m-d')) }}"
                            class="w-full pl-11 border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                            required>
                    </div>
                    @error('expense_date')
                    <span class="flex items-center gap-1 text-red-500 text-sm mt-1.5 font-medium">
                        <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                    </span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block mb-2 text-sm font-semibold text-gray-700">Catégorie</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph ph-tag text-gray-400 text-lg"></i>
                        </div>
                        <select name="category_id" id="category_id"
                            class="w-full pl-11 appearance-none border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                            required>
                            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Choisir une
                                catégorie...</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' : ''
                                }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="ph-bold ph-caret-down text-gray-400"></i>
                        </div>
                    </div>
                    @error('category_id')
                    <span class="flex items-center gap-1 text-red-500 text-sm mt-1.5 font-medium">
                        <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                    </span>
                    @enderror
                </div>

                <div>
                    <label for="payer_id" class="block mb-2 text-sm font-semibold text-gray-700">Qui a payé ?</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph ph-user text-gray-400 text-lg"></i>
                        </div>
                        <select name="payer_id" id="payer_id"
                            class="w-full pl-11 appearance-none border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                            required>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('payer_id', Auth::id())==$member->id ? 'selected' :
                                '' }}>
                                {{ $member->name }} {{ $member->id === Auth::id() ? '(Moi)' : '' }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="ph-bold ph-caret-down text-gray-400"></i>
                        </div>
                    </div>
                    @error('payer_id')
                    <span class="flex items-center gap-1 text-red-500 text-sm mt-1.5 font-medium">
                        <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                    </span>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3 pt-6 mt-4 border-t border-gray-100">
                <a href="{{ route('user.dashboard') }}"
                    class="w-1/3 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold p-3.5 rounded-xl transition-colors">
                    Annuler
                </a>
                <button type="submit"
                    class="w-2/3 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold p-3.5 rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <span>Enregistrer la dépense</span>
                    <i class="ph-bold ph-check"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
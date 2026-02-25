@extends('layout')

@section('title', 'Ajouter une dépense - EasyColoc')

@section('content')
<div class="max-w-2xl mx-auto mt-10 px-4">

    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('user.dashboard') }}" class="text-gray-500 hover:text-blue-600 transition font-medium">
            &larr; Retour
        </a>
        <h1 class="text-3xl font-black text-gray-900">Ajouter une dépense 💸</h1>
    </div>

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="title" class="block mb-2 font-medium text-gray-700">Qu'avez-vous acheté ? (Titre)</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    placeholder="Ex: Courses Carrefour, Facture d'eau, Netflix..."
                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    required>
                @error('title')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="amount" class="block mb-2 font-medium text-gray-700">Montant (MAD)</label>
                    <input type="number" step="0.01" min="0.1" name="amount" id="amount" value="{{ old('amount') }}"
                        placeholder="0.00"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        required>
                    @error('amount')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="expense_date" class="block mb-2 font-medium text-gray-700">Date de l'achat</label>
                    <input type="date" name="expense_date" id="expense_date"
                        value="{{ old('expense_date', date('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        required>
                    @error('expense_date')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block mb-2 font-medium text-gray-700">Catégorie</label>
                    <select name="category_id" id="category_id"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white"
                        required>
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Choisir une catégorie...
                        </option>

                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="payer_id" class="block mb-2 font-medium text-gray-700">Qui a payé ?</label>
                    <select name="payer_id" id="payer_id"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white"
                        required>
                        @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ old('payer_id', Auth::id())==$member->id ? 'selected' : ''
                            }}>
                            {{ $member->name }} {{ $member->id === Auth::id() ? '(Moi)' : '' }}
                        </option>
                        @endforeach
                    </select>
                    @error('payer_id')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <a href="{{ url('/dashboard') }}"
                    class="w-1/3 text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold p-3 rounded-lg transition">
                    Annuler
                </a>
                <button type="submit"
                    class="w-2/3 bg-blue-600 text-white font-bold p-3 rounded-lg hover:bg-blue-700 transition shadow-sm">
                    Enregistrer la dépense
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
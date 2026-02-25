@extends('layout')

@section('title', 'Créer une Colocation - EasyColoc')

@section('content')
<div class="max-w-md mx-auto mt-16 px-4">
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">

        <div class="text-center mb-8">
            <div class="text-5xl mb-3">🏠</div>
            <h2 class="text-2xl font-bold text-gray-900">Nouvelle Colocation</h2>
            <p class="text-gray-500 text-sm mt-2">
                Donnez un nom à votre groupe. En le créant, vous en deviendrez automatiquement l'administrateur (Owner).
            </p>
        </div>

        <form action="{{ url('/colocations') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block mb-2 font-medium text-gray-700">Nom de la colocation</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    placeholder="Ex: La Villa des Potes..."
                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                    required autofocus>

                @error('name')
                <span class="text-red-500 text-sm mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ url('/dashboard') }}"
                    class="w-1/3 text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold p-3 rounded-lg transition">
                    Annuler
                </a>
                <button type="submit"
                    class="w-2/3 bg-blue-600 text-white font-bold p-3 rounded-lg hover:bg-blue-700 transition shadow-sm">
                    Créer le groupe
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
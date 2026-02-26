@extends('layout')

@section('title', 'Créer une Colocation - EasyColoc')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-160px)] px-4 py-8">

    <div
        class="w-full max-w-md bg-white p-8 sm:p-10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4">
                <i class="ph-fill ph-house-line text-4xl"></i>
            </div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Nouvelle Colocation</h2>
            <p class="text-sm text-gray-500 font-medium mt-2 leading-relaxed">
                Donnez un nom à votre groupe. En le créant, vous en deviendrez automatiquement l'administrateur (Owner).
            </p>
        </div>

        <form action="{{ url('/colocations') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block mb-2 text-sm font-semibold text-gray-700">Nom de la colocation</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="ph ph-house text-gray-400 text-lg"></i>
                    </div>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Ex: La Villa des Potes..."
                        class="w-full pl-11 border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                        required autofocus>
                </div>

                @error('name')
                <span class="flex items-center gap-1 text-red-500 text-sm mt-1.5 font-medium">
                    <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                </span>
                @enderror
            </div>

            <div class="flex gap-3 pt-4 mt-2">
                <a href="{{ url('/dashboard') }}"
                    class="w-1/3 flex items-center justify-center text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold p-3.5 rounded-xl transition-colors">
                    Annuler
                </a>
                <button type="submit"
                    class="w-2/3 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold p-3.5 rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <span>Créer le groupe</span>
                    <i class="ph-bold ph-arrow-right"></i>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
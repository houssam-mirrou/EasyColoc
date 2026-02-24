@extends('layout')

@section('title', 'Accueil - EasyColoc')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-20 text-center">
    <h1 class="text-5xl md:text-6xl font-extrabold text-blue-600 mb-6 tracking-tight">
        Fini les embrouilles d'argent entre colocs
    </h1>
    <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
        EasyColoc suit vos dépenses communes et calcule automatiquement qui doit quoi à qui. Simple, rapide et
        transparent.
    </p>

    <div class="flex justify-center gap-4">
        <a href="{{ url('/register') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl text-lg font-bold shadow-lg">
            Commencer maintenant
        </a>
        <a href="{{ url('/login') }}"
            class="bg-white border-2 border-gray-200 hover:border-blue-600 hover:text-blue-600 text-gray-800 px-8 py-4 rounded-xl text-lg font-bold shadow-sm">
            J'ai déjà un compte
        </a>
    </div>
</div>
@endsection
@extends('layout')

@section('title', 'Mon Profil - EasyColoc')

@section('content')
<div class="max-w-4xl mx-auto mt-6 px-4">
    <div class="bg-white p-8 rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100 mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-6">Mon Profil</h1>

        <div class="flex items-center gap-6 mb-8">
            <div
                class="w-24 h-24 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-4xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-500">{{ $user->email }}</p>
                <div class="mt-2 text-sm">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-bold bg-blue-50 text-blue-600">
                        Rôle: {{ ucfirst($user->role ?? 'Utilisateur') }}
                    </span>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-bold {{ $user->reputation_score >= 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} ml-2">
                        Score: {{ $user->reputation_score > 0 ? '+' : '' }}{{ $user->reputation_score }}
                    </span>
                </div>
            </div>
        </div>

        <hr class="border-gray-100 mb-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-info text-blue-600"></i> Informations
                </h3>
                <ul class="space-y-3 text-gray-600">
                    <li class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="font-medium text-gray-500">Membre depuis</span>
                        <span class="font-semibold">{{ $user->created_at->format('d/m/Y') }}</span>
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-gear text-blue-600"></i> Actions
                </h3>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('home') }}"
                        class="flex items-center justify-center gap-2 w-full bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-700 font-bold p-3 rounded-xl transition-colors">
                        <i class="ph-bold ph-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
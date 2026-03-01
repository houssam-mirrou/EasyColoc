@extends('layout')

@section('title', 'Mon Profil - EasyColoc')

@section('content')
<div class="max-w-5xl mx-auto mt-6 px-4 pb-12">

    <!-- Hero Header -->
    <div
        class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-gray-100/60 overflow-hidden mb-10 relative">
        <div class="h-40 bg-gradient-to-br from-indigo-600 via-blue-600 to-cyan-500 relative">
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 p-12 opacity-20 pointer-events-none translate-x-1/4 -translate-y-1/4">
                <div class="w-64 h-64 rounded-full border-[1.5rem] border-white blur-sm"></div>
            </div>
            <div class="absolute bottom-0 left-10 p-12 opacity-10 pointer-events-none translate-y-1/2">
                <div class="w-40 h-40 rounded-full bg-white blur-xl"></div>
            </div>
        </div>

        <div class="px-8 pb-10 flex flex-col sm:flex-row gap-6 items-center sm:items-end -mt-16 relative z-10">
            <div class="w-32 h-32 bg-white rounded-full p-2 shadow-xl relative group">
                <div
                    class="w-full h-full bg-gradient-to-tr from-blue-50 to-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-5xl font-black border border-indigo-100/50 group-hover:scale-105 transition-transform duration-300">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <!-- Status dot -->
                <div class="absolute bottom-3 right-3 w-5 h-5 bg-green-500 border-4 border-white rounded-full"></div>
            </div>

            <div class="flex-1 text-center sm:text-left mb-2 sm:mb-4">
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">{{ $user->name }}</h1>
                <p class="text-gray-500 font-medium text-lg mt-1">{{ $user->email }}</p>
            </div>

            <div class="flex gap-3 mb-2 sm:mb-4">
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl font-bold bg-gray-900 text-white shadow-md">
                    <i class="ph-fill ph-identification-card text-blue-400"></i> {{ ucfirst($user->role ??
                    'Utilisateur') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        <!-- Reputation Score -->
        <div
            class="bg-white p-6 rounded-[2rem] shadow-[0_2px_20px_rgb(0,0,0,0.03)] border border-gray-100/80 flex items-center gap-5 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300 group">
            <div
                class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl font-bold shadow-inner {{ $user->reputation_score >= 0 ? 'bg-green-50 text-green-500 border border-green-100/50' : 'bg-red-50 text-red-500 border border-red-100/50' }} group-hover:scale-110 transition-transform">
                <i class="{{ $user->reputation_score >= 0 ? 'ph-fill ph-star' : 'ph-fill ph-warning-circle' }}"></i>
            </div>
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Réputation</p>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight">
                    {{ $user->reputation_score > 0 ? '+' : '' }}{{ $user->reputation_score }}
                </h3>
            </div>
        </div>

        <!-- Total Colocations -->
        <div
            class="bg-white p-6 rounded-[2rem] shadow-[0_2px_20px_rgb(0,0,0,0.03)] border border-gray-100/80 flex items-center gap-5 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300 group">
            <div
                class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-3xl font-bold border border-blue-100/50 shadow-inner group-hover:scale-110 transition-transform">
                <i class="ph-fill ph-house-line"></i>
            </div>
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Colocations Actives</p>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight">
                    {{ $totalColocations }}
                </h3>
            </div>
        </div>

        <!-- Total Paid -->
        <div
            class="bg-white p-6 rounded-[2rem] shadow-[0_2px_20px_rgb(0,0,0,0.03)] border border-gray-100/80 flex items-center gap-5 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300 group">
            <div
                class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-3xl font-bold border border-indigo-100/50 shadow-inner group-hover:scale-110 transition-transform">
                <i class="ph-fill ph-wallet"></i>
            </div>
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Dépenses Payées</p>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight">
                    {{ number_format($totalPaid, 2) }} <span class="text-lg text-gray-300">MAD</span>
                </h3>
            </div>
        </div>

    </div>

    <!-- Info Sections -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Personal Information -->
        <div
            class="bg-white p-8 rounded-[2rem] shadow-[0_2px_20px_rgb(0,0,0,0.03)] border border-gray-100/80 flex flex-col h-full hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300">
            <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="ph-fill ph-user-circle"></i>
                </div>
                Informations
            </h3>

            <div class="space-y-2 flex-1 relative">
                <!-- Line behind items -->
                <div class="absolute left-[9px] top-6 bottom-6 w-0.5 bg-gray-100"></div>

                <div class="flex flex-col sm:flex-row sm:items-center relative pl-8 py-3 group">
                    <div
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-white border-[5px] border-gray-100 group-hover:border-blue-500 transition-colors z-10">
                    </div>
                    <span class="w-1/3 font-bold text-gray-400 text-xs uppercase tracking-widest">Nom Complet</span>
                    <span class="flex-1 font-bold text-gray-900 text-right">{{ $user->name }}</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center relative pl-8 py-3 group">
                    <div
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-white border-[5px] border-gray-100 group-hover:border-blue-500 transition-colors z-10">
                    </div>
                    <span class="w-1/3 font-bold text-gray-400 text-xs uppercase tracking-widest">Adresse E-mail</span>
                    <span class="flex-1 font-bold text-gray-900 text-right">{{ $user->email }}</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center relative pl-8 py-3 group">
                    <div
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-white border-[5px] border-gray-100 group-hover:border-green-500 transition-colors z-10">
                    </div>
                    <span class="w-1/3 font-bold text-gray-400 text-xs uppercase tracking-widest">Membre Depuis</span>
                    <span class="flex-1 font-bold text-gray-900 text-right">{{ $user->created_at->format('d F Y')
                        }}</span>
                </div>
            </div>
        </div>

        <!-- Manage Account Card -->
        <div class="bg-gray-900 p-8 rounded-[2rem] shadow-xl flex flex-col relative overflow-hidden h-full group">
            <!-- Animated background gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/50 relative z-0"></div>

            <!-- Large background icon -->
            <div
                class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none group-hover:scale-110 group-hover:rotate-12 transition-transform duration-700">
                <i class="ph-fill ph-shield-check text-[10rem] text-white"></i>
            </div>

            <div class="relative z-10">
                <div
                    class="w-12 h-12 rounded-xl bg-white/10 text-white flex items-center justify-center backdrop-blur-md mb-6 border border-white/10">
                    <i class="ph-fill ph-lock-key text-xl"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3">Sécurité & Paramètres</h3>
                <p class="text-gray-400 text-sm font-medium leading-relaxed mb-8">
                    Gérez vos informations de compte, vos préférences de colocation et vos paramètres de sécurité ici.
                </p>
            </div>

            <div class="flex flex-col gap-4 mt-auto relative z-10">
                <button onclick="alert('Cette fonctionnalité arrivera bientôt !')"
                    class="w-full flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 text-white font-bold py-4 px-6 rounded-2xl transition-all border border-white/10 hover:border-white/20 backdrop-blur-sm">
                    <i class="ph-bold ph-key"></i> Changer le mot de passe
                </button>
                <a href="{{ route('user.dashboard') }}"
                    class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 px-6 rounded-2xl shadow-lg transition-all hover:-translate-y-1 hover:shadow-blue-500/25">
                    <i class="ph-bold ph-arrow-left"></i> Retour à l'accueil
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
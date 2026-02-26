@extends('layout')

@section('title', 'Inscription - EasyColoc')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-160px)] px-4 py-8">

    <div
        class="w-full max-w-md bg-white p-8 sm:p-10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4">
                <i class="ph-fill ph-user-plus text-4xl"></i>
            </div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Créer un compte</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">Rejoignez EasyColoc dès aujourd'hui 🚀</p>
        </div>

        <form action="{{ route('register.handle') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700">Nom complet</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="ph ph-user text-gray-400 text-lg"></i>
                    </div>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe"
                        class="w-full pl-11 border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                        required>
                </div>
                @error('name')
                <span class="flex items-center gap-1 text-red-500 text-sm mt-1.5 font-medium">
                    <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                </span>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700">Adresse Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="ph ph-envelope-simple text-gray-400 text-lg"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="vous@exemple.com"
                        class="w-full pl-11 border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                        required>
                </div>
                @error('email')
                <span class="flex items-center gap-1 text-red-500 text-sm mt-1.5 font-medium">
                    <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                </span>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700">Mot de passe</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="ph ph-lock-key text-gray-400 text-lg"></i>
                    </div>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full pl-11 border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                        required>
                </div>
                @error('password')
                <span class="flex items-center gap-1 text-red-500 text-sm mt-1.5 font-medium">
                    <i class="ph-fill ph-warning-circle"></i> {{ $message }}
                </span>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700">Confirmer le mot de passe</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="ph ph-check-circle text-gray-400 text-lg"></i>
                    </div>
                    <input type="password" name="password_confirmation" placeholder="••••••••"
                        class="w-full pl-11 border border-gray-200 rounded-xl p-3 text-gray-900 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                        required>
                </div>
            </div>

            <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white font-bold text-lg p-3.5 rounded-xl hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/25 hover:-translate-y-0.5 transition-all duration-200 mt-4">
                <span>Créer mon compte</span>
                <i class="ph-bold ph-arrow-right"></i>
            </button>

            <p class="text-center text-sm text-gray-500 mt-6 font-medium">
                Vous avez déjà un compte ?
                <a href="{{ url('/login') }}"
                    class="text-blue-600 hover:text-blue-700 hover:underline transition-colors">Connectez-vous</a>
            </p>
        </form>
    </div>
</div>
@endsection
@extends('layout')

@section('title', 'Inscription - EasyColoc')

@section('content')
<div class="max-w-md mx-auto mt-16 bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Créer un compte</h2>

    <form action="{{ route('register.handle') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block mb-1 font-medium text-gray-700">Nom</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                required>
            @error('name')
            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                required>
            @error('email')
            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium text-gray-700">Mot de passe</label>
            <input type="password" name="password"
                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                required>
            @error('password')
            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium text-gray-700">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation"
                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                required>
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white font-bold p-3 rounded-lg hover:bg-blue-700 transition">
            S'inscrire
        </button>
    </form>
</div>
@endsection
@extends('layout')

@section('title', 'Connexion - EasyColoc')

@section('content')
<div class="max-w-md mx-auto mt-16 bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Connexion</h2>

    <form action="{{ route('login.handle') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block mb-1 font-medium text-gray-700">Email</label>
            <input type="email" name="email"
                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                required>
        </div>
        <div>
            <label class="block mb-1 font-medium text-gray-700">Mot de passe</label>
            <input type="password" name="password"
                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                required>
        </div>
        <button type="submit"
            class="w-full bg-blue-600 text-white font-bold p-3 rounded-lg hover:bg-blue-700 transition">
            Se connecter
        </button>
    </form>
</div>
@endsection
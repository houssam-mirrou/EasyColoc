<nav class="bg-white shadow p-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-2xl font-black text-blue-600">
            EasyColoc
        </a>

        <div>
            @guest
            <a href="{{ url('/login') }}" class="mr-4 font-medium text-gray-600 hover:text-blue-600">Connexion</a>
            <a href="{{ url('/register') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">Inscription</a>
            @endguest

            @auth
            <a href="{{ url('/dashboard') }}" class="mr-4 font-medium text-gray-600 hover:text-blue-600">Tableau de
                bord</a>
            <form action="{{ url('/logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-red-500 font-medium hover:text-red-700">Déconnexion</button>
            </form>
            @endauth
        </div>
    </div>
</nav>
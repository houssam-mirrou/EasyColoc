<nav class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">

        <a href="{{ url('/') }}"
            class="flex items-center gap-2 text-2xl font-black text-blue-600 hover:text-blue-700 transition-colors">
            <i class="ph-fill ph-house-line text-3xl"></i>
            <span>Easy<span class="text-gray-900">Coloc</span></span>
        </a>

        <div class="flex items-center gap-4 sm:gap-6">
            @guest
            <a href="{{ url('/login') }}"
                class="flex items-center gap-1.5 font-semibold text-gray-600 hover:text-blue-600 transition-colors">
                <i class="ph ph-sign-in text-xl"></i>
                <span class="hidden sm:inline">Connexion</span>
            </a>
            <a href="{{ url('/register') }}"
                class="flex items-center gap-1.5 bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-blue-700 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                <i class="ph ph-user-plus text-xl"></i>
                <span>Inscription</span>
            </a>
            @endguest

            @auth
            <a href="{{ Auth::user()->role === 'admin' ? url('/admin/dashboard') : url('/user/dashboard') }}"
                class="flex items-center gap-2 font-semibold text-gray-600 hover:text-blue-600 transition-colors">
                <i class="ph ph-squares-four text-xl"></i>
                <span class="hidden sm:inline">Tableau de bord</span>
            </a>

            <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>

            <form action="{{ url('/logout') }}" method="POST" class="inline m-0">
                @csrf
                <button type="submit"
                    class="flex items-center gap-1.5 text-red-500 font-semibold hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors">
                    <i class="ph ph-sign-out text-xl"></i>
                    <span class="hidden sm:inline">Déconnexion</span>
                </button>
            </form>
            @endauth
        </div>
    </div>
</nav>
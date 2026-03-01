<nav class="fixed top-0 left-0 h-screen w-64 bg-white border-r border-gray-100 shadow-sm flex flex-col z-50">

    <div class="h-20 flex items-center px-6 border-b border-gray-50">
        <a href="{{ Auth::check() ? (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard')) : url('/') }}"
            class="flex items-center gap-2 text-2xl font-black text-blue-600 hover:text-blue-700 transition-colors">
            <i class="ph-fill ph-house-line text-3xl"></i>
            <span>Easy<span class="text-gray-900">Coloc</span></span>
        </a>
    </div>

    <div class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">

        @guest
        <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Accès</p>
        <a href="{{ url('/login') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
            <i class="ph ph-sign-in text-xl"></i> Connexion
        </a>
        <a href="{{ url('/register') }}"
            class="flex items-center gap-3 px-3 py-2.5 mt-1 rounded-xl font-bold bg-blue-600 text-white hover:bg-blue-700 transition-colors shadow-sm">
            <i class="ph ph-user-plus text-xl"></i> Inscription
        </a>
        @endguest

        @auth
        <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Application</p>

        <a href="{{ url('/user/dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
            <i class="ph ph-squares-four text-xl"></i> Tableau de bord
        </a>

        <a href="{{ url('/colocations') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
            <i class="ph ph-users text-xl"></i> Colocations
        </a>

        @if(Auth::user()->role === 'admin')
        <a href="{{ url('/admin/dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
            <i class="ph ph-shield-check text-xl"></i> Espace Admin
        </a>
        @endif

        <a href="{{ url('/profile') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
            <i class="ph ph-user text-xl"></i> Mon Profil
        </a>
        @endauth

    </div>

    @auth
    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
        <form action="{{ url('/logout') }}" method="POST" class="w-full m-0">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-red-600 hover:bg-red-50 transition-colors border border-transparent hover:border-red-100">
                <i class="ph ph-sign-out text-xl"></i> Déconnexion
            </button>
        </form>
    </div>
    @endauth
</nav>
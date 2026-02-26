<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EasyColoc')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>

<body
    class="bg-[#f8fafc] text-gray-800 font-sans antialiased selection:bg-blue-200 selection:text-blue-900 flex flex-col min-h-screen">

    @include('partials.navbar')

    <main class="flex-grow w-full">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.02)]">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-center text-sm text-gray-400 font-medium gap-1">
            <i class="ph ph-copyright text-base"></i>
            <span>{{ date('Y') }} EasyColoc - Gestion de colocation.</span>
        </div>
    </footer>

</body>

</html>
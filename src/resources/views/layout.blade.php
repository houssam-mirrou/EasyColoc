<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EasyColoc')</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    @include('partials.navbar')

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t py-4 text-center text-sm text-gray-500 mt-auto">
        &copy; {{ date('Y') }} EasyColoc - Gestion de colocation.
    </footer>

</body>

</html>
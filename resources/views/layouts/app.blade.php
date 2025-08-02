<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OCC')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-light">
    <div class="flex min-h-screen">
        @include('components.layouts.app.sidebar')
        <main class="flex-1 bg-gray-50 p-8 min-h-screen">
            @yield('content')
        </main>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">
<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        @if (isset($slot))
            {{ $slot }}
        @elseif (View::hasSection('content'))
            @yield('content')
        @endif
    </flux:main>
</x-layouts.app.sidebar>
@stack('scripts')
</body>
</html>

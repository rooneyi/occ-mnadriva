<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-700 text-white flex flex-col py-8 px-4 shadow-lg">
        <div class="mb-8 flex items-center space-x-2">
            <span class="font-bold text-2xl tracking-wide">Mon Tableau de Bord</span>
        </div>
        <nav class="flex-1 space-y-2">
            <a href="{{ route('client.dashboard') }}" class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Accueil</a>
            <a href="{{ route('client.declaration') }}" class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Déclaration</a>
            <a href="{{ route('client.declaration') }}#produits" class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Produits</a>
            <a href="{{ route('client.declaration') }}#licence" class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Joindre une licence</a>
            <!-- Notifications -->
            <a href="#" class="block py-2 px-3 rounded opacity-50 cursor-not-allowed" title="Bientôt disponible">Notifications</a>
            <a href="{{ route('client.rapport.download', ['rapportId' => 1]) }}" class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Rapport d'analyse</a>
        </nav>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 bg-gray-50 p-8 min-h-screen">
        {{ $slot }}
    </main>
</div>

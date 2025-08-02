<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-700 text-white flex flex-col py-8 px-4 shadow-lg">
        <div class="mb-8 flex items-center space-x-2">
            <span class="font-bold text-2xl tracking-wide"></span>
        </div>
        <nav class="flex-1 space-y-2">
            @php use Illuminate\Support\Facades\Auth;$role = Auth::user()->role ?? null; @endphp
            @if($role === 'client')
                <a href="{{ route('client.dashboard') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Accueil</a>
                <a href="{{ route('client.declaration') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Déclaration</a>
                <a href="{{ route('client.declaration') }}#produits"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Produits</a>
                <a href="{{ route('client.declaration') }}#licence"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Joindre une licence</a>
                <a href="{{ route('client.notifications') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Notifications</a>
                <a href="{{ route('client.rapport.download', ['rapportId' => 1]) }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Rapport d'analyse</a>
            @elseif($role === 'controleur')
                @php
                    $user = Auth::user();
                    $dashboardRoute = '#';
                    if ($user) {
                        switch ($user->role) {
                            case 'controleur':
                                $dashboardRoute = route('controleur.dashboard');
                                break;
                            case 'laborantin':
                                $dashboardRoute = route('laborantin.dashboard');
                                break;
                            case 'chef_service':
                                $dashboardRoute = route('chefservice.dashboard');
                                break;
                            case 'client':
                            default:
                                $dashboardRoute = route('client.dashboard');
                                break;
                        }
                    }
                @endphp
                <a href="{{ $dashboardRoute }}" class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Tableau
                    de bord</a>
                <a href="{{ route('controleur.produits.create') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Ajouter un produit</a>
                <a href="{{ route('controleur.demandes') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Demandes assignées</a>
                <a href="{{ route('controleur.produits.photos') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Prendre des photos du
                    produit</a>
                <a href="{{ route('controleur.produits.commentaires') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Ajouter des commentaires sur
                    un produit</a>
                <a href="{{ route('controleur.produits.validation') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Valider ou rejeter un
                    produit</a>
                <a href="{{ route('controleur.notifications') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Notifications</a>
            @elseif($role === 'laborantin')
                <a href="{{ route('laborantin.dashboard') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Tableau de bord Laborantin</a>
                <a href="{{ route('laborantin.historique') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Historique des analyses</a>
                <a href="{{ route('laborantin.analyse.form') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Remplir un formulaire d'analyse</a>
            @elseif($role === 'chef_service')
                <a href="{{ route('chefservice.dashboard') }}"
                   class="block py-2 px-3 rounded transition hover:bg-blue-800 hover:pl-5">Tableau de bord</a>
            @endif
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="mt-8">
            @csrf
            <button type="submit"
                    class="flex items-center w-full py-2 px-3 rounded transition hover:bg-red-700 bg-red-600 text-white font-semibold mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                </svg>
                Déconnexion
            </button>
        </form>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 bg-gray-50 p-8 min-h-screen">
    </main>
</div>

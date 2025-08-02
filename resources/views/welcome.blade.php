<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCC - Office Congolais de Contrôle</title>
    <link rel="icon" href="/favicon.ico">
    @vite('resources/css/app.css')
    <style>
        .produits-list { columns: 2; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
<!-- Include this script tag or install `@tailwindplus/elements` via npm: -->
<!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script> -->
<div class="bg-white">
  <header class="absolute mx-auto max-w-7xl inset-x-0 top-0 z-50">
    <nav aria-label="Global" class="flex items-center justify-between p-6 lg:px-8">
      <div class="flex lg:flex-1">
        <a href="{{ route('home') }}" class="-m-1.5 p-1.5 flex items-center gap-2">
          <img src="/apple-touch-icon.png" alt="OCC" class="h-8 w-auto" />
          <span class="font-bold text-blue-900 text-lg tracking-wider">OCC</span>
        </a>
      </div>
      <div class="hidden lg:flex lg:gap-x-12">
        <a href="/" class="text-base font-semibold text-blue-900 hover:text-yellow-500 transition">Accueil</a>
        <a href="{{ route('client.register') }}" class="text-base font-semibold text-blue-900 hover:text-yellow-500 transition">Créer un compte client</a>
        <a href="{{ route('login') }}" class="text-base font-semibold text-white bg-blue-900 px-4 py-2 rounded shadow hover:bg-yellow-500 hover:text-blue-900 transition">Connexion</a>
      </div>
      <div class="flex lg:hidden">
        <!-- Mobile menu button (optionnel) -->
      </div>
    </nav>
  </header>
  <div class="relative isolate px-6 pt-14 lg:px-8">
    <div aria-hidden="true" class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
      <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-288.75"></div>
    </div>
    <div class="mx-auto max-w-7xl py-32 sm:py-48 lg:py-56">
{{--      <div class="hidden sm:mb-8 sm:flex sm:justify-center">--}}
{{--        <div class="relative rounded-full px-3 py-1 text-sm/6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20">--}}
{{--          Announcing our next round of funding. <a href="#" class="font-semibold text-indigo-600"><span aria-hidden="true" class="absolute inset-0"></span>Read more <span aria-hidden="true">&rarr;</span></a>--}}
{{--        </div>--}}
{{--      </div>--}}
      <div class="text-center">
        <h1 class="text-5xl font-extrabold tracking-tight text-blue-900 sm:text-7xl mb-4">OCC - Office Congolais de Contrôle</h1>
        <p class="mt-8 text-lg font-medium text-blue-800 sm:text-xl/8">
          L’OCC est l’organisme officiel chargé du contrôle, de l’inspection et de la certification des marchandises en République Démocratique du Congo.<br>
          Cette application permet d’automatiser la gestion des contrôles, d’assurer la traçabilité et la sécurité des produits importés ou fabriqués localement.
        </p>
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-x-6">
          <a href="{{ route('client.register') }}" class="rounded-md bg-yellow-100 px-6 py-3 text-base font-semibold text-blue-900 shadow hover:bg-yellow-200 transition mb-2 sm:mb-0">Créer un compte client</a>
          <a href="{{ route('login') }}" class="rounded-md border border-blue-900 px-6 py-3 text-base font-semibold text-blue-900 hover:bg-blue-900 hover:text-white transition">Connexion client</a>
        </div>
      </div>
    </div>
    <div class="max-w-7xl mx-auto mt-10 mb-16 grid md:grid-cols-3 gap-8">
      <div class="bg-yellow-50 rounded-xl shadow p-6 flex flex-col items-center">
        <h3 class="text-xl font-bold text-blue-900 mb-2">Espace Client</h3>
        <p class="text-blue-800 text-center mb-2">Déclarez vos marchandises, recevez une confirmation et suivez l’état de votre demande.</p>
      </div>
      <div class="bg-blue-50 rounded-xl shadow p-6 flex flex-col items-center">
        <h3 class="text-xl font-bold text-blue-900 mb-2">Espace Contrôleur</h3>
        <p class="text-blue-800 text-center mb-2">Consultez les demandes, scannez les produits, vérifiez la validité et prenez une décision.</p>
      </div>
      <div class="bg-yellow-100 rounded-xl shadow p-6 flex flex-col items-center">
        <h3 class="text-xl font-bold text-blue-900 mb-2">Espace Laboratoire</h3>
        <p class="text-blue-800 text-center mb-2">Analysez les produits, remplissez le formulaire de résultats, transmettez vos conclusions.</p>
      </div>
    </div>
  </div>
</div>
</body>
</html>

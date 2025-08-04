<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion OCC</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-yellow-100 via-blue-50 to-blue-200 min-h-screen flex flex-col justify-center items-center">
<div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 mt-16">
    <h2 class="text-2xl font-bold text-blue-900 mb-2 text-center">Connectez-vous à votre compte</h2>
    <p class="text-sm text-blue-700 mb-6 text-center">Entrez votre email et mot de passe pour vous connecter</p>
    <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-blue-900">Adresse email</label>
            <input id="email" name="email" type="email" required autofocus class="appearance-none rounded w-full px-3 py-2 border border-blue-200 placeholder-blue-300 text-blue-900 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500" placeholder="email@example.com">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-blue-900">Mot de passe</label>
            <input id="password" name="password" type="password" required class="appearance-none rounded w-full px-3 py-2 border border-blue-200 placeholder-blue-300 text-blue-900 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500" placeholder="Mot de passe">
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-900 focus:ring-yellow-500 border-blue-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-blue-900">Se souvenir de moi</label>
            </div>
        </div>
        <button type="submit" class="w-full py-2 px-4 rounded bg-blue-900 text-white font-bold hover:bg-yellow-500 hover:text-blue-900 transition duration-150 shadow">Connexion</button>
    </form>
    <div class="mt-6 text-center text-sm text-blue-700">
        Vous n'avez pas de compte ? <a href="{{ route('client.register') }}" class="font-bold text-yellow-600 hover:text-blue-900">Créer un compte</a>
    </div>
</div>
</body>
</html>

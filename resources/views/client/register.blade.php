<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte client OCC</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-yellow-100 via-blue-50 to-blue-200 min-h-screen flex flex-col justify-center items-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 mt-16">
        <h2 class="text-2xl font-bold text-blue-900 mb-6 text-center">Créer un compte client OCC</h2>
        @livewire('client.auth.register-form')
    </div>
</body>
</html>

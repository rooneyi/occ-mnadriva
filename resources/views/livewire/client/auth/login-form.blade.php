<div>
    <form wire:submit.prevent="login">
        <div class="mb-5">
            <input type="email" wire:model.lazy="email" class="w-full px-5 py-3 rounded-lg border border-blue-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200 text-lg placeholder-blue-400" placeholder="Email" autocomplete="email" />
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-8">
            <input type="password" wire:model.lazy="password" class="w-full px-5 py-3 rounded-lg border border-blue-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200 text-lg placeholder-blue-400" placeholder="Mot de passe" autocomplete="current-password" />
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        @if($error)
            <div class="mb-4 text-red-500 text-center text-sm">{{ $error }}</div>
        @endif
        <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-300 text-blue-900 font-bold py-3 rounded-lg text-lg transition">Se connecter</button>
        <a href="{{ route('password.request') }}" class="block text-center text-sm text-blue-600 hover:underline mt-4">
            Mot de passe oublié ?
        </a>
        <div class="text-center text-sm text-gray-600 mt-4">
            Pas encore de compte ? <a href="{{ route('client.register') }}" class="text-blue-600 hover:underline">Créer un compte</a>
        </div>
    </form>
</div>

<div>
    <form wire:submit.prevent="register">
        <div class="mb-5">
            <input type="text" wire:model="adresse" class="w-full px-5 py-3 rounded-lg border border-blue-900 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200 text-lg placeholder-gray-300" placeholder="Adresse" />
            @error('adresse') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-5">
            <input type="email" wire:model="email" class="w-full px-5 py-3 rounded-lg border border-blue-900 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200 text-lg placeholder-gray-300" placeholder="Email" />
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-5">
            <input type="password" wire:model="password" class="w-full px-5 py-3 rounded-lg border border-blue-900 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200 text-lg placeholder-gray-300" placeholder="Mot de passe" />
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-8">
            <input type="password" wire:model="password_confirmation" class="w-full px-5 py-3 rounded-lg border border-blue-900 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200 text-lg placeholder-gray-300" placeholder="Confirmation du mot de passe" />
        </div>
        <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-bold py-3 rounded-lg text-lg transition">Créer le compte</button>

        <div class="text-center text-sm text-gray-600 mt-4">
            Déjà un compte ? <a href="{{ route('client.login') }}" class="text-blue-600 hover:underline">Se Connecter</a>
        </div>
    </form>
</div>

<div>
    <form wire:submit.prevent="login">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" wire:model.lazy="email" class="form-control" id="email" required>
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" wire:model.lazy="password" class="form-control" id="password" required>
            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
</div>

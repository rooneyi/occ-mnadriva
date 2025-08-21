<div>
    <h2 class="text-xl font-bold mb-4">Ajouter un produit</h2>
    <form wire:submit="ajouter">
        @if(session('success'))
            <div class="text-green-600 bg-green-200 border rounded p-2  m-2">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="text-red-600 bg-red-200 border rounded p-2  m-2">{{ session('success') }}</div>
        @endif
        <input type="text" wire:model="categorie_produit" placeholder="Catégorie du produit" class="border rounded p-2 mb-2 w-full">
        <input type="text" wire:model="nom_produit" placeholder="Nom du produit" class="border rounded p-2 mb-2 w-full">
        <input type="text" wire:model="description_produit" placeholder="Description du produit" class="border rounded p-2 mb-2 w-full">
        <input type="date" wire:model="date_fabrication" placeholder="Date de fabrication" class="border rounded p-2 mb-2 w-full">
        <input type="date" wire:model="date_expiration" placeholder="Date d'expiration" class="border rounded p-2 mb-2 w-full">
        
        <!-- Section OCR -->
        <div class="mb-4 p-4 border rounded bg-gray-50">
            <h3 class="font-bold mb-2">Extraction de texte depuis une image</h3>
            
            <!-- Champ de téléchargement de fichier -->
            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Télécharger une photo</label>
          <input type="file" id="photo_produit" accept="image/*" capture="environment" 
              wire:model="photo" class="border rounded p-2 w-full" 
              onchange="document.getElementById('extract-status').textContent='Extraction en cours...';">
          <span id="extract-status" class="ml-2 text-sm"></span>
          <p class="text-xs text-gray-500 mt-1">Sur mobile, utilisez l'appareil photo pour une capture directe.</p>
            </div>
            
            <!-- Affichage des résultats OCR -->
            @if(isset($extractedText) && $extractedText)
                <div class="mt-3 p-3 bg-white border rounded">
                    <h4 class="font-semibold mb-2">Texte extrait :</h4>
                    <div class="bg-gray-100 p-2 rounded text-sm mb-3">
                        {{ $extractedText }}
                    </div>
                    
                    @if(!empty($detectedDate))
                        <div class="mt-2">
                            <h4 class="font-semibold">Date détectée :</h4>
                            <p class="text-green-700">{{ $detectedDate }}</p>
                            <p class="text-xs text-gray-500 mt-1">Les champs de date ont été remplis automatiquement.</p>
                        </div>
                    @else
                        <p class="text-yellow-600 text-sm mt-2">Aucune date n'a pu être extraite du texte.</p>
                    @endif
                </div>
            @endif
            
            <div class="mt-2">
                <button type="button" id="extract-dates" class="bg-green-600 text-white px-4 py-2 rounded">
                    Extraire les dates depuis la photo
                </button>
                <span id="extract-status" class="ml-2 text-sm"></span>
            </div>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
    </form>
    <script>
        document.getElementById('extract-dates').addEventListener('click', function() {
            const fileInput = document.getElementById('photo_produit');
            const status = document.getElementById('extract-status');
            status.textContent = '';
            status.className = 'ml-2 text-sm';
            if (fileInput.files.length === 0) {
                status.textContent = 'Veuillez sélectionner une image.';
                status.classList.add('text-red-600');
                return;
            }
            status.textContent = 'Extraction en cours...';
            status.classList.remove('text-red-600');
            status.classList.add('text-blue-600');
            const formData = new FormData();
            formData.append('photo', fileInput.files[0]);
            fetch('/extract-dates', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    status.textContent = 'Erreur : ' + data.error;
                    status.classList.remove('text-blue-600');
                    status.classList.add('text-red-600');
                    return;
                }
                let found = false;
                if (data.date_fabrication) {
                    document.querySelector('input[wire\\:model="date_fabrication"]').value = data.date_fabrication;
                    // Déclenche l'événement input pour Livewire
                    document.querySelector('input[wire\\:model="date_fabrication"]').dispatchEvent(new Event('input', { bubbles: true }));
                    found = true;
                }
                if (data.date_expiration) {
                    document.querySelector('input[wire\\:model="date_expiration"]').value = data.date_expiration;
                    // Déclenche l'événement input pour Livewire
                    document.querySelector('input[wire\\:model="date_expiration"]').dispatchEvent(new Event('input', { bubbles: true }));
                    found = true;
                }
                if (found) {
                    status.textContent = 'Dates extraites avec succès !';
                    status.classList.remove('text-blue-600', 'text-red-600');
                    status.classList.add('text-green-600');
                } else {
                    status.textContent = 'Aucune date trouvée.';
                    status.classList.remove('text-blue-600');
                    status.classList.add('text-yellow-600');
                }
            })
            .catch(() => {
                status.textContent = 'Erreur lors de l\'extraction des dates.';
                status.classList.remove('text-blue-600');
                status.classList.add('text-red-600');
            });
        });
    </script>

    <h3 class="text-lg font-semibold mt-6 mb-2">Liste des produits</h3>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="border px-4 py-2">Catégorie</th>
                <th class="border px-4 py-2">Nom</th>
                <th class="border px-4 py-2">Description</th>
                <th class="border px-4 py-2">Date de fabrication</th>
                <th class="border px-4 py-2">Date d'expiration</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $produit)
                <tr>
                    <td class="border px-4 py-2">{{ $produit->categorie_produit }}</td>
                    <td class="border px-4 py-2">{{ $produit->nom_produit }}</td>
                    <td class="border px-4 py-2">{{ $produit->description_produit }}</td>
                    <td class="border px-4 py-2">{{ $produit->date_fabrication }}</td>
                    <td class="border px-4 py-2">{{ $produit->date_expiration }}</td>
                    <td class="border px-4 py-2">
{{--                        <button wire:click="modifier({{ $produit->id }})" class="bg-yellow-500 text-white px-3 py-1 rounded">Modifier</button>--}}
                        <button wire:click="supprimer({{ $produit->id }})" class="bg-red-600 text-white px-3 py-1 rounded">Supprimer</button>
                    </td>
                </tr>
            @endforeach
    </table>

</div>

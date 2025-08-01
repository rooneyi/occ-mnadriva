@extends('components.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Déclaration</h1>
    <p class="mb-4">Ici, vous pourrez soumettre votre déclaration, ajouter des produits et joindre une licence.</p>
    <!-- Formulaire à compléter selon vos besoins -->
    <form>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nom du produit</label>
            <input type="text" class="w-full border rounded px-3 py-2" placeholder="Nom du produit">
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Licence (PDF, DOCX)</label>
            <input type="file" class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800">Soumettre</button>
    </form>
</div>
@endsection


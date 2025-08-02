<?php
namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    // List all products
    public function index()
    {
        $produits = Produit::all();
        return view('produits.index', compact('produits'));
    }

    // Show create form
    public function create()
    {
        return view('produits.create');
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'categorie_produit' => 'required|string|max:255',
            'nom_produit' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        Produit::create($request->only(['categorie_produit', 'nom_produit', 'description']));
        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès.');
    }

    // Show edit form
    public function edit($id)
    {
        $produit = Produit::findOrFail($id);
        return view('produits.edit', compact('produit'));
    }

    // Update product
    public function update(Request $request, $id)
    {
        $request->validate([
            'categorie_produit' => 'required|string|max:255',
            'nom_produit' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $produit = Produit::findOrFail($id);
        $produit->update($request->only(['categorie_produit', 'nom_produit', 'description']));
        return redirect()->route('produits.index')->with('success', 'Produit modifié avec succès.');
    }

    // Delete product
    public function destroy($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();
        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }
}

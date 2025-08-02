<html>
<head>
    <meta charset="UTF-8">
    <title>Rapport d'analyse</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h2 { color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <h2>Rapport d'analyse</h2>
    <table>
        <tr><th>Produit</th><td>{{ $rapport->designation_produit }}</td></tr>
        <tr><th>Code labo</th><td>{{ $rapport->code_lab }}</td></tr>
        <tr><th>Quantité</th><td>{{ $rapport->quantite }}</td></tr>
        <tr><th>Méthode d'essai</th><td>{{ $rapport->methode_essai }}</td></tr>
        <tr><th>Aspect extérieur</th><td>{{ $rapport->aspect_exterieur }}</td></tr>
        <tr><th>Résultat d'analyse</th><td>{{ $rapport->resultat_analyse }}</td></tr>
        <tr><th>Date fabrication</th><td>{{ $rapport->date_fabrication }}</td></tr>
        <tr><th>Date expiration</th><td>{{ $rapport->date_expiration }}</td></tr>
        <tr><th>Conclusion</th><td>{{ $rapport->conclusion }}</td></tr>
    </table>
    <p style="margin-top:40px;">Fait à ... le {{ $rapport->created_at ? $rapport->created_at->format('d/m/Y') : date('d/m/Y') }}</p>
</body>
</html>

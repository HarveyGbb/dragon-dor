<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Commandes</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Petit style pour le tableau admin */
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .admin-table th, .admin-table td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        .admin-table th { background-color: #333; color: white; }
    </style>
</head>
<body style="padding: 20px; background-color: #f4f4f4;">

    <h1>👨‍🍳 Tableau de Bord Admin</h1>
    <a href="/">← Retour au site</a>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif

    <table class="admin-table" style="background: white;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Total</th>
                <th>Statut Actuel</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commandes as $c)
            <tr>
                <td>#{{ $c->id }}</td>
                <td>{{ $c->nom_client }}<br>{{ $c->telephone }}</td>
                <td>{{ number_format($c->total, 2) }} €</td>
                <td>
                    <strong>{{ $c->statut }}</strong>
                </td>
                <td>
                    <form action="{{ route('admin.commandes.update_status', $c->id) }}" method="POST">
                        @csrf
                        <select name="statut" onchange="this.form.submit()">
                            <option value="en attente" {{ $c->statut == 'en attente' ? 'selected' : '' }}>En attente</option>
                            <option value="en preparation" {{ $c->statut == 'en preparation' ? 'selected' : '' }}>En préparation</option>
                            <option value="pret" {{ $c->statut == 'pret' ? 'selected' : '' }}>Prêt</option>
                            <option value="terminée" {{ $c->statut == 'terminée' ? 'selected' : '' }}>Terminée</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="15"> <title>Administration - Dragon d'Or</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/admin.css'])

    <style>
        /* ... Tes styles existants ... */
        .admin-tabs { display: flex; justify-content: center; gap: 20px; margin-bottom: 30px; border-bottom: 2px solid #e5e7eb; padding-bottom: 0; }
        .tab-btn { background: none; border: none; padding: 15px 30px; font-size: 1.2rem; font-weight: bold; color: #6b7280; cursor: pointer; border-bottom: 4px solid transparent; transition: all 0.3s ease; }
        .tab-btn:hover { color: #ca8a04; background-color: #fefce8; }
        .tab-btn.active { color: #ca8a04; border-bottom: 4px solid #ca8a04; }
        .tab-content { display: none; animation: fadeIn 0.4s; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .btn-qty { width: 32px; height: 32px; border-radius: 50%; border: none; color: white; font-weight: bold; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; justify-content: center; }
        .btn-minus { background-color: #ef4444; }
        .btn-plus { background-color: #22c55e; }

        .stock-val { font-size: 1.2rem; font-weight: 800; min-width: 40px; text-align: center; }

        .btn-logout { background-color: #dc2626; color: white; border: none; padding: 5px 15px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: background 0.3s; }
        .btn-logout:hover { background-color: #b91c1c; }

        /* Style pour les plats archivés */
        .plat-archive { opacity: 0.6; background-color: #f3f4f6; }
    </style>
</head>
<body class="admin-layout">

    <div class="admin-header d-flex justify-content-between align-items-center p-3">
        <div>
            <h1 class="admin-title m-0">Tableau de Bord</h1>
            <p class="text-muted ms-3 mt-1 mb-0">Espace Gérant </p>
        </div>

        <div class="d-flex gap-3 align-items-center">
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm fw-bold">🚪 Déconnexion</button>
            </form>

            <button onclick="window.location.reload();" class="btn btn-outline-secondary btn-sm">🔄 Actualiser</button>
            <a href="{{ route('accueil.index') }}" class="btn btn-outline-primary btn-sm">Retour au site</a>
        </div>
    </div>

    <div class="admin-tabs d-flex justify-content-center gap-4 mb-4 border-bottom">
        <button id="btn-commandes" class="tab-btn active" onclick="switchTab('commandes')">Commandes</button>
        <button id="btn-stocks" class="tab-btn" onclick="switchTab('stocks')">Gestion Stocks & Carte</button>
    </div>

    <div class="container">
        @if(session('success')) <div class="alert alert-success text-center shadow-sm">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger text-center shadow-sm">⚠️ {{ session('error') }}</div> @endif
    </div>

    <div id="tab-commandes" class="tab-content active container">
        <div class="table-container bg-white p-4 rounded shadow-sm">
            @if($commandes->isEmpty())
                <div class="text-center py-5 text-muted">Aucune commande en attente.</div>
            @else
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>N°</th> <th>Client</th> <th>Détail</th> <th>Retrait</th> <th>État</th> <th>Action</th></tr>
                    </thead>
                    <tbody>
                        @foreach($commandes as $commande)
                        <tr style="{{ $commande->statut == 'fini' ? 'opacity: 0.5; background-color: #f9fafb;' : '' }}"> <td class="fw-bold">#{{ $commande->id }}</td>
                            <td><strong>{{ $commande->prenom }} {{ $commande->nom }}</strong><br><small>{{ $commande->telephone }}</small></td>
                            <td>
                                <ul class="list-unstyled mb-0 small">
                                    @foreach($commande->plats as $plat)
                                        <li><span class="badge bg-secondary me-1">{{ $plat->pivot->quantite }}x</span> {{ $plat->nom }}</li>
                                    @endforeach
                                </ul>
                                <div class="text-warning fw-bold small mt-1">Total: {{ number_format($commande->total, 2) }} €</div>
                            </td>
                            <td class="text-danger fw-bold">{{ $commande->heure_retrait }}</td>
                            <td>
                                @php
                                    // Modifié : 'en_cuisine' et 'fini' intégrés au tableau des badges
                                    $badges = ['en_attente' => ['bg-warning', '⏳ Attente'], 'en_cuisine' => ['bg-info', '🔥 Cuisine'], 'prete' => ['bg-success', '✅ Prête'], 'fini' => ['bg-secondary', '🏁 Finie']];
                                    $info = $badges[$commande->statut] ?? ['bg-light', 'Inconnu'];
                                @endphp
                                <span class="badge {{ $info[0] }} text-dark">{{ $info[1] }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.commandes.update_status', $commande->id) }}" method="POST" class="d-flex gap-1">
                                    @csrf
                                    <select name="statut" class="form-select form-select-sm" style="width: 120px;">
                                        <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>Attente</option>
                                        <option value="en_cuisine" {{ $commande->statut == 'en_cuisine' ? 'selected' : '' }}>Cuisine</option>
                                        <option value="prete" {{ $commande->statut == 'prete' ? 'selected' : '' }}>Prête</option>
                                        <option value="fini" {{ $commande->statut == 'fini' ? 'selected' : '' }}>Finie</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-dark">OK</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div id="tab-stocks" class="tab-content container">
        <div class="table-container bg-white p-4 rounded shadow-sm border-top border-4 border-warning">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h5 text-warning m-0">Inventaire & Carte</h3>
                <a href="{{ route('admin.plats.create') }}" class="btn btn-success fw-bold shadow-sm">
                    + Nouveau Plat
                </a>
            </div>

            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Plat</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Visibilité</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tousLesPlats as $plat)
                    <tr class="{{ !$plat->disponible ? 'plat-archive' : '' }}">
                        <td class="fw-bold">
                            {{ $plat->nom }}
                            @if(!$plat->disponible)
                                <br><small class="text-muted">(Retiré de la carte)</small>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <form action="{{ route('admin.plats.update_stock', $plat->id) }}" method="POST">
                                    @csrf <input type="hidden" name="action" value="diminuer">
                                    <button class="btn-qty btn-minus" {{ !$plat->disponible ? 'disabled' : '' }}>-</button>
                                </form>
                                <span class="stock-val {{ $plat->stock == 0 ? 'text-danger' : '' }}">{{ $plat->stock }}</span>
                                <form action="{{ route('admin.plats.update_stock', $plat->id) }}" method="POST">
                                    @csrf <input type="hidden" name="action" value="augmenter">
                                    <button class="btn-qty btn-plus" {{ !$plat->disponible ? 'disabled' : '' }}>+</button>
                                </form>
                            </div>
                        </td>

                        <td class="text-center">
                            @if($plat->disponible && $plat->stock > 0)
                                <span class="badge bg-success rounded-pill">✅ En vente</span>
                            @elseif($plat->disponible && $plat->stock <= 0)
                                <span class="badge bg-warning text-dark rounded-pill">⚠️ Épuisé (Stock 0)</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">🚫 Archivé</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <form action="{{ route('admin.plats.toggle_visibility', $plat->id) }}" method="POST">
<a href="{{ route('admin.plats.edit', $plat->id) }}" class="btn btn-sm btn-primary">Modifier</a>
                                @csrf
                                @if($plat->disponible)
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Retirer de la carte sans supprimer l'historique">
                                        Retirer de la carte
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        Remettre en vente
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active');
            });
            document.getElementById('tab-' + tabName).classList.add('active');
            const activeBtn = document.getElementById('btn-' + tabName);
            activeBtn.classList.add('active');
        }

        // Garder l'onglet actif après un rechargement de page (ex: après un ajout de stock)
        @if(session('tab') == 'stocks')
            switchTab('stocks');
        @endif
    </script>

</body>
</html>

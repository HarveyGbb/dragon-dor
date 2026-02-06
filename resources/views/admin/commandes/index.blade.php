<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Dragon d'Or</title>
    @vite(['resources/css/app.css', 'resources/css/admin.css'])

    <style>
        /* --- STYLE DES ONGLETS (TABS) --- */
        .admin-tabs {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #6b7280;
            cursor: pointer;
            border-bottom: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-btn:hover {
            color: #ca8a04;
            background-color: #fefce8;
        }

        .tab-btn.active {
            color: #ca8a04;
            border-bottom: 4px solid #ca8a04;
        }

        .tab-content {
            display: none; /* Caché par défaut */
            animation: fadeIn 0.4s;
        }

        .tab-content.active {
            display: block; /* Visible si actif */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- STYLES STOCKS --- */
        .btn-qty {
            width: 32px; height: 32px; border-radius: 50%; border: none;
            color: white; font-weight: bold; cursor: pointer; transition: 0.2s;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .btn-minus { background-color: #ef4444; }
        .btn-plus { background-color: #22c55e; }
        .btn-qty:hover { transform: scale(1.1); opacity: 0.9; }

        .stock-val {
            font-size: 1.2rem; font-weight: 800; min-width: 40px;
            text-align: center; display: inline-block;
        }

        /* Style spécifique pour le bouton déconnexion */
        .btn-logout {
            background-color: #dc2626; /* Rouge */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background-color: #b91c1c;
        }
    </style>
</head>
<body class="admin-layout">

    <div class="admin-header">
        <div>
            <h1 class="admin-title">Tableau de Bord</h1>
            <p style="color: #666; margin-left: 25px; margin-top: 5px;">Bienvenue, Chef ! 👨‍🍳</p>
        </div>

        <div style="display: flex; gap: 15px; align-items: center;">

            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">
                    🚪 Déconnexion
                </button>
            </form>
            <button onclick="window.location.reload();" class="btn-refresh">🔄 Actualiser</button>
            <a href="{{ route('accueil.index') }}" class="back-link">Retour au site</a>
        </div>
    </div>

    <div class="admin-tabs">
        <button id="btn-commandes" class="tab-btn active" onclick="switchTab('commandes')">📦 Suivi des Commandes</button>
        <button id="btn-stocks" class="tab-btn" onclick="switchTab('stocks')">📊 Gestion des Stocks</button>
    </div>

    @if(session('success'))
        <div style="text-align: center; margin-bottom: 20px; color: green; font-weight: bold;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div id="tab-commandes" class="tab-content active">
        <div class="table-container">
            @if($commandes->isEmpty())
                <div style="text-align: center; padding: 50px;">
                    <p style="font-size: 1.5rem;">🎉 Tout est calme !</p>
                    <p style="color: #6b7280;">Aucune commande en attente.</p>
                </div>
            @else
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Client</th>
                            <th>Détail (Cuisine)</th>
                            <th>Retrait</th>
                            <th>État</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commandes as $commande)
                        <tr style="{{ $commande->statut == 'recuperee' ? 'opacity: 0.5; background-color: #f9fafb;' : '' }}">
                            <td style="font-weight: bold;">#{{ $commande->id }}</td>
                            <td>
                                <strong>{{ $commande->prenom }} {{ $commande->nom }}</strong><br>
                                <small>📞 {{ $commande->telephone }}</small>
                            </td>
                            <td>
                                <ul class="plat-list">
                                    @foreach($commande->plats as $plat)
                                        <li><span class="qty">x{{ $plat->pivot->quantite }}</span> {{ $plat->nom }}</li>
                                    @endforeach
                                </ul>
                                <div style="color: #ca8a04; font-weight: bold; margin-top:5px;">Total: {{ number_format($commande->total, 2) }} €</div>
                            </td>
                            <td style="color: #ea580c; font-weight: bold;">{{ $commande->heure_retrait }}</td>
                            <td>
                                @php
                                    $badges = [
                                        'en_attente' => ['⏳ Attente', 'attente'],
                                        'en_preparation' => ['🔥 Cuisine', 'cuisine'],
                                        'prete' => ['✅ Prête', 'prete'],
                                        'recuperee' => ['🏁 Finie', 'finish']
                                    ];
                                    $info = $badges[$commande->statut] ?? ['Inconnu', ''];
                                @endphp
                                <span class="badge {{ $info[1] }}">{{ $info[0] }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.commandes.update_status', $commande->id) }}" method="POST">
                                    @csrf
                                    <select name="statut" class="status-select">
                                        <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>⏳ Attente</option>
                                        <option value="en_preparation" {{ $commande->statut == 'en_preparation' ? 'selected' : '' }}>🔥 En cuisine</option>
                                        <option value="prete" {{ $commande->statut == 'prete' ? 'selected' : '' }}>✅ Prête</option>
                                        <option value="recuperee" {{ $commande->statut == 'recuperee' ? 'selected' : '' }}>🏁 Récupérée</option>
                                    </select>
                                    <button type="submit" class="btn-save">OK</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div id="tab-stocks" class="tab-content">
        <div class="table-container" style="border-top: 4px solid #ca8a04;">
            <div style="padding: 20px; text-align: center;">
                <h2 style="margin:0; color: #ca8a04;">Inventaire en temps réel</h2>
                <p class="text-muted">Modifiez les stocks ici, le site client se mettra à jour immédiatement.</p>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Plat</th>
                        <th style="text-align: center;">Quantité Disponible</th>
                        <th style="text-align: center;">État sur le site</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tousLesPlats as $plat)
                    <tr>
                        <td style="font-weight: bold; font-size: 1.1em;">{{ $plat->nom }}</td>
                        <td>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
                                <form action="{{ route('admin.plats.update_stock', $plat->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="diminuer">
                                    <button type="submit" class="btn-qty btn-minus">-</button>
                                </form>

                                <span class="stock-val {{ $plat->stock == 0 ? 'text-danger' : '' }}">
                                    {{ $plat->stock }}
                                </span>

                                <form action="{{ route('admin.plats.update_stock', $plat->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="augmenter">
                                    <button type="submit" class="btn-qty btn-plus">+</button>
                                </form>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            @if($plat->stock > 0)
                                <span style="background: #dcfce7; color: #166534; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9em;">
                                    ✅ En vente
                                </span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9em;">
                                    🚫 Épuisé
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // 1. Cacher tous les contenus
            document.getElementById('tab-commandes').classList.remove('active');
            document.getElementById('tab-stocks').classList.remove('active');

            // 2. Désactiver tous les boutons
            document.getElementById('btn-commandes').classList.remove('active');
            document.getElementById('btn-stocks').classList.remove('active');

            // 3. Activer le bon contenu et le bon bouton
            document.getElementById('tab-' + tabName).classList.add('active');
            document.getElementById('btn-' + tabName).classList.add('active');
        }

        // Si le contrôleur nous dit "reste sur stocks", on clique automatiquement dessus
        @if(session('tab') == 'stocks')
            switchTab('stocks');
        @endif
    </script>

</body>
</html>

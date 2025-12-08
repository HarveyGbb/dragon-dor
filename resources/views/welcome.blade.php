<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dragon d'or - Menu Click & Collect</title>

    <!-- CSS SIMPLE sans Vite -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body>
    <div class="container-layout">
        <header class="main-header">
            <div class="logo-container">
                <h1 class="app-title">
                    <a href="{{ route('menu.index') }}" class="app-title-link">Dragon d'Or 🍜</a>
                </h1>
            </div>

            <nav class="main-nav-container">
                <a href="{{ route('accueil.index') }}" class="nav-link">Accueil</a>
                <a href="{{ route('menu.index') }}" class="nav-link">Menu</a>
                <a href="#" class="nav-link">Contact</a>
                <a href="{{ route('cart.show') }}" class="cart-link nav-link">
                    Panier ({{ $itemCount ?? 0 }}) 🛒
                </a>
            </nav>
        </header>


        <main>
            <h2 class="menu-title">Notre Menu du Jour</h2>

            <!-- CONTAINER PRINCIPAL AVEC SIDEBAR -->
            <div class="menu-container">
                <!-- SIDEBAR DES FILTRES -->
                <aside class="filter-sidebar">
                    <h3 class="filter-title">Catégories</h3>
                    <ul class="filter-list">
                        <li>
                            <button class="filter-link active" data-category="all">
                                <span class="filter-icon">🍽️</span>
                                Toutes catégories
                                <span class="filter-count">({{ $platsParCategorie->flatten()->count() }})</span>
                            </button>
                        </li>
                        @foreach($platsParCategorie as $categorie => $plats)
                        <li>
                            <button class="filter-link" data-category="{{ Str::slug($categorie) }}">
                                <span class="filter-icon">
                                    @if($categorie == 'Plats principaux') 🍛
                                    @elseif($categorie == 'Entrées') 🥟
                                    @elseif($categorie == 'Desserts') 🍮
                                    @elseif($categorie == 'Boissons') 🥤
                                    @elseif($categorie == 'Accompagnements') 🍚
                                    @else 🍽️
                                    @endif
                                </span>
                                {{ $categorie }}
                                <span class="filter-count">({{ count($plats) }})</span>
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </aside>

                <!-- CONTENU PRINCIPAL DES PLATS -->
                <div class="menu-content">
                    @foreach($platsParCategorie as $categorie => $plats)
                    <div class="category-section" id="category-{{ Str::slug($categorie) }}">
                        <h3 class="category-title">
                            {{ $categorie }}
                            @if($categorie == 'Plats principaux') 🍛
                            @elseif($categorie == 'Entrées') 🥟
                            @elseif($categorie == 'Desserts') 🍮
                            @elseif($categorie == 'Boissons') 🥤
                            @elseif($categorie == 'Accompagnements') 🍚
                            @endif
                        </h3>

                        <div class="menu-grid">
                            @foreach($plats as $plat)
                            <div class="menu-card">
                                <img src="{{ asset('images/' . $plat->id . '.jpg') }}" alt="{{ $plat->nom }}">
                                <h3 class="plat-title">{{ $plat->nom }}</h3>
                                <p class="plat-description">{{ $plat->description }}</p>
                                <p class="plat-price">{{ number_format($plat->prix, 2) }} €</p>
                                <div class="add-to-cart-container">
                                    <form method="POST" action="{{ route('cart.add') }}" class="cart-form">
                                        @csrf
                                        <input type="hidden" name="plat_id" value="{{ $plat->id }}">
                                        <input type="number" name="quantity" value="1" min="1" max="10" class="quantity-input">
                                        <button type="submit" class="add-button-link">Ajouter</button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </main>

        <footer class="main-footer">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </footer>
    </div>

    <!-- ⭐⭐ AJOUTEZ CETTE LIGNE POUR CHARGER VOTRE JS ⭐⭐ -->
    <script src="{{ asset('js/menu-filters.js') }}"></script>

</body>
</html>

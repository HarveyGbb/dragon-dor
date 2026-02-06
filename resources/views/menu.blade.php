@extends('layout')

@section('css')
<style>
    /* --- 1. CSS POUR CACHER LES FLÈCHES DU NAVIGATEUR (Chrome/Safari/Edge) --- */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield; /* Firefox */
    }

    /* --- 2. TON DESIGN GOLD EXISTANT --- */
    .filter-link {
        border: 1px solid var(--gold) !important;
        background: transparent; color: var(--black-soft); font-weight: 500;
        transition: all 0.3s ease; border-radius: 8px !important; margin-bottom: 5px;
    }
    .filter-link:hover {
        background-color: #fef3c7; transform: translateX(5px);
        color: var(--gold-dark); border-width: 2px !important;
    }
    .filter-link.active {
        background-color: #fef3c7 !important; border-color: var(--dragon-red) !important;
        border-width: 2px !important; color: var(--dragon-red) !important;
        font-weight: bold; box-shadow: inset 0 0 10px rgba(183, 28, 28, 0.1);
    }

    .menu-card {
        border: 2px solid var(--gold); border-radius: 16px; background: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease; overflow: hidden;
    }
    .menu-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }

    .menu-card-img-wrapper { height: 220px; overflow: hidden; position: relative; border-bottom: 1px solid var(--gold); }
    .menu-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .menu-card:hover .menu-card-img { transform: scale(1.1); }

    .price-tag { font-size: 1.25rem; font-weight: 800; color: var(--dragon-red); }

    .btn-soldout {
        background-color: #f3f4f6; color: #9ca3af; border: 1px dashed #d1d5db;
        cursor: not-allowed; font-weight: bold; text-transform: uppercase;
        font-size: 0.9rem; width: 100%; padding: 10px;
    }
</style>
@endsection

@section('content')

<div class="container py-4" style="max-width: 1200px;">

    <div class="text-center mb-5 animate-fade-down">
        <span class="fs-1">🥢</span>
        <h1 class="fw-bold text-uppercase display-5" style="color: var(--dragon-red);">
            La Carte
        </h1>
        <div style="width: 60px; height: 4px; background: var(--gold); margin: 10px auto;"></div>
        <p class="text-muted lead">Authenticité et saveurs, préparées à la commande.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center mb-4">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-center mb-4">⚠️ {{ session('error') }}</div>
    @endif

    <div class="row g-5">

        <aside class="col-lg-3">
            <div class="sticky-top" style="top: 100px; z-index: 10;">
                <div class="p-3 bg-white rounded-4 shadow-sm">
                    <h5 class="fw-bold mb-3 px-2 text-uppercase text-secondary small ls-1">Catégories</h5>

                    <div class="list-group list-group-flush">
                        <button class="list-group-item list-group-item-action filter-link active d-flex justify-content-between align-items-center p-3" data-category="all">
                            <span>🍽️ Tout voir</span>
                        </button>

                        @foreach($platsParCategorie as $categorie => $plats)
                        <button class="list-group-item list-group-item-action filter-link d-flex justify-content-between align-items-center p-3"
                                data-category="{{ Str::slug($categorie) }}">
                            <span>
                                @if($categorie == 'Plats principaux') 🍜
                                @elseif($categorie == 'Entrées') 🥟
                                @elseif($categorie == 'Desserts') 🍮
                                @elseif($categorie == 'Boissons') 🥤
                                @else 🥢
                                @endif
                                {{ $categorie }}
                            </span>
                            <span class="badge rounded-pill bg-light text-secondary border border-light">{{ count($plats) }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

        <div class="col-lg-9">
            <div class="menu-content">
                @foreach($platsParCategorie as $categorie => $plats)

                <div class="category-section mb-5 animate-up" id="category-{{ Str::slug($categorie) }}">

                    <div class="d-flex align-items-center mb-4">
                        <h2 class="h4 fw-bold mb-0 text-dark">{{ $categorie }}</h2>
                        <div class="ms-3 flex-grow-1 border-bottom"></div>
                    </div>

                    <div class="row row-cols-1 row-cols-md-2 g-4">

                        @foreach($plats as $plat)
                            @if($plat->disponible)
                            <div class="col">
                                <div class="card menu-card h-100 shadow-sm">

                                    <div class="menu-card-img-wrapper">
                                        @if($plat->image)
                                            <img src="{{ asset('storage/' . $plat->image) }}" class="menu-card-img" alt="{{ $plat->nom }}">
                                        @elseif($plat->image_url)
                                            <img src="{{ asset('images/' . $plat->image_url) }}" class="menu-card-img" alt="{{ $plat->nom }}">
                                        @else
                                            <div style="height:100%; display:flex; align-items:center; justify-content:center; background:#eee; font-size:3rem;">🍜</div>
                                        @endif
                                    </div>

                                    <div class="card-body d-flex flex-column p-4">

                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title fw-bold mb-0">{{ $plat->nom }}</h5>
                                        </div>

                                        <p class="card-text text-muted small flex-grow-1" style="line-height: 1.5;">
                                            {{ Str::limit($plat->description, 70) }}
                                        </p>

                                        <div class="mt-3 pt-3 border-top border-light">

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="text-uppercase small text-muted fw-bold">Prix</span>
                                                <span class="price-tag">{{ number_format($plat->prix, 2, ',', ' ') }} €</span>
                                            </div>

                                            @if($plat->stock > 0)
                                                <form method="POST" action="{{ route('cart.add') }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $plat->id }}">

                                                    <div class="d-flex gap-2">
                                                        <div class="input-group" style="width: 110px; flex-shrink: 0;">
                                                            <button type="button" class="btn btn-outline-secondary" onclick="updateQty(this, -1)">-</button>

                                                            <input type="number"
                                                                   name="quantity"
                                                                   value="1"
                                                                   min="1"
                                                                   max="{{ $plat->stock }}"
                                                                   class="form-control text-center p-0 fw-bold"
                                                                   readonly>

                                                            <button type="button" class="btn btn-outline-secondary" onclick="updateQty(this, 1)">+</button>
                                                        </div>

                                                        <button type="submit"
                                                                class="btn text-white fw-bold shadow-sm flex-grow-1"
                                                                style="background-color: #b91c1c; border: none;">
                                                            Ajouter
                                                        </button>
                                                    </div>
                                                </form>
                                            @else
                                                <button class="btn btn-soldout" disabled>
                                                    ❌ Victime de son succès
                                                </button>
                                            @endif
                                            </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach

                    </div>
                </div>
                @endforeach
            </div>

            @if(count($platsParCategorie) == 0)
                <div class="text-center py-5">
                    <h3>😕 Oups !</h3>
                    <p>Aucun plat disponible pour le moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function updateQty(btn, change) {
        // Sélectionne l'input voisin du bouton cliqué
        const input = btn.closest('.input-group').querySelector('input');
        const maxStock = parseInt(input.getAttribute('max'));
        let currentVal = parseInt(input.value);
        let newVal = currentVal + change;

        // Bloque à 1 minimum et au Stock Max
        if (newVal >= 1 && newVal <= maxStock) {
            input.value = newVal;
        }
    }
</script>

@endsection

@extends('layout')

@section('title', 'Votre Panier - Dragon d\'Or')

@section('content')

<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-uppercase" style="color: #b91c1c;">
            <span class="me-2">🛒</span> Votre Panier
        </h1>
        <p class="text-muted">Validez vos choix avant de commander</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $total_general = 0;
        $total_items = 0;
    @endphp

    @if (empty($cart))
        <div class="text-center py-5 bg-light rounded-3 shadow-sm">
            <div style="font-size: 5rem;">🍜</div>
            <h3 class="mt-3 text-secondary">Votre panier est vide 😔</h3>
            <p class="text-muted">Il est temps de le remplir avec nos délicieux plats !</p>
            <a href="{{ route('menu.index') }}" class="btn btn-warning text-white fw-bold mt-3 px-4 py-2 rounded-pill shadow-sm">
                Retourner au Menu
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3">image</th>
                                        <th class="py-3 text-start">Plat</th>
                                        <th class="py-3">Prix</th>
                                        <th class="py-3" style="min-width: 140px;">Quantité</th>
                                        <th class="py-3">Total</th>
                                        <th class="py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $id => $item)
                                        @php
                                            $sous_total = $item['price'] * $item['quantity'];
                                            $total_items += $item['quantity'];
                                            $total_general += $sous_total;
                                        @endphp
                                        <tr>
                                            <td class="p-3">
                                                @if(isset($item['image']) && $item['image'])
                                                    <img src="{{ asset('images/' . $item['image']) }}"
                                                         alt="{{ $item['name'] }}"
                                                         class="rounded shadow-sm"
                                                         style="width: 70px; height: 70px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" style="width: 70px; height: 70px; font-size: 2rem;">
                                                        📷
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="text-start fw-bold text-secondary">
                                                {{ $item['name'] }}
                                            </td>

                                            <td class="text-muted">
                                                {{ number_format($item['price'], 2) }} €
                                            </td>

                                            <td>
                                                <form action="{{ route('cart.update') }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] }}" id="qty-{{ $id }}">

                                                    <div class="input-group input-group-sm justify-content-center">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity({{ $id }}, -1)">−</button>
                                                        <span class="input-group-text bg-white fw-bold" style="min-width: 40px; justify-content: center;">
                                                            {{ $item['quantity'] }}
                                                        </span>
                                                        <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity({{ $id }}, 1)">+</button>
                                                    </div>
                                                </form>
                                            </td>

                                            <td class="fw-bold text-warning text-darken-3">
                                                {{ number_format($sous_total, 2) }} €
                                            </td>

                                            <td>
                                                <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Supprimer cet article ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <button type="submit" class="btn btn-link text-danger text-decoration-none p-0" title="Supprimer">
                                                        <span style="font-size: 1.2rem;">🗑️</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 bg-white shadow-sm p-4 sticky-top" style="top: 20px;">
                    <h4 class="fw-bold mb-4 border-bottom pb-2">Récapitulatif</h4>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Nombre d'articles :</span>
                        <strong>{{ $total_items }}</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                        <span class="fs-5 fw-bold">Total à payer :</span>
                        <span class="fs-2 fw-bold text-danger">
                            {{ number_format($total_general, 2) }} €
                        </span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('order.create') }}" class="btn btn-success btn-lg fw-bold shadow-sm">
                            Procéder au Paiement →
                        </a>
                        <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary">
                            ← Continuer mes achats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function changeQuantity(itemId, change) {
        // 1. Récupération de l'input caché
        const hiddenInput = document.getElementById(`qty-${itemId}`);
        const form = hiddenInput.closest('form');

        let currentQty = parseInt(hiddenInput.value);
        let newQty = currentQty + change;

        // 2. Vérifications de sécurité
        if (newQty < 1) return; // Pas de quantité négative ou nulle
        if (newQty > 99) newQty = 99; // Limite arbitraire (optionnelle)

        // 3. Mise à jour et soumission
        hiddenInput.value = newQty;
        form.submit();
    }
</script>

@endsection

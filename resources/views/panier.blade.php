<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre Panier - Dragon d'Or</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/panier.css') }}">
    <style>
        /* Style pour rendre le bouton de suppression visuellement discret */
        .remove-button-style {
            background: none;
            border: none;
            color: #FF2D20; /* Rouge pour l'action de suppression */
            text-decoration: underline;
            cursor: pointer;
            padding: 0;
            font-size: 1em;
        }
    </style>
</head>
<body>

    <h1 class="cart-title">🛒 Votre Panier</h1>

    @if (session('success'))
        <p class="success-message">{{ session('success') }}</p>
    @endif

    @php
        $total_general = 0;
    @endphp

    @if (empty($cart))
        <p class="empty-cart">Votre panier est vide. Visitez notre <a href="{{ route('menu.index') }}">menu</a> pour ajouter des plats !</p>
    @else
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Plat</th>
                    <th>Prix Unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $id => $item)
                    @php $sous_total = $item['price'] * $item['quantity']; @endphp
                    <tr class="cart-item">
                        <td class="item-name">{{ $item['name'] }}</td>
                        <td class="item-price">{{ number_format($item['price'], 2) }} €</td>
                        <td class="item-quantity">{{ $item['quantity'] }}</td>
                        <td class="item-subtotal">{{ number_format($sous_total, 2) }} €</td>

                        <td class="item-action">
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf <button type="submit" class="remove-button-style">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                        </tr>
                    @php $total_general += $sous_total; @endphp
                @endforeach
            </tbody>
        </table>

        <h2 class="cart-total">Total de la Commande : {{ number_format($total_general, 2) }} €</h2>

        <p class="checkout-container">
            <a href="{{ route('order.create') }}" class="checkout-button">
                Procéder à la Validation de la Commande →
            </a>
        </p>

    @endif

</body>
</html>

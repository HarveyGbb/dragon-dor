
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - Dragon d'Or</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accueil.css') }}">
</head>
<body>

    <div class="container-layout">

        <header class="main-header">
            <h1 class="app-title">🐉 Dragon d'Or</h1>
            <nav class="main-nav-container">
                <a href="{{ route('menu.index') }}" class="nav-link">Notre Menu</a>
                <a href="{{ route('cart.show') }}" class="cart-link">Mon Panier</a>
            </nav>
        </header>

        @if (session('success'))
            <div class="success-alert">
                <h3>✅ Commande Confirmée !</h3>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="hero-section">
            <img src="{{ asset('images/favicon.ico') }}" alt="Logo" style="width: 100px; margin-bottom: 20px;">

            <h2>Bienvenue au Dragon d'Or</h2>
            <p>Savourez nos spécialités asiatiques authentiques, préparées à la commande.</p>
            <p>Commandez en ligne et retirez votre repas à l'heure de votre choix !</p>

            <a href="{{ route('menu.index') }}" class="cta-button">
                Voir le Menu & Commander
            </a>
        </div>

    </div>

</body>
</html>


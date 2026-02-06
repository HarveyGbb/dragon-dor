<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🐉 Dragon d'Or - Restaurant Asiatique</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('css')

    <style>
        /* --- NOUVEAU : BACKGROUND QUI PREND TOUT L'ÉCRAN --- */
        html, body {
            height: 100%; /* Force la hauteur totale */
            margin: 0;
            padding: 0;
            /* Une couleur crème dorée très pâle (Premium) */
            background-color: #FDFCF5;
        }

        /* --- TES STYLES EXISTANTS --- */
        .dragon-gold {
            background: linear-gradient(
                90deg,
                #FFD700 0%,
                #FFEC8B 25%,
                #FFF8DC 50%,
                #FFEC8B 75%,
                #FFD700 100%
            );
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 900;
            background-size: 200% auto;
            animation: shine 3s ease-in-out infinite;
            display: inline-block;
            padding: 0 5px;
        }

        @keyframes shine {
            0%, 100% { background-position: 0% center; }
            50% { background-position: 100% center; }
        }

        .dragon-glow {
            text-shadow:
                0 0 15px rgba(255, 215, 0, 0.7),
                0 0 30px rgba(255, 215, 0, 0.4),
                0 0 45px rgba(255, 215, 0, 0.2);
        }

        /* Pour le footer */
        .footer-dragon {
            color: #FFD700;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    @if (!Request::is('/'))
    <nav class="navbar navbar-expand-lg navbar-dark bg-dragon shadow sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase dragon-glow" href="{{ route('accueil.index') }}">
                <span class="dragon-gold">🐉 Dragon d'Or</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#monMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="monMenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link fw-bold mx-2" href="{{ route('accueil.index') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold mx-2" href="{{ route('menu.index') }}">La Carte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold mx-2" href="{{ route('contact.index') }}">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold mx-2" href="{{ route('cart.show') }}">
                            🛒 Panier
                            @if(session('cart'))
                                <span class="badge bg-white text-dark ms-1">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-light text-dark fw-bold btn-sm rounded-pill px-3" href="/admin/commandes">
                            👨‍🍳 Cuisinier
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endif

    <main class="flex-grow-1 py-4">
        @yield('content')
    </main>

    <footer class="main-footer text-center py-4 bg-dark text-white mt-auto">
        <div class="container">
            <h3 class="fw-bold mb-3">
                <span class="dragon-gold">🐉 Dragon d'Or</span>
            </h3>
            <p class="mb-4">Le meilleur de la cuisine asiatique, directement chez vous.</p>

            <div class="footer-links mb-4">
                <a href="{{ route('menu.index') }}" class="text-warning text-decoration-none mx-3">La carte</a>
                <span class="text-secondary">•</span>
                <a href="{{ route('contact.index') }}" class="text-warning text-decoration-none mx-3">Contact</a>
                <span class="text-secondary">•</span>
                <a href="{{ route('accueil.index') }}" class="text-warning text-decoration-none mx-3">Accueil</a>
            </div>

            <p class="text-secondary small mb-0">
                © {{ date('Y') }} <span class="footer-dragon">Dragon d'Or</span>. Tous droits réservés.
            </p>
        </div>
    </footer>

</body>
</html>

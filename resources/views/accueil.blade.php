@extends('layout')

@section('css')
<style>

    .dragon-shine {
        display: inline-block;
        font-size: 4rem;
        animation: dragonPulse 2s infinite alternate ease-in-out;
        cursor: default;
    }

    @keyframes dragonPulse {
        0% {
            transform: scale(1);
            filter: drop-shadow(0 0 5px rgba(234, 179, 8, 0.5)); /* Ombre dorée légère */
        }
        100% {
            transform: scale(1.1); /* Il grandit un peu */
            filter: drop-shadow(0 0 20px rgba(217, 220, 38, 0.805)); /* Ombre rouge brillante */
        }
    }

    /* 2. EFFET HOVER SUR LES CARTES */
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    /* 3. FOND DÉGRADÉ PREMIUM */
    .premium-bg {
        background: radial-gradient(circle at center, #fffbeb 0%, #fef3c7 100%);
    }
</style>
@endsection

@section('content')

<div class="d-flex flex-column align-items-center justify-content-center premium-bg py-5" style="min-height: 85vh;">

    <div class="text-center mb-5">
        <div class="mb-2">
            <span class="dragon-shine">🐉</span>
        </div>

        <h1 class="display-4 fw-bold text-uppercase" style="color: var(--dragon-red); letter-spacing: 2px;">
            Dragon d'Or
        </h1>
        <p class="lead text-muted">L'excellence asiatique à votre portée</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm text-center mb-4 fade show" role="alert" style="max-width: 600px;">
            <strong>✅ Parfait !</strong> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 mb-5 text-center bg-white" style="max-width: 800px; width: 95%;">

        <div class="card-body">
            <img src="{{ asset('images/download.jpg') }}"
                alt="Logo Dragon d'Or"
                class="rounded-circle border-4 border-warning shadow-sm mb-4"

                style="width: 120px; height: 120px; object-fit: cover;">

            <h2 class="h3 fw-bold mb-3">Bienvenue !</h2>

            <p class="text-secondary mb-4 mx-auto" style="max-width: 600px;">
                Savourez nos spécialités asiatiques authentiques, préparées avec passion par nos chefs expérimentés. Commandez en ligne et retirez votre repas sans attente.
            </p>

            <div class="d-flex justify-content-center gap-4 mb-4 flex-wrap">
                <div class="text-center">
                    <span class="fs-4">🍜</span> <br> <span class="small fw-bold text-muted">Frais</span>
                </div>
                <div class="text-center">
                    <span class="fs-4">⏰</span> <br> <span class="small fw-bold text-muted">Rapide</span>
                </div>
                <div class="text-center">
                    <span class="fs-4">🌟</span> <br> <span class="small fw-bold text-muted">Qualité</span>
                </div>
            </div>

            <a href="{{ route('menu.index') }}" class="btn btn-dragon btn-lg px-5 py-3 rounded-pill shadow fs-5">
                VOIR LA CARTE & COMMANDER
            </a>
        </div>
    </div>

    <div class="container" style="max-width: 1000px;">
        <div class="row g-4">

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center py-3 hover-card" style="border-top: 4px solid var(--gold);">
                    <div class="card-body">
                        <div class="fs-2 mb-2">📍</div>
                        <h5 class="fw-bold">Adresse</h5>
                        <p class="text-muted small mb-0">123 Rue de l'Asie<br>75000 Paris</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center py-3 hover-card" style="border-top: 4px solid var(--gold);">
                    <div class="card-body">
                        <div class="fs-2 mb-2">🕐</div>
                        <h5 class="fw-bold">Horaires</h5>
                        <p class="text-muted small mb-0">Ouvert 7j/7<br>11h00 - 23h00</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <a href="{{ route('contact.index') }}" class="text-decoration-none text-dark">
                    <div class="card h-100 border-0 shadow-sm text-center py-3 hover-card" style="border-top: 4px solid var(--dragon-red); background-color: #fffdfd;">
                        <div class="card-body">
                            <div class="fs-2 mb-2">📞</div>
                            <h5 class="fw-bold">Contact</h5>
                            <p class="text-muted small mb-0">Une question ?<br><span class="text-decoration-underline text-danger">Envoyer un message</span></p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>

</div>

@endsection

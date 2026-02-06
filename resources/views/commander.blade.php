@extends('layout')

@section('css')
    @vite(['resources/css/app.css'])
@endsection

@section('content')

    <div class="container" style="max-width: 900px;">

        <h1 class="text-center mb-5 fw-bold text-uppercase" style="color: var(--dragon-red);">
            Validation de la Commande 🍜
        </h1>

        <div class="row g-4">

            <div class="col-md-7 col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-4 fw-bold border-bottom pb-2">Vos Coordonnées</h2>

                        <form action="{{ route('order.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="form-label fw-bold">Nom</label>
                                    <input type="text" name="nom" id="nom" class="form-control" placeholder="Votre nom" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="prenom" class="form-label fw-bold">Prénom</label>
                                    <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Votre prénom" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label fw-bold">Téléphone</label>
                                <input type="tel" name="telephone" id="telephone" class="form-control" placeholder="06 12 34 56 78" required>
                                <div class="form-text text-muted">Nous vous appellerons uniquement si besoin.</div>
                            </div>

                            <div class="mb-4">
                                <label for="heure_retrait" class="form-label fw-bold">🕒 Heure de retrait souhaitée</label>
                                <input type="time" name="heure_retrait" id="heure_retrait" class="form-control" min="11:00" max="22:30" required>
                            </div>

                            <button type="submit" class="btn btn-dragon w-100 py-3 fs-5 rounded-3 shadow-sm">
                                Confirmer la commande
                            </button>

                            <div class="text-center mt-3">
                                <a href="{{ route('cart.show') }}" class="text-decoration-none text-secondary">
                                    ← Modifier mon panier
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-5 col-lg-4">
                <div class="card shadow border-0" style="background-color: #fffdf5;"> <div class="card-body p-4">
                        <h3 class="h5 mb-4 fw-bold text-center text-uppercase">Récapitulatif</h3>

                        <ul class="list-group list-group-flush bg-transparent mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                                Nombre d'articles
                                <span class="badge bg-secondary rounded-pill">{{ count(session('cart', [])) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                                Paiement
                                <span class="fw-bold text-success">Au comptoir</span>
                            </li>
                        </ul>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                            <span class="fs-5 fw-bold">Total à payer</span>
                            <span class="fs-3 fw-bold" style="color: var(--gold);">
                                {{ number_format($total_general, 2) }} €
                            </span>
                        </div>

                        <div class="alert alert-light border mt-3 text-center small text-muted">
                            <small>Le paiement s'effectuera lors du retrait de votre commande.</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

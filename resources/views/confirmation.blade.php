@extends('layout')

@section('css')
<style>
    /* CADRE PRINCIPAL */
    .confirm-card {
        border: 2px solid var(--gold);
        border-radius: 20px;
        background: white;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    /* BOITE DE STATUT */
    .status-box {
        padding: 25px;
        border-radius: 15px;
        margin: 25px 0;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 1.1rem;
    }

    /* 1. EN ATTENTE */
    .status-en_attente {
        background-color: #fffbeb;
        color: #92400e;
        border: 2px solid var(--gold) !important;
    }

    /* 2. EN CUISINE (C'est ici qu'on applique la classe en_preparation) */
    .status-en_preparation {
        background-color: #f0fdf4;
        color: #166534;
        border: 2px solid #22c55e !important;
    }

    /* 3. PRÊTE */
    .status-prete {
        background-color: #fff5f5;
        color: var(--dragon-red);
        border: 3px solid var(--gold) !important;
        animation: pulse-gold 2s infinite;
    }

    /* 4. RÉCUPÉRÉE (Finie) */
    .status-recuperee {
        background-color: #f3f4f6;
        color: #6b7280;
        border: 2px solid #d1d5db !important;
    }

    @keyframes pulse-gold {
        0% { box-shadow: 0 0 0 0px rgba(234, 179, 8, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(234, 179, 8, 0); }
        100% { box-shadow: 0 0 0 0px rgba(234, 179, 8, 0); }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">

            <div class="confirm-card">
                <div class="mb-4">
                    <span style="font-size: 4rem; filter: drop-shadow(0 0 10px rgba(234, 179, 8, 0.4));">🐉</span>
                </div>

                <h1 class="fw-bold mb-2" style="color: var(--dragon-red);">Suivi de votre commande</h1>
                <p class="text-muted mb-4">Commande n° <strong>#{{ $commande->id }}</strong></p>

                <hr class="my-4" style="border-color: var(--gold); opacity: 0.3;">

                <div class="status-box status-{{ $commande->statut }}">
                    @if($commande->statut == 'en_attente')
                        ⏳ Reçue : En attente du cuisinier
                    @elseif($commande->statut == 'en_preparation')
                        👨‍🍳 En cuisine : Le chef prépare vos plats
                    @elseif($commande->statut == 'prete')
                        🥡 Prête : Vous pouvez venir la récupérer !
                    @elseif($commande->statut == 'recuperee')
                        🏁 Terminée : Votre plat a bien été récupéré.
                    @endif
                </div>

                <div class="mt-4 pt-2">
                    @if($commande->statut != 'recuperee')
                        <p class="small text-muted mb-3">
                            <i>La page se met à jour automatiquement pour vous avertir.</i>
                        </p>
                        <div class="spinner-grow text-warning" role="status" style="width: 1rem; height: 1rem;"></div>
                    @else
                        <p class="fw-bold text-success">Merci de votre visite et à bientôt !</p>
                    @endif
                </div>

                <div class="mt-5">
                    <a href="{{ route('menu.index') }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                        Retour au menu
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Rafraîchit la page toutes les 15 secondes
    setInterval(function(){
        // On arrête de rafraîchir si la commande est récupérée (fin de cycle)
        @if($commande->statut != 'recuperee')
            window.location.reload();
        @endif
    }, 15000);
</script>

@endsection

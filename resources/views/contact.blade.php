@extends('layout')

@section('css')
    @vite(['resources/css/app.css'])
@endsection

@section('content')

    <div class="container" style="max-width: 800px;">

        <div class="text-center mb-5">
            <h1 class="fw-bold text-uppercase" style="color: var(--dragon-red);">
                Contactez-nous ✉️
            </h1>
            <p class="text-muted">Une question sur nos allergènes ou une commande ?</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong>✅ Succès !</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

                <div class="card shadow border-0">
                    <div class="card-body p-4 p-md-5">

                        <h2 class="h4 card-title fw-bold mb-4 text-center">Posez votre question ici</h2>

                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="nom" class="form-label fw-bold">Votre Nom</label>
                                <input type="text" name="nom" id="nom" class="form-control" placeholder="Votre nom" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Votre Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="email@exemple.com" required>
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label fw-bold">Votre Message</label>
                                <textarea name="message" id="message" rows="5" class="form-control" placeholder="Bonjour, j'aimerais savoir si..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-dragon w-100 py-3 fs-5 rounded-3 shadow-sm">
                                Envoyer ma question 🚀
                            </button>
                        </form>

                    </div>
                </div>

                <div class="text-center mt-4 text-muted">
                    <small>Ou appelez-nous directement au <strong>01 23 45 67 89</strong></small>
                </div>

            </div>
        </div>

    </div>

@endsection

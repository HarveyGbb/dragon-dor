<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Dragon d'Or</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg border-0 rounded-4" style="width: 100%; max-width: 450px;">

        <div class="card-header bg-dark text-warning text-center py-4 rounded-top-4 border-0">
            <h2 class="mb-0 fw-bold">🐉 Dragon d'Or</h2>
            <p class="mb-0 text-light mt-1">Espace Administration</p>
        </div>

        <div class="card-body p-4 p-md-5">

            <x-auth-session-status class="alert alert-success mb-4" :status="session('status')" />
            @if ($errors->any())
                <div class="alert alert-danger pb-0">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Adresse Email</label>
                    <input id="email" type="email" class="form-control form-control-lg" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Mot de passe</label>
                    <input id="password" type="password" class="form-control form-control-lg" name="password" required>
                </div>

                <div class="mb-4 form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label" for="remember_me">Se souvenir de moi</label>
                </div>

                <div class="d-grid gap-3">
                    <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark shadow-sm">
                        Se connecter
                    </button>

                    @if (Route::has('password.request'))
                        <a class="text-center text-decoration-none text-muted mt-2" href="{{ route('password.request') }}">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de Commande - Dragon d'Or</title>
    <!-- CSS Global (si vous en avez un) -->
 <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- JUSTE cette ligne pour le CSS spécifique -->
    <link rel="stylesheet" href="{{ asset('css/commande.css') }}">
</head>
<body>

    <!-- VOTRE FORMULAIRE EXISTANT (sans styles inline) -->
    <form action="{{ route('order.store') }}" method="POST" class="order-form">
        @csrf

        <div class="form-group">
            <label for="nom_client" class="form-label">Votre Nom :</label>
            <input type="text" name="nom_client" id="nom_client" class="form-input" required>
        </div>

        <div class="form-group">
            <label for="telephone" class="form-label">Téléphone (Confirmation) :</label>
            <input type="tel" name="telephone" id="telephone" class="form-input" placeholder="06..." required>
        </div>

        <div class="form-group">
            <label for="heure_retrait" class="form-label">Heure de retrait souhaitée :</label>
            <input type="time" name="heure_retrait" id="heure_retrait" class="form-input" min="11:30" max="22:00" required>
        </div>

        <button type="submit" class="submit-button">Confirmer la commande</button>
    </form>

</body>
</html>


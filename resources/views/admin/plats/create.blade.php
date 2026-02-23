<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Plat - Dragon d'Or</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css'])
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-warning text-dark fw-bold text-center py-3">
                     AJOUTER UN NOUVEAU PLAT
                </div>
                <div class="card-body p-4">

                    <form action="{{ route('admin.plats.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Nom -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nom du plat</label>
                            <input type="text" name="nom" class="form-control" required placeholder="Ex: Porc au Caramel">
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3" required placeholder="Ingrédients, saveurs..."></textarea>
                        </div>

                        <!-- Ligne Prix / Stock / Catégorie -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Prix (€)</label>
                                <input type="number" step="0.01" name="prix" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Stock initial</label>
                                <input type="number" name="stock" class="form-control" value="10" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Catégorie</label>
                                <select name="categorie_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="mb-4">
                            <label class="form-label fw-bold">Photo du plat</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <div class="form-text">Format acceptés : JPG, PNG.</div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dark btn-lg fw-bold">Enregistrer le plat</button>
                            <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>

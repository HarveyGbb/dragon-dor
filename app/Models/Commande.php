<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    // Nom de la table (confirmé : 'commande' au singulier)
    protected $table = 'commande';

    // Mappage des dates personnalisées (date_creation)
    const CREATED_AT = 'date_creation';
    const UPDATED_AT = null;

    // Les champs que vous avez ajoutés et qui sont obligatoires pour l'enregistrement (y compris l'invité)
    protected $fillable = [
        'client_id',      // NULL pour les invités (Click & Collect)
        'nom_client',     // Champ invité ajouté
        'telephone',      // Champ invité ajouté
        'heure_retrait',  // Champ invité ajouté
        'total',
        'statut',
        'date_creation'
    ];
}


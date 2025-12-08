<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    // C'est crucial : lie le modèle à votre table 'plat' singulière
    protected $table = 'plat';

    // Indique à Laravel que la table n'a pas de colonnes created_at/updated_at
    public $timestamps = false;

    // Les champs que vous utilisez (à vérifier avec votre BDD)
    protected $fillable = ['nom', 'description', 'prix', 'categorie', 'id'];
}

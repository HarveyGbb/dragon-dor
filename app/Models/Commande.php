<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commande';


    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'heure_retrait',
        'total',
        'statut',
    ];

    public function plats()
    {
        return $this->belongsToMany(Plat::class, 'commande_plat', 'commande_id', 'plat_id')
                    ->withPivot('quantite', 'prix_unitaire');
    }
}

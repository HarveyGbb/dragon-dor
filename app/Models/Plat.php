<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    protected $table = 'plat';

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'image_url', // Vérifie que c'est bien le nom en base de données
        'disponible',
        'categorie_id',
        'stock'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'disponible' => 'boolean',
        'stock' => 'integer'
    ];

    public function laCategorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_plat', 'plat_id', 'commande_id')
                    ->withPivot('quantite', 'prix_unitaire');
    }

    public function getPrixFormateAttribute()
    {
        return number_format($this->prix, 2, ',', ' ') . ' €';
    }
}

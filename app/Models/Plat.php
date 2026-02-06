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
        'image_url',
        'disponible',
        'categorie_id'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'disponible' => 'boolean'
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

    public function estDisponible()
    {
        return $this->disponible === true;
    }

    public function getPrixFormateAttribute()
    {
        return number_format($this->prix, 2, ',', ' ') . ' €';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bassin extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_bassin',
        'seuil_temperature',
        'seuil_ph',
        'ip_arduino',
        'frequence_retrieval',
    ];

    public function mesures(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Mesure::class);
    }
}

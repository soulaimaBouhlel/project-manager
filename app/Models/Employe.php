<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    // Define the relationship with Projet (many-to-many)
    public function projets()
    {
        return $this->belongsToMany(Projet::class, 'projet_employe');
    }

    // Define the relationship with Tache (one-to-many)
    public function taches()
    {
        return $this->hasMany(Tache::class);
    }

    // Define the relationship with Equipement (many-to-many)
    public function equipements()
    {
        return $this->belongsToMany(Equipement::class);
    }
}

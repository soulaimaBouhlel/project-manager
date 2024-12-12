<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    public function taches()
    {
        return $this->hasMany(Tache::class);
    }

    // Define the relationship with Equipement
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }

    // Define the relationship with Employe
    public function employes()
    {
        return $this->belongsToMany(Employe::class, 'projet_employe');
    }
}

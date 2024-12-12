<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChefDeProjet extends Model
{
    public function projets()
    {
        return $this->hasMany(Projet::class);
    }}

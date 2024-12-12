<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsableMateriel extends Model
{
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }}

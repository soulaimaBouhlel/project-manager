<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsableMateriel extends Model
{
    use HasFactory;

    protected $table = 'responsable_materiels';
    protected $fillable = [
        'name',      // User's name
        'email',     // Email address
        'telephone', // Telephone number
        'password',  // Password field
        'user_id',   // Foreign key reference to the users table
    ];
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }
}

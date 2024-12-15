<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChefDeProjet extends Model
{
    use HasFactory;

    protected $table = 'chef_de_projets'; // Ensure this matches your table name

    // Add fillable attributes
    protected $fillable = [
        'name',      // User's name
        'email',     // Email address
        'telephone', // Telephone number
        'password',  // Password field
        'user_id',   // Foreign key reference to the users table
    ];
    public function projets()
    {
        return $this->hasMany(Projet::class);
    }}

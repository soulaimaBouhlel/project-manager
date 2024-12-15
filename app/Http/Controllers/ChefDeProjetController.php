<?php

namespace App\Http\Controllers;

use App\Models\ChefDeProjet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use App\Services\XmlManager;

class ChefDeProjetController extends Controller
{


    public function create( $user)
    {
        $password = isset($user['password']) ? bcrypt($user['password']) : bcrypt('defaultpassword');
        ChefDeProjet::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'telephone' => $user['telephone'], // Include optional telephone field
            'password' => $password,

        ]);
        return response()->json(['message' => 'Chef de projet created successfully!']);
    }
}

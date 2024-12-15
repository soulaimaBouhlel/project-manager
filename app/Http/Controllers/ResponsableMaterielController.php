<?php

namespace App\Http\Controllers;

use App\Models\ResponsableMateriel;
use Illuminate\Http\Request;
use App\Services\XmlManager;

class ResponsableMaterielController extends Controller
{

    public function create( $user)
    {
        $password = isset($user['password']) ? bcrypt($user['password']) : bcrypt('defaultpassword');
        ResponsableMateriel::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'telephone' => $user['telephone'], // Include optional telephone field
            'password' => $password,

        ]);
        return response()->json(['message' => 'Responsable materiel created successfully!']);
    }
}

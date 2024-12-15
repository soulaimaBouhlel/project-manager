<?php

namespace App\Http\Controllers;


use App\Models\ResponsableRH;
use Illuminate\Http\Request;
use App\Services\XmlManager;

class ResponsableRHController extends Controller
{
        public function create( $user)
    {
        $password = isset($user['password']) ? bcrypt($user['password']) : bcrypt('defaultpassword');
        ResponsableRH::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'telephone' => $user['telephone'], // Include optional telephone field
            'password' => $password,

        ]);
        return response()->json(['message' => 'Responsable RH created successfully!']);
    }
}

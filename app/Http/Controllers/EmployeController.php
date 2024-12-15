<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\XmlManager;

class EmployeController extends Controller
{

   
    public function index()
    {
        $employes = Employe::all();
        return view('employes.index', compact('employes'));
    }

    
    public function create()
    {
        return view('employes.create');
    }

  
    public function store(Request $request) {
       
    }

    public function show($id) {
       
    }


  
}

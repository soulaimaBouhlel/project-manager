<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\XmlManager;

class ResponsableMaterielController extends Controller
{
    private $xmlManager;

    public function __construct()
    {
        $this->xmlManager = new XmlManager(storage_path('responsables_materiel.xml'));
    }

    public function create(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string',
        ]);

        // Prepare the data for XML
        $data = [
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
        ];

        // Serialize and save to XML
        $existingData = $this->xmlManager->deserializeFromXml();
        $existingData[] = $data;

        $this->xmlManager->saveXmlToFile($this->xmlManager->serializeToXml($existingData));

        return response()->json(['message' => 'Responsable Materiel created successfully!']);
    }
}

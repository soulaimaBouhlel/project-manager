<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\XmlManager;

class EmployeController extends Controller
{
    private $xmlManager;

    public function __construct()
    {
        $this->xmlManager = new XmlManager(storage_path('employes.xml'));
    }

    public function create(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'competences' => 'required|string',
            'certifications' => 'required|string',
        ]);

        // Prepare the data for XML
        $data = [
            'nom' => $validated['nom'],
            'competences' => $validated['competences'],
            'certifications' => $validated['certifications'],
        ];

        // Serialize and save to XML
        $existingData = $this->xmlManager->deserializeFromXml();
        $existingData[] = $data;

        $this->xmlManager->saveXmlToFile($this->xmlManager->serializeToXml($existingData));

        return response()->json(['message' => 'Employe created successfully!']);
    }
}

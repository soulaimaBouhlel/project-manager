<?php

namespace App\Http\Controllers;
use App\Models\Projet;
use App\Services\XmlManager;
use Illuminate\Http\Request;

class ProjetController extends Controller
{
    protected $xmlManager;

    public function __construct(XmlManager $xmlManager)
    {
        $this->xmlManager = $xmlManager;
    }

    public function store(Request $request)
    {
        // Get the XML content from the request
        $xmlContent = $request->getContent();

        // Path to the XSD schema for project creation
        $xsdPath = storage_path('schema/projects.xsd'); // Update path as needed

        try {
            // Validate the XML content against the XSD schema
            $this->xmlManager->validateXML($xmlContent, $xsdPath);

            // Parse the XML content into an associative array
            $data = $this->xmlManager->parseXML($xmlContent);

            // Process each project
            if (isset($data['project'])) {
                $projects = $data['project'];
                if (!isset($projects[0])) {
                    $projects = [$projects]; // Ensure it's an array if only one project
                }

                foreach ($projects as $projectData) {
                    $this->addProjectToDatabase($projectData);
                }
            }

            return response()->json(['message' => 'Projects created successfully.'], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function addProjectToDatabase(array $projectData)
    {
        // Store the project
        Projet::create([
            'name' => $projectData['name'],
            'description' => $projectData['description'],
            'start_date' => $projectData['start_date'],
            'end_date' => $projectData['end_date'],
        ]);
    }
}

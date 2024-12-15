<?php


namespace App\Http\Controllers;
use App\Models\Employe;
use App\Models\Equipement;
use App\Services\XmlManager;
use Illuminate\Http\Request;

class EquipementController extends Controller
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

        // Path to XSD schema for validation
        $xsdPath = storage_path('schema/equipments.xsd'); // Make sure the XSD path is correct

        try {
            // Validate the XML content against the XSD schema
            $this->xmlManager->validateXML($xmlContent, $xsdPath);

            // Parse the XML content into an associative array
            $data = $this->xmlManager->parseXML($xmlContent);

            // Check if 'equipment' exists in the parsed data
            if (isset($data['equipment'])) {
                $equipments = $data['equipment'];
                if (!isset($equipments[0])) {
                    $equipments = [$equipments]; // Ensure it's an array even if only one equipment is provided
                }

                // Loop through each equipment and store it in the database
                foreach ($equipments as $equipment) {
                    $this->addEquipmentToDatabase($equipment); // Add equipment to the database
                }
            }

            return response()->json([
                'message' => 'Equipments processed and added successfully.',
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function addEquipmentToDatabase(array $equipment)
    {
        // Store the equipment in the database
        Equipement::create([
            'name' => $equipment['name'],
            'type' => $equipment['type'],
            'serial_number' => $equipment['serial_number'],
            'purchase_date' => $equipment['purchase_date'],
            'status' => $equipment['status'],
        ]);
    }
    public function assignEquipments(Request $request)
    {
        $xmlContent = $request->getContent();
        $xsdPath = storage_path('schema/association.xsd'); // Ensure the XSD file path is correct

        try {
            $this->xmlManager->validateXML($xmlContent, $xsdPath);

            // Parse XML
            $data = $this->xmlManager->parseXML($xmlContent);

            if (isset($data['association'])) {
                $associations = $data['association'];
                if (!isset($associations[0])) {
                    $associations = [$associations]; // Ensure it's an array
                }

                foreach ($associations as $association) {
                    $employeeId = $association['employe_id'];
                    $equipmentIds = $association['equipments']['equipement_id'];

                    if (!is_array($equipmentIds)) {
                        $equipmentIds = [$equipmentIds]; // Ensure it's an array
                    }

                    // Attach equipment to the employee
                    $employee = Employe::findOrFail($employeeId);
                    $employee->equipements()->attach($equipmentIds);
                }
            }

            return response()->json([
                'message' => 'Equipments successfully assigned to employees.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function addEquipmentToEmployee(array $associations)
    {
        // Extract employee ID and equipment IDs
        foreach ($associations as $association) {
            $employeeId = $association['employee_id'];
            $equipmentIds = $association['equipement_ids']['equipement_id'];

            if (!is_array($equipmentIds)) {
                $equipmentIds = [$equipmentIds]; // Ensure it's an array
            }

            // Attach equipment to the employee
            $employee = Employe::findOrFail($employeeId);
            $employee->equipements()->attach($equipmentIds);
        }

        // Attach the equipment to the employee using the pivot table
        $employee->equipements()->syncWithoutDetaching($equipmentIds);
    }

}

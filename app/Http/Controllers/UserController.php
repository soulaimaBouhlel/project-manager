<?php
namespace App\Http\Controllers;

use App\Models\User;  // Assuming you have a User model for database interactions
use App\Services\XmlManager;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $xmlManager;

    public function __construct(XmlManager $xmlManager)
    {
        $this->xmlManager = $xmlManager;
    }

    public function store(Request $request)
    {
        // Get the XML content directly from the request body
        $xmlContent = $request->getContent();

        // Path to XSD schema for validation
        $xsdPath = storage_path('schema/users.xsd'); // Ensure the XSD path is correct

        try {
            // Validate the XML content
            $this->xmlManager->validateXML($xmlContent, $xsdPath);

            // Parse the XML content into an associative array
            $data = $this->xmlManager->parseXML($xmlContent);

            // Check if 'utilisateur' exists in the parsed data
            if (isset($data['utilisateur'])) {
                $users = $data['utilisateur'];
                if (!isset($users[0])) {
                    $users = [$users]; // Ensure it's an array even if only one user is provided
                }

                // Loop through each user and store it in the database
                foreach ($users as $user) {
                    $this->addUserToDatabase($user); // Add user to the database
                }
            }

            return response()->json([
                'message' => 'Users processed and added successfully.',
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Add a user directly to the database.
     *
     * @param array $user
     */
    private function addUserToDatabase(array $user)
    {
        $password = isset($user['password']) ? bcrypt($user['password']) : bcrypt('defaultpassword'); // Set default password

        // Create user in the database
        $newUser = User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'telephone' => $user['telephone'] ?? null, // Include optional telephone field
            'password' => $password,
            'role' => $user['role'] ?? 'Employe', // Default role if not provided
        ]);

        // Call the appropriate controller based on the role
        switch ($newUser->role) {
            case 'chef de projet':
                app(ChefDeProjetController::class)->create($newUser);
                break;

            case 'responsable materiel':
                app(ResponsableMaterielController::class)->create($newUser);
                break;

            case 'responsable rh':
                app(ResponsableRHController::class)->create($newUser);
                break;

            case 'employe':
                app(EmployeController::class)->create($newUser);
                break;

            default:
                throw new \Exception('Invalid role provided.');
        }
    }
}

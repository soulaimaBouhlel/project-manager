<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\Skill;

use Illuminate\Http\Request;
use App\Services\XmlManager;

class EmployeController extends Controller
{

    public function create( $user)
    {
        $password = isset($user['password']) ? bcrypt($user['password']) : bcrypt('defaultpassword');
        $employe = Employe::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'telephone' => $user['telephone'], // Include optional telephone field
            'password' => $password,
            'status' => $userData['status'] ?? 'active',

        ]);
        if (!empty($data['skills'])) {
            foreach ($data['skills'] as $skill) {
                Skill::create([
                    'employe_id' => $employe->id,
                    'skill' => $skill,
                ]);
            }
        }


    }
    public function update(Request $request, $id)
    {
        $employe = Employe::findOrFail($id);

        // Update employee details
        $employe->update($request->only(['name', 'email', 'status']));

        return response()->json([
            'message' => 'Employee updated successfully.',
            'employe' => $employe,
        ]);
    }



    public function activate($id)
    {
        $employe = Employe::findOrFail($id);
        $employe->update(['status' => 'active']);

        return response()->json([
            'message' => 'Employee activated successfully.',
            'employe' => $employe,
        ]);
    }

    /**
     * Set an employee's status to inactive.
     */
    public function deactivate($id)
    {
        $employe = Employe::findOrFail($id);
        $employe->update(['status' => 'inactive']);

        return response()->json([
            'message' => 'Employee deactivated successfully.',
            'employe' => $employe,
        ]);
    }

}

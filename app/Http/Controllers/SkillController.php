<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function store(Request $request, $employeId)
    {
        // Find the employe by ID
        $employe = Employe::findOrFail($employeId);

        // Validate the skills in the request
        $validated = $request->validate([
            'skills' => 'required|array',
            'skills.*' => 'string|max:255', // Ensure each skill is a string
        ]);

        // Save the skills to the database
        foreach ($validated['skills'] as $skill) {
            Skill::create([
                'employe_id' => $employe->id,
                'skill' => $skill,
            ]);
        }

        return response()->json(['message' => 'Skills saved successfully!'], 200);
    }
}

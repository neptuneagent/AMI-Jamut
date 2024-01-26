<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Standard;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function store(Request $request, Standard $standard)
    {
        $request->validate([
            'description' => 'required',
            'weight' => 'integer',
        ]);

        $criterias = $standard->criterias()->create([
            'description' => $request->description,
            'weight' => $request->weight,
        ]);

        return back()->with('success', 'Criteria added successfully.');
    }
    
    public function update(Request $request, Criteria $Criteria)
    {
        $request->validate([
            'description' => 'required',
            'weight' => 'integer',
        ]);

        $Criteria->update($request->all());

        return back()->with('success', 'Criteria updated successfully.');
    }

    public function destroy(Criteria $Criteria)
    {
        $Criteria->delete();

        return back()->with('success', 'Criteria deleted successfully.');
    }
}

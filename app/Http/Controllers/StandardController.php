<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Standard;

class StandardController extends Controller
{
    public function store(Request $request, Question $question)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $question = $question->standards()->create([
            'title' => $request->title,
        ]);

        return back()->with('success', 'Standard added successfully.');
    }
    
    public function update(Request $request, Standard $standard)
    {
        $request->validate([
            'title' => 'required|string',
            // Add other validation rules as needed
        ]);

        $standard->update($request->all());

        return back()->with('success', 'Standard updated successfully.');
    }

    public function destroy(Standard $standard)
    {
        $standard->delete();

        return back()->with('success', 'Standard deleted successfully.');
    }
}

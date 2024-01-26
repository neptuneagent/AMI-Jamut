<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Question;

class QuestionController extends Controller
{
    public function store(Request $request, Form $form)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $question = $form->questions()->create([
            'title' => $request->title,
        ]);

        return back()->with('success', 'Question added successfully.');
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'title' => 'required|string',
            // Add other validation rules as needed
        ]);

        $question->update($request->all());

        return back()->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return back()->with('success', 'Question deleted successfully.');
    }
}

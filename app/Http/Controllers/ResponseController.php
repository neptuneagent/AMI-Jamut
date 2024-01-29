<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Response;
use App\Models\ResponseDetail;
use App\Models\ResponseHistory;

class ResponseController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('prodi')) {
            $responses = Response::where('user_id', $user->id)->get();
        } elseif ($user->hasRole('gkm')) {
            $responses = Response::where('status', 'waiting')->get();
        } elseif ($user->hasRole('auditor')) {
            $responses = Response::where('status', 'completed')->get();
        } elseif ($user->hasRole('jamut')) {
            $responses = Response::all();
        } else {
            $responses = collect(); 
        }

        return view('responses.index', compact('responses'));
    }

    public function show($responseId)
    {
        $response = Response::findOrFail($responseId);

        return view('responses.show', compact('response'));
    }

    public function store(Request $request, $formId)
    {
        // Validate the request
        $request->validate([
            // Add any validation rules as needed
        ]);

        // Create a new response
        $response = Response::create([
            'form_id' => $formId,
            'user_id' => auth()->user()->id, // Assuming you have user authentication
            'submitted_at' => now(),
        ]);

        // Save response details
        foreach ($request->input('criteria_answers') as $criteriaId => $answer) {
            ResponseDetail::create([
                'response_id' => $response->id,
                'criteria_id' => $criteriaId,
                'answer' => $answer,
            ]);
        }

        ResponseHistory::create([
            'response_id' => $response->id,
            'action' => 'submitted the form',
            'user_id' => auth()->user()->id,
        ]);
        
        return redirect()->route('home')->with('success', 'Form submitted successfully!');
    }
}

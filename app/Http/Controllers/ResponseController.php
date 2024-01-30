<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Response;
use App\Models\ResponseDetail;
use App\Models\ResponseHistory;
use App\Models\ResponseEvidence;
use App\Models\ResponseFinding;

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
        } elseif ($user->hasRole('jamut|admin')) {
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

    public function uploadEvidence(Request $request, $responseId)
    {
        $request->validate([
            'evidence_name' => 'required|string',
            'evidence_description' => 'required|string',
            'evidence_file' => 'required|file|mimes:pdf,doc,docx,zip',
        ]);

        $response = Response::findOrFail($responseId);

        $file = $request->file('evidence_file');
        $filePath = $file->store('evidence', 'public');

        $evidence = ResponseEvidence::create([
            'name' => $request->input('evidence_name'),
            'description' => $request->input('evidence_description'),
            'file_path' => $filePath,
            'response_id' => $response->id,
        ]);

        return redirect()->back()->with('success', 'Evidence uploaded successfully!');
    }

    /**
     * Update the specified evidence in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Response  $response
     * @param  \App\Models\ResponseEvidence  $evidence
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEvidence(Request $request, ResponseEvidence $evidence)
    {
        $request->validate([
            'evidence_name' => 'required|string|max:255',
            'evidence_description' => 'required|string',
        ]);

        $evidence->update([
            'name' => $request->input('evidence_name'),
            'description' => $request->input('evidence_description'),
        ]);

        return redirect()->back()->with('success', 'Evidence updated successfully!');
    }

    /**
     * Remove the specified evidence from storage.
     *
     * @param  \App\Models\Response  $response
     * @param  \App\Models\ResponseEvidence  $evidence
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteEvidence(ResponseEvidence $evidence)
    {
        $evidence->delete();

        return redirect()->back()->with('success', 'Evidence deleted successfully!');
    }

    public function markComplete($responseId)
    {
        $response = Response::findOrFail($responseId);

        if ($response->status !== 'waiting') {
            return redirect()->back()->with('error', 'It is against the workflow');
        }

        $response->update(['status' => 'completed']);

        ResponseHistory::create([
            'response_id' => $response->id,
            'action' => 'marked the form as complete',
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('responses.index');
    }

    public function addFinding(Request $request, $responseId)
    {
        $request->validate([
            'finding_description' => 'required|string',
            'criteria_id' => 'required|exists:criterias,id',
            'root_cause' => 'required|string',
            'recommendation' => 'required|string',
            'category' => 'required|in:observation,discrepancy', // Validate the category field
        ]);

        ResponseFinding::create([
            'response_id' => $responseId,
            'description' => $request->input('finding_description'),
            'criteria_id' => $request->input('criteria_id'),
            'root_cause' => $request->input('root_cause'),
            'recommendation' => $request->input('recommendation'),
            'category' => $request->input('category'),
        ]);

        return redirect()->back()->with('success', 'Finding added successfully!');
    }

    public function updateFinding(Request $request, $findingId)
    {
        $request->validate([
            'finding_description' => 'required|string',
            'criteria_id' => 'required|exists:criterias,id',
            'root_cause' => 'required|string',
            'recommendation' => 'required|string',
            'category' => 'required|in:observation,discrepancy', // Validate the category field
        ]);

        $finding = ResponseFinding::findOrFail($findingId);

        $finding->update([
            'description' => $request->input('finding_description'),
            'criteria_id' => $request->input('criteria_id'),
            'root_cause' => $request->input('root_cause'),
            'recommendation' => $request->input('recommendation'),
            'category' => $request->input('category'),
        ]);

        return redirect()->back()->with('success', 'Finding updated successfully!');
    }

    public function deleteFinding($findingId)
    {
        $finding = ResponseFinding::findOrFail($findingId);
        $finding->delete();

        return redirect()->back()->with('success', 'Finding deleted successfully!');
    }

    public function markAudited($responseId)
    {
        $response = Response::findOrFail($responseId);

        if ($response->status !== 'completed') {
            return redirect()->back()->with('error', 'It is against the workflow');
        }

        $response->update(['status' => 'audited']);

        ResponseHistory::create([
            'response_id' => $response->id,
            'action' => 'marked the form as audited',
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('responses.index');
    }

    public function markAsDone($responseId)
    {
        $response = Response::findOrFail($responseId);

        if ($response->status !== 'audited') {
            return redirect()->back()->with('error', 'It is against the workflow');
        }

        $response->update(['status' => 'done']);

        ResponseHistory::create([
            'response_id' => $response->id,
            'action' => 'marked the form as done',
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('responses.index');
    }

    public function edit($responseId)
    {
        $response = Response::findOrFail($responseId);

        if ($response->status !== 'audited') {
            return redirect()->back()->with('error', 'It is against the workflow');
        }

        return view('responses.resubmit', compact('response'));
    }

    public function update(Request $request, $responseId)
    {
        // Validate the request
        $request->validate([
            // Add any validation rules as needed
        ]);

        $response = Response::findOrFail($responseId);

        if ($response->status !== 'audited') {
            return redirect()->back()->with('error', 'It is against the workflow');
        }

        // Save response details
        foreach ($request->input('criteria_answers') as $criteriaId => $answer) {
            ResponseDetail::updateOrCreate(
                ['response_id' => $response->id, 'criteria_id' => $criteriaId],
                ['answer' => $answer]
            );
        }

        $response->update(['status' => 'waiting', 'submitted_at' => now()]);

        ResponseHistory::create([
            'response_id' => $response->id,
            'action' => 'resubmitted the form',
            'user_id' => auth()->user()->id,
        ]);
        
        return redirect()->route('home')->with('success', 'Form resubmitted successfully!');
    }
}

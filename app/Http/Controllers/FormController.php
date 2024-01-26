<?php

// app/Http/Controllers/FormController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::all();
        return view('forms.index', compact('forms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $form = Form::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('forms.show', ['form' => $form]);
    }

    public function show(Form $form)
    {
        // You can show the details of the form here
        return view('forms.show', compact('form'));
    }
    
    public function update(Request $request, Form $form)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $form->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('forms.show', ['form' => $form])->with('success', 'Form updated successfully.');
    }

    public function destroy(Form $form)
    {
        $form->delete();

        return redirect()->back()->with('success', 'Form deleted successfully.');
    }

    public function show_available()
    {
        $forms = Form::all();
        return view('forms.show-available', compact('forms'));
    }

    public function fill(Form $form)
    {
        return view('forms.fill', compact('form'));
    }
}

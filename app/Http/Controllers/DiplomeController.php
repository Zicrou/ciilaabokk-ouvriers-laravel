<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Diplome;

class DiplomeController extends Controller 
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ouvriers.diplomes.index', [
            'diplomes' => Diplome::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create(){
        $diplome = new Diplome();
        return view('ouvriers.diplomes.form', [
            'diplome' => $diplome,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required','string'],
        ]);

        Diplome::create($request->only('nom'));

        return redirect()->route('diplome.index')->with('success', 'Diplome créér avec succés.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $diplome = Diplome::findOrFail($id);
        return view('ouvriers.diplomes.form', compact('diplome'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nom' => ['required','string'],
        ]);

        $diplome = Diplome::findOrFail($id);
        $diplome->update($request->only('nom'));

        return redirect()->route('diplome.index')->with('success', 'Diplome mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $diplome = Diplome::findOrFail($id);
        $diplome->delete();

        return redirect()->route('diplome.index')->with('success', 'Diplome supprimé avec succés.');
    }
}

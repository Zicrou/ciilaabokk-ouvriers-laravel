<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Metier;
use \App\Models\Domaine;
class MetierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ouvriers.metiers.index', [
            'metiers' => Metier::with('domaine')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ouvriers.metiers.form', [
            'domaines' => Domaine::pluck('nom', 'id'),
            'metier' => new Metier()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'domaine_id' => ['uuid', 'required', 'exists:domaines,id'],
        ]);

        Metier::create($request->only('nom', 'domaine_id'));

        return redirect()->route('metier.index')->with('success', 'Metier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $metier = Metier::findOrFail($id)->load('domaine');
        $domaines = Domaine::pluck('nom', 'id');
        return view('ouvriers.metiers.form', ['metier' => $metier, 'domaines' => $domaines]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'domaine_id' => ['uuid', 'required', 'exists:domaines,id'],
        ]);

        $metier = Metier::findOrFail($id);
        $metier->update($request->only('nom', 'domaine_id'));

        return redirect()->route('metier.index')->with('success', 'Metier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $metier = Metier::findOrFail($id);
        $metier->delete();

        return redirect()->route('metier.index')->with('success', 'Metier deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Domaine;
class DomaineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ouvriers.domaines.index', [
            'domaines' => Domaine::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $domaine = new Domaine();
        return view('ouvriers.domaines.form', ['domaine' => $domaine]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
        ]);

        Domaine::create($request->only('nom'));

        return redirect()->route('domaine.index')->with('success', 'Domaine created successfully.');
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
        $domaine = Domaine::findOrFail($id);
        return view('ouvriers.domaines.form', compact('domaine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
        ]);
        $domaine = Domaine::findOrFail($id);
        $domaine->update($request->only('nom'));

        return redirect()->route('domaine.index')->with('success', 'Domaine updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $domaine = Domaine::findOrFail($id);
        $domaine->delete();

        return redirect()->route('domaine.index')->with('success', 'Domaine deleted successfully.');
    }
}

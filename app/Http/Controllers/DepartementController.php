<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Departement;
use \App\Models\Region;
class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ouvriers.departements.index', [
            'departements' => Departement::with('region')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ouvriers.departements.form', [
            'regions' => Region::pluck('nom', 'id'),
            'departement' => new Departement(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'region_id' => ['uuid', 'required', 'exists:regions,id'],
        ]);

        Departement::create($request->only('nom', 'region_id'));

        return redirect()->route('departement.index')->with('success', 'Departement created successfully.');
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
        $departement = Departement::findOrFail($id);
        $regions = Region::pluck('nom', 'id');
        return view('ouvriers.departements.form', ['departement' => $departement, 'regions' => $regions]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nom' => ['required','string','max:255'],
            'region_id' => ['uuid', 'required','exists:regions,id'],
        ]);

        $departement = Departement::findOrFail($id);
        $departement->update($request->only('nom', 'region_id'));

        return redirect()->route('departement.index')->with('success', 'Departement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $departement = Departement::findOrFail($id);
        $departement->delete();

        return redirect()->route('departement.index')->with('success', 'Departement deleted successfully.');
    }
}

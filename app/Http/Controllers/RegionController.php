<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Region;
use \App\Models\Countries;
class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(Region::with('country')->get());
        return view('ouvriers.regions.index', [
            'regions' => Region::with('country')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ouvriers.regions.form', [
            'countries' => Countries::pluck('nom', 'id'),
            'region' => new Region()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'country_id' => ['uuid','required', 'exists:countries,id'],
        ]);

        Region::create($request->only('nom', 'country_id'));

        return redirect()->route('region.index')->with('success', 'Region created successfully.');
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
        $region = Region::findOrFail($id);
        $countries = Countries::pluck('nom', 'id');
        return view('ouvriers.regions.form', [
            'region' => $region, 
            'countries' => $countries,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],            
            'country_id' => ['uuid','required', 'exists:countries,id'],
        ]);

        $region = Region::findOrFail($id);
        $region->update($request->only('nom', 'country_id'));

        return redirect()->route('region.index')->with('success', 'Region updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $region = Region::findOrFail($id);
        $region->delete();

        return redirect()->route('region.index')->with('success', 'Region deleted successfully.');
    }
}

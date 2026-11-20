<?php

namespace App\Http\Controllers;

use App\Http\Requests\OuvrierFormRequest;
use App\Http\Requests\OuvrierUpdateFormRequest;
use App\Models\Ouvrier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\Departement;
use \App\Models\Metier;
use \App\Models\Region;
use \App\Models\Domaine;
use \App\Models\Countries;



class OuvrierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $departements = Departement::all();
        $metiers = Metier::all();
        $regions = Region::all();
        $domaines = Domaine::all();
        $ouvriers = Ouvrier::query()
        ->with([
        'metiers.domaine',
        'region',
        'departement',
        'country',
    ])->paginate(10);
    
        return view('ouvriers.ouvriers.index', [
            'ouvriers' => $ouvriers,
            'departements' => $departements,
            'metiers' => $metiers,
            'regions' => $regions,
            'domaines' => $domaines
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('ouvriers.ouvriers.create', [
            'countries' => Countries::all(),
            'regions' => Region::with('country')->get(),
            'departements' => Departement::all(),
            'metiers' => Metier::pluck('nom', 'id'),
            'domaines' => Domaine::pluck('nom', 'id'),
        ]);
    }

    public function metiersByDomaine(Request $request)
    {
        if ($request->domaine_id == null || $request->domaine_id == ""){
            return Metier::all();
        }
        return Metier::where('domaine_id',$request->domaine_id)->get();
    }

public function departementsByRegion(Request $request)
    {
        if($request->region_id == null || $request->region_id == ""){
            return Departement::all();
        }
        return Departement::where('region_id',$request->region_id)->get();
    }
    

  public function rechercher(Request $request)
    {
        $query = Ouvrier::query();

        $search = $request->query('search');

        if (is_string($search) && trim($search) !== '') {
            $search = trim($search);

            $query->where(function ($query) use ($search) {
                $query->where('nom', 'like', '%' . $search . '%')
                    ->orWhere('telephone', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('domaine_id')) {
            $query->whereHas('domaines', function ($q) use ($request) {
                $q->where('domaines.id', $request->domaine_id);
            });
        }

        if ($request->filled('metier_id')) {
            $query->whereHas('metiers', function ($q) use ($request) {
                $q->where('metiers.id', $request->metier_id);
            });
        }

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('departement_id')) {
            $query->where('departement_id', $request->departement_id);
        }


        $ouvriers = $query->with([
            'domaines',
            'metiers.domaine',
            'region',
            'departement',
            'country',
        ])->paginate(10);;
        $metiers = Metier::all();
        $departements = Departement::all();
        $regions = Region::all();
        $domaines = Domaine::all();

        return view('ouvriers.ouvriers.index', ['ouvriers' => $ouvriers, 'departements' => $departements, 'metiers' => $metiers, 'regions' => $regions, 'domaines' => $domaines]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OuvrierFormRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $ouvrier = Ouvrier::create($data);
        $ouvrier->metiers()->sync($request->validated('metiers'));
        $ouvrier->domaines()->sync($request->validated('domaines'));
        
        return redirect()->route('ouvrier.index')->with('success', 'Ouvrier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ouvrier = Ouvrier::with([
            'metiers.domaine',
            'region',
            'departement',
            'country',
        ])->findOrFail($id);
        // dd($ouvrier);        
        
        return view('ouvriers.ouvriers.show', compact('ouvrier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ouvrier = Ouvrier::findOrFail($id)->load(['region', 'departement', 'country']);
        $selectedmetiers = $ouvrier->metiers->pluck('id');
        $selectedDomaines = $ouvrier->domaines->pluck('id');
        $selectedCountry = $ouvrier->country->pluck('id');
        // dd($ouvrier);
        return view('ouvriers.ouvriers.edit', [
            'ouvrier' => $ouvrier,
            'countries' => Countries::pluck('nom', 'id'),
            'regions' => Region::all(),
            'departements' => Departement::all(),
            'metiers' => Metier::pluck('nom', 'id'),
            'domaines' => Domaine::pluck('nom', 'id'),
            'selectedDomaines' => $selectedDomaines,
            'selectedMetiers' => $selectedmetiers,
            'selectedCountry' => $selectedCountry
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OuvrierUpdateFormRequest $request, string $id)
    {
        $dataOuvrier = $request->validated();
        $ouvrier = Ouvrier::findOrFail($id);
        
        $ouvrier->update($request->only('name', 'country_id', 'region_id', 'departement_id', 'date_of_birth', 'phone_number', 'email', 'address', 'phone_number_2', 'numero_cni', 'photo', 'photo_cni','annees_experience', 'user_id'));
        $ouvrier->metiers()->sync($dataOuvrier['metiers']);
        $ouvrier->domaines()->sync($dataOuvrier['domaines']);
        
        return redirect()->route('ouvrier.show', $ouvrier->id)->with('success', 'Ouvrier updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ouvrier = Ouvrier::findOrFail($id);
        $ouvrier->delete();

        return redirect()->route('ouvrier.index')->with('success', 'Ouvrier deleted successfully.');
    }
}

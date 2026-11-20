<?php

namespace App\Http\Controllers;
use App\Http\Requests\OuvrierFormRequest;
use Illuminate\Http\Request;
use App\Models\Domaine;
use App\Models\Metier;
use App\Models\Ouvrier;
use App\Models\Departement;
use App\Models\Region;
use App\Models\Entreprise;
class OuvrierController extends Controller
{
    public function index(){
        $departements = Departement::all();
        $metiers = Metier::all();
        $regions = Region::all();
        $domaines = Domaine::all();
        $ouvriers = Ouvrier::query()->with([
            'metiers.domaine',
            'region',
            'departement',
        ])->get();
        return view("ouvrier.index", [
            'ouvriers' => $ouvriers,
            'departements' => $departements,
            'metiers' => $metiers,
            'regions' => $regions,
            'domaines' => $domaines
        ]);
    }

    public function create(){
        return view('ouvriers.ouvriers.form', [
            'regions' => Region::all(),
            'departements' => Departement::all(),
            'metiers' => Metier::pluck('nom', 'id'),
            'domaines' => Domaine::pluck('nom', 'id'),
        ]);
    }

    public function metiersParDomaine(Request $request)
    {
        if ($request->domaine_id == null || $request->domaine_id == ""){
            return Metier::all();
        }
        return Metier::where('domaine_id',$request->domaine_id)->get();
    }

public function departementsParRegion(Request $request)
    {
        if($request->region_id == null || $request->region_id == ""){
            return Departement::all();
        }
        return Departement::where('region_id',$request->region_id)->get();
    }
    // Recherche Ouvrier
    public function rechercher(Request $request)
    {
        $query = Ouvrier::query();

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
        ])->get();
        $metiers = Metier::all();
        $departements = Departement::all();
        $regions = Region::all();
        $domaines = Domaine::all();

        return view('ouvriers.index', ['ouvriers' => $ouvriers, 'departements' => $departements, 'metiers' => $metiers, 'regions' => $regions, 'domaines' => $domaines]);
    }


    public function store(OuvrierFormRequest $request){
        try{
            $data = $request->validated();
            $uploadedImages = [];
            // $photoProfile = $request->validated('photo');
            if($request->hasFile('photo')){
                $image = $request->file('photo');
                $path = $image->store('ouvrier_profile', 'public');
                
                $data['photo'] = $path;
                // dd($data['photo']);
            }
            $data['user_id'] = $request->user()->id;
            $ouvrier = \App\Models\Ouvrier::create($data);
            $ouvrier->diplomes()->sync($request->validated('diplomes'));
            $ouvrier->metiers()->sync($request->validated('metiers'));
            $ouvrier->domaines()->sync($request->validated('domaines'));
            
            $entrepriseIds = [];

            foreach ($request->entreprises as $name) {
                if($name != null){
                    $entreprise = Entreprise::firstOrCreate([
                        'name' => $name,
                    ]);
                    $entrepriseIds[] = $entreprise->id;
                }
            }

            $ouvrier->entreprises()->sync($entrepriseIds);

            if($request->hasFile('images')){
                foreach ($request->file('images') as $image) {

                $path = $image->storeAs('portfolios/'. $request->user()->id . '/', 'public');

                $uploadedImages[] = $path;

                }
                
            }
            return redirect()->route('ouvriers.index')->with('success', 'Ouvrier created successfully.');
        }catch(\Throwable $e){
            report($e);
        }
    }

    public function edit(Metier $metier){
        $domaines = Domaine::all();
        return view('metier.form', [
            'metier' => $metier, 
            'domaines' => $domaines
        ]);
    }

    public function update(MetierFormRequest $request, Domaine $domaine){
        try{
            $domaine->update($request->validated());
            return to_route('domaine.index')->with('success', 'Le domaine a été modifié');
        }catch(\Throwable $e){
            report($e);
        }
    }
            
    public function destroy(Domaine $domaine)
    {
        $domaine->delete();
        return to_route('domaine.index')->with('success', 'Le domaine a été supprimé');
    }
}


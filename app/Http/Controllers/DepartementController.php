<?php

namespace App\Http\Controllers;
use App\Http\Requests\DepartementformRequest;
use Illuminate\Http\Request;
use App\Models\Departement;
use App\Models\Region;
class DepartementController extends Controller
{
    public function index(){
        $departements = Departement::all();
        return view("ouvriers.departements.index", [
            "departements" => $departements,
        ]);
    }

    public function create(){
        $departement = new Departement();
        $regions = Region::all();
        return view('ouvriers.departements.form', [
            'departement' => $departement,
            'regions' => $regions,
        ]);
    }

    public function store(DepartementFormRequest $request){
        try{
            $departement = Departement::create($request->validated());
            return to_route('departement.index')->with('success', 'Le departement a été créé');
        }catch(\Throwable $e){
            report($e);
        }
    }

    public function edit(Departement $departement){
        $regions = Region::all();
        return view('ouvriers.departements.form', [
            'departement' => $departement, 
            'regions' => $regions
        ]);
    }

    public function update(DepartementFormRequest $request, Departement $departement){
        try{
            $departement->update($request->validated());
            return to_route('departement.index')->with('success', 'Le departement a été modifié');
        }catch(\Throwable $e){
            report($e);
        }
    }
            
    public function destroy(Departement $departement)
    {
        $departement->delete();
        return to_route('departement.index')->with('success', 'Le departement a été supprimé');
    }
}

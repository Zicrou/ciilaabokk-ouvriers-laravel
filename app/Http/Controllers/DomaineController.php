<?php

namespace App\Http\Controllers;
use App\Http\Requests\DomaineFormRequest;
use Illuminate\Http\Request;
use App\Models\Domaine;
class DomaineController extends Controller
{
    public function index(){
        $domaines = Domaine::all();
        return view("ouvriers.domaines.index", [
            "domaines" => $domaines,
        ]);
    }

    public function create(){
        $domaine = new Domaine();
        return view('ouvriers.domaines.form', [
            'domaine' => $domaine,
        ]);
    }

    public function store(DomaineFormRequest $request){
        try{
            $domaine = Domaine::create($request->validated());
            return to_route('domaine.index')->with('success', 'Le domaine a été créé');
        }catch(\Throwable $e){
            report($e);
        }
    }

    public function edit(Domaine $domaine){
        return view('ouvriers.domaines.form', [
            'domaine' => $domaine, 
        ]);
    }

    public function update(DomaineFormRequest $request, Domaine $domaine){
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

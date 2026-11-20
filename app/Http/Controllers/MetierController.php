<?php

namespace App\Http\Controllers;
use App\Http\Requests\MetierFormRequest;
use Illuminate\Http\Request;
use App\Models\Domaine;
use App\Models\Metier;
class MetierController extends Controller
{
    public function index(){
        $metiers = Metier::all();
        return view("ouvriers.metiers.index", [
            "metiers" => $metiers,
        ]);
    }

    public function create(){
        $metier = new metier();
        $domaines = Domaine::all();
        return view('ouvriers.metiers.form', [
            'metier' => $metier,
            'domaines' => $domaines,
        ]);
    }

    public function store(MetierFormRequest $request){
        try{
            $metier = Metier::create($request->validated());
            $domaine = Metier::create($request->validated());
            return to_route('metier.index')->with('success', 'Le metier a été créé');
        }catch(\Throwable $e){
            report($e);
        }
    }

    public function edit(Metier $metier){
        $domaines = Domaine::all();
        return view('ouvriers.metiers.form', [
            'metier' => $metier, 
            'domaines' => $domaines
        ]);
    }

    public function update(MetierFormRequest $request, Metier $metier){
        try{
            $metier->update($request->validated());
            return to_route('metier.index')->with('success', 'Le metier a été modifié');
        }catch(\Throwable $e){
            report($e);
        }
    }
            
    public function destroy(Metier $metier)
    {
        $metier->delete();
        return to_route('metier.index')->with('success', 'Le metier a été supprimé');
    }
}

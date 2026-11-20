<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegionFormRequest;
use Illuminate\Http\Request;
use App\Models\Region;
class RegionController extends Controller
{
    public function index(){
        $regions = Region::all();
        return view("ouvriers.regions.index", [
            "regions" => $regions,
        ]);
    }

    public function create(){
        $region = new Region();
        return view('ouvriers.regions.form', [
            'region' => $region,
        ]);
    }

    public function store(RegionFormRequest $request){
        $validated = $request->validated();
        try{
            $region = Region::create($validated);
            return to_route('region.index')->with('success', 'La region a été créée');
        }catch(\Throwable $e){
            report($e);
        }
    }

    public function edit(Region $region){
        return view('ouvriers.regions.form', [
            'region' => $region, 
        ]);
    }

    public function update(RegionFormRequest $request, Region $region){
        $validated = $request->validated();
        try{
            $region->update($request->validated());
            return to_route('region.index')->with('success', 'La region a été modifiée');
        }catch(\Throwable $e){
            report($e);
        }
    }
            
    public function destroy(Region $region)
    {
        $region->delete();
        return to_route('region.index')->with('success', 'La regio a été supprimée');
    }
}

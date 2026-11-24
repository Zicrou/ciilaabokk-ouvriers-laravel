<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OuvrierFormRequest;
use App\Http\Requests\OuvrierUpdateFormRequest;
use App\Models\Ouvrier;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\Departement;
use \App\Models\Metier;
use \App\Models\Region;
use \App\Models\Domaine;
use \App\Models\Countries;
use App\Http\Controllers\Controller;



class OuvrierController extends Controller
{
    private const INITIAL_SEARCH_RADIUS_KM = 1;
    private const SEARCH_RADIUS_STEP_KM = 5;
    private const MAX_SEARCH_RADIUS_KM = 25;
    private const EARTH_RADIUS_KM = 6371;

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
        'domaines',
        'region',
        'departement',
        'country',
    ])->get();
    
        return [
            'ouvriers' => $ouvriers,
            'departements' => $departements,
            'metiers' => $metiers,
            'regions' => $regions,
            'domaines' => $domaines
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return[
            'countries' => Countries::all(),
            'regions' => Region::with('country')->get(),
            'departements' => Departement::all(),
            'metiers' => Metier::pluck('nom', 'id'),
            'domaines' => Domaine::pluck('nom', 'id'),
        ];
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
        $coordinates = $request->validate([
            'latitude' => ['nullable', 'required_with:longitude', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'required_with:latitude', 'numeric', 'between:-180,180'],
        ]);
        $latitude = isset($coordinates['latitude']) ? (float) $coordinates['latitude'] : null;
        $longitude = isset($coordinates['longitude']) ? (float) $coordinates['longitude'] : null;
        $query = Ouvrier::query();

        $search = $request->query('search');

        if (is_string($search) && trim($search) !== '') {
            $search = trim($search);

            $query->where(function (Builder $query) use ($search) {
                $query->where('nom', 'like', '%'.$search.'%')
                    ->orWhere('telephone', 'like', '%'.$search.'%');
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

        if ($latitude !== null && $longitude !== null) {
            $this->applyMaximumRadiusBounds($query, $latitude, $longitude);
        }

        $ouvriers = $query->with([
            'domaines',
            'metiers.domaine',
            'region',
            'departement',
            'country',
        ])->get();

        $searchRadiusKm = null;
        if ($latitude !== null && $longitude !== null) {
            $ouvriers = $ouvriers->map(function (Ouvrier $ouvrier) use ($latitude, $longitude) {
                $ouvrier->setAttribute(
                    'distance_km',
                    $this->distanceInKilometers(
                        $latitude,
                        $longitude,
                        (float) $ouvrier->latitude,
                        (float) $ouvrier->longitude,
                    ),
                );

                return $ouvrier;
            });

            $searchRadiusKm = self::INITIAL_SEARCH_RADIUS_KM;
            while (
                $searchRadiusKm < self::MAX_SEARCH_RADIUS_KM
                && ! $ouvriers->contains(
                    fn (Ouvrier $ouvrier) => $ouvrier->distance_km <= $searchRadiusKm,
                )
            ) {
                $searchRadiusKm += self::SEARCH_RADIUS_STEP_KM;
            }

            $ouvriers = $ouvriers
                ->filter(fn (Ouvrier $ouvrier) => $ouvrier->distance_km <= $searchRadiusKm)
                ->sortBy('distance_km')
                ->values();

            $ouvriers->each(function (Ouvrier $ouvrier) {
                $ouvrier->setAttribute('distance_km', round($ouvrier->distance_km, 2));
            });
        }

        $metiers = Metier::all();
        $departements = Departement::all();
        $regions = Region::all();
        $domaines = Domaine::all();

        return [
            'searchRadiusKm' => $searchRadiusKm,
            'ouvriers' => $ouvriers,
            'departements' => $departements,
            'metiers' => $metiers,
            'regions' => $regions,
            'domaines' => $domaines,
            'search_radius_km' => $searchRadiusKm,
        ];
    }

    private function applyMaximumRadiusBounds(Builder $query, float $latitude, float $longitude): void
    {
        $angularRadius = self::MAX_SEARCH_RADIUS_KM / self::EARTH_RADIUS_KM;
        $latitudeDelta = rad2deg($angularRadius);
        $minimumLatitude = max(-90, $latitude - $latitudeDelta);
        $maximumLatitude = min(90, $latitude + $latitudeDelta);

        $query->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereBetween('latitude', [$minimumLatitude, $maximumLatitude]);

        if ($minimumLatitude <= -90 || $maximumLatitude >= 90) {
            return;
        }

        $maximumAbsoluteLatitude = max(abs($minimumLatitude), abs($maximumLatitude));
        $longitudeRatio = sin($angularRadius) / cos(deg2rad($maximumAbsoluteLatitude));

        if ($longitudeRatio >= 1) {
            return;
        }

        $longitudeDelta = rad2deg(asin($longitudeRatio));
        $minimumLongitude = $longitude - $longitudeDelta;
        $maximumLongitude = $longitude + $longitudeDelta;

        $query->where(function (Builder $longitudeQuery) use ($minimumLongitude, $maximumLongitude) {
            if ($minimumLongitude < -180) {
                $longitudeQuery->where('longitude', '>=', $minimumLongitude + 360)
                    ->orWhere('longitude', '<=', $maximumLongitude);
            } elseif ($maximumLongitude > 180) {
                $longitudeQuery->where('longitude', '>=', $minimumLongitude)
                    ->orWhere('longitude', '<=', $maximumLongitude - 360);
            } else {
                $longitudeQuery->whereBetween('longitude', [$minimumLongitude, $maximumLongitude]);
            }
        });
    }

    private function distanceInKilometers(
        float $originLatitude,
        float $originLongitude,
        float $workerLatitude,
        float $workerLongitude,
    ): float {
        $latitudeDelta = deg2rad($workerLatitude - $originLatitude);
        $longitudeDelta = deg2rad($workerLongitude - $originLongitude);
        $originLatitude = deg2rad($originLatitude);
        $workerLatitude = deg2rad($workerLatitude);

        $haversine = sin($latitudeDelta / 2) ** 2
            + cos($originLatitude) * cos($workerLatitude) * sin($longitudeDelta / 2) ** 2;

        return self::EARTH_RADIUS_KM * 2 * asin(min(1, sqrt($haversine)));
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
        
        return [
            'ouvrier' => $ouvrier->load(['metiers', 'domaines']),
            'success' => true, 
            'message' => 'Ouvrier created successfully'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $ouvrier = Ouvrier::with([
            'metiers',
            'domaines',
            'region',
            'departement',
            'country',
        ])->where('user_id', $request->user()->id)->get();
        // dd($ouvrier);        
        
        return ['ouvrier' => $ouvrier];
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
        if($request->user()->id != $ouvrier->user_id){
            return ["message" => "vous n'êtes pas le prpopriétiare"];
        }
        $ouvrier->update($request->only('nom', 'country_id', 'region_id', 'departement_id', 'telephone', 'address', 'telephone_2', 'user_id', 'latitude', 'longitude'));
        $ouvrier->metiers()->sync($dataOuvrier['metiers']);
        $ouvrier->domaines()->sync($dataOuvrier['domaines']);
        
        return [
            'ouvrier' => $ouvrier,
            'success' => true,
            'message' => 'Ouvrier updated successfully'
        ];
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ouvrier = Ouvrier::findOrFail($id);
        $ouvrier->delete();

        return ['success' => true, 'message' => 'Ouvrier deleted successfully.'];
    }
}

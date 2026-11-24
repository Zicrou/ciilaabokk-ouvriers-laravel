<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ouvrier;
use App\Services\FirebaseOuvrierLocationWriter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OuvrierLocationController extends Controller
{
    public function store(Request $request, FirebaseOuvrierLocationWriter $locationWriter): JsonResponse
    {
        $coordinates = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $ouvrier = $request->user()->ouvrier;

        abort_unless($ouvrier instanceof Ouvrier, 404, 'Aucune fiche ouvrier associée à ce compte.');

        $updatedAt = $locationWriter->write(
            $ouvrier,
            (float) $coordinates['latitude'],
            (float) $coordinates['longitude'],
        );

        return response()->json([
            'success' => true,
            'updated_at' => $updatedAt,
        ]);
    }
}

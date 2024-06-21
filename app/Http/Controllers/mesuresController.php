<?php

namespace App\Http\Controllers;

use App\Models\Mesure;
use Illuminate\Http\Request;

class mesuresController extends Controller
{
    public function getMesures($bassinId, Request $request): \Illuminate\Http\JsonResponse
    {
        $limit = $request->input('limit', 30);

        $mesures = Mesure::where('bassin_id', $bassinId)
            ->orderBy('created_at', 'asc')
            ->offset($limit)
            ->get();

        // Formatage des données
        $temperatures = $mesures->pluck('temperature')->toArray();
        $phValues = $mesures->pluck('ph')->toArray();
        $labels = $mesures->pluck('created_at')->toArray();

        return response()->json([
            'temperatures' => $temperatures,
            'phValues' => $phValues,
            'labels' => $labels
        ]);
    }
}

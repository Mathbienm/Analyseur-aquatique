<?php

namespace App\Http\Controllers;

use App\Models\Bassin;
use App\Models\Mesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class arduinoController extends Controller
{
    public function createTemperature(Request $request): \Illuminate\Http\JsonResponse
    {
        Log::info($request->get('token'));
        if ($request->get('temperature') && $request->get('ph') && $request->get('bassin_id') && $request->get('token') && $request->get('token') == env('TOKEN'))  {
            $temperature = $request->input('temperature');
            $ph = $request->input('ph');
            $ip = $request->ip();
            $bassin_id = $request->input('bassin_id');
            Log::info($ip);
            $bassin = Bassin::where('ip_arduino', $ip)->first();

            if ($bassin) {
                $mesure = Mesure::create([
                    'temperature' => $temperature,
                    'ph' => $ph,
                    'bassin_id' => $bassin_id,
                    'bassinId' => $bassin->id,
                ]);

                return response()->json(['success' => true, 'message' => 'Mesure ajoutée avec succès']);
            } else {
                return response()->json(['success' => false, 'message' => 'Aucun bassin trouvé pour cette adresse IP', $ip]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Les données de température, de pH ou l\'adresse IP sont manquantes']);
        }
    }
}

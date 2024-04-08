<?php

namespace App\Http\Controllers;

use App\Models\Mesure;
use Illuminate\Http\Request;

class arduinoController extends Controller
{
    public function createTemperature(Request $request){
        if ($request->get('temperature') && $request->get('ph')) {
            $temperature = $request->get('temperature');
            $ph = $request->get('ph');

            $mesure = Mesure::create([
                'temperature' => $temperature,
                'ph' => $ph
            ]);
        }

        return response()->json(['success' => true]);
    }
}

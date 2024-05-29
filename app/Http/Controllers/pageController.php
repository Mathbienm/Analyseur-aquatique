<?php

namespace App\Http\Controllers;

use App\Models\Bassin;
use App\Models\Mesure;
use Illuminate\Http\Request;

class pageController extends Controller
{
    public function page(){
        $bassins = Bassin::all();
        foreach ($bassins as $bassin) {
            $bassin->moyenneTemp = (int) round($bassin->mesures()->whereDate('created_at', now()->format('Y-m-d'))->avg('mesures.temperature')) ?? 0;
            $bassin->moyennePh = (int) round($bassin->mesures()->whereDate('created_at', now()->format('Y-m-d'))->avg('mesures.ph')) ?? 0;
        }

        $moyenneTemperature = (int) round(Mesure::whereDate('created_at', now()->format('Y-m-d'))->avg('temperature')) ?? 0;

        return view('page', compact('bassins', 'moyenneTemperature'));
    }

    public function afficherMoyenneTemperature()
    {
        $dateActuelle = now()->format('Y-m-d');
        $moyenneTemperature = Mesure::whereDate('created_at', $dateActuelle)->avg('temperature');

        if ($moyenneTemperature !== null) {
            return view('page', ['moyenneTemperature' => round($moyenneTemperature, 0)]);
        } else {
            return view('page')->with('erreur', "Aucune mesure de température disponible pour aujourd'hui.");
        }
    }

}


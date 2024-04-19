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
            $bassin->moyenneTemp = (int) round($bassin->mesures()->whereDate('created_at', now()->format('d-m-Y'))->avg('mesures.temperature')) ?? 0;
            $bassin->moyennePh = (int) round($bassin->mesures()->whereDate('created_at', now()->format('d-m-Y'))->avg('mesures.ph')) ?? 0;
        }

        // Récupération de la moyenne de température pour la journée actuelle
        $moyenneTemperature = (int) round(Mesure::whereDate('created_at', now()->format('d-m-Y'))->avg('temperature')) ?? 0;

        return view('page', compact('bassins', 'moyenneTemperature'));
    }

    public function afficherMoyenneTemperature()
    {
        $dateActuelle = now()->format('d-m-Y');
        $moyenneTemperature = Mesure::whereDate('created_at', $dateActuelle)->avg('temperature');

        if ($moyenneTemperature !== null) {
            return view('page', ['moyenneTemperature' => round($moyenneTemperature, 0)]);
        } else {
            return view('page')->with('erreur', "Aucune mesure de température disponible pour aujourd'hui.");
        }
    }

}


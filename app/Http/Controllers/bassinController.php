<?php
namespace App\Http\Controllers;

use App\Models\Bassin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class bassinController extends Controller
{
    public function export()
    {
        $bassins = Bassin::all();

        $csvFileName = 'bassins.csv';
        $tempFilePath = storage_path('app/' . Str::random(16) . '.csv');

        $handle = fopen($tempFilePath, 'w');
        fputcsv($handle, array('ID', 'Nom', 'Seuil Température', 'Seuil pH'));

        foreach ($bassins as $bassin) {
            fputcsv($handle, array($bassin->id, $bassin->nom_bassin, $bassin->seuil_temperature, $bassin->seuil_ph));
        }

        fclose($handle);

        return response()->download($tempFilePath, $csvFileName)->deleteFileAfterSend(true);
    }
}

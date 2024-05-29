<?php
namespace App\Http\Controllers;

use App\Models\Bassin;
use App\Models\Mesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class bassinController extends Controller
{
    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $mesures = Mesure::all();
        $csvFileName = 'all_mesures.csv';
        $tempFilePath = storage_path('app/' . Str::random(16) . '.csv');
        $handle = fopen($tempFilePath, 'w');
        $delimiter = ';';
        fputcsv($handle, array('Numero du bassin', 'Temperature', 'pH', 'date_heure'), $delimiter);
        foreach ($mesures as $mesure) {
            fputcsv($handle, array($mesure->bassin_id, $mesure->temperature, $mesure->ph, $mesure->created_at), $delimiter);
        }
        fclose($handle);
        return response()->download($tempFilePath, $csvFileName)->deleteFileAfterSend(true);
    }

    public function exportBassin(Request $request, $bassinId): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
    {
        $mesures = Mesure::where('bassin_id', $bassinId)->get();

        if ($mesures->isEmpty()) {
            // Retourner un message d'avertissement si aucune mesure n'est disponible pour ce bassin
            return response()->json(['warning' => "Aucune mesure disponible pour ce bassin. L'exportation du fichier CSV ne contiendra aucune donnée."]);
        }

        $csvFileName = 'mesures_bassin_' . $bassinId . '.csv';
        $tempFilePath = storage_path('app/' . Str::random(16) . '.csv');
        $handle = fopen($tempFilePath, 'w');
        $delimiter = ';';
        fputcsv($handle, array('Numero du bassin', 'Temperature', 'pH', 'date_heure'), $delimiter);
        foreach ($mesures as $mesure) {
            fputcsv($handle, array($mesure->bassin_id, $mesure->temperature, $mesure->ph, $mesure->created_at), $delimiter);
        }
        fclose($handle);
        return response()->download($tempFilePath, $csvFileName)->deleteFileAfterSend(true);
    }


    public function updateThreshold(Request $request): \Illuminate\Http\JsonResponse
    {
        $bassinId = $request->input('bassinId');
        $newThresholdTemperature = $request->input('newThresholdTemperature');
        $newThresholdPH = $request->input('newThresholdPH');

        $bassin = Bassin::findOrFail($bassinId);
        $bassin->seuil_temperature = $newThresholdTemperature;
        $bassin->seuil_ph = $newThresholdPH;
        $bassin->save();

        return response()->json(['success' => true]);
    }

}

<?php

namespace App\Console\Commands;

use App\Mail\AlertMail;
use App\Models\Bassin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AlertEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:alert-email-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bassins = Bassin::all();
        foreach ($bassins as $bassin) {
            $mesure = $bassin->mesures()->orderBy('id', 'desc')->first();
            $message = null;
            if ($mesure) {
                if ($mesure->temperature > $bassin->seuil_temperature && $mesure->ph > $bassin->seuil_ph) {
                    $message = "Les seuils de température et de pH sont dépassés dans le bassin ".$bassin->id;
                }else if ($mesure->temperature > $bassin->seuil_temperature) {
                    $message = "Le seuil de température est dépassé dans le bassin ".$bassin->id;
                }else if ($mesure->ph > $bassin->seuil_ph) {
                    $message = "Le seuil de pH est dépassé dans le bassin ".$bassin->id;
                }
                if ($message) {
                    $to_email = "analyseuraquatique@gmail.com";
                    $subject = "Alerte - Seuils dépassés";
                    $threshold = "temperature";
                    Mail::to($to_email)->send(new AlertMail($subject, $message, $threshold));
                }
            }
        }
    }
}

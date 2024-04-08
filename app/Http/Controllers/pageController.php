<?php

namespace App\Http\Controllers;

use App\Models\Bassin;
use Illuminate\Http\Request;

class pageController extends Controller
{
    public function page(){

        $bassins = Bassin::all();

        return view('page', compact('bassins'));
    }
}


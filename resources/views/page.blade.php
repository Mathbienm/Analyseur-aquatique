@extends('layouts.head')

@section('title', 'Page d\'accueil Analyseur aquatique')

@section('content')

    <div class="kodchasan-semibold" style="text-align: center; color: #CEF2E9">
        <h1>ANALYSE AQUATIQUE DES BASSINS</h1>
    </div>

    <swiper-container class="mySwiper" pagination="true" pagination-clickable="true" slides-per-view="3" space-between="30" free-mode="true">
        @foreach($bassins as $bassin)
            <swiper-slide class="josefin-sans-uniquifier" style="color: #B9EDDD">
                <h2>{{$bassin->nom_bassin}}</h2>
                <div class="div-padding">
                    <div class="grid_temp_ph">
                        <img src="/img/bassin1.jpg" alt="Bassin 1">
                        <img class="img_zizi" src="/img/temp.png" alt="Bassin 1">
                    </div>
                    <div>
                        <img src="/img/bassin1.jpg" alt="Bassin 1">
                        <img class="img_zizi" src="/img/ph.png" alt="Bassin 1">
                    </div>
                </div>
                <div class="div-padding">


                </div>
                <div style="margin-top: 120px">
                    <div style="text-align: center">
                        <p>Température : {{ $bassin->seuil_temperature }} °C</p>
                        <a href="" class="modifier-button josefin-sans-uniquifier">Modifier</a>
                    </div>
                    <div style="text-align: center">
                        <p>pH : {{ $bassin->seuil_ph }}
                        </p>
                        <a href="" class="modifier-button josefin-sans-uniquifier">Modifier</a>
                    </div>
                </div>
            </swiper-slide>
        @endforeach
    </swiper-container>

    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('bassins.export') }}" class="export-csv-button josefin-sans-uniquifier">Export CSV</a>
    </div>

@endsection

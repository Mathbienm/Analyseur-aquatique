@extends('layouts.head')

@section('title', 'Page d\'accueil Analyseur aquatique')

@section('content')

    <div class="kodchasan-semibold" style="text-align: center; color: #CEF2E9"><h1>ANALYSE AQUATIQUE DES BASSINS</h1></div>

    <swiper-container class="mySwiper" pagination="true" pagination-clickable="true" slides-per-view="3" space-between="30" free-mode="true">
        @foreach($bassins as $bassin)
            <swiper-slide class="josefin-sans-uniquifier" style="color: #B9EDDD">
                <h2>{{$bassin->nom_bassin}}</h2>
                <div class="div-padding">
                    <img src="/img/bassin1.jpg" alt="Bassin 1">
                    <img src="/img/bassin1.jpg" alt="Bassin 1">
                </div>
                <p>Température : {{ $bassin->seuil_temperature }} °C</p>
                <p>pH : {{ $bassin->seuil_ph}}</p>
            </swiper-slide>
        @endforeach
    </swiper-container>

    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('bassins.export') }}" class="export-csv-button">Export CSV</a>
    </div>

@endsection

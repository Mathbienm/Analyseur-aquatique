@extends('layouts.head')

@section('title', 'Page d\'accueil Analyseur aquatique')
@section('meta')
    <meta http-equiv="refresh" content="60">
@endsection
@section('content')

    <style>
        .padding-temp {
        }
    </style>
    <div class="kodchasan-semibold" style="text-align: center; color: #CEF2E9">
        <h1>ANALYSE AQUATIQUE DES BASSINS</h1>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p style="padding: 5px">Nouveau seuil de température : <label
                    for="newThresholdTemperature"></label><input type="number" id="newThresholdTemperature" style="width: 10%;"></p>
            <p style="padding-bottom: 5px">Nouveau seuil de pH : <label for="newThresholdPH"></label><input type="number" id="newThresholdPH" style="width: 10%;"></p>
            <button style="padding: 5px;background-color: skyblue;border-radius: 10px;" id="submitThreshold" >Valider</button>
        </div>
    </div>

    <swiper-container class="mySwiper" pagination="true" pagination-clickable="true" slides-per-view="1" space-between="30" free-mode="true">
        @foreach($bassins as $bassin)
            <swiper-slide class="josefin-sans-uniquifier" style="color: #B9EDDD">
                <h2>{{$bassin->nom_bassin}}</h2>
                <div class="div-padding">
                    <canvas class="canva" id="temperatureChart_{{ $bassin->id }}"></canvas>
                    <canvas class="canva" id="phChart_{{ $bassin->id }}"></canvas>

                </div>
                <div class="div-padding-moyenne">
                    <p class="padding-temp">Moyenne de la température journalière : {{ $moyenneTemperature }}</p>
                    <p class="padding-ph">Moyenne du pH journalière : {{ $bassin->moyennePh }}</p>
                </div>

                <div>
                    <div style="text-align: center">
                        <p>Seuil de température : <span id="seuilTemperature_{{ $bassin->id }}">{{ $bassin->seuil_temperature }}</span> °C</p>
                    </div>
                    <div style="text-align: center">
                        <p>Seuil de pH : <span id="seuilPH_{{ $bassin->id }}">{{ $bassin->seuil_ph }}</span></p>
                        <a class="modifier-button josefin-sans-uniquifier" data-bassin-id="{{ $bassin->id }}">Modifier</a>
                        <a class="export-csv-2-button josefin-sans-uniquifier"href="{{ route('bassins.exportBassin', ['bassinId' => $bassin->id]) }}">Export CSV de ce bassin</a>
                    </div>
                </div>
            </swiper-slide>
        @endforeach
    </swiper-container>

    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('bassins.export') }}" class="export-csv-button josefin-sans-uniquifier">Export CSV global</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script>
        $(document).ready(function() {
            var modal = $("#modal");
            var span = $(".close");
            var btns = $(".modifier-button");

            btns.click(function() {
                var btn = $(this);
                var bassinId = btn.data('bassin-id');
                var seuilTemperatureElement = $("#seuilTemperature_" + bassinId);
                var seuilPHElement = $("#seuilPH_" + bassinId);
                var seuilTemperature = seuilTemperatureElement.text();
                var seuilPH = seuilPHElement.text();

                $("#newThresholdTemperature").val(seuilTemperature);
                $("#newThresholdPH").val(seuilPH);
                modal.data('bassin-id', bassinId);
                modal.css("display", "block");

                $("#submitThreshold").unbind('click').click(function() {
                    var newThresholdTemperature = $("#newThresholdTemperature").val();
                    var newThresholdPH = $("#newThresholdPH").val();

                    seuilTemperatureElement.text(newThresholdTemperature);
                    seuilPHElement.text(newThresholdPH);
                    modal.css("display", "none");

                    var bassinId = modal.data('bassin-id');
                    $.ajax({
                        url: "{{ route('updateThreshold') }}",
                        type: "GET",
                        data: {
                            bassinId: bassinId,
                            newThresholdTemperature: newThresholdTemperature,
                            newThresholdPH: newThresholdPH
                        },
                        success: function(response) {
                            console.log("Nouvelles valeurs des seuils enregistrées avec succès !");
                        },
                        error: function(xhr, status, error) {
                            console.error("Une erreur est survenue lors de l'enregistrement des nouvelles valeurs des seuils : ", error);
                        }
                    });
                });
            });

            span.click(function() {
                modal.css("display", "none");
            });

            $(window).click(function(event) {
                if (event.target === modal[0]) {
                    modal.css("display", "none");
                }
            });
        });

        @foreach($bassins as $bassin)
        var temperatureChart_{{ $bassin->id }} = new Chart(document.getElementById("temperatureChart_{{ $bassin->id }}"), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Température (°C)',
                    data: [],
                    fill: false,
                    borderColor: 'rgba(75, 192, 192)',
                    tension: 0.1,
                    color: 'rgba(255,255,255)',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'white'
                        },
                        title: {
                            display: true,
                            text: 'Valeur des températures',
                            color: 'rgba(255,255,255)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255,255,255)'
                        },
                        ticks: {
                            color: 'rgba(255,255,255)'
                        },
                    }
                },
            }
        });

        var phChart_{{ $bassin->id }} = new Chart(document.getElementById("phChart_{{ $bassin->id }}"), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'pH',
                    data: [],
                    fill: false,
                    borderColor: 'rgb(157,24,24)',
                    tension: 0.1,
                    color: 'rgba(255,255,255)',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'white'
                        },
                        title: {
                            display: true,
                            text: 'Valeur du pH',
                            color: 'rgba(255,255,255)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255,255,255)'
                        },
                        ticks: {
                            color: 'rgba(255,255,255)'
                        },
                    }
                }
            }
        });
        @endforeach

        @foreach($bassins as $bassin)
        $.ajax({
            url: "{{ route('getMesures', ['bassin' => $bassin->id]) }}?limit=30",
            type: "GET",
            success: function(response) {
                var temperatures = response.temperatures;
                var phValues = response.phValues;
                var labels = response.labels.map(function(dateString) {
                    let date = moment(dateString, 'YYYY-MM-DD HH:mm:ss');
                    date = date.addHours(2)
                    return date.format('DD-MM-YYYY HH:mm:ss');
                });

                // Mettre à jour les graphiques avec les nouvelles données
                temperatureChart_{{ $bassin->id }}.data.datasets[0].data = temperatures;
                phChart_{{ $bassin->id }}.data.datasets[0].data = phValues;
                temperatureChart_{{ $bassin->id }}.data.labels = labels;
                phChart_{{ $bassin->id }}.data.labels = labels;
                temperatureChart_{{ $bassin->id }}.update();
                phChart_{{ $bassin->id }}.update();
            },
            error: function(xhr, status, error) {
                console.error("Une erreur est survenue lors de la récupération des mesures pour le bassin {{ $bassin->id }} : ", error);
            }
        });
        @endforeach
    </script>
@endsection



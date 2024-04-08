@extends('layouts.head')

@section('title', 'Page d\'accueil Analyseur aquatique')

@section('content')

    <div class="kodchasan-semibold" style="text-align: center; color: #CEF2E9">
        <h1>ANALYSE AQUATIQUE DES BASSINS</h1>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p style="padding: 5px">Nouveau seuil de température : <input type="number" id="newThresholdTemperature"></p>
            <p style="padding-bottom: 5px">Nouveau seuil de pH : <input type="number" id="newThresholdPH"></p>
            <button id="submitThreshold">Valider</button>
        </div>
    </div>

    <swiper-container class="mySwiper" pagination="true" pagination-clickable="true" slides-per-view="3" space-between="30" free-mode="true">
        @foreach($bassins as $bassin)
            <swiper-slide class="josefin-sans-uniquifier" style="color: #B9EDDD">
                <h2>{{$bassin->nom_bassin}}</h2>
                <div class="div-padding">
                    <canvas id="temperatureChart_{{ $bassin->id }}"></canvas>
                </div>
                <div class="div-padding">
                    <canvas id="phChart_{{ $bassin->id }}"></canvas>
                </div>
                <div>
                    <div style="text-align: center">
                        <p>Seuil de température : <span id="seuilTemperature_{{ $bassin->id }}">{{ $bassin->seuil_temperature }}</span> °C</p>
                    </div>
                    <div style="text-align: center">
                        <p>Seuil de pH : <span id="seuilPH_{{ $bassin->id }}">{{ $bassin->seuil_ph }}</span></p>
                        <a class="modifier-button josefin-sans-uniquifier" data-bassin-id="{{ $bassin->id }}">Modifier</a>
                    </div>
                </div>
            </swiper-slide>
        @endforeach
    </swiper-container>

    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('bassins.export') }}" class="export-csv-button josefin-sans-uniquifier">Export CSV</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            var modal = $("#modal");
            var span = $(".close");
            var btns = $(".modifier-button");

            btns.click(function() {
                var btn = $(this);
                var bassinId = btn.data('bassin-id'); // Récupérer l'ID du bassin à partir du bouton cliqué
                var seuilTemperature = $("#seuilTemperature_" + bassinId).text();
                var seuilPH = $("#seuilPH_" + bassinId).text();

                $("#newThresholdTemperature").val(seuilTemperature);
                $("#newThresholdPH").val(seuilPH);
                modal.data('bassin-id', bassinId); // Stocker l'ID du bassin dans l'attribut de données de la modal

                modal.css("display", "block");
            });

            span.click(function() {
                modal.css("display", "none");
            });

            $(window).click(function(event) {
                if (event.target === modal[0]) {
                    modal.css("display", "none");
                }
            });

            $("#submitThreshold").click(function() {
                var newThresholdTemperature = $("#newThresholdTemperature").val();
                var newThresholdPH = $("#newThresholdPH").val();
                var bassinId = modal.data('bassin-id');
                var token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "/updateThreshold",
                    type: "POST",
                    contentType: "application/json",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: JSON.stringify({
                        _token: token,
                        bassinId: bassinId,
                        newThresholdTemperature: newThresholdTemperature,
                        newThresholdPH: newThresholdPH
                    }),
                    success: function(response) {
                        $("#seuilTemperature_" + bassinId).text(newThresholdTemperature);
                        $("#seuilPH_" + bassinId).text(newThresholdPH);
                        modal.hide();
                    },
                    error: function(xhr, status, error) {
                        console.error("Une erreur est survenue lors de la requête : ", error);
                    }
                });
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
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var phChart_{{ $bassin->id }} = new Chart(document.getElementById("phChart_{{ $bassin->id }}"), {
            type: 'line',
            data: {
                labels: [], // Les labels des échelles X (ex: les jours)
                datasets: [{
                    label: 'pH',
                    data: [], // Les données de pH moyennes par jour
                    fill: false,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Générer des données aléatoires pour plusieurs jours
        var days = 5; // Nombre de jours
        var temperatureData_{{ $bassin->id }} = [];
        var phData_{{ $bassin->id }} = [];

        for (var day = 1; day <= days; day++) {
            var temperatureDayTotal = 0;
            var phDayTotal = 0;

            for (var i = 0; i < 24; i++) { // Générer des données pour chaque heure du jour
                var temperatureValue = Math.random() * (30 - 20) + 20; // Valeurs aléatoires entre 20 et 30 pour la température
                var phValue = Math.random() * (8.5 - 7) + 7; // Valeurs aléatoires entre 7 et 8.5 pour le pH

                temperatureDayTotal += temperatureValue;
                phDayTotal += phValue;
            }

            // Calculer la moyenne pour chaque jour
            var temperatureDayAverage = temperatureDayTotal / 24;
            var phDayAverage = phDayTotal / 24;

            temperatureData_{{ $bassin->id }}.push(temperatureDayAverage);
            phData_{{ $bassin->id }}.push(phDayAverage);
        }

        // Générer les labels pour les jours
        var today = new Date();
        var labels = [];

        for (var i = 0; i < days; i++) {
            var date = new Date(today);
            date.setDate(date.getDate() - (days - 1 - i));
            labels.push(date.toLocaleDateString());
        }

        // Ajouter les données moyennes aux graphiques
        temperatureChart_{{ $bassin->id }}.data.labels = labels; // Labels de jours
        temperatureChart_{{ $bassin->id }}.data.datasets[0].data = temperatureData_{{ $bassin->id }}; // Données de température
        temperatureChart_{{ $bassin->id }}.update(); // Mettre à jour le graphique de température

        phChart_{{ $bassin->id }}.data.labels = labels; // Labels de jours
        phChart_{{ $bassin->id }}.data.datasets[0].data = phData_{{ $bassin->id }}; // Données de pH
        phChart_{{ $bassin->id }}.update(); // Mettre à jour le graphique de pH
        @endforeach
    </script>
@endsection



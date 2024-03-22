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
                        <p>Température : <span id="seuilTemperature_{{ $bassin->id }}">{{ $bassin->seuil_temperature }}</span> °C</p>
                    </div>
                    <div style="text-align: center">
                        <p>pH : <span id="seuilPH_{{ $bassin->id }}">{{ $bassin->seuil_ph }}</span></p>
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
                var bassinId = modal.data('bassin-id'); // Récupérer l'ID du bassin à partir de l'attribut de données de la modal
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
    </script>
@endsection



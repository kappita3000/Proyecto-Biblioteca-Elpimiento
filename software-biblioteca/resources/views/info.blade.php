@extends('layouts.aa')

@section('content')
<div style="background-color: #e8f5e9; padding: 20px;">
<div class="container">
    <!-- Sección del mapa -->
    <div class="map-section">
        <div class="map-container">
            <!-- Aquí iría el mapa integrado de Google Maps -->
            <iframe src="https://www.google.com/maps/embed?..." width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        </div>
        <div class="map-text">
            <p>Nos encontramos en la plaza de el pimiento</p>
        </div>
    </div>
    </div>
    </div>

    <div class="container2">

    <!-- Sección "Sobre nosotros" -->
    <div class="about-section">
        <h2>Sobre nosotros</h2>
        <div class="about-cards">
            <div class="card">
                <h3>Quiénes somos</h3>
                <p>Añade texto para describir quiénes son.</p>
                <button>Leer más</button>
            </div>
            <div class="card">
                <h3>Nuestra visión</h3>
                <p>Añade texto para describir la visión.</p>
                <button>Leer más</button>
            </div>
            <div class="card">
                <h3>Nuestra misión</h3>
                <p>Añade texto para describir la misión.</p>
                <button>Leer más</button>
            </div>
        </div>
    </div>
</div>


</div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfe4LVLV1Ah3E6avo8JHFo92kIjDjUKdY" async defer></script>
    <script>
        function initMap() {
            const ubicacion = { lat: -33.57346760204582, lng: -70.98180886814293 };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 19,
                center: ubicacion,
            });

            const marker = new google.maps.Marker({
                position: ubicacion,
                map: map,
                title: "Aquí estamos",
                icon: {
                    url: "https://img.icons8.com/?size=100&id=ChugQlss1ohB&format=png&color=000000",
                    size: new google.maps.Size(40, 40),
                    scaledSize: new google.maps.Size(40, 40),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(20, 40)
                }
            });

            const infowindow = new google.maps.InfoWindow({
                content: "Aquí estamos",
            });

            infowindow.open(map, marker);
        }

        window.onload = initMap;
    </script>
@endsection

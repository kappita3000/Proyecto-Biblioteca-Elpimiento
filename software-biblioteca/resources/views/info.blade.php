@extends('layouts.aa')

@section('content')
<div style="background-color: #e8f5e9; padding: 20px;">
    <div class="container">
        <!-- Sección del mapa -->
        <div class="map-section">
            <div class="map-container" id="map"></div>
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
                <p>Somos un espacio comunitario donde las personas pueden leer, buscar información y estudiar. Nuestro único objetivo es garantizar oportunidades de acceso a la información, fomentando la lectura y el uso de servicios virtuales. Aunque estamos en una zona rural, no estamos ajenos al desarrollo, la cultura y la recreación.</p>
            </div>
            <div class="card">
                <h3>Misión</h3>
                <p>Nuestra misión es facilitar el acceso a la información como un eje central para promover la lectura y el uso de nuevas tecnologías. Buscamos ser un centro cultural que contribuye a la integración social y a la preservación de la herencia cultural, satisfaciendo las necesidades de usuarios de todas las edades mediante servicios bibliotecarios eficientes y de calidad.</p>
            </div>
            <div class="card">
                <h3>Visión</h3>
                <p>Nuestra visión es convertirnos en un referente de información y cultura para nuestra comunidad y sus usuarios, ofreciendo un espacio moderno y accesible. Aspiramos a fomentar la creatividad y el uso del tiempo libre, contribuyendo al desarrollo personal y comunitario.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfe4LVLV1Ah3E6avo8JHFo92kIjDjUKdY" async defer></script>
<script>
    function initMap() {
        const ubicacion = { lat: -33.57346760204582, lng: -70.98180886814293 }; // Coordenadas de tu ubicación

        // Crear el mapa
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 19,
            center: ubicacion,
        });

        // Crear el marcador con una imagen personalizada y un tamaño específico
        const marker = new google.maps.Marker({
            position: ubicacion,
            map: map,
            title: "Aquí estamos",
            icon: {
                url: "https://img.icons8.com/?size=100&id=ChugQlss1ohB&format=png&color=000000",
                size: new google.maps.Size(40, 40),  // Tamaño del marcador (ancho, alto)
                scaledSize: new google.maps.Size(40, 40), // Escala el tamaño si es necesario
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(20, 40) // Cambiar según el punto de anclaje
            }
        });

        // Crear el infowindow con el mensaje
        const infowindow = new google.maps.InfoWindow({
            content: "Aquí estamos", // Mensaje que quieres mostrar
        });

        // Mostrar el infowindow al cargar la página
        infowindow.open(map, marker);
    }

    // Inicializar el mapa al cargar la página
    window.onload = initMap;
</script>
@endsection

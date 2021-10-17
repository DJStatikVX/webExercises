class MapaDinamico {
    constructor() {
        $(document).ready(this.initMap);
    }

    initMap() {
        var ubicacion = { lat: 43, lng: -5 };
    
        var mapa = new google.maps.Map(document.getElementById("mapa"), {
            center: ubicacion,
            zoom: 8
        });
    
        var indicador = new google.maps.InfoWindow();
    
        // Se pide permiso de ubicación
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(posicion) {
                var coordenadas = { lat: posicion.coords.latitude, lng: posicion.coords.longitude };
                indicador.setPosition(coordenadas);
                indicador.setContent("Estás aquí");
                indicador.open(mapa);
                mapa.setCenter(coordenadas);
            },
            function() {
                gestionDeErrores(true, indicador, mapa.getCenter());
            });
    
        } else {
            // No hay geolocalización disponible
            this.gestionDeErrores(false, indicador, mapa.getCenter());
        }
    }

    gestionDeErrores(hayGeolocalizacion, indicador, posicion) {
        indicador.setPosition(posicion);
        indicador.setContent(hayGeolocalizacion ? "Error: Ha ocurrido un fallo de geolocalización."
                                                   : "Error: Tu navegador no soporta la geolocalización.");
        indicador.open(mapa);
    }
}

var mapaDinamico = new MapaDinamico();
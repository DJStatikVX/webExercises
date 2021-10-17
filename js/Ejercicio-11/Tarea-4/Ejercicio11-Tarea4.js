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
    
        var marcador = new google.maps.Marker(
            {
                position: ubicacion,
                map: mapa
            }
        );
    }
}

var mapaDinamico = new MapaDinamico();
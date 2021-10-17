class GestorMapaGeoJSON {
    constructor() {
        $(document).ready(this.initMap);
        this.error = false;
    }

    // Configuración del mapa dinámico
    initMap() {
        // Tomamos como centro Madrid
        var centro = { lat: 40.3999984, lng: -3.6833306 };

        // Creación del mapa
        gestorMapaGeoJSON.mapa = new google.maps.Map(document.getElementById("mapa"), {
            center: centro,
            zoom: 6
        });
    }

    cargar(archivos) {
        // Recuperamos el fichero
        var archivo = archivos[0];

        // Comprobamos que el tipo sea KML
        if (archivo.name.split(".")[1].toLowerCase() != "geojson") {
            $("#contenido").append("<p id='error'>El formato de archivo es incorrecto. Debes cargar un archivo GeoJSON.</p>")
            this.error = true;
            return;
        } else {
            // En caso afirmativo, se esconde el botón y se carga el fichero
            $("#selectorFicheros").hide();
            if (this.error)
                $("#error").remove();

            // Definimos la función de lectura
            var lector = new FileReader();
            lector.onload = function (e) {
                // Convertimos la entrada a un objeto de JavaScript
                var geojson = JSON.parse(lector.result);
                // Se recorren los features
                for (var i = 0; i < geojson.features.length; i++) {
                    // Obtenemos datos y puntos de la ruta
                    var nombre = geojson.features[i].properties.name;
                    var descripcion = geojson.features[i].properties.description;
                    var coordHitos = geojson.features[i].geometry.coordinates;
                    var posicionesPolyline = [];

                    // Creamos puntos de posición a partir de las coordenadas obtenidas
                    for (var j = 0; j < coordHitos.length; j++) {
                        var longitud = parseFloat(coordHitos[j][0]);
                        var latitud = parseFloat(coordHitos[j][1]);
                        var posicion = { lat: latitud, lng: longitud };
                        // Añadir punto al array de posiciones
                        posicionesPolyline.push(posicion);
                        // Se crea un nuevo marcador
                        var marcador = new google.maps.Marker({
                            position: posicion,
                            title: nombre,
                            map: gestorMapaGeoJSON.mapa
                        });
                        // Se crea un nuevo InfoWindow
                        var indicador = new google.maps.InfoWindow({
                            content: nombre,
                        });

                        // Se vincula al marcador para que aparezca al hacer click
                        marcador.indicador = indicador;
                        marcador.addListener("click", function () {
                            this.indicador.open(gestorMapaGeoJSON.mapa, this);
                        });
                    }

                    // Se crea la polilínea correspondiente a la ruta
                    var linea = new google.maps.Polyline({
                        path: posicionesPolyline,
                        geodesic: true,
                        strokeColor: "#FF0000",
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    });

                    linea.setMap(gestorMapaGeoJSON.mapa);
                }
            }

            // Por último, se lee el fichero
            lector.readAsText(archivo);
        }
    }
}

var gestorMapaGeoJSON = new GestorMapaGeoJSON();
class GestorMapaKML {
    constructor() {
        $(document).ready(this.initMap);
        this.error = false;
    }

    // Configuración del mapa dinámico
    initMap() {
        // Tomamos como centro Madrid
        var centro = { lat: 40.3999984, lng: -3.6833306 };

        // Creación del mapa
        gestorMapaKML.mapa = new google.maps.Map(document.getElementById("mapa"), {
            center: centro,
            zoom: 6
        });
    }

    cargar(archivos) {
        // Recuperamos el fichero
        var archivo = archivos[0];

        // Comprobamos que el tipo sea KML
        if (archivo.name.split(".")[1].toLowerCase() != "kml") {
            $("#contenido").append("<p id='error'>El formato de archivo es incorrecto. Debes cargar un archivo KML.</p>")
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
                // Convertimos la entrada a un vocabulario XML
                var kml = $.parseXML(lector.result);
                // Se abre el Document y se recorren los Placemark
                $("Document", kml).find("Placemark").each(function() {
                    var nombre = $("name", this).text();
                    var descripcion = $("description", this).text();
                    var coordHitos = $("LineString", this).find("coordinates").text().split("\n");
                    // Array con las coordenadas de los futuros hitos parseados
                    var posicionesPolyline = [];

                    // Se parsean las coordenadas de cada hito
                    for (var i = 0; i < coordHitos.length; i++) {
                        coordHitos[i] = coordHitos[i].trim();
                        if (coordHitos[i].length != 0) {
                            var coordenadas = coordHitos[i].split(",");
                            var longitud = parseFloat(coordenadas[0]);
                            var latitud = parseFloat(coordenadas[1]);
                            var posicion = { lat: latitud, lng: longitud };
                            // Añadir punto al array de posiciones
                            posicionesPolyline.push(posicion);
                            // Se crea un nuevo marcador
                            var marcador = new google.maps.Marker({
                                position: posicion,
                                title: nombre,
                                map: gestorMapaKML.mapa
                            });
                            // Se crea un nuevo InfoWindow
                            var indicador = new google.maps.InfoWindow({
                                content: nombre,
                            });

                            // Se vincula al marcador para que aparezca al hacer click
                            marcador.indicador = indicador;
                            marcador.addListener("click", function() {
                                this.indicador.open(gestorMapaKML.mapa, this);
                            });
                        }
                    }

                    // Se crea la polilínea correspondiente a la ruta
                    var linea = new google.maps.Polyline({
                        path: posicionesPolyline,
                        geodesic: true,
                        strokeColor: "#FF0000",
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    });

                    linea.setMap(gestorMapaKML.mapa);
                });
            }

            // Por último, se lee el fichero
            lector.readAsText(archivo);
        }
    }
}

var gestorMapaKML = new GestorMapaKML();
class MeteoAPI {
    constructor() {
        this.apiKey = "6c869f77fa7de5750a06123cff06d6c8"
        this.ciudades = ["Gijón", "Mérida", "Sevilla", "La Bañeza", "Santoña"];
        this.tipo = "xml";
        this.unidades = "metric";
        this.idioma = "es";
        this.url = "https://api.openweathermap.org/data/2.5/weather?q=ciudad,ES&mode=" + this.tipo + "&units=" + this.unidades
            + "&lang=" + this.idioma + "&APPID=" + this.apiKey;

        this.datosCargados = false;
    }

    accionDatos() {
        if (!this.datosCargados)
            this.cargarDatos();
        else
            this.actualizarDatos();
    }

    cargarDatos() {
        var urlCopia;
        for (var i = 0; i < this.ciudades.length; i++) {
            urlCopia = this.url.split("ciudad");
            urlCopia = urlCopia[0] + this.ciudades[i] + urlCopia[1];
            $.ajax({
                dataType: "xml",
                url: urlCopia,
                method: 'GET',
                success: function (datos) {
                    var stringDatos = "<div class='ciudad'><div class='encabezado'><h2>" + $("city", datos).attr("name") + ", " + $("country", datos).text() + "</h2>";
                    stringDatos += "<p>(" + $("coord", datos).attr("lat") + ", " + $("coord", datos).attr("lon") + ")</p></div>";
                    stringDatos += "<div class='grados'><img src=\"https://openweathermap.org/img/w/" + $("weather", datos).attr("icon") + ".png"
                        + "\" alt=\"" + $("weather", datos).attr("value") + "\"/><p><strong>" + Math.round(parseFloat($("temperature", datos).attr("value"))) + "º</strong></p></div>";
                    stringDatos += "<p>Sensación térmica: " + Math.round(parseFloat($("feels_like", datos).attr("value"))) + "º</p>";
                    stringDatos += "<p>Temperatura mínima: " + Math.round(parseFloat($("temperature", datos).attr("min"))) + "º</p>";
                    stringDatos += "<p>Temperatura máxima: " + Math.round(parseFloat($("temperature", datos).attr("max"))) + "º</p>"
                    stringDatos += "<p>Presión: " + $("pressure", datos).attr("value") + " " + $("pressure", datos).attr("unit") + "</p>";
                    stringDatos += "<p>Humedad: " + $("humidity", datos).attr("value") + $("humidity", datos).attr("unit") + "</p>";
                    var amanecer = $("sun", datos).attr("rise");
                    var minutosZonaHoraria = new Date().getTimezoneOffset();
                    var amanecerMiliSeg1970 = Date.parse(amanecer);
                    amanecerMiliSeg1970 -= minutosZonaHoraria * 60 * 1000;
                    stringDatos += "<p>Amanece a las " + (new Date(amanecerMiliSeg1970)).toLocaleTimeString("es-ES") + "</p>";
                    var oscurecer = $("sun", datos).attr("set");
                    var oscurecerMiliSeg1970 = Date.parse(oscurecer);
                    oscurecerMiliSeg1970 -= minutosZonaHoraria * 60 * 1000;
                    stringDatos += "<p>Anochece a las " + (new Date(oscurecerMiliSeg1970)).toLocaleTimeString("es-ES") + "</p>";
                    stringDatos += "<p><strong>Viento</strong></p>";
                    var direccionViento = $("direction", datos).attr("value");
                    if (direccionViento != null)
                        stringDatos += "<p>Dirección: " + direccionViento + " grados</p>";
                    else
                        stringDatos += "<p>Dirección: Sin definir</p>";
                    stringDatos += "<p>Velocidad: " + $("speed", datos).attr("value") + " " + $("speed", datos).attr("unit") + "</p>";
                    stringDatos += "<p><strong>Nubes</strong></p>";
                    stringDatos += "<p>Visibilidad: " + $("visibility", datos).attr("value") + " metros</p>";
                    stringDatos += "<p>Nubosidad: " + $("clouds", datos).attr("value") + "% (" + $("clouds", datos).attr("name") + ")</p>";
                    var medicion = $("lastupdate", datos).attr("value");
                    var medicionMiliSeg1970 = Date.parse(medicion);
                    medicionMiliSeg1970 -= minutosZonaHoraria * 60 * 1000;
                    stringDatos += "<p class='medicion'>Última medición: " + (new Date(medicionMiliSeg1970)).toLocaleTimeString("es-ES") + "</p>";

                    $("#ciudades").append("</div>" + stringDatos);
                },
                error: function () {
                    alert("Se ha producido un error recuperando la información meteorológica de " + ciudades[i] + ".");
                }
            })
        }

        this.marcarDatosCargados();
    }

    marcarDatosCargados() {
        this.datosCargados = true;
        $("#botonDatos").attr("value", "Actualizar Datos");
    }

    actualizarDatos() {
        $("#ciudades").html("");
        this.cargarDatos();
    }

}

var meteoApi = new MeteoAPI();
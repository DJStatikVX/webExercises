class MeteoAPI {
    constructor() {
        this.apiKey = "6c869f77fa7de5750a06123cff06d6c8"
        this.ciudades = ["Gijón", "Mérida", "Sevilla"];
        this.pais = "ES";
        this.unidades = "metric";
        this.idioma = "es";
        this.url = "https://api.openweathermap.org/data/2.5/weather?q=ciudad," + this.pais + "&units=" + this.unidades
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
                dataType: "json",
                url: urlCopia,
                method: 'GET',
                success: function (datos) {
                    var stringDatos = "<div class='ciudad'><div class='encabezado'><h2>" + datos.name + ", " + datos.sys.country + "</h2>";
                    stringDatos += "<p>(" + datos.coord.lat + ", " + datos.coord.lon + ")</p></div>";
                    stringDatos += "<div class='grados'><img src=\"https://openweathermap.org/img/w/" + datos.weather[0].icon + ".png"
                        + "\" alt=\"" + datos.weather[0].main + " (" + datos.weather[0].description + ")\"" + "/><p><strong>" + Math.round(parseFloat(datos.main.temp)) + "º</strong></p></div>";
                    stringDatos += "<p>Sensación térmica: " + Math.round(parseFloat(datos.main.feels_like)) + "º</p>";
                    stringDatos += "<p>Temperatura mínima: " + Math.round(parseFloat(datos.main.temp_min)) + "º</p>";
                    stringDatos += "<p>Temperatura máxima: " + Math.round(parseFloat(datos.main.temp_max)) + "º</p>"
                    stringDatos += "<p>Presión: " + datos.main.pressure + " mbar</p>";
                    stringDatos += "<p>Humedad: " + datos.main.humidity + "%</p>";
                    stringDatos += "<p>Amanece a las " + new Date(datos.sys.sunrise * 1000).toLocaleTimeString() + "</p>";
                    stringDatos += "<p>Anochece a las " + new Date(datos.sys.sunset * 1000).toLocaleTimeString() + "</p>";
                    stringDatos += "<p><strong>Viento</strong></p>";
                    stringDatos += "<p>Dirección: " + datos.wind.deg + " grados</p>";
                    stringDatos += "<p>Velocidad: " + datos.wind.speed + " m/s</p>";
                    stringDatos += "<p><strong>Nubes</strong></p>";
                    stringDatos += "<p>Visibilidad: " + datos.visibility + " metros</p>";
                    stringDatos += "<p>Nubosidad: " + datos.clouds.all + " %</p>";

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
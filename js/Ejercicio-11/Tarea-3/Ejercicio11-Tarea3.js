"use strict";
class GeoLocalizacion {
    constructor() {
        navigator.geolocation.getCurrentPosition(this.getPosicion.bind(this), this.mostrarError.bind(this));
    }

    getPosicion(posicion) {
        this.mensaje = "Ubicación obtenida con éxito";
        this.longitud = posicion.coords.longitude;
        this.latitud = posicion.coords.latitude;
        this.precision = posicion.coords.accuracy;
        this.altitud = posicion.coords.altitude;
        this.precisionAltitud = posicion.coords.altitudeAccuracy;
        this.rumbo = posicion.coords.heading;
        this.velocidad = posicion.coords.speed;

        this.comprobarCamposNull();
    }

    mostrarInfoEn(elemento) {
        var elementoAEscribir = document.getElementById(elemento);
        var stringDatos = "<p>" + this.mensaje + "</p>"; 

        stringDatos += "<p>Tu longitud es: " + this.longitud + " grados</p>"; 
        stringDatos += "<p>Tu latitud es: " + this.latitud + " grados</p>"; 
        stringDatos += "<p>Precisión de la latitud y longitud: " + this.precision + " metros</p>";
        stringDatos += "<p>Altitud: " + this.altitude + " metros</p>";
        stringDatos += "<p>Precisión de la altitud: " + this.precisionAltitud + " metros</p>"; 
        stringDatos += "<p>Rumbo: " + this.rumbo + " grados</p>"; 
        stringDatos += "<p>Tu velocidad es: " + this.velocidad + " m/s</p>";

        elementoAEscribir.innerHTML = stringDatos + "<div id='mapa'></div>";

        this.mostrarMapaEstaticoGoogleEn("mapa");
    }

    mostrarMapaEstaticoGoogleEn(elemento) {
        var ubicacion = document.getElementById(elemento);
        
        // API Key de JavaScript Google Maps
        var apiKey = "&key=your_api_key";
        // URL: obligatoriamente https
        var url = "https://maps.googleapis.com/maps/api/staticmap?";

        // Parámetros
        // Zoom a 15 para ver las calles de alrededor
        var zoom = "&zoom=15";
        // Tamaño del mapa en píxeles
        var tamaño = "&size=600x400";
        // Tipo de mapa
        var tipo = "&maptype=roadmap";
        // Idioma (opcional)
        var idioma = "&language=es";
        //region (opcional)
        var region = "&region=ES";
        //marcadores (opcional)
        var marcador = "&markers=color:red%7Clabel:S%7C" + this.latitud + "," + this.longitud;

        var sensor = "&sensor=false"; 
        
        this.imagenMapa = url + zoom + tamaño + tipo + idioma + region + marcador + sensor + apiKey;
        ubicacion.innerHTML = "<img src='" + this.imagenMapa + "'/>";
    }

    comprobarCamposNull() {
        if (this.longitud == null)
            this.longitud = "No definida";
        if (this.latitud == null)
            this.latitud = "No definida";
        if (this.precision == null)
            this.precision = "No definida";
        if (this.altitud == null)
            this.altitud = "No definida";
        if (this.precisionAltitud == null)
            this.precisionAltitud = "No definida";
        if (this.rumbo == null)
            this.rumbo = "No definido";
        if (this.velocidad == null)
            this.velocidad = "No definida";
    }

    mostrarError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                this.mensaje = "No has concedido permiso a esta página web para acceder a tu ubicación.";
                break;
            case error.POSITION_UNAVAILABLE:
                this.mensaje = "La información de geolocalización no se encuentra actualmente disponible.";
                break;
            case error.TIMEOUT:
                this.mensaje = "La petición de geolocalización ha caducado.";
                break;
            case error.UNKNOWN_ERROR:
                this.mensaje = "Se ha producido un error desconocido.";
                break;
        }
    }
}

var posicionGPS = new GeoLocalizacion();
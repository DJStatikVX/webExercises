"use strict";
class GeoLocalizacion {
    constructor() {
        navigator.geolocation.getCurrentPosition(this.getPosicion.bind(this));
    }

    getPosicion(posicion) {
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
        var stringDatos = ""; 

        stringDatos += "<p>Tu longitud es: " + this.longitud + " grados</p>"; 
        stringDatos += "<p>Tu latitud es: " + this.latitud + " grados</p>"; 
        stringDatos += "<p>Precisión de la latitud y longitud: " + this.precision + " metros</p>";
        stringDatos += "<p>Altitud: " + this.altitude + " metros</p>";
        stringDatos += "<p>Precisión de la altitud: " + this.precisionAltitud + " metros</p>"; 
        stringDatos += "<p>Rumbo: " + this.rumbo + " grados</p>"; 
        stringDatos += "<p>Tu velocidad es: " + this.velocidad + " m/s</p>";

        elementoAEscribir.innerHTML = stringDatos;
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
}

var posicionGPS = new GeoLocalizacion();
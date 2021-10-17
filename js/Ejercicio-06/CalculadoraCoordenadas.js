class CalculadoraCoordenadas {
    ayuda() {
        alert("Pulsa en las flechas para convertir las coordenadas. \nA continuación, pulsa el botón para obtener la distancia en kilómetros.");
    }
    
    convertirPrimeraCoordenada() {
        if (!document.getElementById("x1").value) {
            alert("Debes especificar una coordenada X para el primer punto.");
            return;
        }
    
        if (!document.getElementById("y1").value) {
            alert("Debes especificar una coordenada Y para el primer punto.");
            return;
        }
    
        var numHuso = parseFloat(document.getElementById("huso1").value);
    
        if (!document.getElementById("huso1").value) {
            alert("Debes especificar un huso UTM para el primer punto.");
            return;
        } else if (numHuso < 1 || numHuso > 60) {
            alert("Debes introducir un huso UTM válido para el primer punto\n(entre 1 y 60 inclusive).");
            return;
        }
    
        var hemisferioNorte = (document.getElementById("hemisferio1").value.toString() == "N")
    
        if (document.getElementById("hemisferio1").value.toString() == "Hemisferio") {
            alert("Debes seleccionar un hemisferio para el primer punto.");
            return;
        }
    
        var X = parseFloat(document.getElementById("x1").value);
        var Y = parseFloat(document.getElementById("y1").value);
    
        if (isNaN(X) || isNaN(Y)) {
            alert("El formato de las coordenadas del primer punto no es correcto.\nPor favor, inténtelo de nuevo.");
            return;
        }
    
        var resultado = this.convertirUtmALatLong(numHuso, X, Y, hemisferioNorte).split(",");
    
        document.getElementById("latitud1").value = resultado[0];
        document.getElementById("longitud1").value = resultado[1];
    }
    
    convertirSegundaCoordenada() {
        if (!document.getElementById("x2").value) {
            alert("Debes especificar una coordenada X para el segundo punto.");
            return;
        }
    
        if (!document.getElementById("y2").value) {
            alert("Debes especificar una coordenada Y para el segundo punto.");
            return;
        }
    
        var numHuso = parseFloat(document.getElementById("huso2").value);
    
        if (!document.getElementById("huso2").value) {
            alert("Debes especificar un huso UTM para el segundo punto.");
            return;
        } else if (numHuso < 1 || numHuso > 60) {
            alert("Debes introducir un huso UTM válido para el segundo punto\n(entre 1 y 60 inclusive).");
            return;
        }
    
        var hemisferioNorte = (document.getElementById("hemisferio2").value.toString() == "N")
    
        if (document.getElementById("hemisferio1").value.toString == "Hemisferio") {
            alert("Debes seleccionar un hemisferio para el segundo punto.");
            return;
        }
    
        var X = parseFloat(document.getElementById("x2").value);
        var Y = parseFloat(document.getElementById("y2").value);
    
        if (isNaN(X) || isNaN(Y)) {
            alert("El formato de las coordenadas del segundo punto no es correcto.\nPor favor, inténtelo de nuevo.");
            return;
        }
    
        var resultado = this.convertirUtmALatLong(numHuso, X, Y, hemisferioNorte).split(",");
    
        document.getElementById("latitud2").value = resultado[0];
        document.getElementById("longitud2").value = resultado[1];
    }
    
    convertirUtmALatLong(numHuso, X, Y, hemisferioNorte) {
        if (!hemisferioNorte)
            Y = 10000000 - Y;
    
        var a = 6378137.0;            // a = radio ecuatorial en metros
        var e = 0.081819190842622;    // e = excentricidad
        var e1sq = 0.006739497;       // e1sq = cuadrado de la segunda excentricidad
        var k0 = 0.9996;              // factor de escala en el meridiano central k0 = 0.9996
    
        var arco = Y / k0;
        var mu = arco / (a * (1 - Math.pow(e, 2) / 4.0 - 3 * Math.pow(e, 4) / 64.0 - 5 * Math.pow(e, 6) / 256.0));
    
        var ei = (1 - Math.pow((1 - e * e), (1 / 2.0))) / (1 + Math.pow((1 - e * e), (1 / 2.0)));
    
        var ca = 3 * ei / 2 - 27 * Math.pow(ei, 3) / 32.0;
    
        var cb = 21 * Math.pow(ei, 2) / 16 - 55 * Math.pow(ei, 4) / 32;
        var cc = 151 * Math.pow(ei, 3) / 96;
        var cd = 1097 * Math.pow(ei, 4) / 512;
        var phi1 = mu + ca * Math.sin(2 * mu) + cb * Math.sin(4 * mu) + cc * Math.sin(6 * mu) + cd * Math.sin(8 * mu);
    
        var n0 = a / Math.pow((1 - Math.pow((e * Math.sin(phi1)), 2)), (1 / 2.0));
    
        var r0 = a * (1 - e * e) / Math.pow((1 - Math.pow((e * Math.sin(phi1)), 2)), (3 / 2.0));
        var fact1 = n0 * Math.tan(phi1) / r0;
    
        var _a1 = 500000 - X;
        var dd0 = _a1 / (n0 * k0);
        var fact2 = dd0 * dd0 / 2;
    
        var t0 = Math.pow(Math.tan(phi1), 2);
        var Q0 = e1sq * Math.pow(Math.cos(phi1), 2);
        var fact3 = (5 + 3 * t0 + 10 * Q0 - 4 * Q0 * Q0 - 9 * e1sq) * Math.pow(dd0, 4) / 24;
    
        var fact4 = (61 + 90 * t0 + 298 * Q0 + 45 * t0 * t0 - 252 * e1sq - 3 * Q0 * Q0) * Math.pow(dd0, 6) / 720;
    
        var lof1 = _a1 / (n0 * k0);
        var lof2 = (1 + 2 * t0 + Q0) * Math.pow(dd0, 3) / 6.0;
        var lof3 = (5 - 2 * Q0 + 28 * t0 - 3 * Math.pow(Q0, 2) + 8 * e1sq + 24 * Math.pow(t0, 2)) * Math.pow(dd0, 5) / 120;
        var _a2 = (lof1 - lof2 + lof3) / Math.cos(phi1);
        var _a3 = _a2 * 180 / Math.PI;
    
        var latitud = 180 * (phi1 - fact1 * (fact2 + fact3 + fact4)) / Math.PI;
    
        if (!hemisferioNorte)
            latitud = 0 - latitud;
    
        var longitud = ((numHuso > 0) && (6 * numHuso - 183.0) || 3.0) - _a3;
    
        var resultado = latitud.toString() + "," + longitud.toString();
    
        return resultado;
    }
    
    mostrarDistancia() {
        if (document.getElementById("latitud1").value == "" || document.getElementById("longitud1").value == ""
            || document.getElementById("latitud2").value == "" || document.getElementById("longitud2").value == "") {
            
            alert("Por favor, convierta ambos puntos a Latitud/Longitud para obtener la distancia.");
            return;
        }
    
        try {
            var latitud1 = parseFloat(document.getElementById("latitud1").value);
            var longitud1 = parseFloat(document.getElementById("longitud1").value);
            var latitud2 = parseFloat(document.getElementById("latitud2").value);
            var longitud2 = parseFloat(document.getElementById("longitud2").value);
        } catch (err) {
            alert("Se ha producido un error inesperado de cálculo.");
        }
    
        var distancia = Math.round(this.calcularDistancia(latitud1, longitud1, latitud2, longitud2) * 10) / 10;
    
        document.getElementById("resultado").value = (distancia + " km");
    }
    
    calcularDistancia(latitud1, longitud1, latitud2, longitud2) {
        const R = 6371;
        var f1 = latitud1 * Math.PI / 180;
        var f2 = latitud2 * Math.PI / 180;
        var df = (latitud2 - latitud1) * Math.PI / 180;
        var dl = (longitud2 - longitud1) * Math.PI / 180;
    
        var a = Math.sin(df / 2) * Math.sin(df / 2) +
                Math.cos(f1) * Math.cos(f2) *
                Math.sin(dl / 2) * Math.sin(dl / 2);
    
        var c = 2 * Math.atan(Math.sqrt(a), Math.sqrt(1 - a));
    
        var d = R * c;
    
        return d;
    }
}

var calculadora = new CalculadoraCoordenadas();
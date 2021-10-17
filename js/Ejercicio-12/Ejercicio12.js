class GestorFicheros {
    constructor() {
        this.archivos = [];
        this.cargado = false;
    }

    cargar(archivos) {
        if (!this.cargado && this.comprobarSoporteAPIFile()) {
            this.archivos = archivos;
            this.recorrerArchivos();
            this.cargado = true;
        } else {
            this.limpiarInformacion();
            this.cargar(archivos);
        }
    }

    limpiarInformacion() {
        $("#archivos").html("");
        this.cargado = false;
    }

    recorrerArchivos() {
        for (var i = 0; i < this.archivos.length; i++) {
            this.mostrarDatos(this.archivos[i], i);
        }
    }

    mostrarDatos(archivo, i) {
        var tipo = archivo.type;

        // Nuevo div para el archivo
        $("#archivos").append("<div class='archivo' id='archivo" + i + "'></div>");
        $("#archivo" + i).append("<div class='encabezado' id='encabezado" + i + "'></div>");

        // Datos del encabezado
        $("#encabezado" + i).append("<h2>" + archivo.name + "</h2>");
        $("#encabezado" + i).append("<p>" + "Tipo: " + tipo + "</p>");
        $("#encabezado" + i).append("</div>");

        // Información detallada
        $("#archivo" + i).append("<p>Tamaño: " + archivo.size + " bytes");
        $("#archivo" + i).append("<p>Última modificación: " + archivo.lastModifiedDate.toLocaleDateString("es-ES") + "</p>");

        // Mostrar contenido para TXT, JSON y XML
        if (tipo == "text/plain" || tipo == "application/json" || tipo == "text/xml") {
            $("#archivo" + i).append("<div class='contenido' id='contenido" + i + "'></div>");
            $("#contenido" + i).append("<p>Contenido:</p>");
            $("#contenido" + i).append("<pre class='texto' id='texto" + i + "'></p>");

            var lector = new FileReader();
            lector.onload = function(e) {
                $("#texto" + i).append(document.createTextNode(lector.result));
            }

            lector.readAsText(archivo);
        }
    }

    comprobarSoporteAPIFile() {
        if (!(window.File && window.FileReader && window.FileList && window.Blob)) {
            this.escribirNoSoportaAPI();
            return false;
        }

        return true;
    }

    escribirNoSoportaAPI() {
        $("#archivos").append("<p>Tu navegador no soporta la API File de HTML5.</p>")
    }
}

var gestorFicheros = new GestorFicheros();
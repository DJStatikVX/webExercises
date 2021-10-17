// Clase responsable de parsear el XML y dibujar las figuras
class EditorFiguras {
    constructor() {
        this.ayuda = false;
    }

    cargarFiguras(archivos) {
        var ficheroFiguras = archivos[0];
        // Se comprueba el formato del archivo
        if (ficheroFiguras.name.split(".")[1].toLowerCase() != "xml") {
            alert("El formato de fichero introducido no es válido.\nDebes cargar un archivo de tipo XML.");
            return;
        }

        // Definimos función de parseo
        var lector = new FileReader();
        var contador = this.contador;
        lector.onload = function() {
            try {
                // Conversión de texto a XML
                var xml = $.parseXML(lector.result);

                // Se recorren las figuras
                $("figuras", xml).find("figura").each(function() {
                    // Creación de un div individual para la nueva figura
                    var contador = $(".figura").length;
                    $("#figuras").append("<div class='figura' id='figura" + contador + "'</div>");
                    $("#figura" + contador).append("<canvas class='canvas' id='canvas" + contador 
                    + "' width='150' height='150' onclick=\"pantallaCompleta.visualizar('canvas" + contador + "')\"></canvas>");
                    // Recuperación de datos de la figura (tipo y color)
                    var tipo = $(this).attr("tipo");
                    var color = $(this).find("color");
                    // Se establecen las propiedades del API Canvas
                    var strColor = "rgba(" + color.attr("r") + "," + color.attr("g") + "," + color.attr("b") + "," + color.attr("a") + ")";
                    var canvas = document.getElementById("canvas" + contador);
                    var ctx = canvas.getContext("2d");
                    ctx.fillStyle = strColor;
                    // En función del tipo de figura detectado, se realizan unas operaciones u otras
                    switch (tipo) {
                        case "Cuadrado":
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                            break;
                        case "Triángulo":
                            ctx.beginPath();
                            ctx.moveTo(0, canvas.height);
                            ctx.lineTo(canvas.width / 2, 0);
                            ctx.lineTo(canvas.width, canvas.height);
                            ctx.lineTo(0, canvas.height);
                            ctx.closePath();
                            ctx.fill();
                            break;
                        case "Círculo":
                            ctx.beginPath();
                            ctx.arc(canvas.width / 2, canvas.height / 2, canvas.height / 2, 0, Math.PI * 2, false);
                            ctx.closePath();
                            ctx.fill();
                            break;
                    }
                });

            // Gestión de errores de formato del XML
            } catch (err) {
                alert("El archivo XML introducido no es válido.\nPor favor, revise la estructura requerida y vuelva a intentarlo.")
            }
        }

        // Por último, se lee el fichero XML de las figuras a dibujar
        lector.readAsText(ficheroFiguras);
    }

    toggleAyuda() {
        if (!this.ayuda) {
            this.ayuda = true;
            $("#ayuda").append("<p>El fichero XML debe seguir el siguiente formato:</p>");
            $("#ayuda").append("<pre class='formato'></pre>");
            $("#botonAyuda").attr("value", "Ocultar ayuda");
            $(".formato").text("<?xml version='1.0' encoding='UTF-8'?>\n<figuras>\n\t<figura tipo='Cuadrado'>\n\t\t<color r='255' g='0' b='0' a='1' />\n\t</figura>\n"
            + "\t<figura tipo='Triángulo'>\n\t\t<color r='0' g='255' b='0' a='1' />\n\t</figura>\n\t<figura tipo='Círculo'>\n\t\t<color r='0' g='0' b='255' a='1' />\n\t</figura>\n</figuras>");
        } else {
            $("#ayuda").html("");
            $("#botonAyuda").attr("value", "Mostrar ayuda");
            this.ayuda = false;
        }
    }
}

// Clase encargada de visualizar elementos en pantalla completa
class PantallaCompleta {
    visualizar(idElemento) {
        var elemento = document.getElementById(idElemento);
        // No es necesario definir método de salir, funciona por defecto mediante la tecla Esc
        if (elemento.requestFullscreen)
            elemento.requestFullscreen();
    } 
}

// Instancia de editor que conecta con el HTML
var editor = new EditorFiguras();

// Instancia de visualización a pantalla completa para responder peticiones desde el cliente
var pantallaCompleta = new PantallaCompleta();
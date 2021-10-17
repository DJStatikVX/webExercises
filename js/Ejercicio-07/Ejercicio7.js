class Listener {
    constructor() {
        this.hiddenH1 = false;
        this.hiddenH2 = false;
        this.hiddenH3 = false;
        this.hiddenP = false;
        this.hiddenTable = false;
        this.imagen = false;
        this.cuotas = false;
    }

    toggleH1() {
        if (!this.hiddenH1) {
            $("#h1").hide();
            $("#botonH1").attr('value', 'Mostrar h1');
            this.hiddenH1 = true;
        } else {
            $("#h1").show();
            $("#botonH1").attr('value', 'Ocultar h1');
            this.hiddenH1 = false;
        }
    }

    toggleH2() {
        if (!this.hiddenH2) {
            $("#h2").hide();
            $("#botonH2").attr('value', 'Mostrar h2');
            this.hiddenH2 = true;
        } else {
            $("#h2").show();
            $("#botonH2").attr('value', 'Ocultar h2');
            this.hiddenH2 = false;
        }
    }

    toggleH3() {
        if (!this.hiddenH3) {
            $("#h3").hide();
            $("#botonH3").attr('value', 'Mostrar h3');
            this.hiddenH3 = true;
        } else {
            $("#h3").show();
            $("#botonH3").attr('value', 'Ocultar h3');
            this.hiddenH3 = false;
        }
    }

    toggleP() {
        if (!this.hiddenP) {
            $("#p").hide();
            $("#botonP").attr('value', 'Mostrar Párrafo');
            this.hiddenP = true;
        } else {
            $("#p").show();
            $("#botonP").attr('value', 'Ocultar Párrafo');
            this.hiddenP = false;
        }
    }

    toggleTable() {
        if (!this.hiddenTable) {
            $("#table").hide();
            $("#botonTable").attr('value', 'Mostrar Tabla');
            this.hiddenTable = true;
        } else {
            $("#table").show();
            $("#botonTable").attr('value', 'Ocultar Tabla');
            this.hiddenTable = false;
        }
    }

    verVentas() {
        $("#estadisticas").html("<p id='p'>Estas son las ventas de la última semana:</p> <table id='table'> <tr> <th>Fabricante</th> "
        + "<th>Modelo</th> <th>Unidades vendidas</th> </tr> <tr> <td>Samsung</td> <td>Galaxy A51</td> <td>27</td> </tr> <tr> <td>Apple</td> " 
        + "<td>iPhone 12</td> <td>78</td> </tr> <tr> <td>OPPO</td> <td>realme 7</td> <td>21</td> </tr> <tr> <td>Xiaomi</td> <td>Redmi Note 9S</td> "
        + "<td>94</td> </tr> </table> <div id='estadisticasVer'> <input type='button' id='botonVerVentas' onclick='listener.verVentas()' "
        + "value='Ver Ventas'/> <input type='button' id='botonVerCuota' onclick='listener.verCuotas()' value='Ver Cuotas'/> </div> "
        + "<div id='estadisticasImagen'> <input type='button' id='botonAdjuntarImagen' onclick='listener.adjuntarImagen()' value='Adjuntar Imagen'/> "
        + "<input type='button' id='botonEliminarImagen' onclick='listener.eliminarImagen()' value='Eliminar Imagen'/> </div> <div id='botonesSumar'> <input type='button' id='botonCalcularFilasTablaCuotas' "
        + "onclick='listener.calcularFilasTablaCuotas()' value='Sumar Filas Cuotas'/> <input type='button' id='botonCalcularColumnasTablaCuotas' onclick='listener.calcularColumnasTablaCuotas()' "
        + "value='Sumar Columnas Cuotas'/> </div>");
        
        this.cuotas = false;
    }

    verCuotas() {
        $("#estadisticas").html("<p id='p'>Estas es la cuota de mercado de los fabricantes:</p> <table id='table'> <tr> <th>Fabricante</th> <th>Cuota</th> "
        + "<th>Crecimiento</th><th>Total</th> </tr> <tr> <td>Samsung</td> <td>26%</td> <td>+6%</td> <td></td> </tr> <tr> <td>Apple</td> <td>7%</td> <td>+24%</td> <td></td></tr> "
        + "<tr> <td>OPPO</td> <td>8%</td> <td>+1456%</td> <td></td></tr> <tr> <td>Xiaomi</td> <td>34%</td> <td>+93%</td> <td></td></tr><tr><td>Total</td><td></td><td></td></tr> </table>  "
        + "<div id='estadisticasVer'> <input type='button' id='botonVerVentas' onclick='listener.verVentas()' value='Ver Ventas'/> "
        + "<input type='button' id='botonVerCuota' onclick='listener.verCuotas()' value='Ver Cuotas'/> </div> <div id='estadisticasImagen'> "
        + "<input type='button' id='botonAdjuntarImagen' onclick='listener.adjuntarImagen()' value='Adjuntar Imagen'/> <input type='button' id='botonEliminarImagen' "
        + "onclick='listener.eliminarImagen()' value='Eliminar Imagen'/> </div> <div id='botonesSumar'> <input type='button' id='botonCalcularFilasTablaCuotas' "
        + "onclick='listener.calcularFilasTablaCuotas()' value='Sumar Filas Cuotas'/> <input type='button' id='botonCalcularColumnasTablaCuotas' onclick='listener.calcularColumnasTablaCuotas()' "
        + "value='Sumar Columnas Cuotas'/> </div>");

        this.cuotas = true;
    }

    adjuntarImagen() {
        if (!this.imagen) {
            var htmlAnterior = $("#contenido").html();
            var nuevoHtml = htmlAnterior.concat("<img src='https://i.blogs.es/091a6a/image-2020-11-06-10-25-31/1366_2000.jpg' width='400' height='200' alt='Cuota del mercado español de smartphones en el tercer trimestre de 2020'/>")
            $("#contenido").html(nuevoHtml);
            this.imagen = true;
        }
    }

    eliminarImagen() {
        if (this.imagen) {
            var htmlActual = $("#contenido").html().toString();
            var htmlAEliminar = "<img src='https://i.blogs.es/091a6a/image-2020-11-06-10-25-31/1366_2000.jpg' width='400' height='200' alt='Cuota del mercado español de smartphones en el tercer trimestre de 2020'/>";
            var nuevoHtml = htmlActual.substring(0, htmlActual.length - htmlAEliminar.length);
            $("#contenido").html(nuevoHtml);
            this.imagen = false;
        }
    }

    calcularFilasTablaCuotas() {
        if (this.cuotas) {
            var indiceFila = 0;
            $("table tr").each(function () {
                if (indiceFila < $("table tr").length - 1) {
                    var row = $(this);
                    var total = 0;

                    var datos = $(this).children("td");

                    for (var i = 1; i < datos.length; i++) {
                        if (i != datos.length - 1) {
                            total += (parseInt($(datos[i]).text().substring(0, $(datos[i]).text().length - 1))) / 100;
                        } else {
                            $(datos[i]).text("" + total * 100 + "%");
                        }
                    }
                }

                indiceFila++;
            }); 
        }
    }

    calcularColumnasTablaCuotas() {
        if (this.cuotas) {
            var filas = $("table tr");
            var nColumnas = $(filas[0]).children("th").length - 1;

            for (var i = 1; i < nColumnas; i++) {
                var indiceFila = 0;
                var total = 0;

                filas.each(function () {
                    if (indiceFila > 0) {
                        var elemento = $(this).children("td")[i];
                        if (indiceFila < filas.length - 1) {
                            total += (parseInt($(elemento).text().substring(0, $(elemento).text().length - 1))) / 100;
                        } else if (indiceFila == filas.length - 1 ) {
                            $(elemento).text("" + total * 100 + "%");
                        }
                    }

                    indiceFila++;
                });
            }
        }
    }

    recorrerDOM() {
        $("*", document.body).each(function() {
            var etiquetaPadre = $(this).parent().get(0).tagName;
            $(this).prepend(document.createTextNode( "Tag padre : <"  + etiquetaPadre + "> elemento : <" + $(this).get(0).tagName +"> valor: "));
        });
    }
}

var listener = new Listener();
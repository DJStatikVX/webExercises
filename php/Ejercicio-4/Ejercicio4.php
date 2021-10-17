<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width"/>
        <title>Ejercicio 4</title>
        <link rel="stylesheet" type="text/css" href="Ejercicio4.css"/>
    </head>
    <body>
        <section>
            <h1>Cotizaciones en Bolsa</h1>
            <p class="hidden">UO271612</p>
            <?php 
                session_name("Ejercicio-4");

                session_start();

                $_SESSION["marketStackAPI"] = new MarketStackAPI();

                class MarketStackAPI {
                    private $urlBase;
                    private $apiKey;
                    private $htmlResultado;

                    public function __construct() {
                        $this->urlBase = "http://api.marketstack.com/v1/tickers/";
                        $this->apiKey = "21396f4f5699ddfc4c99c76b7402bdbb";
                        $this->htmlResultado = "";
                    }

                    public function getHtmlResultado() {
                        return $this->htmlResultado;
                    }

                    public function cargarDatos($simbolo) {
                        $url = $this->urlBase . $simbolo . "/eod/latest?access_key=" . $this->apiKey;
                        $datos = file_get_contents($url);

                        // En caso de error
                        if ($datos == false) {
                            echo "<p class='error'>No se ha encontrado información para " . $simbolo . ".</p>";
                        } else {
                            // Si se encuentra el fichero, se intenta convertir a JSON
                            $datosJson = json_decode($datos);

                            if ($datosJson == null) {
                                echo "<p class='error'>Se ha producido un error procesando la solicitud.</p>";
                            } else {
                                // Obtenemos los datos
                                $intercambio = $datosJson->exchange;
                                $valorApertura = $datosJson->open;
                                $valorCierre = $datosJson->close;
                                $valorMaximo = $datosJson->high;
                                $valorMinimo = $datosJson->low;
                                $volumen = $datosJson->volume;
                                $fecha = $datosJson->date;

                                $this->mostrarResultados($simbolo, $intercambio, $valorApertura, 
                                    $valorCierre, $valorMaximo, $valorMinimo, $volumen, $fecha);
                            }
                        }
                    }

                    private function mostrarResultados($simbolo, $intercambio, $valorApertura, 
                        $valorCierre, $valorMaximo, $valorMinimo, $volumen, $fecha) {

                        // Convertimos la fecha
                        $fecha = date("d/m/Y", strtotime($fecha));

                        // Generamos el HTML dinámicamente
                        $resultado =
                            "<div class='empresa'>
                                <section class='encabezado'>
                                    <h2>" . $simbolo . "</h2>
                                    <p class='intercambio'>Intercambio: " . $intercambio . "</p>
                                </section>
                                <div class='cuerpo'>
                                    <p class='strong'>Valor de cierre: " . $valorCierre . "</p>
                                    <p>Valor de apertura: " . $valorApertura . "</p>
                                    <p>Valor máximo del día: " . $valorMaximo . "</p>
                                    <p>Valor mínimo del día: " . $valorMinimo . "</p>
                                    <p>Volumen: " . $volumen . " miles de acciones</p>
                                    <p class='actualizacion'>Actualizado a día " . $fecha . "</p>
                                </div>
                            </div>";

                        // Y lo mostramos en la página
                        $this->htmlResultado = $resultado;
                    }
                }

                if (isset($_POST["botonDatos"])) {
                    $simbolo = $_POST["inputEmpresa"];
                    $_SESSION["marketStackAPI"]->cargarDatos($simbolo);
                }

                echo '
                <div id="contenido">
                    <form action="#" method="post" name="entrada">
                        <label for="inputEmpresa">Símbolo de la empresa:</label>
                        <input type="text" id="inputEmpresa" name="inputEmpresa"/>
                        <input type="submit" id="botonDatos" name="botonDatos" value="Obtener Últimos Datos del Mercado"/>
                    </form>
                    <div id="tickers">' . $_SESSION["marketStackAPI"]->getHtmlResultado() . '</div>
                </div>
                '
            ?>
        </section>
        <footer>
            <a href="http://validator.w3.org/check/referer" hreflang="en-us">
                <img src="http://di002.edv.uniovi.es/~cueva/JavaScript/valid-html5-button.png" alt="¡HTML5 válido!" height="31" width="88" /></a>
            <a href="http://jigsaw.w3.org/css-validator/check/referer">
                <img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="¡CSS Válido!" /></a>
        </footer>
    </body>
</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Ejercicio 2</title>
    <link rel="stylesheet" type="text/css" href="CalculadoraCientifica.css" />
</head>

<body>
    <h1>Calculadora científica</h1>
    <?php

    session_name("CalculadoraCientifica");

    session_start();

    if (!isset($_SESSION["calculadoraCientifica"])) {
        $_SESSION["calculadoraCientifica"] = new CalculadoraCientifica();
    }

    class CalculadoraBasica
    {
        protected $pantalla;
        private $memoria;
        protected $resultado;
        private $resetearPantalla;

        public function __construct()
        {
            $this->pantalla = "";
            $this->memoria = 0;
            $this->resultado = 0;
            $this->resetearPantalla = false;
        }

        public function getResetearPantalla() {
            return $this->resetearPantalla;
        }

        public function añadirAExpresion($caracter)
        {
            if ($this->resetearPantalla || $this->pantalla == "NAN" || $this->pantalla == "INF")
                $this->borrar();

            $this->pantalla .= $caracter;
        }

        public function borrar()
        {
            $this->pantalla = "";
            $this->resultado = 0;
            $this->resetearPantalla = false;
        }

        public function digitos($digito)
        {
            $this->añadirAExpresion($digito);
        }

        public function punto()
        {
            $this->añadirAExpresion('.');
        }

        public function suma()
        {
            $this->añadirAExpresion('+');
        }

        public function resta()
        {
            $this->añadirAExpresion('-');
        }

        public function multiplicacion()
        {
            $this->añadirAExpresion('*');
        }

        public function division()
        {
            $this->añadirAExpresion('/');
        }

        public function mrc()
        {
            $this->pantalla .= eval("return $this->memoria;");
        }

        public function mMas()
        {
            $this->memoria += eval("return $this->pantalla;");
        }

        public function mMenos()
        {
            $this->memoria -= eval("return $this->pantalla;");
        }

        public function igual() {
            try {
                $this -> resultado = eval("return $this->pantalla;");
                $this -> pantalla = $this -> resultado;
            } catch (ParseError $pe) {
                $this -> pantalla = "Error";
                $this -> resetearPantalla = true;
            }
        }

        public function responderPeticion() 
        {
            if (isset($_POST["recuperarMemoria"])) $this -> mrc();
            if (isset($_POST["restarMemoria"])) $this -> mMenos();
            if (isset($_POST["sumarMemoria"])) $this -> mMas();
            if (isset($_POST["División"])) $this -> division();

            if (isset($_POST["7"])) $this -> digitos(7);
            if (isset($_POST["8"])) $this -> digitos(8);
            if (isset($_POST["9"])) $this -> digitos(9);
            if (isset($_POST["Multiplicación"])) $this -> multiplicacion();

            if (isset($_POST["4"])) $this -> digitos(4);
            if (isset($_POST["5"])) $this -> digitos(5);
            if (isset($_POST["6"])) $this -> digitos(6);
            if (isset($_POST["Resta"])) $this -> resta();

            if (isset($_POST["1"])) $this -> digitos(1);
            if (isset($_POST["2"])) $this -> digitos(2);
            if (isset($_POST["3"])) $this -> digitos(3);
            if (isset($_POST["Suma"])) $this -> suma();

            if (isset($_POST["0"])) $this -> digitos(0);
            if (isset($_POST["puntoDecimal"])) $this -> punto();
            if (isset($_POST["Borrar"])) $this -> borrar();
            if (isset($_POST["Igual"])) $this -> igual();
        }
    }

    class CalculadoraCientifica extends CalculadoraBasica {
        private $hayOperador;
        private $operandos;

        public function __construct() 
        {
            parent::__construct();
            $this->hayOperador = false;
            $this->operandos = [];
        }

        public function getPantalla() {
            return $this->pantalla;
        }

        public function añadirAExpresion($caracter) 
        {
            // Ante un error
            if (parent::getResetearPantalla() || strpos($this->pantalla,"NAN") !== FALSE || strpos($this->pantalla,"INF") !== FALSE)
                $this->borrar();

            // Rellenar el exp
            if (strpos("+-*/^", strval($caracter)) && Util::endsWith($this->pantalla, "*10^"))
                $this->digitos(0);

            if (count($this->operandos) == 0 && strpos("+-*/^", strval($caracter)) !== FALSE)
                array_push($this->operandos, $this->pantalla);

            // Resolver cada dos operandos
            if ($this->hayOperador && count($this->operandos) != 0 && strpos("+-*/", strval($caracter)) !== FALSE) {
                $this->igual();
                $this->hayOperador = false;
            }

            // Añadir operador
            if (!$this->hayOperador && strpos("+-*/^", strval($caracter)) !== FALSE) {
                array_push($this->operandos, $this->pantalla);
                $this->hayOperador = true;

            // Añadir primer operando
            } else if ($this->hayOperador && !(strpos("+-*/^", strval($caracter)) !== FALSE)
                || (!$this->hayOperador && !(strpos("+-*/^", strval($caracter)) !== FALSE) && count($this->operandos) != 0)) {
                
                array_push($this->operandos, $caracter);
            }

            // Escribir en pantalla
            $this->pantalla .= $caracter;
        }

        public function responderPeticion() 
        {
            parent::responderPeticion();

            if (isset($_POST["raizCuadrada"])) $this->raizCuadrada();
            if (isset($_POST["numeroE"])) $this->numeroE();
            if (isset($_POST["exponenteBase10"])) $this->exp();
            if (isset($_POST["logaritmo"])) $this->log();

            if (isset($_POST["Arcoseno"])) $this->arcoSeno();
            if (isset($_POST["Arcocoseno"])) $this->arcoCoseno();
            if (isset($_POST["Arcotangente"])) $this->arcoTangente();
            if (isset($_POST["Potencia"])) $this->potencial();

            if (isset($_POST["Seno"])) $this->seno();
            if (isset($_POST["Coseno"])) $this->coseno();
            if (isset($_POST["Tangente"])) $this->tangente();
            if (isset($_POST["funcionCuadrado"])) $this->cuadrado();
        }

        public function borrar() {
            parent::borrar();
            $this->hayOperador = false;
            $this->operandos = [];
        }

        public function igual() {
            $this->pantalla = str_replace("^", "**", $this->pantalla);
            $this->pantalla = str_replace("e", eval("return exp(1);"), $this->pantalla);
            parent::igual();
            $this->hayOperador = false;
            $this->operandos = [];
        }

        public function aplicarFuncion($resultadoFuncion) {
            if (count($this->operandos) == 0)
                array_push($this->operandos, $this->pantalla);

            $pos = -1;

            for ($i = strlen($this->pantalla); $i >= 0; $i--) {
                if (strpos("+-*/^", $this->pantalla[$i]) !== false) {
                    $pos = $i + 1;
                    break;
                }
            }

            if ($pos != -1) {
                $this->pantalla = substr($this->pantalla, 0, $pos);
                $this->pantalla = $this->pantalla . strval($resultadoFuncion);
            }
        }

        private function buscarUltimoOperando() {
            $pos = -1;

            for ($i = strlen($this->pantalla); $i >= 0; $i--) {
                if (strpos("+-*/^", substr($this->pantalla, $i, 1)) !== FALSE) {
                    $pos = $i + 1;
                    break;
                }
            }

            if ($pos != -1) {
                return substr($this->pantalla, $pos, strlen($this->pantalla));
            }
        }

        public function raizCuadrada() {
            error_reporting(E_ERROR | E_PARSE);
            if (count($this->operandos) == 0) {
                $this->resultado = sqrt(eval("return $this->pantalla;"));
                $this->pantalla = $this->resultado;
            } else {
                $ultimoOperando = $this->buscarUltimoOperando();
                $raiz = sqrt($ultimoOperando);
                $this->aplicarFuncion($raiz);
            }
        }

        public function seno() {
            error_reporting(E_ERROR | E_PARSE);
            if (count($this->operandos) == 0) {
                $this->resultado = sin(eval("return $this->pantalla;"));
                $this->pantalla = $this->resultado;
            } else {
                $ultimoOperando = $this->buscarUltimoOperando();
                $seno = sin($ultimoOperando);
                $this->aplicarFuncion($seno);
            }
        }

        public function arcoSeno() {
            error_reporting(E_ERROR | E_PARSE);
            if (count($this->operandos) == 0) {
                $this->resultado = asin(eval("return $this->pantalla;"));
                $this->pantalla = $this->resultado;
            } else {
                $ultimoOperando = $this->buscarUltimoOperando();
                $arcoSeno = asin($ultimoOperando);
                $this->aplicarFuncion($arcoSeno);
            }
        }

        public function coseno() {
            error_reporting(E_ERROR | E_PARSE);
            if (count($this->operandos) == 0) {
                $this->resultado = cos(eval("return $this->pantalla;"));
                $this->pantalla = $this->resultado;
            } else {
                $ultimoOperando = $this->buscarUltimoOperando();
                $coseno = cos($ultimoOperando);
                $this->aplicarFuncion($coseno);
            }
        }

        public function arcoCoseno() {
            error_reporting(E_ERROR | E_PARSE);
            if (count($this->operandos) == 0) {
                $this->resultado = acos(eval("return $this->pantalla;"));
                $this->pantalla = $this->resultado;
            } else {
                $ultimoOperando = $this->buscarUltimoOperando();
                $arcoCoseno = acos($ultimoOperando);
                $this->aplicarFuncion($arcoCoseno);
            }
        }

        public function tangente() {
            error_reporting(E_ERROR | E_PARSE);
            if (count($this->operandos) == 0) {
                $this->resultado = tan(eval("return $this->pantalla;"));
                $this->pantalla = $this->resultado;
            } else {
                $ultimoOperando = $this->buscarUltimoOperando();
                $tangente = tan($ultimoOperando);
                $this->aplicarFuncion($tangente);
            }
        }

        public function arcoTangente() {
            error_reporting(E_ERROR | E_PARSE);
            if (count($this->operandos) == 0) {
                $this->resultado = atan(eval("return $this->pantalla;"));
                $this->pantalla = $this->resultado;
            } else {
                $ultimoOperando = $this->buscarUltimoOperando();
                $arcoTangente = atan($ultimoOperando);
                $this->aplicarFuncion($arcoTangente);
            }
        }

        public function cuadrado() {
            error_reporting(E_ERROR | E_PARSE);
            if (count($this->operandos) == 0) {
                $this->resultado = pow(eval("return $this->pantalla;"), 2);
                $this->pantalla = $this->resultado;
            } else {
                $ultimoOperando = $this->buscarUltimoOperando();
                $cuadrado = pow($ultimoOperando, 2);
                $this->aplicarFuncion($cuadrado);
            }
        }

        public function potencial() {
            error_reporting(E_ERROR | E_PARSE);
            if (!Util::endsWith($this->pantalla, "^")) {
                $this->añadirAExpresion("^");
            }
        }

        public function numeroE() {
            error_reporting(E_ERROR | E_PARSE);
            $this->añadirAExpresion(eval("return exp(1);"));
        }

        public function exp() {
            error_reporting(E_ERROR | E_PARSE);
            if (!Util::endsWith($this->pantalla, "e")) {
                $this->añadirAExpresion("*10^");
            }
        }

        public function log() {
            error_reporting(E_ERROR | E_PARSE);
            if (count($this->operandos) == 0) {
                $this->resultado = log(eval("return $this->pantalla;"));
                $this->pantalla = $this->resultado;
            } else {
                $ultimoOperando = $this->buscarUltimoOperando();
                $log = log($ultimoOperando);
                $this->aplicarFuncion($log);
            }
        }
    }

    class Util {
        public static function endsWith($string, $charSequence) {
            if (strlen($charSequence) > strlen($string)) {
                return false;
            }

            return substr_compare($string, $charSequence, strlen($string) - strlen($charSequence), strlen($charSequence)) == 0;
        }
    }

    if (count($_POST) > 0) {
        $_SESSION["calculadoraCientifica"] -> responderPeticion();
    }

    echo '
    <div class="calculadora">
        <label for="pantalla">Pantalla de la calculadora:</label>
        <input type="text" value="' . $_SESSION["calculadoraCientifica"]->getPantalla() . '" name="display" id="pantalla" readonly />
        <form action="#" method="post" name="botones">
            <div class="botones">
                <input type="submit" name="raizCuadrada" value = "√" class="funcion"/>
                <input type="submit" name="numeroE" value = "e" class="funcion"/>
                <input type="submit" name="exponenteBase10" value = "exp" class="funcion"/>
                <input type="submit" name="logaritmo" value = "log" class="funcion"/>

                <input type="submit" name="Arcoseno" value = "asin" class="funcion"/>
                <input type="submit" name="Arcocoseno" value = " acos " class="funcion"/>
                <input type="submit" name="Arcotangente" value = " atan " class="funcion"/>
                <input type="submit" name="Potencia" value = " xⁿ " class="funcion"/>

                <input type="submit" name="Seno" value = " sin " class="funcion"/>
                <input type="submit" name="Coseno" value = " cos " class="funcion"/>
                <input type="submit" name="Tangente" value = " tan " class="funcion"/>
                <input type="submit" name="funcionCuadrado" value = " x² " class="funcion"/>

                <input type="submit" name="recuperarMemoria" value=" mrc " class="memoria" />
                <input type="submit" name="restarMemoria" value=" m- " class="memoria" />
                <input type="submit" name="sumarMemoria" value=" m+ " class="memoria" />
                <input type="submit" name="División" value=" / " class="operador" />

                <input type="submit" name="7" value=" 7 " />
                <input type="submit" name="8" value=" 8 " />
                <input type="submit" name="9" value=" 9 " />
                <input type="submit" name="Multiplicación" value=" * " class="operador" />

                <input type="submit" name="4" value=" 4 " />
                <input type="submit" name="5" value=" 5 " />
                <input type="submit" name="6" value=" 6 " />
                <input type="submit" name="Resta" value=" - " class="operador" />

                <input type="submit" name="1" value=" 1 " />
                <input type="submit" name="2" value=" 2 " />
                <input type="submit" name="3" value=" 3 " />
                <input type="submit" name="Suma" value=" + " class="operador" />

                <input type="submit" name="0" value=" 0 " />
                <input type="submit" name="puntoDecimal" value=" . " />
                <input type="submit" name="Borrar" value=" C " />
                <input type="submit" name="Igual" value=" = " />
            </div>
        </form>
    </div>
    <footer>
        <a href="http://validator.w3.org/check/referer" hreflang="en-us">
            <img src="http://di002.edv.uniovi.es/~cueva/JavaScript/valid-html5-button.png" alt="¡HTML5 válido!" height="31" width="88" /></a>
        <a href="http://jigsaw.w3.org/css-validator/check/referer">
            <img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="¡CSS Válido!" /></a>
    </footer>
    '?>
</body>

</html>
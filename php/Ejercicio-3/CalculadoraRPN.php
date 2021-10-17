<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Ejercicio 3</title>
    <link rel="stylesheet" type="text/css" href="CalculadoraRPN.css" />
</head>

<body>
    <h1>Calculadora RPN</h1>
    <?php

    session_name("CalculadoraRPN");

    session_start();

    if (!isset($_SESSION["calculadoraRPN"])) {
        $_SESSION["calculadoraRPN"] = new CalculadoraRPN();
    }

    class CalculadoraRPN
    {
        protected $pila;
        private $htmlPila;
        protected $entrada;
        private $nuevaEntrada;

        public function __construct()
        {
            $this->pila = array();
            $this->entrada = "0";
            $this->nuevaEntrada = true;
            $this->htmlPila = "";
        }

        public function getEntrada()
        {
            return $this->entrada;
        }

        public function getHtmlPila()
        {
            return $this->htmlPila;
        }

        public function añadirAEntrada($caracter)
        {
            if ($this->nuevaEntrada) {
                $this->entrada = strval($caracter);
                $this->nuevaEntrada = false;
            } else {
                $this->entrada .= strval($caracter);
            }
        }

        public function enter()
        {
            array_unshift($this->pila, $this->entrada);
            $this->refrescarPantalla();
            $this->nuevaEntrada = true;
            $this->entrada = "0";
        }

        private function refrescarPantalla()
        {
            $elementosPila = "";

            for ($i = count($this->pila) - 1; $i >= 0; $i--) {
                $elementosPila .= ("<p>" . $this->pila[$i] . "</p>");
            }

            $this->htmlPila = $elementosPila;
        }

        public function punto()
        {
            $this->añadirAEntrada('.');
        }

        public function digitos($digito)
        {
            $this->añadirAEntrada($digito);
        }

        public function suma()
        {
            if (count($this->pila) > 1) {
                $resultado = array_shift($this->pila) + array_shift($this->pila);
                array_unshift($this->pila, $resultado);
                $this->refrescarPantalla();
            }
        }

        public function resta()
        {
            if (count($this->pila) > 1) {
                $sustraendo = array_shift($this->pila);
                $minuendo = array_shift($this->pila);
                $resultado = $minuendo - $sustraendo;

                array_unshift($this->pila, $resultado);
                $this->refrescarPantalla();
            }
        }

        public function multiplicacion()
        {
            if (count($this->pila) > 1) {
                $resultado = array_shift($this->pila) * array_shift($this->pila);
                array_unshift($this->pila, $resultado);
                $this->refrescarPantalla();
            }
        }

        public function division()
        {
            if (count($this->pila) > 1) {
                $divisor = array_shift($this->pila);
                $dividendo = array_shift($this->pila);
                $resultado = $dividendo / $divisor;

                array_unshift($this->pila, $resultado);
                $this->refrescarPantalla();
            }
        }

        public function retroceder()
        {
            if (strlen($this->entrada) == 1) {
                $this->entrada = "0";
                $this->nuevaEntrada = true;
            } else {
                $this->entrada = substr($this->entrada, 0, strlen($this->entrada) - 1);
            }
        }

        public function borrar()
        {
            $this->pila = array();
            $this->entrada = "0";
            $this->nuevaEntrada = true;
            $this->refrescarPantalla();
        }

        public function raizCuadrada()
        {
            $ultimoOperando = array_shift($this->pila);
            $raiz = sqrt($ultimoOperando);
            array_unshift($this->pila, $raiz);
            $this->refrescarPantalla();
        }

        public function numeroE()
        {
            $this->añadirAEntrada(eval("return exp(1);"));
        }

        public function log()
        {
            $ultimoOperando = array_shift($this->pila);
            $logaritmo = log($ultimoOperando);
            array_unshift($this->pila, $logaritmo);
            $this->refrescarPantalla();
        }

        public function potencial()
        {
            if (count($this->pila) > 1) {
                $exponente = array_shift($this->pila);
                $base = array_shift($this->pila);
                $potencia = pow($base, $exponente);
                array_unshift($this->pila, $potencia);
                $this->refrescarPantalla();
            }
        }

        public function seno()
        {
            $ultimoOperando = array_shift($this->pila);
            $seno = sin($ultimoOperando);
            array_unshift($this->pila, $seno);
            $this->refrescarPantalla();
        }

        public function arcoSeno()
        {
            $ultimoOperando = array_shift($this->pila);
            $arcoSeno = asin($ultimoOperando);
            array_unshift($this->pila, $arcoSeno);
            $this->refrescarPantalla();
        }

        public function coseno()
        {
            $ultimoOperando = array_shift($this->pila);
            $coseno = cos($ultimoOperando);
            array_unshift($this->pila, $coseno);
            $this->refrescarPantalla();
        }

        public function arcoCoseno()
        {
            $ultimoOperando = array_shift($this->pila);
            $arcoCoseno = acos($ultimoOperando);
            array_unshift($this->pila, $arcoCoseno);
            $this->refrescarPantalla();
        }

        public function tangente()
        {
            $ultimoOperando = array_shift($this->pila);
            $tangente = tan($ultimoOperando);
            array_unshift($this->pila, $tangente);
            $this->refrescarPantalla();
        }

        public function arcoTangente()
        {
            $ultimoOperando = array_shift($this->pila);
            $arcoTangente = atan($ultimoOperando);
            array_unshift($this->pila, $arcoTangente);
            $this->refrescarPantalla();
        }

        public function responderPeticion()
        {
            if (isset($_POST["raizCuadrada"])) $this->raizCuadrada();
            if (isset($_POST["numeroE"])) $this->numeroE();
            if (isset($_POST["logaritmo"])) $this->log();
            if (isset($_POST["Potencia"])) $this->potencial();

            if (isset($_POST["Arcoseno"])) $this->arcoSeno();
            if (isset($_POST["Arcocoseno"])) $this->arcoCoseno();
            if (isset($_POST["Arcotangente"])) $this->arcoTangente();
            if (isset($_POST["retroceder"])) $this->retroceder();

            if (isset($_POST["Seno"])) $this->seno();
            if (isset($_POST["Coseno"])) $this->coseno();
            if (isset($_POST["Tangente"])) $this->tangente();
            if (isset($_POST["División"])) $this->division();

            if (isset($_POST["7"])) $this->digitos(7);
            if (isset($_POST["8"])) $this->digitos(8);
            if (isset($_POST["9"])) $this->digitos(9);
            if (isset($_POST["Multiplicación"])) $this->multiplicacion();

            if (isset($_POST["4"])) $this->digitos(4);
            if (isset($_POST["5"])) $this->digitos(5);
            if (isset($_POST["6"])) $this->digitos(6);
            if (isset($_POST["Resta"])) $this->resta();

            if (isset($_POST["1"])) $this->digitos(1);
            if (isset($_POST["2"])) $this->digitos(2);
            if (isset($_POST["3"])) $this->digitos(3);
            if (isset($_POST["Suma"])) $this->suma();

            if (isset($_POST["Borrar"])) $this->borrar();
            if (isset($_POST["0"])) $this->digitos(0);
            if (isset($_POST["puntoDecimal"])) $this->punto();
            if (isset($_POST["Enter"])) $this->enter();
        }
    }

    if (count($_POST) > 0) {
        $_SESSION["calculadoraRPN"]->responderPeticion();
    }

    echo '
    <div class="calculadora">
        <div id="pantalla">
            <div id="entrada">' . $_SESSION["calculadoraRPN"]->getEntrada() . '</div>
            <div id="pila">' . $_SESSION["calculadoraRPN"]->getHtmlPila() . '</div>
        </div>
        <form action="#" method="post" name="botones">
            <div class="botones">
                <input type="submit" name="raizCuadrada" value = "√" class="funcion"/>
                <input type="submit" name="numeroE" value = "e" class="funcion"/>
                <input type="submit" name="logaritmo" value = "log" class="funcion"/>
                <input type="submit" name="Potencia" value = " xⁿ " class="funcion"/>

                <input type="submit" name="Arcoseno" value = "asin" class="funcion"/>
                <input type="submit" name="Arcocoseno" value = " acos " class="funcion"/>
                <input type="submit" name="Arcotangente" value = " atan " class="funcion"/>
                <input type="submit" name="retroceder" value = " DEL " class ="enter"/> 

                <input type="submit" name="Seno" value = " sin " class="funcion"/>
                <input type="submit" name="Coseno" value = " cos " class="funcion"/>
                <input type="submit" name="Tangente" value = " tan " class="funcion"/>
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

                <input type="submit" name="Borrar" value=" C " />
                <input type="submit" name="0" value=" 0 " />
                <input type="submit" name="puntoDecimal" value=" . " />
                <input type="submit" name="Enter" value=" ENTER " class="enter" />
            </div>
        </form>
    </div>
    <footer>
        <a href="http://validator.w3.org/check/referer" hreflang="en-us">
            <img src="http://di002.edv.uniovi.es/~cueva/JavaScript/valid-html5-button.png" alt="¡HTML5 válido!" height="31" width="88" /></a>
        <a href="http://jigsaw.w3.org/css-validator/check/referer">
            <img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="¡CSS Válido!" /></a>
    </footer>
    ' ?>
</body>

</html>
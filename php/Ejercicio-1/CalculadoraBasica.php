<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Ejercicio 1</title>
    <link rel="stylesheet" type="text/css" href="CalculadoraBasica.css" />
</head>

<body>
    <h1>Calculadora básica</h1>
    <?php

    session_name("CalculadoraBasica");

    session_start();

    if (!isset($_SESSION["calculadora"])) {
        $_SESSION["calculadora"] = new CalculadoraBasica();
    }

    class CalculadoraBasica
    {
        private $pantalla;
        private $memoria;
        private $resultado;
        private $resetearPantalla;

        public function __construct()
        {
            $this->pantalla = "";
            $this->memoria = 0;
            $this->resultado = 0;
            $this->resetearPantalla = false;
        }

        public function getPantalla() {
            return $this -> pantalla;
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

        public function responderPeticion() {
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

    if (count($_POST) > 0) {
        $_SESSION["calculadora"] -> responderPeticion();
    }

    echo '
    <div class="calculadora">
        <label for="pantalla">Pantalla de la calculadora:</label>
        <input type="text" value="' . $_SESSION["calculadora"] -> getPantalla() . '" name="display" id="pantalla" readonly />
        <form action="#" method="post" name="botones">
            <div class="botones">
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
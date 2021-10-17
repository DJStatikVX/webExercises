<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Ejercicio 7</title>
    <link rel="stylesheet" type="text/css" href="Ejercicio7.css" />
</head>

<body>
    <section>
        <h1>Ejercicio 7 - Mis Películas Favoritas</h1>
        <p class="hidden">UO271612</p>
        <form action="#" method="post" name="opciones">
            <nav>
                <input type="submit" name="crearBaseDatos" value="Crear Base de Datos" />
                <input type="submit" name="crearTabla" value="Crear tablas" />
                <input type="submit" name="importarDatos" value="Cargar datos" />
            </nav>
        </form>
    </section>
    <div class="contenido">
        <?php

        session_name("Ejercicio-7");
        session_start();

        $_SESSION["baseDatos"] = new BaseDatos();

        class BaseDatos
        {
            private $servidor;
            private $nombre;
            private $usuario;
            private $contrasena;
            private $db;

            public function __construct()
            {
                $this->servidor = "localhost";
                $this->nombre = "Ejercicio7";
                $this->usuario = "DBUSER2020";
                $this->contrasena = "DBPSWD2020";

                // Creación de la conexión
                $this->db = new mysqli($this->servidor, $this->usuario, $this->contrasena);
            }

            public function responderPeticion()
            {
                // Menú de navegación
                if (isset($_POST["crearBaseDatos"])) $this->crearBaseDatos();
                if (isset($_POST["crearTabla"])) $this->crearTabla();
                if (isset($_POST["importarDatos"])) $this->importarDatos();

                // Buscar películas
                if (isset($_POST["enviarBuscarPeliculas"])) $this->buscarPeliculas();

                // Buscar actores
                if (isset($_POST["enviarBuscarActores"])) $this->buscarActores();
            }

            private function crearBaseDatos()
            {
                $sql = "CREATE DATABASE IF NOT EXISTS " . $this->nombre;

                if ($this->db->query($sql) === TRUE) {
                    echo "<p class='colorVerde'>Se ha creado la base de datos (o ya se encuentra creada)</p>";
                } else {
                    echo "<p class='error'>No se ha podido crear la base de datos." . $this->db->error . "</p>";
                }
            }

            private function crearTabla()
            {
                // TABLA PELÍCULAS
                $sql = "CREATE TABLE IF NOT EXISTS Peliculas (
                                    id TINYINT UNSIGNED NOT NULL,
                                    titulo VARCHAR(100) NOT NULL,
                                    argumento VARCHAR(255) NOT NULL,
                                    categoria VARCHAR(40) NOT NULL,
                                    duracion VARCHAR(10) NOT NULL,
                                    fecha VARCHAR(10) NOT NULL,
                                    caratula VARCHAR(255) NOT NULL,
                                    PRIMARY KEY (id)
                                )
                        ";

                // Seleccionamos la base de datos para crear la tabla
                $this->db->select_db($this->nombre);

                if ($this->db->query($sql) === TRUE) {
                    echo "<p class='colorVerde'>Se ha creado la tabla Peliculas (o ya se encuentra creada)</p>";
                } else {
                    echo "<p class='error'>No se ha podido crear la tabla Peliculas. " . $this->db->error . "</p>";
                }

                // TABLA ACTORES
                $sql = "CREATE TABLE IF NOT EXISTS Actores (
                        id TINYINT UNSIGNED NOT NULL,
                        nombre VARCHAR(40) NOT NULL,
                        imagen VARCHAR(255) NOT NULL,
                        imdb VARCHAR(10) NOT NULL,
                        PRIMARY KEY (id)
                    )
                ";

                if ($this->db->query($sql) === TRUE) {
                    echo "<p class='colorVerde'>Se ha creado la tabla Actores (o ya se encuentra creada)</p>";
                } else {
                    echo "<p class='error'>No se ha podido crear la tabla Actores. " . $this->db->error . "</p>";
                }

                // TABLA REPARTO
                $sql = "CREATE TABLE IF NOT EXISTS Reparto (
                        id_actor TINYINT UNSIGNED NOT NULL,
                        id_pelicula TINYINT UNSIGNED NOT NULL,
                        personaje VARCHAR(40) NOT NULL,
                        PRIMARY KEY (id_actor, id_pelicula),
                        FOREIGN KEY (id_actor) REFERENCES Actores(id),
                        FOREIGN KEY (id_pelicula) REFERENCES Peliculas(id) 
                    )
                ";

                if ($this->db->query($sql) === TRUE) {
                    echo "<p class='colorVerde'>Se ha creado la tabla Reparto (o ya se encuentra creada)</p>";
                } else {
                    echo "<p class='error'>No se ha podido crear la tabla Reparto. " . $this->db->error . "</p>";
                }
            }


            private function getNumeroFilas()
            {
                try {

                    if ($this->db->connect_error) {
                        echo "<p class='error>Error de conexión a la base de datos.</p>";
                        return;
                    }

                    $this->db->select_db($this->nombre);

                    // Si todo está bien, se procede a preparar la consulta
                    $resultado = $this->db->query("SELECT * FROM PruebasUsabilidad");

                    // Devolvemos número de filas
                    return $resultado->num_rows;
                } catch (Error $e) {
                    echo "<p class='error'>Se ha producido un error buscando en la base de datos. " . $e->getMessage() . "</p>";
                    return -1;
                }
            }

            private function importarDatos()
            {
                try {

                    $this->db->select_db($this->nombre);

                    // IMPORTAR PELÍCULAS

                    // Abrimos el fichero CSV
                    $fichero = fopen("peliculas.csv", "r");

                    // Leemos cada línea
                    while (!feof($fichero)) {
                        $linea = fgetcsv($fichero, 2000, ";");

                        if ($linea != NULL && $linea != false) {
                            $id = NULL;
                            $titulo = NULL;
                            $argumento = NULL;
                            $categoria = NULL;
                            $duracion = NULL;
                            $fecha = NULL;
                            $caratula = NULL;

                            try {
                                // Guardamos los datos en variables
                                $id = $linea[0];
                                $titulo = $linea[1];
                                $argumento = $linea[2];
                                $categoria = $linea[3];
                                $duracion = $linea[4];
                                $fecha = $linea[5];
                                $caratula = "https://image.tmdb.org/t/p/original/" . $linea[6];
                            } catch (Error $e) {
                                echo "<p class='error'>El formato del archivo CSV no es válido.</p>";
                                return;
                            }

                            // Comprobación de campos
                            if (
                                strlen($id) == 0 || strlen($titulo) == 0 || strlen($argumento) == 0
                                || strlen($categoria) == 0 || strlen($duracion) == 0 || strlen($fecha) == 0
                                || strlen($caratula) == 0
                            ) {

                                echo "<p class='error'>Alguno de los campos del CSV es vacío.</p>";
                                return;
                            }

                            if (strpos($fecha, "/") === false) {
                                echo "<p class='error'>Alguno de los datos de fechas es inválido.</p>";
                                return;
                            }

                            try {

                                if ($this->db->connect_error) {
                                    echo "<p class='error>Error de conexión a la base de datos.</p>";
                                    return;
                                }

                                $this->db->select_db($this->nombre);

                                // Si todo está bien, se procede a preparar la consulta
                                $consulta = $this->db->prepare("INSERT INTO Peliculas (id, titulo, argumento, categoria, duracion, fecha, caratula) VALUES (?, ?, ?, ?, ?, ?, ?)");

                                // Vinculamos parámetros
                                $consulta->bind_param(
                                    'issssss',
                                    $id,
                                    $titulo,
                                    $argumento,
                                    $categoria,
                                    $duracion,
                                    $fecha,
                                    $caratula,
                                );

                                // Ejecutamos la sentencia
                                $consulta->execute();

                                // Y la cerramos
                                $consulta->close();
                            } catch (Error $e) {
                                echo "<p class='error'>Se ha producido un error insertando en la base de datos. " . $e->getMessage() . "</p>";
                                return;
                            }
                        }
                    }

                    // Cerramos el fichero exportado
                    fclose($fichero);

                    // IMPORTAR ACTORES

                    // Abrimos el fichero CSV
                    $fichero = fopen("actores.csv", "r");

                    // Leemos cada línea
                    while (!feof($fichero)) {
                        $linea = fgetcsv($fichero, 1000, ";");

                        if ($linea != NULL && $linea != false) {
                            $id = NULL;
                            $nombre = NULL;
                            $imagen = NULL;
                            $imdb = NULL;

                            try {
                                // Guardamos los datos en variables
                                $id = $linea[0];
                                $nombre = $linea[1];
                                $imagen = "https://image.tmdb.org/t/p/original/" . $linea[2];
                                $imdb = "https://www.imdb.com/name/" . $linea[3];
                            } catch (Error $e) {
                                echo "<p class='error'>El formato del archivo CSV no es válido.</p>";
                                return;
                            }

                            // Comprobación de campos
                            if (
                                strlen($id) == 0 || strlen($nombre) == 0 || strlen($imagen) == 0
                                || strlen($imdb) == 0
                            ) {

                                echo "<p class='error'>Alguno de los campos del CSV es vacío.</p>";
                                return;
                            }

                            try {

                                if ($this->db->connect_error) {
                                    echo "<p class='error>Error de conexión a la base de datos.</p>";
                                    return;
                                }

                                $this->db->select_db($this->nombre);

                                // Si todo está bien, se procede a preparar la consulta
                                $consulta = $this->db->prepare("INSERT INTO Actores (id, nombre, imagen, imdb) VALUES (?, ?, ?, ?)");

                                // Vinculamos parámetros
                                $consulta->bind_param(
                                    'isss',
                                    $id,
                                    $nombre,
                                    $imagen,
                                    $imdb
                                );

                                // Ejecutamos la sentencia
                                $consulta->execute();

                                // Y la cerramos
                                $consulta->close();
                            } catch (Error $e) {
                                echo "<p class='error'>Se ha producido un error insertando en la base de datos. " . $e->getMessage() . "</p>";
                                return;
                            }
                        }
                    }

                    // Cerramos el fichero exportado
                    fclose($fichero);

                    // IMPORTAR REPARTO

                    // Abrimos el fichero CSV
                    $fichero = fopen("reparto.csv", "r");

                    // Leemos cada línea
                    while (!feof($fichero)) {
                        $linea = fgetcsv($fichero, 1000, ";");

                        if ($linea != NULL && $linea != false) {
                            $id_actor = NULL;
                            $id_pelicula = NULL;
                            $personaje = NULL;

                            try {
                                // Guardamos los datos en variables
                                $id_actor = $linea[0];
                                $id_pelicula = $linea[1];
                                $personaje = $linea[2];
                            } catch (Error $e) {
                                echo "<p class='error'>El formato del archivo CSV no es válido.</p>";
                                return;
                            }

                            // Comprobación de campos
                            if (strlen($id_actor) == 0 || strlen($id_pelicula) == 0 || strlen($personaje) == 0) {
                                echo "<p class='error'>Alguno de los campos del CSV es vacío.</p>";
                                return;
                            }

                            try {

                                if ($this->db->connect_error) {
                                    echo "<p class='error>Error de conexión a la base de datos.</p>";
                                    return;
                                }

                                $this->db->select_db($this->nombre);

                                // Si todo está bien, se procede a preparar la consulta
                                $consulta = $this->db->prepare("INSERT INTO Reparto (id_actor, id_pelicula, personaje) VALUES (?, ?, ?)");

                                // Vinculamos parámetros
                                $consulta->bind_param(
                                    'iis',
                                    $id_actor,
                                    $id_pelicula,
                                    $personaje
                                );

                                // Ejecutamos la sentencia
                                $consulta->execute();

                                // Y la cerramos
                                $consulta->close();
                            } catch (Error $e) {
                                echo "<p class='error'>Se ha producido un error insertando en la base de datos. " . $e->getMessage() . "</p>";
                                return;
                            }
                        }
                    }

                    // Cerramos el fichero exportado
                    fclose($fichero);

                    // Datos cargados con éxito
                    echo "<p class='colorVerde'>Los datos han sido cargados con éxito</p>";
                    
                } catch (Error $e) {
                    echo "<p class='error'>Se ha producido un error durante la importación.</p>";
                }
            }

            private function buscarPeliculas() {
                $pelicula = trim($_POST["buscarPeliculas"]);

                // Comprobamos que haya texto en la búsqueda
                if (strlen($pelicula) == 0) {
                    echo "<p class='error'>Debes introducir una película</p>";
                    return;
                }

                // Se procede a buscar en la base de datos
                try {

                    if ($this->db->connect_error) {
                        echo "<p class='error>Error de conexión a la base de datos.</p>";
                        return;
                    }

                    $this->db->select_db($this->nombre);

                    // Si todo está bien, se procede a preparar la consulta
                    $consulta = $this->db->prepare("SELECT * FROM Peliculas WHERE titulo = ?");

                    // Vinculamos el parámetro
                    $consulta->bind_param('s', $pelicula);

                    // Ejecutamos la sentencia
                    $consulta->execute();
                    $resultado = $consulta->get_result();

                    $idPelicula = NULL;

                    // Mostramos los datos
                    if ($resultado->fetch_assoc() > 0) {
                        $resultado->data_seek(0);
                        while ($row = $resultado->fetch_assoc()) {
                            echo '
                                <div class="pelicula">
                                    <img class="caratula" src=' . $row["caratula"] . ' alt="Carátula de ' . $pelicula . '"/>
                                    <section class="tituloArgumentoPelicula">
                                        <h2>' . $row["titulo"] . ' (' . explode("/", $row["fecha"])[2] . ')</h2>
                                        <div class="masInfoPelicula">
                                            <p>' . $row["categoria"] . '</p>
                                            <p>' . $row["duracion"] . '</p>
                                        </div>
                                    </section>
                                </div>
                            ';

                            $idPelicula = $row["id"];
                        }

                        $consulta->close();

                        // Recuperamos la información de su reparto
                        $consulta = $this->db->prepare("SELECT nombre, personaje, imagen FROM Peliculas, Reparto, Actores WHERE Peliculas.id = Reparto.id_pelicula AND Reparto.id_actor = Actores.id AND id_pelicula = ?");

                        // Vinculamos el parámetro
                        $consulta->bind_param('s', $idPelicula);

                        // Ejecutamos la sentencia
                        $consulta->execute();
                        $resultado = $consulta->get_result();

                        // Imprimimos separador
                        echo '<h3 class="separador">Actores</h3>';

                        $resultado->data_seek(0);
                        while ($row = $resultado->fetch_assoc()) {
                            echo '
                                <div class="actor">
                                    <img class="caratula" src=' . $row["imagen"] . ' alt="Imagen de ' . $row["nombre"] . '"/>
                                    <section class="nombrePersonajeActor">
                                        <h3>' . $row["nombre"] . '</h3>
                                        <div class="masInfoPelicula">
                                            <p>' . $row["personaje"] . '</p>
                                        </div>
                                    </section>
                                </div>
                            ';
                        }

                        $consulta->close();

                    } else {
                        echo "<p>No se han encontrado datos para la película " . $pelicula . ".</p>";
                    }

                } catch (Error $e) {
                    echo "<p class='error'>Se ha producido un error accediendo a la base de datos. " . $e->getMessage() . "</p>";
                }
            }

            private function buscarActores() {
                $actor = trim($_POST["buscarActores"]);

                // Comprobamos que haya texto en la búsqueda
                if (strlen($actor) == 0) {
                    echo "<p class='error'>Debes introducir un nombre de actor</p>";
                    return;
                }

                // Se procede a buscar en la base de datos
                try {

                    if ($this->db->connect_error) {
                        echo "<p class='error>Error de conexión a la base de datos.</p>";
                        return;
                    }

                    $this->db->select_db($this->nombre);

                    // Si todo está bien, se procede a preparar la consulta
                    $consulta = $this->db->prepare("SELECT * FROM Actores WHERE nombre = ?");

                    // Vinculamos el parámetro
                    $consulta->bind_param('s', $actor);

                    // Ejecutamos la sentencia
                    $consulta->execute();
                    $resultado = $consulta->get_result();

                    $idActor = NULL;

                    // Mostramos los datos
                    if ($resultado->fetch_assoc() > 0) {
                        $resultado->data_seek(0);
                        while ($row = $resultado->fetch_assoc()) {
                            echo '
                                <div class="actor">
                                    <img class="caratula" src=' . $row["imagen"] . ' alt="Imagen de ' . $actor . '"/>
                                    <section class="nombrePersonajeActor">
                                        <h2>' . $row["nombre"] . '</h2>
                                    </section>
                                </div>
                            ';

                            $idActor = $row["id"];
                        }

                        $consulta->close();

                        // Recuperamos la información de las películas en las que actúa
                        $consulta = $this->db->prepare("SELECT titulo, caratula, categoria, fecha, duracion FROM Peliculas, Reparto, Actores WHERE Peliculas.id = Reparto.id_pelicula AND Reparto.id_actor = Actores.id AND id_actor = ?");

                        // Vinculamos el parámetro
                        $consulta->bind_param('s', $idActor);

                        // Ejecutamos la sentencia
                        $consulta->execute();
                        $resultado = $consulta->get_result();

                        // Imprimimos separador
                        echo '<h3 class="separador">Películas</h3>';

                        $resultado->data_seek(0);
                        while ($row = $resultado->fetch_assoc()) {
                            echo '
                                <div class="pelicula">
                                    <img class="caratula" src=' . $row["caratula"] . ' alt="Carátula de ' . $row["titulo"] . '"/>
                                    <section class="tituloArgumentoPelicula">
                                        <h3>' . $row["titulo"] . ' (' . explode("/", $row["fecha"])[2] . ')</h3>
                                        <div class="masInfoPelicula">
                                            <p>' . $row["categoria"] . '</p>
                                            <p>' . $row["duracion"] . '</p>
                                        </div>
                                    </section>
                                </div>
                            ';
                        }

                        $consulta->close();

                    } else {
                        echo "<p>No se ha encontrado al actor " . $actor . ".</p>";
                    }

                } catch (Error $e) {
                    echo "<p class='error'>Se ha producido un error accediendo a la base de datos. " . $e->getMessage() . "</p>";
                }
            }

        }

        if (count($_POST) > 0) {
            $_SESSION["baseDatos"]->responderPeticion();
        }

        echo 
        '
            <form action="#" method="post" name="buscar">
                <div class="formulario">
                    <div class="campo">
                        <div class="etiquetaCampo">
                            <label for="buscarPeliculas">Buscar Películas: </label>
                            <input type="text" id="buscarPeliculas" name="buscarPeliculas"/>
                        </div>
                        <input type="submit" class="enviar" name="enviarBuscarPeliculas" value="Enviar"/>
                    </div>
                    <div class="campo">
                        <div class="etiquetaCampo">
                            <label for="buscarActores">Buscar Actores: </label>
                            <input type="text" id="buscarActores" name="buscarActores"/>
                        </div>
                        <input type="submit" class="enviar" name="enviarBuscarActores" value="Enviar"/>
                    </div>
                </div>
            </form>
        ';

        ?>
    </div>
    <footer>
        <a href="http://validator.w3.org/check/referer" hreflang="en-us">
            <img src="http://di002.edv.uniovi.es/~cueva/JavaScript/valid-html5-button.png" alt="¡HTML5 válido!" height="31" width="88" /></a>
        <a href="http://jigsaw.w3.org/css-validator/check/referer">
            <img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="¡CSS Válido!" /></a>
    </footer>
</body>

</html>
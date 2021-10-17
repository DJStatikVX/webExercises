<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Ejercicio 6</title>
    <link rel="stylesheet" type="text/css" href="Ejercicio6.css" />
</head>

<body>
    <section>
        <h1>Ejercicio 6</h1>
        <p class="hidden">UO271612</p>
        <form action="#" method="post" name="opciones">
            <nav>
                <input type="submit" name="crearBaseDatos" value="Crear Base de Datos" />
                <input type="submit" name="crearTabla" value="Crear tabla" />
                <input type="submit" name="insertarDatos" value="Insertar datos" />
                <input type="submit" name="buscarDatos" value="Buscar datos" />
                <input type="submit" name="modificarDatos" value="Modificar datos" />
                <input type="submit" name="eliminarDatos" value="Eliminar datos" />
                <input type="submit" name="generarInforme" value="Generar informe" />
                <input type="submit" name="importarDatos" value="Cargar datos" />
                <input type="submit" name="exportarDatos" value="Exportar datos" />
            </nav>
        </form>
    </section>
    <div class="contenido">
        <?php

        session_name("Ejercicio-6");
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
                $this->nombre = "Ejercicio6";
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
                if (isset($_POST["insertarDatos"])) $this->mostrarInsertarDatos();
                if (isset($_POST["buscarDatos"])) $this->mostrarBuscarDatos();
                if (isset($_POST["modificarDatos"])) $this->mostrarModificarDatos();
                if (isset($_POST["eliminarDatos"])) $this->mostrarEliminarDatos();
                if (isset($_POST["generarInforme"])) $this->mostrarGenerarInforme();
                if (isset($_POST["importarDatos"])) $this->importarDatos();
                if (isset($_POST["exportarDatos"])) $this->exportarDatos();

                // Insertar datos
                if (isset($_POST["enviarInsertarDatos"])) $this->insertarDatos();

                // Buscar datos
                if (isset($_POST["enviarBuscarDatos"])) $this->buscarDatos();

                // Modificar datos
                if (isset($_POST["enviarCheckModificarDatos"])) $this->checkModificarDatos();
                if (isset($_POST["enviarModificarDatos"])) $this->modificarDatos();

                // Borrar datos
                if (isset($_POST["enviarEliminarDatos"])) $this->eliminarDatos();
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
                $sql = "CREATE TABLE IF NOT EXISTS PruebasUsabilidad (
                                    dni VARCHAR(9) NOT NULL,
                                    nombre VARCHAR(255) NOT NULL,
                                    apellidos VARCHAR(255) NOT NULL,
                                    email VARCHAR(255) NOT NULL,
                                    telefono BIGINT UNSIGNED NOT NULL,
                                    edad TINYINT UNSIGNED NOT NULL,
                                    sexo CHAR(1) NOT NULL,
                                    nivel TINYINT UNSIGNED NOT NULL,
                                    duracion DECIMAL(7,3) NOT NULL,
                                    correcto VARCHAR(2) NOT NULL,
                                    comentarios VARCHAR(255) NOT NULL,
                                    propuestas VARCHAR(255) NOT NULL,
                                    valoracion TINYINT UNSIGNED NOT NULL,
                                    PRIMARY KEY (dni),
                                    CHECK (sexo = 'M' OR sexo = 'F'),
                                    CHECK (nivel <= 10),
                                    CHECK (correcto = 'Sí' OR correcto = 'No'),
                                    CHECK (valoracion <= 10)
                                )
                        ";

                // Seleccionamos la base de datos para crear la tabla
                $this->db->select_db($this->nombre);

                if ($this->db->query($sql) === TRUE) {
                    echo "<p class='colorVerde'>Se ha creado la tabla PruebasUsabilidad (o ya se encuentra creada)</p>";
                } else {
                    echo "<p class='error'>No se ha podido crear la tabla PruebasUsabilidad. " . $this->db->error . "</p>";
                }
            }

            private function mostrarInsertarDatos()
            {
                // Se muestra el formulario
                echo '
                            <form action="#" method="post" name="formInsertarDatos">
                                <div class="formulario">
                                    <div class="campo">
                                        <label for="campoDNI">DNI: </label>
                                        <input type="text" id="campoDNI" name="campoDNI"/>
                                    </div>
                                    <div class="campo">
                                        <label for="campoNombre">Nombre: </label>
                                        <input type="text" id="campoNombre" name="campoNombre"/>
                                    </div>
                                    <div class="campo">
                                        <label for="campoApellidos">Apellidos: </label>
                                        <input type="text" id="campoApellidos" name="campoApellidos"/>
                                    </div>
                                    <div class="campo">
                                        <label for="campoEmail">E-mail: </label>
                                        <input type="text" id="campoEmail" name="campoEmail"/>
                                    </div>
                                    <div class="campo">
                                        <label for="campoTelefono">Nº Teléfono: </label>
                                        <input type="text" id="campoTelefono" name="campoTelefono"/>
                                    </div>
                                    <div class="campo">
                                        <label for="campoEdad">Edad: </label>
                                        <input type="text" id="campoEdad" name="campoEdad"/>
                                    </div>
                                    <div class="campo">
                                        <label for="campoSexo">Sexo: </label>
                                        <select id="campoSexo" name="campoSexo">
                                            <option value="M">M</option>
                                            <option value="F">F</option>
                                        </select>
                                    </div>
                                    <div class="campo">
                                        <label for="campoExperiencia">Experiencia (1-10): </label>
                                        <input type="text" id="campoExperiencia" name="campoExperiencia"/>
                                    </div>
                                    <div class="campo">
                                        <label for="campoDuracion">Duración (s): </label>
                                        <input type="text" id="campoDuracion" name="campoDuracion"/>
                                    </div>
                                    <div class="campo">
                                        <label for="campoCorrecto">Realizada correctamente: </label>
                                        <select id="campoCorrecto" name="campoCorrecto">
                                            <option value="Sí">Sí</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="campo campoGrande">
                                        <label for="campoComentarios">Comentarios: </label>
                                        <input type="text" id="campoComentarios" name="campoComentarios"/>
                                    </div>
                                    <div class="campo campoGrande">
                                        <label for="campoPropuestas">Propuestas de mejora: </label>
                                        <input type="text" id="campoPropuestas" name="campoPropuestas"/>
                                    </div>
                                    <div class="campo">
                                        <label for="campoValoracion">Valoración (1-10): </label>
                                        <input type="text" id="campoValoracion" name="campoValoracion"/>
                                    </div>
                                    <div class="divEnviar">
                                        <input type="submit" class="enviar" name="enviarInsertarDatos" value="Enviar"/>
                                    </div>
                                </div>
                            </form>
                        ';
            }

            private function insertarDatos()
            {
                // Recuperamos el valor de los campos
                $dni = $_POST["campoDNI"];
                $nombre = $_POST["campoNombre"];
                $apellidos = $_POST["campoApellidos"];
                $email = $_POST["campoEmail"];
                $telefono = $_POST["campoTelefono"];
                $edad = $_POST["campoEdad"];
                $sexo = $_POST["campoSexo"];
                $experiencia = $_POST["campoExperiencia"];
                $duracion = $_POST["campoDuracion"];
                $correcto = $_POST["campoCorrecto"];
                $comentarios = $_POST["campoComentarios"];
                $propuestas = $_POST["campoPropuestas"];
                $valoracion = $_POST["campoValoracion"];

                // Comprobación de campos
                if (
                    strlen($dni) == 0 || strlen($nombre) == 0 || strlen($apellidos) == 0
                    || strlen($email) == 0 || strlen($telefono) == 0 || strlen($edad) == 0
                    || strlen($sexo) == 0 || strlen($experiencia) == 0 || strlen($duracion) == 0
                    || strlen($correcto) == 0 || strlen($valoracion) == 0
                ) {

                    echo "<p class='error'>Debes rellenar todos los campos.</p>";
                    $this->mostrarInsertarDatos();
                    return;
                }

                if (strpos($email, "@") === false || strpos($email, ".") === false) {
                    echo "<p class='error'>Introduce un e-mail válido.</p>";
                    $this->mostrarInsertarDatos();
                    return;
                }

                if (intval($telefono) == 0 || intval($telefono) == 1) {
                    echo "<p class='error'>Introduce un número de teléfono válido.</p>";
                    $this->mostrarInsertarDatos();
                    return;
                }

                if ($edad < 0 || $edad > 127) {
                    echo "<p class='error'>Introduce una edad válida.</p>";
                    $this->mostrarInsertarDatos();
                    return;
                }

                if (strval($sexo) != 'M' && strval($sexo) != 'F') {
                    echo "<p class='error'>Introduce un sexo válido.</p>";
                    $this->mostrarInsertarDatos();
                    return;
                }

                if ($experiencia < 0 || $experiencia > 10) {
                    echo "<p class='error'>Introduce un nivel válido de experiencia.</p>";
                    $this->mostrarInsertarDatos();
                    return;
                }

                if (floatval($duracion) == 0) {
                    echo "<p class='error'>Introduce una duración válida.</p>";
                    $this->mostrarInsertarDatos();
                    return;
                }

                if (strval($correcto) != "Sí" && strval($correcto) != "No") {
                    echo "<p class='error'>Introduce una respuesta válida en el campo \"Realizada correctamente\".</p>";
                    $this->mostrarInsertarDatos();
                    return;
                }

                if ($valoracion < 0 || $valoracion > 10) {
                    echo "<p class='error'>Introduce una valoración válida.</p>";
                    $this->mostrarInsertarDatos();
                    return;
                }

                try {

                    if ($this->db->connect_error) {
                        echo "<p class='error>Error de conexión a la base de datos.</p>";
                        return;
                    }

                    $this->db->select_db($this->nombre);

                    // Si todo está bien, se procede a preparar la consulta
                    $consulta = $this->db->prepare("INSERT INTO PruebasUsabilidad (dni, nombre, apellidos, email, telefono, edad, sexo, nivel, duracion, correcto, comentarios, propuestas, valoracion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    // Vinculamos parámetros
                    $consulta->bind_param(
                        'ssssiisidsssi',
                        $dni,
                        $nombre,
                        $apellidos,
                        $email,
                        $telefono,
                        $edad,
                        $sexo,
                        $experiencia,
                        $duracion,
                        $correcto,
                        $comentarios,
                        $propuestas,
                        $valoracion
                    );

                    // Ejecutamos la sentencia
                    $consulta->execute();

                    if (strlen($consulta->error) != 0) {
                        echo "<p class='error'>" . $consulta->error . "</p>";
                        return;
                    } else {
                        echo "<p class='colorVerde'>Datos insertados con éxito</p>";
                    }

                    // Y la cerramos
                    $consulta->close();
                } catch (Error $e) {
                    echo "<p class='error'>Se ha producido un error insertando en la base de datos. " . $e->getMessage() . "</p>";
                    return;
                }
            }

            private function mostrarBuscarDatos()
            {
                // Se muestra el formulario
                echo '
                                <form action="#" method="post" name="formBuscarDatos">
                                    <div class="formulario">
                                        <div class="campo">
                                            <label for="campoDNIBuscar">DNI a buscar: </label>
                                            <input type="text" id="campoDNIBuscar" name="campoDNIBuscar"/>
                                        </div>
                                        <div class="divEnviar">
                                            <input type="submit" class="enviar" name="enviarBuscarDatos" value="Enviar"/>
                                        </div>
                                    </div>
                                </form>
                            ';
            }

            private function buscarDatos()
            {
                $dni = $_POST["campoDNIBuscar"];

                if (strlen($dni) == 0) {
                    echo "<p class='error'>Debes introducir un DNI a buscar.</p>";
                    $this->mostrarBuscarDatos();
                    return;
                }

                try {

                    if ($this->db->connect_error) {
                        echo "<p class='error>Error de conexión a la base de datos.</p>";
                        return;
                    }

                    $this->db->select_db($this->nombre);

                    // Si todo está bien, se procede a preparar la consulta
                    $consulta = $this->db->prepare("SELECT * FROM PruebasUsabilidad WHERE DNI = ?");

                    // Vinculamos el parámetro
                    $consulta->bind_param('s', $dni);

                    // Ejecutamos la sentencia
                    $consulta->execute();
                    $resultado = $consulta->get_result();

                    // Mostramos los datos
                    if ($resultado->fetch_assoc() > 0) {
                        $resultado->data_seek(0);
                        while ($row = $resultado->fetch_assoc()) {
                            echo '
                                <div class="busqueda">
                                    <p>DNI: ' . $row["dni"] . '</p>
                                    <p>Nombre: ' . $row["nombre"] . '</p>
                                    <p>Apellidos: ' . $row["apellidos"] . '</p>
                                    <p>E-mail: ' . $row["email"] . '</p>
                                    <p>Nº Teléfono: ' . $row["telefono"] . '</p>
                                    <p>Edad: ' . $row["edad"] . '</p>
                                    <p>Sexo: ' . $row["sexo"] . '</p>
                                    <p>Experiencia: ' . $row["nivel"] . '</p>
                                    <p>Duración: ' . $row["duracion"] . '</p>
                                    <p>Realizada correctamente: ' . $row["correcto"] . '</p>
                                    <p>Comentarios: ' . $row["comentarios"] . '</p>
                                    <p>Propuestas de mejora: ' . $row["propuestas"] . '</p>
                                    <p>Valoración: ' . $row["valoracion"] . '</p>
                                </div>
                            ';
                        }

                        $consulta->close();
                    } else {
                        echo "<p>No se han encontrado datos para el DNI " . $dni . ".</p>";
                        $this->mostrarBuscarDatos();
                    }

                } catch (Error $e) {
                    echo "<p class='error'>Se ha producido un error buscando en la base de datos. " . $e->getMessage() . "</p>";
                    return;
                }
            }

            private function mostrarModificarDatos()
            {
                // Se muestra el formulario
                echo '
                                <form action="#" method="post" name="formBusquedaModificarDatos">
                                    <div class="formulario">
                                        <div class="campo">
                                            <label for="campoDNIModificar">DNI de fila a modificar: </label>
                                            <input type="text" id="campoDNIModificar" name="campoDNIModificar"/>
                                        </div>
                                        <div class="divEnviar">
                                            <input type="submit" class="enviar" name="enviarCheckModificarDatos" value="Enviar"/>
                                        </div>
                                    </div>
                                </form>
                            ';
            }

            private function checkModificarDatos()
            {
                $dni = $_POST["campoDNIModificar"];

                if (strlen($dni) == 0) {
                    echo "<p class='error'>Debes introducir un DNI a buscar para su modificación.</p>";
                    $this->mostrarModificarDatos();
                    return;
                }

                try {

                    if ($this->db->connect_error) {
                        echo "<p class='error>Error de conexión a la base de datos.</p>";
                        return;
                    }

                    $this->db->select_db($this->nombre);

                    // Si todo está bien, se procede a preparar la consulta
                    $consulta = $this->db->prepare("SELECT * FROM PruebasUsabilidad WHERE DNI = ?");

                    // Vinculamos el parámetro
                    $consulta->bind_param('s', $dni);

                    // Ejecutamos la sentencia
                    $consulta->execute();
                    $resultado = $consulta->get_result();

                    // Mostramos los datos
                    if ($resultado->fetch_assoc() != NULL) {
                        $resultado->data_seek(0);
                        while ($row = $resultado->fetch_assoc()) {
                            $this->mostrarFormModificarDatos($row["dni"], $row["nombre"], $row["apellidos"], $row["email"], $row["telefono"], $row["edad"], $row["sexo"], $row["nivel"], $row["duracion"], $row["correcto"], $row["comentarios"], $row["propuestas"], $row["valoracion"]);
                            break;
                        }
                    } else {
                        echo "<p>No se ha encontrado ningún registro para el DNI " . $dni . ".</p>";
                        $this->mostrarModificarDatos();
                    }

                    $consulta->close();
                } catch (Error $e) {
                    echo "<p class='error'>Se ha producido un error buscando en la base de datos. " . $e->getMessage() . "</p>";
                    return;
                }
            }

            private function mostrarFormModificarDatos($dni, $nombre, $apellidos, $email, $telefono, $edad, $sexo, $nivel, $duracion, $correcto, $comentarios, $propuestas, $valoracion)
            {
                // Se muestra el formulario
                echo '
                                <form action="#" method="post" name="formModificarDatos">
                                    <div class="formulario">
                                        <div class="campo">
                                            <label for="campoDNIModificar">DNI: </label>
                                            <input type="text" id="campoDNIModificar" name="campoDNIModificar" value=' . $dni . ' readonly/>
                                        </div>
                                        <div class="campo">
                                            <label for="campoNombreModificar">Nombre: </label>
                                            <input type="text" id="campoNombreModificar" name="campoNombreModificar" value=\'' . $nombre . '\'/>
                                        </div>
                                        <div class="campo">
                                            <label for="campoApellidosModificar">Apellidos: </label>
                                            <input type="text" id="campoApellidosModificar" name="campoApellidosModificar" value=\'' . $apellidos . '\'/>
                                        </div>
                                        <div class="campo">
                                            <label for="campoEmailModificar">E-mail: </label>
                                            <input type="text" id="campoEmailModificar" name="campoEmailModificar" value=\'' . $email . '\'/>
                                        </div>
                                        <div class="campo">
                                            <label for="campoTelefonoModificar">Nº Teléfono: </label>
                                            <input type="text" id="campoTelefonoModificar" name="campoTelefonoModificar" value=\'' . $telefono . '\'/>
                                        </div>
                                        <div class="campo">
                                            <label for="campoEdadModificar">Edad: </label>
                                            <input type="text" id="campoEdadModificar" name="campoEdadModificar" value=\'' . $edad . '\'/>
                                        </div>';
                if ($sexo == 'M') {
                    echo '                    
                                        <div class="campo">
                                            <label for="campoSexoModificar">Sexo: </label>
                                            <select id="campoSexoModificar" name="campoSexoModificar">
                                                <option selected="selected" value="M">M</option>
                                                <option value="F">F</option>
                                            </select>
                                        </div>
                    ';
                } else {
                    echo '                    
                                        <div class="campo">
                                            <label for="campoSexoModificar">Sexo: </label>
                                            <select id="campoSexoModificar" name="campoSexoModificar">
                                                <option value="M">M</option>
                                                <option selected="selected" value="F">F</option>
                                            </select>
                                        </div>
                    ';
                }
                echo '
                                        <div class="campo">
                                            <label for="campoExperienciaModificar">Experiencia (1-10): </label>
                                            <input type="text" id="campoExperienciaModificar" name="campoExperienciaModificar" value=\'' . $nivel . '\'/>
                                        </div>
                                        <div class="campo">
                                            <label for="campoDuracionModificar">Duración (s): </label>
                                            <input type="text" id="campoDuracionModificar" name="campoDuracionModificar" value=\'' . $duracion . '\'/>
                                        </div>
                ';
                if ($correcto == "Sí") {
                    echo '
                                        <div class="campo">
                                            <label for="campoCorrectoModificar">Realizada correctamente: </label>
                                            <select id="campoCorrectoModificar" name="campoCorrectoModificar">
                                                <option selected="selected" value="Sí">Sí</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                    ';
                } else {
                    echo '
                                        <div class="campo">
                                            <label for="campoCorrectoModificar">Realizada correctamente: </label>
                                            <select id="campoCorrectoModificar" name="campoCorrectoModificar">
                                                <option value="Sí">Sí</option>
                                                <option selected="selected" value="No">No</option>
                                            </select>
                                        </div>
                    ';
                }
                echo '
                                        <div class="campo campoGrande">
                                            <label for="campoComentariosModificar">Comentarios: </label>
                                            <input type="text" id="campoComentariosModificar" name="campoComentariosModificar" value=\'' . $comentarios . '\'/>
                                        </div>
                                        <div class="campo campoGrande">
                                            <label for="campoPropuestasModificar">Propuestas de mejora: </label>
                                            <input type="text" id="campoPropuestasModificar" name="campoPropuestasModificar" value=\'' . $propuestas . '\'/>
                                        </div>
                                        <div class="campo">
                                            <label for="campoValoracionModificar">Valoración (1-10): </label>
                                            <input type="text" id="campoValoracionModificar" name="campoValoracionModificar" value=\'' . $valoracion . '\'/>
                                        </div>
                                        <div class="divEnviar">
                                            <input type="submit" class="enviar" name="enviarModificarDatos" value="Enviar"/>
                                        </div>
                                    </div>
                                </form>
                ';
            }

            private function modificarDatos()
            {
                // Recuperamos el valor de los campos
                $dni = $_POST["campoDNIModificar"];
                $nombre = $_POST["campoNombreModificar"];
                $apellidos = $_POST["campoApellidosModificar"];
                $email = $_POST["campoEmailModificar"];
                $telefono = $_POST["campoTelefonoModificar"];
                $edad = $_POST["campoEdadModificar"];
                $sexo = $_POST["campoSexoModificar"];
                $experiencia = $_POST["campoExperienciaModificar"];
                $duracion = $_POST["campoDuracionModificar"];
                $correcto = $_POST["campoCorrectoModificar"];
                $comentarios = $_POST["campoComentariosModificar"];
                $propuestas = $_POST["campoPropuestasModificar"];
                $valoracion = $_POST["campoValoracionModificar"];

                // Comprobación de campos
                if (
                    strlen($dni) == 0 || strlen($nombre) == 0 || strlen($apellidos) == 0
                    || strlen($email) == 0 || strlen($telefono) == 0 || strlen($edad) == 0
                    || strlen($sexo) == 0 || strlen($experiencia) == 0 || strlen($duracion) == 0
                    || strlen($correcto) == 0 || strlen($valoracion) == 0
                ) {

                    echo "<p class='error'>Debes rellenar todos los campos.</p>";
                    $this->mostrarFormModificarDatos($dni, $nombre, $apellidos, $email, $telefono, $edad, $sexo, $experiencia, $duracion, $correcto, $comentarios, $propuestas, $valoracion);
                    return;
                }

                if (strpos($email, "@") === false || strpos($email, ".") === false) {
                    echo "<p class='error'>Introduce un e-mail válido.</p>";
                    $this->mostrarFormModificarDatos($dni, $nombre, $apellidos, $email, $telefono, $edad, $sexo, $experiencia, $duracion, $correcto, $comentarios, $propuestas, $valoracion);
                    return;
                }

                if (intval($telefono) == 0 || intval($telefono) == 1) {
                    echo "<p class='error'>Introduce un número de teléfono válido.</p>";
                    $this->mostrarFormModificarDatos($dni, $nombre, $apellidos, $email, $telefono, $edad, $sexo, $experiencia, $duracion, $correcto, $comentarios, $propuestas, $valoracion);
                    return;
                }

                if ($edad < 0 || $edad > 127) {
                    echo "<p class='error'>Introduce una edad válida.</p>";
                    $this->mostrarFormModificarDatos($dni, $nombre, $apellidos, $email, $telefono, $edad, $sexo, $experiencia, $duracion, $correcto, $comentarios, $propuestas, $valoracion);
                    return;
                }

                if (strval($sexo) != 'M' && strval($sexo) != 'F') {
                    echo "<p class='error'>Introduce un sexo válido.</p>";
                    $this->mostrarFormModificarDatos($dni, $nombre, $apellidos, $email, $telefono, $edad, $sexo, $experiencia, $duracion, $correcto, $comentarios, $propuestas, $valoracion);
                    return;
                }

                if ($experiencia < 0 || $experiencia > 10) {
                    echo "<p class='error'>Introduce un nivel válido de experiencia.</p>";
                    $this->mostrarFormModificarDatos($dni, $nombre, $apellidos, $email, $telefono, $edad, $sexo, $experiencia, $duracion, $correcto, $comentarios, $propuestas, $valoracion);
                    return;
                }

                if (floatval($duracion) == 0) {
                    echo "<p class='error'>Introduce una duración válida.</p>";
                    $this->mostrarFormModificarDatos($dni, $nombre, $apellidos, $email, $telefono, $edad, $sexo, $experiencia, $duracion, $correcto, $comentarios, $propuestas, $valoracion);
                    return;
                }

                if (strval($correcto) != "Sí" && strval($correcto) != "No") {
                    echo "<p class='error'>Introduce una respuesta válida en el campo \"Realizada correctamente\".</p>";
                    $this->mostrarFormModificarDatos($dni, $nombre, $apellidos, $email, $telefono, $edad, $sexo, $experiencia, $duracion, $correcto, $comentarios, $propuestas, $valoracion);
                    return;
                }

                if ($valoracion < 0 || $valoracion > 10) {
                    echo "<p class='error'>Introduce una valoración válida.</p>";
                    $this->mostrarFormModificarDatos($dni, $nombre, $apellidos, $email, $telefono, $edad, $sexo, $experiencia, $duracion, $correcto, $comentarios, $propuestas, $valoracion);
                    return;
                }

                try {

                    if ($this->db->connect_error) {
                        echo "<p class='error>Error de conexión a la base de datos.</p>";
                        return;
                    }

                    $this->db->select_db($this->nombre);

                    // Si todo está bien, se procede a preparar la consulta
                    $consulta = $this->db->prepare("UPDATE PruebasUsabilidad SET nombre = ?, apellidos = ?, email = ?, telefono = ?, edad = ?, sexo = ?, nivel = ?, duracion = ?, correcto = ?, comentarios = ?, propuestas = ?, valoracion = ? WHERE dni = ?");

                    // Vinculamos parámetros
                    $consulta->bind_param(
                        'sssiisidsssis',
                        $nombre,
                        $apellidos,
                        $email,
                        $telefono,
                        $edad,
                        $sexo,
                        $experiencia,
                        $duracion,
                        $correcto,
                        $comentarios,
                        $propuestas,
                        $valoracion,
                        $dni
                    );

                    // Ejecutamos la sentencia
                    $consulta->execute();

                    if (strlen($consulta->error) != 0) {
                        echo "<p class='error'>" . $consulta->error . "</p>";
                        return;
                    } else {
                        echo "<p class='colorVerde'>Registro actualizado con éxito</p>";
                    }

                    // Y la cerramos
                    $consulta->close();
                } catch (Error $e) {
                    echo "<p class='error'>Se ha producido un error actualizando la base de datos. " . $e->getMessage() . "</p>";
                    return;
                }
            }

            private function mostrarEliminarDatos()
            {
                // Se muestra el formulario
                echo '
                                <form action="#" method="post" name="formEliminarDatos">
                                    <div class="formulario">
                                        <div class="campo">
                                            <label for="campoDNIEliminar">DNI a eliminar: </label>
                                            <input type="text" id="campoDNIEliminar" name="campoDNIEliminar"/>
                                        </div>
                                        <div class="divEnviar">
                                            <input type="submit" class="enviar" name="enviarEliminarDatos" value="Enviar"/>
                                        </div>
                                    </div>
                                </form>
                            ';
            }

            private function eliminarDatos()
            {
                $dni = $_POST["campoDNIEliminar"];

                if (strlen($dni) == 0) {
                    echo "<p class='error'>Debes introducir un DNI a eliminar.</p>";
                    $this->mostrarEliminarDatos();
                    return;
                }

                try {

                    if ($this->db->connect_error) {
                        echo "<p class='error>Error de conexión a la base de datos.</p>";
                        return;
                    }

                    $this->db->select_db($this->nombre);

                    // Si todo está bien, se procede a preparar la consulta
                    $consulta = $this->db->prepare("SELECT * FROM PruebasUsabilidad WHERE DNI = ?");

                    // Vinculamos el parámetro
                    $consulta->bind_param('s', $dni);

                    // Ejecutamos la sentencia
                    $consulta->execute();
                    $resultado = $consulta->get_result();

                    // Mostramos los datos
                    if ($resultado->fetch_assoc() != NULL) {
                        $consultaBorrar = $this->db->prepare("DELETE FROM PruebasUsabilidad WHERE DNI = ?");
                        $consultaBorrar->bind_param('s', $dni);
                        $consultaBorrar->execute();
                        $consultaBorrar->close();

                        echo "<p class='colorVerde'>Los datos de la fila han sido borrados</p>";
                    } else {
                        echo "<p>No se han encontrado datos a borrar para el DNI " . $dni . ".</p>";
                        $this->mostrarEliminarDatos();
                    }

                    // Y la cerramos
                    $consulta->close();
                } catch (Error $e) {
                    echo "<p class='error'>Se ha producido un error borrando en la base de datos. " . $e->getMessage() . "</p>";
                    return;
                }
            }

            private function mostrarGenerarInforme()
            {
                $elementos = $this->getNumeroFilas();

                if ($elementos == 0) {
                    echo "<p>No hay elementos en la base de datos.</p>";
                } elseif ($elementos != -1) {
                    try {
                        $edadPromedio = intval($this->getPromedio("edad"));
                        $numHombres = $this->getNumHombres();
                        $numMujeres = $this->getNumMujeres();
                        $porcentajeHombres = ($numHombres * 1.0 / $elementos) * 100;
                        $porcentajeMujeres = ($numMujeres * 1.0 / $elementos) * 100;
                        $nivelPromedio = round($this->getPromedio("nivel"), 2);
                        $tiempoMedio = round($this->getPromedio("duracion"), 2);
                        $porcentajeCorrecto = ($this->getNumCorrectos() / $elementos) * 100;
                        $valoracionMedia = round($this->getPromedio("valoracion"));

                        echo '
                        <div class="busqueda">
                            <p>Edad media de los usuarios: ' . $edadPromedio . '</p>
                            <p>Porcentaje de hombres: ' . $porcentajeHombres . '%</p>
                            <p>Porcentaje de mujeres: ' . $porcentajeMujeres . '%</p>
                            <p>Pericia o nivel promedio: ' . $nivelPromedio . '</p>
                            <p>Tiempo medio de realización: ' . $tiempoMedio . ' segundos</p>
                            <p>Porcentaje realizado correctamente: ' . $porcentajeCorrecto . '%</p>
                            <p>Puntuación media de los usuarios: ' . $valoracionMedia . '</p>
                        </div>
                        ';
                    } catch (Error $e) {
                        echo "<p class='error'>Se ha producido un error buscando en la base de datos. " . $e->getMessage() . "</p>";
                        return;
                    }
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

            private function getPromedio($param)
            {
                $this->db->select_db($this->nombre);

                // Si todo está bien, se procede a preparar la consulta
                $resultado = $this->db->query("SELECT AVG(" . $param . ") AS MEDIA FROM PruebasUsabilidad");

                $promedio = -1;

                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        $promedio = $row["MEDIA"];
                    }
                }

                return $promedio;
            }

            private function getNumHombres()
            {
                $this->db->select_db($this->nombre);

                // Si todo está bien, se procede a preparar la consulta
                $resultado = $this->db->query("SELECT COUNT(*) AS TOTAL FROM PruebasUsabilidad WHERE sexo = 'M'");

                $total = -1;

                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        $total = $row["TOTAL"];
                    }
                }

                return $total;
            }

            private function getNumMujeres()
            {
                $this->db->select_db($this->nombre);

                // Si todo está bien, se procede a preparar la consulta
                $resultado = $this->db->query("SELECT COUNT(*) AS TOTAL FROM PruebasUsabilidad WHERE sexo = 'F'");

                $total = -1;

                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        $total = $row["TOTAL"];
                    }
                }

                return $total;
            }

            private function getNumCorrectos()
            {
                $this->db->select_db($this->nombre);

                // Si todo está bien, se procede a preparar la consulta
                $resultado = $this->db->query("SELECT COUNT(*) AS TOTAL FROM PruebasUsabilidad WHERE correcto = 'Sí'");

                $total = -1;

                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        $total = $row["TOTAL"];
                    }
                }

                return $total;
            }

            private function importarDatos()
            {
                try {

                    // Abrimos el fichero CSV
                    $fichero = fopen("pruebasUsabilidad.csv", "r");

                    $this->db->select_db($this->nombre);

                    // Leemos cada línea
                    while (!feof($fichero)) {
                        $linea = fgetcsv($fichero, 1000, ";");

                        if ($linea != NULL && $linea != false) {
                            $dni = NULL;
                            $nombre = NULL;
                            $apellidos = NULL;
                            $email = NULL;
                            $telefono = NULL;
                            $edad = NULL;
                            $sexo = NULL;
                            $experiencia = NULL;
                            $duracion = NULL;
                            $correcto = NULL;
                            $comentarios = NULL;
                            $propuestas = NULL;
                            $valoracion = NULL;

                            try {
                                // Guardamos los datos en variables
                                $dni = $linea[0];
                                $nombre = $linea[1];
                                $apellidos = $linea[2];
                                $email = $linea[3];
                                $telefono = $linea[4];
                                $edad = $linea[5];
                                $sexo = $linea[6];
                                $experiencia = $linea[7];
                                $duracion = $linea[8];
                                $correcto = $linea[9];
                                $comentarios = $linea[10];
                                $propuestas = $linea[11];
                                $valoracion = $linea[12];
                            } catch (Error $e) {
                                echo "<p class='error'>El formato del archivo CSV no es válido.</p>";
                                return;
                            }

                            // Comprobación de campos
                            if (
                                strlen($dni) == 0 || strlen($nombre) == 0 || strlen($apellidos) == 0
                                || strlen($email) == 0 || strlen($telefono) == 0 || strlen($edad) == 0
                                || strlen($sexo) == 0 || strlen($experiencia) == 0 || strlen($duracion) == 0
                                || strlen($correcto) == 0 || strlen($valoracion) == 0
                            ) {

                                echo "<p class='error'>Alguno de los campos del CSV es vacío.</p>";
                                $this->mostrarInsertarDatos();
                                return;
                            }

                            if (strpos($email, "@") === false || strpos($email, ".") === false) {
                                echo "<p class='error'>Introduce un e-mail válido.</p>";
                                $this->mostrarInsertarDatos();
                                return;
                            }

                            if (intval($telefono) == 0 || intval($telefono) == 1) {
                                echo "<p class='error'>Introduce un número de teléfono válido.</p>";
                                $this->mostrarInsertarDatos();
                                return;
                            }

                            if ($edad < 0 || $edad > 127) {
                                echo "<p class='error'>Introduce una edad válida.</p>";
                                $this->mostrarInsertarDatos();
                                return;
                            }

                            if (strval($sexo) != 'M' && strval($sexo) != 'F') {
                                echo "<p class='error'>Introduce un sexo válido.</p>";
                                $this->mostrarInsertarDatos();
                                return;
                            }

                            if ($experiencia < 0 || $experiencia > 10) {
                                echo "<p class='error'>Introduce un nivel válido de experiencia.</p>";
                                $this->mostrarInsertarDatos();
                                return;
                            }

                            if (floatval($duracion) == 0) {
                                echo "<p class='error'>Introduce una duración válida.</p>";
                                $this->mostrarInsertarDatos();
                                return;
                            }

                            if (strval($correcto) != "Sí" && strval($correcto) != "No") {
                                echo "<p class='error'>Introduce una respuesta válida en el campo \"Realizada correctamente\".</p>";
                                $this->mostrarInsertarDatos();
                                return;
                            }

                            if ($valoracion < 0 || $valoracion > 10) {
                                echo "<p class='error'>Introduce una valoración válida.</p>";
                                $this->mostrarInsertarDatos();
                                return;
                            }

                            try {

                                if ($this->db->connect_error) {
                                    echo "<p class='error>Error de conexión a la base de datos.</p>";
                                    return;
                                }

                                $this->db->select_db($this->nombre);

                                // Si todo está bien, se procede a preparar la consulta
                                $consulta = $this->db->prepare("INSERT INTO PruebasUsabilidad (dni, nombre, apellidos, email, telefono, edad, sexo, nivel, duracion, correcto, comentarios, propuestas, valoracion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                                // Vinculamos parámetros
                                $consulta->bind_param(
                                    'ssssiisidsssi',
                                    $dni,
                                    $nombre,
                                    $apellidos,
                                    $email,
                                    $telefono,
                                    $edad,
                                    $sexo,
                                    $experiencia,
                                    $duracion,
                                    $correcto,
                                    $comentarios,
                                    $propuestas,
                                    $valoracion
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

                    echo "<p class='colorVerde'>Los datos han sido cargados con éxito</p>";
                } catch (Error $e) {
                    echo "<p class='error'>Se ha producido un error durante la importación.</p>";
                }
            }

            private function exportarDatos()
            {
                try {
                    $this->db->select_db($this->nombre);

                    // Si todo está bien, se procede a preparar la consulta
                    $resultado = $this->db->query("SELECT * FROM PruebasUsabilidad");

                    // Fichero a exportar
                    $fichero = fopen("pruebasUsabilidad.csv", "w");

                    if ($resultado->num_rows > 0) {
                        while ($row = $resultado->fetch_assoc()) {
                            fputcsv($fichero, $row, ";");
                        }
                    }

                    // Cerramos el fichero exportado
                    fclose($fichero);

                    echo "<p class='colorVerde'>Los datos han sido exportados con éxito</p>";
                } catch (Error $e) {
                    echo "<p class='error'>Se ha producido un error durante la exportación.</p>";
                }
            }
        }

        if (count($_POST) > 0) {
            $_SESSION["baseDatos"]->responderPeticion();
        }

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
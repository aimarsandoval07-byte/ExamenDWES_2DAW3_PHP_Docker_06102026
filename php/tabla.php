<html>
    <head>
        <title>Examen de Desarrollo web en entorno servidor</title>
        <link rel="icon" type="image/png" sizes="32x32" href="../imagenes/favicon.jpeg">
        <link rel="stylesheet" type="text/css" href="../estilos/estilos.css">
    </head>
    <body>
        <h1>Centro de Ayuda al Empleo</h1>
        <h2>    
            <?php
            $tipoParam = $_REQUEST['profesion'] ?? $_REQUEST['tipo']; //este trozo de codigo se encarga de recoger el valor del parametro profesion o tipo del formulario y si no se ha recibido ningun valor se asigna una cadena vacia
                $tipo = "Soldadura";
                if ($tipoParam == 'informatica'){
                    $tipo = 'Informática';
                } elseif ($tipoParam == 'socio'){
                    $tipo = "Asistencia Sociosanitaria";
                }
                echo "Solicitudes de $tipo";
            ?>
        </h2>

        <?php 
        require ("conexion.php");

        if (isset($_REQUEST['nombre'])) { //aqui se hace la comprobacion de si se han recibido los datos del formulario y sino se muestra un mensaje de error
            $nombre    = $_REQUEST['nombre'];
            $apellidos = $_REQUEST['apellidos'];
            $dni       = $_REQUEST['dni'];
            $f_nac     = $_REQUEST['f_nac'];
            $tlf       = $_REQUEST['tlf'];
            $email     = $_REQUEST['email'];
            $profesion = $_REQUEST['profesion'];
            
            $jornadaCompleta = $_REQUEST['jornada'] ?? $_REQUEST['jornadaParcial'] ?? ''; //este trozo de codigo se encarga de recoger jornadaParcial o jornada del formulario y si no se ha recibido ningun valor se asigna una cadena vacia
            $jornadaParcial = ($jornadaCompleta == 'parcial' || $jornadaCompleta == '1') ? 1 : 0;//y este de aqui se encarga de asignar 1 si es jornada parcial y 0 si es jornada completa

            $idiomasArr = array(); //este trozo de codigo se encarga de recoger los idiomas seleccionados en el formulario y si no se ha recibido ningun valor se asigna una cadena vacia
            if (isset($_REQUEST['euskera'])) {
                $idiomasArr[] = $_REQUEST['euskera'];
            }
            if (isset($_REQUEST['ingles'])) {
                $idiomasArr[] = $_REQUEST['ingles'];
            }
            $idiomas = implode(", ", $idiomasArr); // aqui meto la funcion implode para que los idiomas se muestren separados por comas en la base de datos y si no se ha recibido ningun valor se asigna una cadena vacia

            mysqli_query($conexion, "INSERT INTO SOLICITUD(nombre, apellidos, dni, f_nac, tlf, email, profesion, jornadaParcial, idiomas)
            VALUES ('$nombre', '$apellidos', '$dni', '$f_nac', '$tlf', '$email', '$profesion', $jornadaParcial, '$idiomas')")
            or die("Problemas en el insert:" . mysqli_error($conexion)); //esto de aqui se encarga de hacer la insert de los datos en la base de datos y si no se ha podido hacer la insert se muestra un mensaje de error
           
            $id = mysqli_insert_id($conexion);//guarda el id de la solicitud insertada en la base de datos para mostrarlo en el mensaje de confirmacion
            echo "Se ha insertado correctamente la solicitud número $id"; //esto de aqui se encarga de mostrar un mensaje de que se ha insertado correctamente la solicitud y el id de la solicitud 
        }
        mysqli_close($conexion);  //aqui cerramos la conexion con la base de datos para que no se quede abierta y se pueda seguir usando la base de datos     
        ?>
        
        <table>  <!--Este trozo de codigo es el que se encarga de mostrar la tabla con los datos en funcion de la profesion que se ha buscado-->
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>DNI</th>
                <th>Fecha de nacimiento</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Profesión</th>
                <th>Jornada parcial</th>
                <th>Idiomas</th>
            </tr>

            <?php
            require("conexion.php"); //aqui se hace la conexion con la base de datos para poder mostrar los datos en la tabla
            $registros = mysqli_query($conexion, "SELECT * FROM SOLICITUD WHERE profesion = '$tipoParam'") //aqui se hace la consulta a la base de datos para mostrar los datos en funcion de la profesion que se ha buscado y sino se muestra un mensaje de error
            or die("Problemas en la select:" . mysqli_error($conexion));
            
            while ($reg = mysqli_fetch_array($registros)) { //aqui se hace un bucle para mostrar los datos en la tabla y sino se muestra un mensaje de error
                echo "<tr>";
                echo "<td>" . $reg['id'] . "</td>";
                echo "<td>" . $reg['nombre'] . "</td>";
                echo "<td>" . $reg['apellidos'] . "</td>";
                echo "<td>" . $reg['dni'] . "</td>";
                echo "<td>" . $reg['f_nac'] . "</td>";
                echo "<td>" . $reg['tlf'] . "</td>"; 
                echo "<td>" . $reg['email'] . "</td>";
                echo "<td>" . $reg['profesion'] . "</td>";
                echo "<td>" . ($reg['jornadaParcial'] == 1 ? "Sí" : "No") . "</td>"; //esto lo que hace es mostrar "Sí" si la jornada es parcial y "No" si es completa en la tabla
                echo "<td>" . $reg['idiomas'] . "</td>";
                echo "</tr>";
            }
            mysqli_close($conexion); //aqui se cerraria la conexion con la base de datos
            ?>
        </table>

        <br>
        <button onclick="location.href='../html/index.html'">Volver al formulario</button>
    </body>
</html>
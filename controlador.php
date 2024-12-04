<?php
session_start(); // Iniciamos una sesion para almacenar ahi los mensajes de errores y mostrarlos en diferentes páginas
require('conexion.php');
require('helpers.php');

$db = new Conexion();
$conexion = $db->getConexion();

// Recibir lo enviado en el formulario

$nombre = trim($_REQUEST['nombre']);
$apellido = trim($_REQUEST['apellido']);
$correo = trim($_REQUEST['correo']);
$fechaNac = $_REQUEST['fechaNac'];
$ciudad = $_REQUEST['ciudad'];
$genero = $_REQUEST['genero'];
$lenguajes = $_REQUEST['lenguaje'] ?? [];


// Llamamos a una función para validar los datos, la cual nos retornará un array con todos los erores que
// tuvo el usuario al enviar el formulario
$errores = validar($_REQUEST, true);

// die();

if (!empty($errores)) {
    // Almacenamos el array de errore en la sesión para mostrarlo en la página anterior.
    $_SESSION['errores'] = implode("✖", $errores);
    $_SESSION['usuario'] = $_REQUEST;
    header("Location: index.php");
    exit();
}

try {

    // Comenzamos una transacción para insertar datos en la base de datos
    $conexion->beginTransaction();

    // Insertar en la tabla de usuarios
    
    $sqlA = "INSERT INTO usuarios(nombres, apellidos, correo, fecha_nacimiento, id_ciudad, id_genero ) VALUES(:nombre,:apellido, :correo, :fechaNac, :ciudad, :genero)";
    
    $stm = $conexion->prepare($sqlA);
    
    // Bindear los datos
    
    $stm -> bindParam(':nombre', $nombre);
    $stm -> bindParam(':apellido', $apellido);
    $stm -> bindParam(':correo', $correo);
    $stm -> bindParam(':fechaNac', $fechaNac);
    $stm -> bindParam(':ciudad', $ciudad);
    $stm -> bindParam(':genero', $genero);
    
    $stm->execute();
    
    // Extraer id del usuario
    $lastID = $conexion->lastInsertId();
    
    // Insertar en la tabla lenguaje_usuario
    
    $sqlB = "INSERT INTO lenguaje_usuario(id_usuario, id_lenguaje) VALUES(:id_usuario, :id_lenguaje)";
    $stm = $conexion->prepare($sqlB);
    
    foreach ($lenguajes as $key => $value) {
    
        $stm -> bindParam('id_usuario', $lastID);
        $stm -> bindParam('id_lenguaje', $value);
        $stm->execute();
        
    }

    // Confirmamos transacción
    $conexion->commit();
     
    header("Location: read.php");
} catch (Exception $e) {

    $conexion->rollBack();

    $_SESSION['mensaje'] = "Ha ocurrido un error: " . $e->getMessage();
    header("Location: index.php");
}

?>
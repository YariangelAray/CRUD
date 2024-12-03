<?php
session_start(); // Iniciamos una sesion para almacenar ahi los mensajes de errores y mostrarlos en diferentes páginas
require('conexion.php');
$db = new Conexion();
$conexion = $db->getConexion();

// Recibir lo enviado en el formulario

$nombre = $_REQUEST['nombre'];
$apellido = $_REQUEST['apellido'];
$correo = $_REQUEST['correo'];
$fechaNac = $_REQUEST['fechaNac'];
$ciudad = $_REQUEST['ciudad'];
$genero = $_REQUEST['genero'];
$lenguajes = $_REQUEST['lenguaje'] ?? [];

// Expresión regular para validar el correo electrónico
$regexCorreo = "/^[a-zA-Z0-9\._+-]+@[a-zA-Z\.-]+\.[a-zA-Z]{2,}$/";
// Expresión regular para validar la fecha
$regexFecha = "/^[\d]{4}-[\d]{2}-[\d]{2}$/";

try {
    // Validación de correo
    if (!preg_match($regexCorreo, $correo)) {
        // Almacenamos un mensaje de error en la sesión para mostrarlo en la página anterior
        $_SESSION['mensaje'] = "El correo no cumple con lo solicitado. Debe contener un @ y al menos un dominio.";
        header("Location: index.php");
        exit();// Aseguramos que el script se detenga después de redirigir
    }

    // Validación de fecha
    if (!preg_match($regexFecha, $fechaNac)) {        
        $_SESSION['mensaje'] = "La fecha no cumple con el formato solicitado. (DD/MM/YYYY)";
        header("Location: index.php");
        exit();
    }

     // Validación de lenguajes
     if (empty($lenguajes)) {
        $_SESSION['mensaje'] = "Debe seleccionar al menos un lenguaje de programación.";
        header("Location: index.php");
        exit();
    }

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
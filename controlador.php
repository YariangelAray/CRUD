<?php

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

try {
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

    $conexion->commit();
     
    header("Location: read.php");
} catch (Exception $e) {

    $conexion->rollBack();

    echo "Ha ocurrido un error. ---->".$e->getMessage();
}

?>
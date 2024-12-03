<?php
session_start();
require('conexion.php');
$db = new Conexion();
$conexion = $db->getConexion();

// Recibimos los datos que hayan sido enviados

$idUser = $_REQUEST['id_usuario'];
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
// Expresión para válidar el nombre y el apellido
$regexText = "/([a-zA-Z]+\s*[a-zA-Z]*){3,}/";


try {

    // Validación de correo
    if (!preg_match($regexCorreo, $correo)) {
        // Almacenamos un mensaje de error en la sesión para mostrarlo en la página anterior
        $_SESSION['mensaje'] = "El correo modificado no cumple con lo solicitado. Debe contener un @ y al menos un dominio.";
        header("Location: editar.php?id=$idUser"); 
        exit(); // salimos de la sesión
    }

    // Validación de fecha
    if (!preg_match($regexFecha, $fechaNac)) {        
        $_SESSION['mensaje'] = "La fecha modificada no cumple con el formato solicitado. (DD/MM/YYYY)";
        header("Location: editar.php?id=$idUser");
        exit();
    }

    // Validación de lenguajes
    if (empty($lenguajes)) {
        $_SESSION['mensaje'] = "Debe tener seleccionado al menos un lenguaje de programación.";
        header("Location: editar.php?id=$idUser");
        exit();
    }

    // Validación de nombres y apellidos
    if (empty($nombre) || empty($apellido)) {
        $_SESSION['mensaje'] = "Los campos de nombre o apellido no pueden estar vacíos.";
        header("Location: editar.php?id=$idUser");
        exit();
    }

    // Validación de nombres y apellidos con expresiones regulares
    if (!preg_match($regexText, $nombre) || !preg_match($regexText, $nombre)) {
        $_SESSION['mensaje'] = "No se pueden ingresar números. Deben ser más de 3 carácteres.";
        header("Location: editar.php?id=$idUser");
        exit();
    }

    // Validación de generos
    if (empty($genero)) {
        $_SESSION['mensaje'] = "Debe seleccionar su género.";
        header("Location: editar.php?id=$idUser");
        exit();
    }

    $conexion->beginTransaction();
    // Actualizar el registro en la tabla usuarios
    
    $sqlUsuario = "UPDATE usuarios SET nombres=:nombre, apellidos= :apellido, correo= :correo, fecha_nacimiento= :fechaNac, id_ciudad= :ciudad, id_genero= :genero WHERE id_usuario = :id_usuario";    
    
    $stm = $conexion->prepare($sqlUsuario);
    
    // Bindear los datos
    $stm -> bindParam(':id_usuario', $idUser);
    $stm -> bindParam(':nombre', $nombre);
    $stm -> bindParam(':apellido', $apellido);
    $stm -> bindParam(':correo', $correo);
    $stm -> bindParam(':fechaNac', $fechaNac);
    $stm -> bindParam(':ciudad', $ciudad);
    $stm -> bindParam(':genero', $genero);
    
    $stm->execute();
    
    
    // Actualizar el registro en la tabla lenguaje_usuario
    
    // Primero se elimina el registro
    
    $sqlELim = "DELETE FROM lenguaje_usuario WHERE id_usuario = :id_usuario";
    $stm = $conexion->prepare($sqlELim);
    $stm -> bindParam(':id_usuario', $idUser);
    $stm->execute();
    
    // Luego los volvemos a registrar
    
    $sqlLeng = "INSERT INTO lenguaje_usuario(id_usuario, id_lenguaje) VALUES(:id_usuario, :id_lenguaje)";
    $stm = $conexion->prepare($sqlLeng);
    
    foreach ($lenguajes as $key => $value) {
    
        $stm -> bindParam('id_usuario', $idUser);
        $stm -> bindParam('id_lenguaje', $value);
        $stm->execute();
        
    }

    $conexion->commit();
    
    header("Location: read.php");
} catch (Exception $e) {

    // En caso de que se generen errores todo vuelve a su estado original

    $conexion->rollBack();
    $_SESSION['mensaje'] = "Ha ocurrido un error: " . $e->getMessage();
    header("Location: editar.php?id=$idUser");

}



?>
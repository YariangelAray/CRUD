<?php
session_start();
require('conexion.php');
require('helpers.php');
$db = new Conexion();
$conexion = $db->getConexion();

// Recibimos los datos que hayan sido enviados

$idUser = $_REQUEST['id_usuario'];
$nombre = trim($_REQUEST['nombre']);
$apellido = trim($_REQUEST['apellido']);
$correo = trim($_REQUEST['correo']);
$fechaNac = $_REQUEST['fechaNac'];
$ciudad = $_REQUEST['ciudad'];
$genero = $_REQUEST['genero'];
$lenguajes = $_REQUEST['lenguaje'] ?? [];

// Llamamos a una función para validar los datos, la cual nos retornará un array con todos los erores que
// tuvo el usuario al enviar el formulario
$errores = validar($_REQUEST, false); // El falso es para que no se haga la validación del correo existente en la base de datos.


if (!empty($errores)) {
    // Almacenamos el array de errore en la sesión para mostrarlo en la página anterior.
    $_SESSION['errores'] = implode("✖", $errores);
    header("Location: editar.php?id=$idUser");
    exit();
}

try {


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
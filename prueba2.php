<?php

// require('conexion.php');
// $db = new Conexion();
// $conexion = $db->getConexion();

// $idUser = $_REQUEST['id_usuario'];
// $nombre = $_REQUEST['nombre'];
// $apellido = $_REQUEST['apellido'];
// $correo = $_REQUEST['correo'];
// $fechaNac = $_REQUEST['fechaNac'];
// $ciudad = $_REQUEST['ciudad'];
// $genero = $_REQUEST['genero'];
// $lenguajes = $_REQUEST['lenguaje'] ?? [];


// // Actualizar el registro en la tabla usuarios

// $sqlUsuario = "UPDATE usuarios SET nombres=:nombre, apellidos= :apellido, correo= :correo, fecha_nacimiento= :fechaNac, id_ciudad= :ciudad, id_genero= :genero WHERE id_usuario = $idUser";
// // $sqlUsuario = "INSERT INTO usuarios(nombres, apellidos, correo, fecha_nacimiento, id_ciudad, id_genero ) VALUES(:nombre,:apellido, :correo, :fechaNac, :ciudad, :genero)";

// $stm = $conexion->prepare($sqlUsuario);

// // Bindear los datos

// $stm -> bindParam(':nombre', $nombre);
// $stm -> bindParam(':apellido', $apellido);
// $stm -> bindParam(':correo', $correo);
// $stm -> bindParam(':fechaNac', $fechaNac);
// $stm -> bindParam(':ciudad', $ciudad);
// $stm -> bindParam(':genero', $genero);

// $stm->execute();


// // Actualizar el registro en la tabla lenguaje_usuario

// // Primero se elimina el registro

// $sqlELim = "DELETE FROM lenguaje_usuario WHERE id_usuario = $idUser";
// $stm = $conexion->prepare($sqlELim);
// $stm->execute();

// // Luego los volvemos a registrar

// $sqlLeng = "INSERT INTO lenguaje_usuario(id_usuario, id_lenguaje) VALUES(:id_usuario, :id_lenguaje)";
// $stm = $conexion->prepare($sqlLeng);

// foreach ($lenguajes as $key => $value) {

//     $stm -> bindParam('id_usuario', $idUser);
//     $stm -> bindParam('id_lenguaje', $value);
//     $stm->execute();
    
// }

// echo "Actualización hecha con éxito.";

?>
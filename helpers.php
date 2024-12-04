<?php
require_once('conexion.php');

function validar($usuario, $controlador){
    $db = new Conexion();
    $conexion = $db->getConexion();

    $errores = [];
    $lenguajes = $usuario['lenguaje'] ?? [];

    // Buscamos si hay un registro del correo existente
    $sqlCorreos = "SELECT correo FROM usuarios WHERE correo = :correo";
    $stm = $conexion->prepare($sqlCorreos);
    $stm -> bindParam(":correo", $usuario['correo']);
    $stm->execute();
    $correos = $stm->fetch();

    // Expresión regular para validar el correo electrónico
    $regexCorreo = "/^[a-zA-Z0-9\._+-]+@[a-zA-Z\.-]+\.[a-zA-Z]{2,}$/";
    // Expresión regular para validar la fecha
    $regexFecha = "/^[\d]{4}-[\d]{2}-[\d]{2}$/";
    // Expresión para válidar el nombre y el apellido
    $regexText = "/([a-zA-Z]+\s*[a-zA-Z]*){3,}/";
    

    // Validar que el correo no exista
    if (!empty($correos) && $controlador) {
        $errores[] = "El correo que intenta agregar ya existe en nuestra base de datos.";
    }

    // Validación de correo con expresión regular
    if (!preg_match($regexCorreo, $usuario['correo'])) {
        $errores[] = "El correo no cumple con lo solicitado. Debe contener un @ y al menos un dominio.";
    }

    // Validación de fecha
    if (!preg_match($regexFecha, $usuario['fechaNac'])) {        
        $errores[] = "La fecha no cumple con el formato solicitado. (DD/MM/YYYY)";
    }

    // Validación de lenguajes
    if (empty($lenguajes)) {
        $errores[] = "Debe tener seleccionado al menos un lenguaje de programación.";
    }

    // Validación de generos
    if (empty($usuario['genero'])) {
        $errores[] = "Debe seleccionar su género.";
    }

    // Validación de nombres y apellidos
    if (empty($usuario['nombre']) || empty($usuario['apellido'])) {
        $errores[] = "Los campos de nombre o apellido no pueden estar vacíos.";
    }

    // Validación de nombres y apellidos con expresiones regulares
    if (!preg_match($regexText, $usuario['nombre']) || !preg_match($regexText, $usuario['apellido'])) {
        $errores[] = "No se pueden ingresar números en los campos nombre y apellido. Deben ser más de 3 carácteres.";
    }

    return $errores;
}

function sacarIdLenguajes($lenguajes) {

    $lenguajesID = [];

    foreach ($lenguajes as $key => $value) {
        // print_r($value['id_lenguaje']);
        $lenguajesID[] = $value['id_lenguaje'];
    }

    return $lenguajesID;
}


?>
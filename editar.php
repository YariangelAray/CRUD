<?php

require('conexion.php');

$db = new Conexion();
$conexion = $db->getConexion();

$sqlCiudades = "SELECT * FROM ciudades";
$banderaCiudades = $conexion->prepare($sqlCiudades);
$banderaCiudades->execute();
$ciudades = $banderaCiudades->fetchAll();

$sqlGeneros = "SELECT * FROM generos";
$banderaGeneros = $conexion->prepare($sqlGeneros);
$banderaGeneros->execute();
$generos = $banderaGeneros->fetchAll();


$sqlLenguajes = "SELECT * FROM lenguajes";
$banderaLenguajes = $conexion->prepare($sqlLenguajes);
$banderaLenguajes->execute();
$lenguajes = $banderaLenguajes->fetchAll();

// Recibir el id del usuario

$idUsuario = $_GET['id'];

// Obtener la información del usuario

$sqlUsuario = "SELECT * FROM usuarios WHERE id_usuario=:id_usuario";
$stm = $conexion->prepare($sqlUsuario);
$stm -> bindParam(':id_usuario', $idUsuario);
$stm->execute();
$usuario = $stm->fetchAll();
$usuario = $usuario[0];

// Obtener los lenguajes que seleccionó el usuario

$sqlUsuarioLeng = "SELECT * FROM lenguaje_usuario WHERE id_usuario=:id_usuario";
$stm = $conexion->prepare($sqlUsuarioLeng);
$stm -> bindParam(':id_usuario', $idUsuario);
$stm->execute();
$usuarioLeng = $stm->fetchAll();

// Guardar solo los id en un arreglo
$lengUsuario = [];
foreach ($usuarioLeng as $key => $value) {
    // print_r($value['id_lenguaje']);
    $lengUsuario[] = $value['id_lenguaje'];
}

// echo "<pre>";
// print_r($lengUsuario);
// echo "</pre>";

// var_dump(in_array(6, $lengUsuario));

?>

<form action="update.php" method="post">

    <fieldset>

        <legend><h2>DATOS USUARIO: <?=$idUsuario?> </h2></legend>

        <input type="hidden" name="id_usuario" value="<?=$idUsuario?>">

        <label for="nombre"> Nombre:
            <input type="text" id="nombre" name="nombre" placeholder="Nombre" value="<?=$usuario['nombres']?>">
        </label>
        <br><br>

        <label for="apellido"> Apellido:
            <input type="text" id="apellido" name="apellido" placeholder="Apellido" value="<?=$usuario['apellidos']?>">
        </label>
        <br><br>

        <label for="correo"> Correo electrónico:
            <input type="text" id="correo" name="correo" placeholder="Correo" value="<?=$usuario['correo']?>">
        </label>
        <br><br>

        <label for="fechaNac"> Fecha nacimiento:
            <input type="date" name="fechaNac" id="fechaNac" value="<?=$usuario['fecha_nacimiento']?>">
        </label>
        <br><br>

        <div>
            <label for="id_ciudad">Ciudad: </label>

            <select name="ciudad" id="id_ciudad">
                <?php foreach ($ciudades as $key => $value)
            {?>
                <option id="<?=$value['id_ciudad']?>" value="<?=$value['id_ciudad']?>"
                <?php if ($value['id_ciudad'] == $usuario['id_ciudad']){ ?>
                selected
                <?php } ?>
                >
                    <?=$value['ciudad']?>
                </option>
                <?php
            }?>
            </select>

        </div>

        <br>

        <div>
            <label>Genero: </label><br>
            <?php
            foreach ($generos as $key => $value){
            ?>
            <label for="gen_<?=$value['id_genero']?>">            
                <input id="gen_<?=$value['id_genero']?>" type="radio" name="genero" value="<?=$value['id_genero']?>"
                <?php if ($value['id_genero'] == $usuario['id_genero']){ ?>
                checked
                <?php } ?>
                >
                <?=$value['genero']?>
            </label>
            <br>
            <?php
            }
            ?>
        </div>

        <br>

        <div>
            <label>Lenguajes de Programación: </label><br>
            <?php
            foreach ($lenguajes as $key => $value){
                ?>
            <label for="len_<?=$value['id_lenguaje']?>">            
                <input id="len_<?=$value['id_lenguaje']?>" type="checkbox" name="lenguaje[]" value="<?=$value['id_lenguaje']?>"
                <?php if (in_array( $value['id_lenguaje'], $lengUsuario)) { ?>
                checked
                <?php } ?>
                >
                <?=$value['lenguaje']?>
            </label>
            <br>
            <?php
            }
            ?>
        </div>

        <br>        
        <input type="submit" value="Enviar">
    
    </fieldset>

</form>
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

// Guardar solo los id en un arreglo para tener un mejor acceso
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

<head>
    <link rel="stylesheet" href="css/style.css">
</head>

<form action="update.php" method="post">

    <fieldset>

        <legend><h2>DATOS USUARIO: <?=$idUsuario?> </h2></legend>

        <input type="hidden" name="id_usuario" value="<?=$idUsuario?>">

        <label for="nombre" class="titulo"> Nombre:
            <div class="input-validar">
                <input type="text" class="ingresar" id="nombre" name="nombre" placeholder="Nombre" value="<?=$usuario['nombres']?>"
                required autocomplete="off" pattern="([a-zA-Z]+\s*[a-zA-Z]*)+" title="No se pueden ingresar números">
                <span class="validado"></span>
            </div>
        </label>

        <label for="apellido" class="titulo"> Apellido:
            <div class="input-validar">
                <input type="text" class="ingresar" id="apellido" name="apellido" placeholder="Apellido" value="<?=$usuario['apellidos']?>"
                required autocomplete="off" pattern="([a-zA-Z]+\s*[a-zA-Z]*)+" title="No se pueden ingresar números">
                <span class="validado"></span>
            </div>
        </label>

        <label for="correo" class="titulo"> Correo electrónico:
            <div class="input-validar">
                <input type="text" class="ingresar" id="correo" name="correo" placeholder="Correo" value="<?=$usuario['correo']?>"
                required autocomplete="off" pattern="^[a-zA-Z0-9._+-]+@[a-zA-Z.-]+\.[a-zA-Z]{2,}$">
                <span class="validado"></span>
            </div>
        </label>

        <label for="fechaNac" class="titulo"> Fecha nacimiento:
            <div class="input-validar">
                <input type="date" class="ingresar" name="fechaNac" id="fechaNac" value="<?=$usuario['fecha_nacimiento']?>" required>
                <span class="validado"></span>
            </div>
        </label>

        <div>
            <label for="id_ciudad" class="titulo">Ciudad: </label>

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

        <div class="genero-lenguajes">
            <div>
                <label class="titulo">Lenguajes de Programación: </label><br>
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

            <div>
                <label class="titulo">Genero: </label><br>
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
        </div>
        <div class="botones">
            <button class="boton">Actualizar</button>
            <a href="delete.php?id=<?=$idUsuario?>" class="boton boton--link">Eliminar</a>
        </div>
    
    </fieldset>

</form>
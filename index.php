<?php

require('conexion.php');

$db = new Conexion();
$conexion = $db->getConexion();

$sqlCiudades = "SELECT * FROM ciudades";
$banderaCiudades = $conexion->prepare($sqlCiudades);
$banderaCiudades->execute();
$ciudades = $banderaCiudades->fetchAll();

// echo "<pre>";
// print_r($ciudades);
// echo "</pre>";

$sqlGeneros = "SELECT * FROM generos";
$banderaGeneros = $conexion->prepare($sqlGeneros);
$banderaGeneros->execute();
$generos = $banderaGeneros->fetchAll();

// echo "<pre>";
// print_r($generos);
// echo "</pre>";

$sqlLenguajes = "SELECT * FROM lenguajes";
$banderaLenguajes = $conexion->prepare($sqlLenguajes);
$banderaLenguajes->execute();
$lenguajes = $banderaLenguajes->fetchAll();

// echo "<pre>";
// print_r($lenguajes);
// echo "</pre>";

?>

<form action="controlador.php" method="post">

    <fieldset>

        <legend><h2>FORMULARIO</h2></legend>

        <label for="nombre"> Nombre:
            <input type="text" id="nombre" name="nombre" placeholder="Nombre">
        </label>
        <br><br>

        <label for="apellido"> Apellido:
            <input type="text" id="apellido" name="apellido" placeholder="Apellido">
        </label>
        <br><br>

        <label for="correo"> Correo electrónico:
            <input type="text" id="correo" name="correo" placeholder="Correo">
        </label>
        <br><br>

        <label for="fechaNac"> Fecha nacimiento:
            <input type="date" name="fechaNac" id="fechaNac">
        </label>
        <br><br>

        <div>
            <label for="id_ciudad">Ciudad: </label>
            <select name="ciudad" id="id_ciudad">
                <?php foreach ($ciudades as $key => $value)
            {?>
                <option id="<?=$value['id_ciudad']?>" value="<?=$value['id_ciudad']?>">
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
                <input id="gen_<?=$value['id_genero']?>" type="radio" name="genero" value="<?=$value['id_genero']?>">
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
                <input id="len_<?=$value['id_lenguaje']?>" type="checkbox" name="lenguaje[]" value="<?=$value['id_lenguaje']?>">
                <?=$value['lenguaje']?>
            </label>
            <br>
            <?php
            }
            ?>
        </div>

        <br>        
        <input type="submit" value="Enviar">
        <a href="read.php">Vista</a>
    
    </fieldset>

</form>
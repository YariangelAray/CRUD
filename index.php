<?php
session_start();
require_once('conexion.php');

$db = new Conexion();
$conexion = $db->getConexion();

// Sacar de la base de datos las ciudades disponibles

$sqlCiudades = "SELECT * FROM ciudades";
$banderaCiudades = $conexion->prepare($sqlCiudades);
$banderaCiudades->execute();
$ciudades = $banderaCiudades->fetchAll();

// Sacar de la bases de datos todos los generos

$sqlGeneros = "SELECT * FROM generos";
$banderaGeneros = $conexion->prepare($sqlGeneros);
$banderaGeneros->execute();
$generos = $banderaGeneros->fetchAll();

// Sacar de la base de datos todos los lenguajes disponibles

$sqlLenguajes = "SELECT * FROM lenguajes";
$banderaLenguajes = $conexion->prepare($sqlLenguajes);
$banderaLenguajes->execute();
$lenguajes = $banderaLenguajes->fetchAll();

// Mostramos los errores enviados desde la otra sesión
if (isset($_SESSION['errores'])) {
    echo "<script>alert('". $_SESSION['errores'] ."');</script>";
    unset($_SESSION['errores']); // Eliminamos el mensaje después de mostrarlo
}

// Datos del usuario
if (isset($_SESSION['usuario'])){
    $usuario = $_SESSION['usuario'];
    unset($_SESSION['usuario']);
}
?>

<head>
    <link rel="stylesheet" href="css/style.css">
</head>

<form action="controlador.php" method="post">

    <fieldset>

        <legend><h2>FORMULARIO</h2></legend>

        <label for="nombre" class="titulo"> Nombre:
            <div class="input-validar">
                <input type="text" class="ingresar" id="nombre" name="nombre" placeholder="Nombre" 
                required autocomplete="off" pattern="([a-zA-Z]+\s*[a-zA-Z]*){3,}" title="No se pueden ingresar números. Deben ser más de 3 carácteres."
                <?= !empty($usuario) ? 'value="'.$usuario['nombre'].'"' : ""?>>
                <span class="validado"></span>
            </div>
        </label>

        <label for="apellido" class="titulo"> Apellido:
            <div class="input-validar">
                <input type="text" class="ingresar" id="apellido" name="apellido" placeholder="Apellido" 
                required autocomplete="off" pattern="([a-zA-Z]+\s*[a-zA-Z]*){3,}" title="No se pueden ingresar números. Deben ser más de 3 carácteres."
                <?= !empty($usuario) ? 'value="'.$usuario['apellido'].'"' : ""?>>
                <span class="validado"></span>            
            </div>
        </label>

        <label for="correo" class="titulo"> Correo electrónico:
            <div class="input-validar">
                <input type="email" class="ingresar" id="correo" name="correo" placeholder="Correo" 
                required autocomplete="off" pattern="^[a-zA-Z0-9\._+-]+@[a-zA-Z\.-]+\.[a-zA-Z]{2,}$" title="Debe incluir un @ y un dominio."
                <?= !empty($usuario) ? 'value="'.$usuario['correo'].'"' : ""?>>
                <!-- https://regex101.com/ -->
                <span class="validado"></span>
            </div>
        </label>

        <label for="fechaNac" class="titulo"> Fecha nacimiento:
            <div class="input-validar">
                <input type="date" class="ingresar" name="fechaNac" id="fechaNac" required title="Formato: dd/mm/yyyy" max="<?=date('Y')?>-<?=date('m')?>-<?=date('d')?>"
                <?= !empty($usuario) ? 'value="'.$usuario['fechaNac'].'"' : ""?>>
                <span class="validado"></span>
            </div>
        </label>

        <div>
            <label for="id_ciudad" class="titulo">Ciudad: </label>
            <select name="ciudad" id="id_ciudad">
                <?php foreach ($ciudades as $key => $value)
            {?>
                <option id="<?=$value['id_ciudad']?>" value="<?=$value['id_ciudad']?>"
                <?= !empty($usuario) ? ($usuario['ciudad'] == $value['id_ciudad'] ? "selected" : "") : ""?>>
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
                    <?= !empty($usuario['lenguaje']) ? (in_array($value['id_lenguaje'], $usuario['lenguaje']) ? "checked" : "") : "" ?>>
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
                    <input id="gen_<?=$value['id_genero']?>" type="radio" name="genero" value="<?=$value['id_genero']?>" required
                    <?= !empty($usuario) ? ($usuario['genero'] == $value['id_genero'] ? "checked" : "") : ""?>>
                    <?=$value['genero']?>
                </label>
                <br>
                <?php
                }
                ?>
            </div>
    
        </div>
        <div class="botones">
            <button class="boton">Enviar</button>
            <a href="read.php" class="boton boton--link">Ver usuarios</a>
        </div>
    
    </fieldset>

</form>
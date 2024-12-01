<?php

// require('conexion.php');
// $db = new Conexion();
// $conexion = $db->getConexion();

// $sqlUsuario = "SELECT u.id_usuario, u.nombres, u.apellidos, u.correo, u.fecha_nacimiento, g.genero, c.ciudad FROM usuarios u INNER JOIN generos g ON u.id_genero = g.id_genero INNER JOIN ciudades c ON u.id_ciudad = c.id_ciudad ORDER BY u.id_usuario ASC";
// $stm = $conexion->prepare($sqlUsuario);
// $stm->execute();
// $usuarios = $stm->fetchAll();

// // echo "<pre>";
// // print_r($usuarios);
// // echo "</pre>";


?>

<!-- <table border="1">
    <thead>
        <th>ID</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Correo</th>
        <th>Fecha nacimiento</th>
        <th>Genero</th>
        <th>Ciudad</th>
        <th>Editar</th>
        <th>ELiminar</th>
    </thead>
    <tbody>
    <?php
    foreach ($usuarios as $key => $value) {
    ?>
    <tr>
        <td><?=$value['id_usuario']?></td>
        <td><?=$value['nombres']?></td>
        <td><?=$value['apellidos']?></td>
        <td><?=$value['correo']?></td>
        <td><?=$value['fecha_nacimiento']?></td>
        <td><?=$value['genero']?></td>
        <td><?=$value['ciudad']?></td>
        <td><a href="vistaEditar.php?id=<?=$value['id_usuario']?>"> Editar </a></td>
        <td><a href="vistaEliminar.php?id=<?=$value['id_usuario']?>"> Eliminar </a></td>
    </tr>
        <?php
    }
    ?>
    </tbody>
</table> -->
<?php

session_start();
include("conexion.php");
$idUsuario = $_SESSION['idUsuario'];
$tipo = $_GET["op"];

if($tipo=="ActualizarContra"){

    $nuevaContra = $_POST["contraNueva"];

    $registrar = "UPDATE Usuario SET contrasena = '$nuevaContra' where id = $idUsuario";
    $resultado = mysqli_query($conectar, $registrar);

    if($resultado){
        echo 1;
    }
    else{
        echo 2;
    }

}

// if($tipo=="RegistrarAnuncio"){

//     $titulo = $_POST["titulo"];
//     $detalle = $_POST["detalle"];
//     $fechaLimite = $_POST["fechaLimite"];

//     $registrar = "INSERT INTO anuncios VALUES(null,'$titulo','$detalle',sysdate(),'$fechaLimite','Habilitado')";
//     $resultado = mysqli_query($conectar, $registrar);

//     if($resultado){
//         echo 1;
//     }
//     else{
//         echo 2;
//     }

// }

// if($tipo=="EditarAnuncio"){

//     $id = $_POST["id"];
//     $titulo = $_POST["titulo"];
//     $detalle = $_POST["detalle"];
//     $fechaLimite = $_POST["fechaLimite"];

//     $registrar = "UPDATE anuncios SET titulo ='$titulo', detalle = '$detalle', fechaLimite= '$fechaLimite' where id = $id";
//     $resultado = mysqli_query($conectar, $registrar);

//     if($resultado){
//         echo 1;
//     }
//     else{
//         echo 2;
//     }

// }


// if($tipo=="QuitarAnuncio"){

//     $id = $_POST["id"];
   

//     $registrar = "UPDATE anuncios SET estado = 'Deshabilitado' where id = $id";
//     $resultado = mysqli_query($conectar, $registrar);

//     if($resultado){
//         echo 1;
//     }
//     else{
//         echo 2;
//     }

// }


// if($tipo=="DatosAnuncio"){

//     $id = $_POST["id"];

//     $datos = array();
//     $Consultar = "SELECT * FROM anuncios  where id = $id";
//     $resultado = mysqli_query($conectar, $Consultar);
//     $datos = mysqli_fetch_assoc($resultado);

//     if($resultado){
//         echo json_encode($datos,JSON_UNESCAPED_UNICODE);
//     }
//     else{
//         echo 'error';
//     } 

// }
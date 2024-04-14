<?php

session_start();
include("conexion.php");

$tipo = $_GET["op"];


if($tipo == "ListarAnuncios"){

$consultar = "SELECT * from anuncios";
$resultado1 = mysqli_query($conectar, $consultar);

    $tabla = "";
    $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titulo</th>
                        <th>Detalle</th>  
                        <th width="70px;">Publicado</th>
                        <th width="70px;">Limite</th>
                        <th>Acción</th>   
                    </tr>
                </thead>
                <tbody > ';
 
    $cont = 0;
    if ($resultado1) {
        while ($listado = mysqli_fetch_array($resultado1)) {
            $cont++;
            $tabla .= "<tr>";
            $tabla .= "<td data-title=''>" .  $cont . "</td>";
            $tabla .= "<td data-title=''>" . $listado['titulo'] . "</td>";
            $tabla .= "<td data-title=''>" . $listado['detalle'] . "</td>";
            $tabla .= "<td data-title=''>" . $listado['fechaPublicacion'] . "</td>";
            $tabla .= "<td data-title=''>" . $listado['fechaLimite'] . "</td>";  
            if($listado['estado'] == 'Habilitado'){
                $tabla .= "<td data-title=''><button type='button' class='btn btn-danger btn-sm' onclick='ConfirmarQuitarAnuncio(" . $listado['id'] . ")'>Deshabilitar</button></td>";
            } 
            else{
              
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm' onclick='ModalEditarAnuncio(" . $listado['id'] . ")'>Habilitar</button></td>";
            }
           
            $tabla .= "</tr>";
        }
    }

    $tabla .= "</tbody>
            
            </table>";
    echo  $tabla;   
}

if($tipo=="RegistrarAnuncio"){

    $titulo = $_POST["titulo"];
    $detalle = $_POST["detalle"];
    $fechaLimite = $_POST["fechaLimite"];

    $registrar = "INSERT INTO anuncios VALUES(null,'$titulo','$detalle',sysdate(),'$fechaLimite','Habilitado')";
    $resultado = mysqli_query($conectar, $registrar);

    if($resultado){
        echo 1;
    }
    else{
        echo 2;
    }

}

if($tipo=="EditarAnuncio"){

    $id = $_POST["id"];
    $titulo = $_POST["titulo"];
    $detalle = $_POST["detalle"];
    $fechaLimite = $_POST["fechaLimite"];

    $registrar = "UPDATE anuncios SET titulo ='$titulo', detalle = '$detalle', estado = 'Habilitado', fechaLimite= '$fechaLimite' where id = $id";
    $resultado = mysqli_query($conectar, $registrar);

    if($resultado){
        echo 1;
    }
    else{
        echo 2;
    }

}

if($tipo=="HabilitarAnuncio"){

    $id = $_POST["id"];
    $fechaLimite = $_POST["fechaLimite"];
   

    $registrar = "UPDATE anuncios SET estado = 'Habilitado', fechaLimite = '$fechaLimite' where id = $id";
    $resultado = mysqli_query($conectar, $registrar);

    if($resultado){
        echo 1;
    }
    else{
        echo 2;
    }

}

if($tipo=="QuitarAnuncio"){

    $id = $_POST["id"];
   

    $registrar = "UPDATE anuncios SET estado = 'Deshabilitado' where id = $id";
    $resultado = mysqli_query($conectar, $registrar);

    if($resultado){
        echo 1;
    }
    else{
        echo 2;
    }

}


if($tipo=="DatosAnuncio"){

    $id = $_POST["id"];

    $datos = array();
    $Consultar = "SELECT * FROM anuncios  where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    } 

}
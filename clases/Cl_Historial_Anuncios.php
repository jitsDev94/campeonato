<?php

session_start();
require_once '../conexion/parametros.php';
$parametro = new parametros();

$tipo = $_GET["op"];


if($tipo == "ListarAnuncios"){
  
    $resultado = $parametro->ListarAnuncios(); 

    $tabla = "";
   
    $cont = 0;
    if ($resultado->RowCount() > 0) {
        $resultado->MoveFirst();
        while (!$resultado->EndOfSeek()) {  
           
            $listado = $resultado->Row();
            $cont++;
            $tabla .= "<tr>";
            $tabla .= "<td data-title=''>" .  $cont . "</td>";
            $tabla .= "<td data-title=''>" . $listado->titulo . "</td>";
            $tabla .= "<td data-title=''>" . $listado->detalle . "</td>";
            $tabla .= "<td data-title=''>" . $listado->fechaPublicacion . "</td>";
            $tabla .= "<td data-title=''>" . $listado->fechaLimite . "</td>";  
            $tabla .= "<td data-title=''>";  
            if($listado->estado == 'Habilitado'){
                if ($parametro->verificarPermisos($_SESSION['idUsuario'],27) > 0) { 
                $tabla .= "<button type='button' class='btn btn-danger btn-sm' onclick='ConfirmarQuitarAnuncio(" . $listado->id . ")'>Deshabilitar</button>";
                }
            } 
            else{     
                if ($parametro->verificarPermisos($_SESSION['idUsuario'],26) > 0) {          
                    $tabla .= "<button type='button' class='btn btn-primary btn-sm' onclick='ModalEditarAnuncio(".chr(34). $listado->id .chr(34).",".chr(34). $listado->titulo .chr(34)."," .chr(34). $listado->detalle .chr(34).",".chr(34). $listado->fechaLimite .chr(34).")'>Habilitar</button>";
                }
            }
           
            $tabla .= "</td></tr>";
        }
    }

    echo  $tabla;   
}

if($tipo=="RegistrarAnuncio"){

    $titulo = $_POST["titulo"];
    $detalle = $_POST["detalle"];
    $fechaLimite = $_POST["fechaLimite"];


    $resultado = $parametro->RegistrarAnuncio($titulo,$detalle,$fechaLimite); 

    echo $resultado;
   

}

if($tipo=="EditarAnuncio"){

    $id = $_POST["id"];
    $titulo = $_POST["titulo"];
    $detalle = $_POST["detalle"];
    $fechaLimite = $_POST["fechaLimite"];

    $resultado = $parametro->EditarAnuncio($id,$titulo,$detalle,$fechaLimite); 

    echo $resultado;

}

if($tipo=="DeshabilitarAnunciosAntiguos"){

    $resultado = $parametro->DeshabilitarAnunciosAntiguos(); 

    echo $resultado;

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
   
    $resultado = $parametro->QuitarAnuncio($id); 

    echo $resultado;

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
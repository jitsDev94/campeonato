<?php

session_start();
require_once '../conexion/parametros.php';
$parametro = new parametros();

$tipo = $_GET["op"];

if($tipo == "CrearPrecio"){

    $precio = $_POST["precio"];
    $motivo = $_POST["motivo"];

    $resultado = $parametro->CrearPrecio($motivo,$precio); 

   echo $resultado;
}

if($tipo == "EstadoPrecio"){

    $estado = $_POST["estado"];
    $id = $_POST["id"];

    $resultado = $parametro->EstadoPrecio($id,$estado); 

   echo $resultado;
}


if($tipo == "ActualizarPrecio"){

    $precio = $_POST["precio"];
    $id = $_POST["id"];

    $resultado = $parametro->ActualizarPrecio($id,$precio); 

   echo $resultado;
}



if($tipo == "ListarCobros"){

   
    $resultado = $parametro->ListarCobros(); 
        $tabla = "";
     
        $cont = 0;
        if ($resultado->RowCount() > 0) {
            $resultado->MoveFirst();
            while (!$resultado->EndOfSeek()) {                                
                $listado = $resultado->Row();
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $listado->id . "</td>";
                $tabla .= "<td data-title=''>" . $listado->motivo . "</td>";                                            
                if($listado->precio == null){
                    $tabla .= "<td data-title=''>Bs. 0.00</td>";                  
                }
                else{
                    $tabla .= "<td data-title=''>Bs. " . $listado->precio . ".00</td>";                  
                }
                
                $tabla .= "<td data-title=''>";
                if ($parametro->verificarPermisos($_SESSION['idUsuario'],28) > 0) { 
                    $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='AbrirModalActualizarPrecio(".chr(34).$listado->id.chr(34).",".chr(34).$listado->motivo.chr(34).")'><i class='fas fa-edit'></i></button>";  
                }
                if ($parametro->verificarPermisos($_SESSION['idUsuario'],29) > 0) { 
                    if($listado->estado == 'Habilitado'){
                        $tabla .= "&nbsp <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".chr(34).$listado->id.chr(34).")'>Deshabilitar</button>";  
                    }
                    else{
                        $tabla .= "&nbsp <button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".chr(34).$listado->id.chr(34).")'>Habilitar</button>";  
                    }
                }
                $tabla .= "</td>";

                $tabla .= "</tr>";
            }
        }
    
        echo  $tabla;   
  
}

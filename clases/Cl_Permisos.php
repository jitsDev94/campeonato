<?php

session_start();

include("../conexion/parametros.php");

$parametro = new parametros();

$tipo = $_GET["op"];
if($tipo == "EstadoRol"){


    $id = $_POST["id"];
    $estado = $_POST["estado"];

    $resultado = $parametro->EstadoRol( $id,$estado);
    echo $resultado;     

}


if($tipo == "RegistrarRol"){

    $nombre = $_POST["nombre"];
  
    $resultado = $parametro->RegistrarRol( $nombre);
    echo $resultado;
  
}

if($tipo == "ListarRoles"){

    $resp = $parametro->ListarRoles();
 
       $tabla = "";   
       $cont = 0;
       if ($resp->RowCount() > 0) {
        $resp->MoveFirst();
        while (!$resp->EndOfSeek()) {
            $row = $resp->Row();
             
               $cont++;
            
               $tabla .= "<tr>";
               $tabla .= "<td data-title=''>" .  $cont . "</td>";
               $tabla .= "<td data-title=''>" . $row->nombreRol . "</td>";             
               $tabla .= "<td data-title=''>" . $row->estado . "</td>";
               $tabla .= "<td data-title=''>";
                if($row->estado == "Deshabilitado"){
                 $tabla .= "<button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".$row->id.")'>Habilitar</button>";                 
                }
                else{
                    $tabla .= "&nbsp <button type='button' title='Administrar Permisos' class='btn btn-primary btn-sm checkbox-toggle' onclick='modalListarPermisos(".$row->id.")'><i class='fas fa-list'></i></button>";
                    $tabla .= "&nbsp <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$row->id.")'>Deshabilitar</button>";                     
                }
              $tabla .= "</td>";
               $tabla .= "</tr>";
           }
     
       echo  $tabla;   
       }
       else{
           echo $tabla;
       }
   
       
}

if($tipo == "ListarPermisos"){

    $idRol = $_POST['idRol'];
    $resp = $parametro->ListarPermisos();
 
       $tabla = "";   
       $cont = 0;
       if ($resp->RowCount() > 0) {
        $resp->MoveFirst();
        while (!$resp->EndOfSeek()) {
        $row = $resp->Row();            
            $cont++;        
            $tabla .= "<tr>";
            $checked = "";
            if($parametro->PermisoAsignado($row->idPermiso,$idRol) > 0){
                $checked = "checked";
            }
            $tabla .= "<td data-title=''>
            <div class='form-check form-switch'>
                <input class='form-check-input' type='checkbox' id='chekPermisos".$row->idPermiso."'  ".$checked." onclick='guardarPermisoRol($row->idPermiso,$idRol)'>
                <label class='form-check-label' for='chekPermisos".$row->idPermiso."'></label>
            </div>
            </td>";
            $tabla .= "<td data-title=''>" . $row->nombrePermiso . "</td>";
            $tabla .= "</tr>";
        }
     
       echo  $tabla;   
       }
       else{
           echo $tabla;
       }
   
       
}

if($tipo == "guardarPermisoRol"){

    $idRol = $_POST["idRol"];
    $idPermiso = $_POST["idPermiso"];
  
    $resultado = $parametro->guardarPermisoRol( $idPermiso,$idRol);
    echo $resultado;
  
}
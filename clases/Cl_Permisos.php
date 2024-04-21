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


if($tipo == "EditarRol"){

    $nombre = $_POST["nombre"];
    $idRol = $_POST["idRol"];

    $resultado = $parametro->EditarRol( $idRol,$nombre);
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
                if($row->estado == "Deshabilitado" && $parametro->verificarPermisos($_SESSION['idUsuario'],24) > 0){
                 $tabla .= "<button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".$row->id.")'>Habilitar</button>";                 
                }
                else{
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],23) > 0){
                        $tabla .= "&nbsp <button type='button' title='Editar Rol' class='btn btn-primary btn-sm checkbox-toggle' onclick='abrirModalEditarRol(".chr(34).$row->id.chr(34).",".chr(34).$row->nombreRol.chr(34).")'><i class='fas fa-user-edit'></i></button>";                     
                    }
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],25) > 0){
                    $tabla .= "&nbsp <button type='button' title='Administrar Permisos' class='btn btn-secondary btn-sm checkbox-toggle' onclick='modalListarPermisos(".$row->id.")'><i class='fas fa-list'></i></button>";
                    }
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],24) > 0){
                    $tabla .= "&nbsp <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$row->id.")'>Deshabilitar</button>";                     
                    }
                  
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

if($tipo == "ListarPermisos2"){

    $idRol = $_POST['idRol'];
    $resp = $parametro->ListarPermisos();
    $count = $resp->RowCount();
    $contador = 0;
    $separacion = 0;
       $tabla = "";         
       if ($count > 0) {
        $mitad = $count / 2;
        $resp->MoveFirst();
        $tabla .= "<div class='row'>";
        $tabla .= "<div class='col-md-12'>";
        $lastMenu = '';
        while (!$resp->EndOfSeek()) {
            $row = $resp->Row();            
            $contador++;
           
            if($lastMenu == ''){
             
                $lastMenu =  $row->menu;               
                $tabla .= "<h5>".$row->menu."</h5>";
            }
            
            if($lastMenu != $row->menu){ 
                // if($contador >= $mitad &&  $separacion == 0){
                //     $separacion = 1;
                //     $tabla .= "</div>";
                //     $tabla .= "<div class='col-md-6'>";
                // } 
                $lastMenu = $row->menu;                          
                $tabla .= "<br><br><h5>".$row->menu."</h5>";
            }   

            $checked = "";
            if($parametro->PermisoAsignado($row->idPermiso,$idRol) > 0){
                $checked = "checked";
            }

            $tabla .="<div class='uv-checkbox-wrapper'>
            <input type='checkbox' class='uv-checkbox' id='chekPermisos".$row->idPermiso."'  ".$checked." onclick='guardarPermisoRol($row->idPermiso,$idRol)'/>
            <label for='chekPermisos".$row->idPermiso."' class='uv-checkbox-label'>
              <div class='uv-checkbox-icon'>
                <svg viewBox='0 0 24 24' class='uv-checkmark'>
                  <path d='M4.1,12.7 9,17.6 20.3,6.3' fill='none'></path>
                </svg>
              </div>
              <span class='uv-checkbox-text'>" . $row->nombrePermiso . "</span>
            </label>
          </div> &nbsp &nbsp";      

            $lastMenu = $row->menu;           
        }

        $tabla .= "</div></div>";
       echo  $tabla;   
       }
       else{
           echo $tabla;
       }
   
       
}


if($tipo == "ListarPermisos"){ //ya no se utiliza, se reemplazo por el ListarPermisos2

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
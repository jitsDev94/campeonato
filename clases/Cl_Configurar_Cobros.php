<?php

session_start();
require_once '../conexion/parametros.php';
$parametro = new parametros();

$tipo = $_GET["op"];


if($tipo == "ActualizarPrecio"){

    $precio = $_POST["precio"];
    $id = $_POST["id"];

    $Consultar = "UPDATE configuracionCobros SET precio = $precio where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
  
    if($resultado){
        echo 1;
    }
    else{
        echo 2;
    } 
}


if($tipo == "NombreMotivo"){

    $id = $_POST["id"];

    $datos = array();
    $Consultar = "SELECT * FROM configuracionCobros where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    } 
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
                    $tabla .= "<td data-title=''>";
                    if ($parametro->verificarPermisos($_SESSION['idUsuario'],3) > 0) { 
                        
                        $tabla .= "<button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='NombreMotivo(".$listado->id.")'>Agregar Precio</button>";  
                    }
                    $tabla .= "</td>";
                }
                else{
                    $tabla .= "<td data-title=''>Bs. " . $listado->precio . ".00</td>";
                    $tabla .= "<td data-title=''>";
                    if ($parametro->verificarPermisos($_SESSION['idUsuario'],28) > 0) { 
                    $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='NombreMotivo(".$listado->id.")'>Modificar Precio</button>";  
                    }
                    $tabla .= "</td>";
                }
                
                $tabla .= "</tr>";
            }
        }
    
        echo  $tabla;   

        // $resultado = $parametro->ListarCobros(); 
        // $tabla = "";       
        // $cont = 0;
        // if ($resultado->RowCount() > 0) {
        //     while (!$resultado->EndOfSeek()) {  
        //         $resultado->MoveFirst();
        //         $listado = $resultado->Row();
        //         $cont++;
        //         $tabla .= "<tr>";
        //         $tabla .= "<td data-title=''>" .  $cont . "</td>";
        //         $tabla .= "<td data-title=''>" . $listado->motivo . "</td>";
        //         $tabla .= "<td data-title=''>" . $listado->detalle . "</td>";
        //         $tabla .= "<td data-title=''>" . $listado->fechaPublicacion . "</td>";
        //         $tabla .= "<td data-title=''>" . $listado->fechaLimite . "</td>";  
        //         if($listado->precio == null){
        //             $tabla .= "<td data-title=''>Bs. 0.00</td>";
        //             $tabla .= "<td data-title=''><button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='NombreMotivo(".$listado->id.")'>Agregar Precio</button></td>";  
        //         }
        //         else{
        //             $tabla .= "<td data-title=''>Bs. " . $listado->precio . ".00</td>";
        //             $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='NombreMotivo(".$listado->id.")'>Modificar Precio</button></td>";  
        //         }
        //        // $tabla .= "<td data-title=''>";  
        //         // if($listado->estado == 'Habilitado'){
        //         //     if ($parametro->verificarPermisos($_SESSION['idUsuario'],27) > 0) { 
        //         //     $tabla .= "<button type='button' class='btn btn-danger btn-sm' onclick='ConfirmarQuitarAnuncio(" . $listado->id . ")'>Deshabilitar</button>";
        //         //     }
        //         // } 
        //         // else{     
        //         //     if ($parametro->verificarPermisos($_SESSION['idUsuario'],26) > 0) {          
        //         //         $tabla .= "<button type='button' class='btn btn-primary btn-sm' onclick='ModalEditarAnuncio(".chr(34). $listado->id .chr(34).",".chr(34). $listado->titulo .chr(34)."," .chr(34). $listado->detalle .chr(34).",".chr(34). $listado->fechaLimite .chr(34).")'>Habilitar</button>";
        //         //     }
        //         // }
               
        //         $tabla .= "</tr>";
        //     }
        // }
    
        //echo  $tabla;   
}

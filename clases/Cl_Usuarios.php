<?php

session_start();
//include("../conexion/conexion.php");
include("../conexion/parametros.php");

$parametro = new parametros();

$tipo = $_GET["op"];
if($tipo == "EstadoUsario"){


    $id = $_POST["id"];
    $estado = $_POST["estado"];

    $insertar = "UPDATE Usuario SET estado = '$estado' where id=$id";
    $resultado = mysqli_query($conectar, $insertar);

    if($resultado){
       echo 1;  
    }
    else{
        echo 2;
    }

}


if($tipo == "RegistrarUsuario"){


    $idRol = $_POST["idRol"];
    $idEquipo = $_POST["idEquipo"];
    $usuario = $_POST["usuario"];
    $contra = 'campeonato'.date('Y');

    $resultado = $parametro->RegistrarUsuario( $idRol,$idEquipo,$usuario ,$contra);
    echo $resultado;
  
}



if($tipo == "ListarUsuarios"){

    $resp = $parametro->ListarUsuarios();
 
       $tabla = "";
    //    $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
    //                <thead>
    //                     <tr>
    //                         <th>#</th>
    //                         <th>Rol</th>
    //                         <th>Equipo</th>
    //                         <th>Usuario</th> 
    //                         <th>Estado</th>                 
    //                         <th>Acción</th>          
    //                     </tr>
    //                </thead>
    //                <tbody > ';
    
       $cont = 0;
       if ($resp->RowCount() > 0) {
        $resp->MoveFirst();
        while (!$resp->EndOfSeek()) {
            $row = $resp->Row();
             
               $cont++;
            
               $tabla .= "<tr>";
               $tabla .= "<td data-title=''>" .  $cont . "</td>";
               $tabla .= "<td data-title=''>" . $row->nombreRol . "</td>";
               $tabla .= "<td data-title=''>" . $row->nombreEquipo . "</td>";
               $tabla .= "<td data-title=''>" . $row->usuario . "</td>";
               $tabla .= "<td data-title=''>" . $row->estado . "</td>";
               $tabla .= "<td data-title=''>";
                if($row->estado == "Deshabilitado"){
                 $tabla .= "<button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".$row->idUsuario.")'>Habilitar</button>";                 
                }
                else{
                    $tabla .= "&nbsp <button type='button' title='Administrar Permisos' class='btn btn-primary btn-sm checkbox-toggle' onclick='editarPermisos(".$row->idUsuario.")'><i class='fas fa-list'></i></button>";
                    $tabla .= "&nbsp <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$row->idUsuario.")'>Deshabilitar</button>";                     
                }
              $tabla .= "</td>";
               $tabla .= "</tr>";
           }
        //    $tabla .= "</tbody>               
        //        </table>";
       echo  $tabla;   
       }
       else{
           echo $tabla;
       }
   
       
}
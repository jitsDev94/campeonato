<?php

session_start();
include("../conexion/conexion.php");
require_once '../conexion/parametros.php';
$parametro = new parametros();


$idRol = $_SESSION['idRol'];
$idEquipoDelegado = $_SESSION['idEquipo'];
$tipo = $_GET["op"];


if($tipo == "RegistrarMulta"){

    $motivoMulta  = $_POST["motivoMulta"];
    $fecha = $_POST["fecha"];
    $total  = $_POST["total"];
    $idEquipo = $_POST["idEquipo"];

    $resultado = $parametro->RegistrarMulta($motivoMulta,$fecha,$total,$idEquipo);
    
    if($resultado == 'ok'){
        echo 1;
    }
    else{
        echo 2;
    }
}

if($tipo == "EliminarMulta"){

    $idMulta  = $_POST["id"];

        $eliminar = "DELETE FROM Multa where id = $idMulta";
        $resultado = mysqli_query($conectar, $eliminar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}

if($tipo == "CobrarMulta"){

    $idMulta  = $_POST["id"];

        $eliminar = "UPDATE Multa SET estado = 'Pagado' where id = $idMulta";
        $resultado = mysqli_query($conectar, $eliminar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}


if($tipo == "precioMulta"){

    $consultar = "SELECT precio FROM configuracionCobros where motivo = 'Multas'";
    $resultado2 = mysqli_query($conectar, $consultar);
    $row = $resultado2->fetch_assoc();
    $precio = $row['precio'];

    if($resultado2)
    {
        echo $precio;
    }
    else
    {
        echo 'error';
    }
   
    
}


if($tipo == "FiltrarMultas"){

    $idCampeonato = $_POST["idCampeonato"];
    $idEquipo = $_POST["idEquipo"];
      
    $resultado = $parametro->obtenerListadoMultas($idCampeonato,$idEquipo,$idRol,$idEquipoDelegado); 

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Motivo Multa</th>
                    <th>Equipo</th>
                    <th>Total</th>
                    <th>Torneo</th>
                    <th>Estado</th>';
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],'42,43') > 0){
                  $tabla .= '  <th>Acción</th>  ';      
                    }  
                $tabla .= '</tr>
            </thead>
            <tbody > ';

        $cont = 0;
        if ($resultado->RowCount() > 0) {
            while (!$resultado->EndOfSeek()) {
                $listado = $resultado->Row();
                $cont++;
               
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado->fecha . "</td>";
                $tabla .= "<td data-title=''>" . $listado->motivoMulta . "</td>";
                $tabla .= "<td data-title=''>".$listado->nombreEquipo."</td>";
                $tabla .= "<td data-title=''>" . $listado->total . "</td>";
                $tabla .= "<td data-title=''>" . $listado->nombreCampeonato . "</td>";
                $tabla .= "<td data-title=''>" . $listado->estado . "</td>";
                if($parametro->verificarPermisos($_SESSION['idUsuario'],'42,43') > 0 ){
                    $tabla .= "<td data-title=''>";
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],'42') > 0 && $listado->estado == 'Pendiente'){
                        $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='ConfirmarCobrarMulta(".$listado->id.")'><i title= 'Cobrar Multa' class='fas fa-hand-holding-usd'></i> Cobrar</button>";  
                    }
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],'43') > 0 && $listado->estado == 'Pendiente'){
                        $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarEliminar(".$listado->id.")'><i title= 'Eliminar Multa' class='fas fa-trash'></i> Eliminar</button>";                          
                    }
                    $tabla .= "</td>";
                }
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}

<?php
require_once '../conexion/parametros.php';
$parametro = new parametros();
session_start();
$idRol = $_SESSION['idRol'];   
$idEquipoDelegado = $_SESSION['idEquipo'];
$idUsuario = $_SESSION['idUsuario'];

$tipo = $_GET["op"];


if($tipo == "AceptarObservacion"){

    $codObservacion = $_POST["codObservacion"];
    $idPartido = $_POST["idPartido"];
    $estado = $_POST["estado"];
    $motivo = $_POST["motivo"];
    $castigos = $_POST["castigos"];    
    $puntos = $_POST["puntos"];
    $goles = $_POST["goles"];
    $codEquipoObservado = $_POST["codEquipoObservado"];
    $equipoLocal = $_POST["equipoLocal"];
    $esquipoVisitante = $_POST["esquipoVisitante"];
    $equipoObservado = $_POST["equipoObservado"];

    $equipoNoObservado = '';
    $equipoObservador = '';
    if($equipoObservado == $equipoLocal){
        $equipoNoObservado = $esquipoVisitante;
        $equipoObservador = 'visitante';
    }
    else{
        $equipoNoObservado = $equipoLocal;
        $equipoObservador = 'local';
    }

    $resultado = $parametro->AceptarObservacion($codObservacion, $idPartido,$estado,$motivo,$castigos,$puntos,$goles,$codEquipoObservado,$idUsuario,$equipoObservado,$equipoNoObservado,$equipoObservador); 
    echo $resultado;
        // $registrar = "UPDATE Partido SET estadoObservacion = '$estado', motivoRechazo = '$motivo' where id = $id";
        // $resultado = mysqli_query($conectar, $registrar);
    
        // if($resultado){
        //     if($castigos == "puntos"){
        //         $registrar = "UPDATE TablaPosicion SET puntos = puntos - $puntos  where idEquipo = $codEquipoObservado and idCampeonato = (SELECT id FROM Campeonato where estado = 'En Curso')";
        //         $resultado = mysqli_query($conectar, $registrar);

        //         if($resultado){
        //             echo '1';
        //         }
        //         else{
        //             $registrar = "UPDATE Partido SET estadoObservacion = 'Pendiente', motivoRechazo = '' where id = $id";
        //             $resultado = mysqli_query($conectar, $registrar);
        //             echo '3';
        //         }
        //     }
        //     else{
        //         echo "1";
        //     }
        // }
        // else{
        //     echo '2';
        // }
}



if($tipo == "DatosTarjeta"){

    $id = $_POST["id"];
    $datos = array();
    $Consultar = "SELECT j.nombre,j.apellidos FROM HechosPartido as hp 
                    LEFT JOIN Jugador as j on j.id = hp.idJugador 
                    where hp.id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    }   
}

if($tipo == "FiltrarObservaciones"){


    $idEquipo = $_POST["idEquipos"];
    $idCampeonato = $_POST["idCampeonato"];
    

    $resultado = $parametro->FiltrarObservaciones($idCampeonato,$idEquipo); 
  
            $tabla = "";
            $tabla .= '<table id="example" class="table table-bordered table-striped"  method="POST">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Partido</th> 
                                 <th>Equipo Observado</th>                            
                                
                                <th>Torneo</th>
                                <th>Modo</th>
                                <th>Observación</th>                               
                                <th>Estado</th>
                                <th width="80px">Acción</th>   
                            </tr>
                        </thead>
                        <tbody > ';
         
            $cont = 0;
            if ($resultado->RowCount() > 0) {
                while (!$resultado->EndOfSeek()) {
                    $listado = $resultado->Row();
                    $cont++;
                    $fechaRespuesta = '';
                    if($listado->fechaRespuesta != ''){
                        $fechaRespuesta =  date('d-m-Y',strtotime($listado->fechaRespuesta));
                    }
                    $tabla .= "<tr>";
                    $tabla .= "<td data-title=''>" .  $cont . "</td>";
                    $tabla .= "<td data-title=''>" . $listado->fechaPartido . "</td>";
                    $tabla .= "<td data-title=''>" . $listado->equipoLocal . " - " . $listado->esquipoVisitante . "</td>";               
                    $tabla .= "<td data-title=''>" . $listado->equipoObservado . "</td>";
                    $tabla .= "<td data-title=''>" . $listado->nombreCampeonato . "</td>";          
                    $tabla .= "<td data-title=''>" . $listado->Modo . "</td>";
                    $tabla .= "<td data-title=''>" . $listado->Observacion . "</td>";                   
                    $tabla .= "<td data-title=''>" . $listado->estadoObservacion . "</td>";
                    $tabla .= "<td data-title=''>";
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],'5') > 0 && $listado->estadoObservacion == 'Pendiente'){
                        $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='ConfirmarAceptarObservacion(" . chr(34) . $listado->idObservacion . chr(34) . "," . chr(34) . $listado->idPartido . chr(34) . "," . chr(34) . $listado->idEquipoObservado . chr(34) . "," . chr(34) . $listado->equipoLocal . chr(34) . "," . chr(34) . $listado->esquipoVisitante . chr(34) . "," . chr(34) . $listado->equipoObservado . chr(34) . ")'><i class='fas fa-thumbs-up'></i></button>";      
                        $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='RechazarObservacion(" . chr(34) . $listado->idObservacion . chr(34) . ",". chr(34).$listado->idPartido . chr(34) .")'><i class='fas fa-thumbs-down'></i></button>";
                    }
                    else{
                        if($listado->estadoObservacion == 'Rechazado'){
                            $tabla .= "<button type='button' title='Ver Motivo Rechazo' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verMotivoRechazo(" . chr(34) . $listado->motivoRechazo . chr(34) . "," . chr(34) . $fechaRespuesta . chr(34) . "," . chr(34) . $listado->usuario . chr(34) . ")'><i class='fas fa-exclamation-triangle'></i></button>";      
                        }
                        else{
                            if($listado->estadoObservacion == 'Aceptado'){
                                $tabla .= "<button type='button' title='Ver Detalle Castigo' class='btn btn-success btn-sm checkbox-toggle' onclick='verAprobacion(" . chr(34) . $listado->castigo . chr(34) . "," . chr(34) . $fechaRespuesta . chr(34) . "," . chr(34) . $listado->usuario . chr(34) . ")'><i class='fas fa-exclamation-triangle'></i></button>";      
                            }
                        }
                    }
                    $tabla .= "</td>";
                    $tabla .= "</tr>";
                }
            }
        
            $tabla .= "</tbody>                    
            </table>";

        echo  $tabla;   
}
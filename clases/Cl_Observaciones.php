<?php

session_start();
$idRol = $_SESSION['idRol'];   
$idEquipoDelegado = $_SESSION['idEquipo'];

include("conexion.php");

$tipo = $_GET["op"];


if($tipo == "AceptarObservacion"){

    $id = $_POST["id"];
    $estado = $_POST["estado"];
    $motivo = $_POST["motivo"];
    $castigos = $_POST["castigos"];
    $puntos = $_POST["puntos"];
    $goles = $_POST["goles"];
    $codEquipoObservado = $_POST["codEquipoObservado"];

        $registrar = "UPDATE Partido SET estadoObservacion = '$estado', motivoRechazo = '$motivo' where id = $id";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            if($castigos == "puntos"){
                $registrar = "UPDATE TablaPosicion SET puntos = puntos - $puntos  where idEquipo = $codEquipoObservado and idCampeonato = (SELECT id FROM Campeonato where estado = 'En Curso')";
                $resultado = mysqli_query($conectar, $registrar);

                if($resultado){
                    echo '1';
                }
                else{
                    $registrar = "UPDATE Partido SET estadoObservacion = 'Pendiente', motivoRechazo = '' where id = $id";
                    $resultado = mysqli_query($conectar, $registrar);
                    echo '3';
                }
            }
            else{
                echo "1";
            }
        }
        else{
            echo '2';
        }
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


if($tipo == "ListaObservaciones"){

    $condicion = "";

    if($idRol == 3){
        $condicion = "and p.equipoObservado = $idEquipoDelegado";
    }

    $consultar = "SELECT p.id as idPartido, e1.nombreEquipo as equipoLocal, e2.nombreEquipo as esquipoVisitante,s.nombreSede,c.nombre as nombreCampeonato,
    p.fechaPartido,p.Modo,p.Observacion,e3.nombreEquipo as nombreEquipoObservado,p.equipoObservado FROM Partido as p 
    LEFT JOIN Equipo as e1 on e1.id = p.idEquipoLocal
    LEFT JOIN Equipo as e2 on e2.id = p.idEquipoVisitante
    LEFT JOIN Equipo as e3 on e3.id = p.equipoObservado
    LEFT join Campeonato as c on c.id = p.idCampeonato
    LEFT join Sede as s on s.id = p.idSede
    where p.estadoObservacion = 'Pendiente' and c.estado = 'En Curso' $condicion";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        if($idRol == 3){
            $tabla .= '<table id="example" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th width="80px">Fecha</th>
                    <th>Partido</th>
                    <th>Equipo Observado</th>                       
                    <th>Modo</th>
                    <th>Observación</th> 
                </tr>
            </thead>
            <tbody > ';
        }
        else{
            $tabla .= '<table id="example" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th width="80px">Fecha</th>
                    <th>Partido</th>
                    <th>Equipo Observado</th>                       
                    <th>Modo</th>
                    <th>Observación</th>
                    <th width="80px">Acción</th>   
                </tr>
            </thead>
            <tbody > ';
        }
       
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaPartido'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['equipoLocal'] . " - " . $listado['esquipoVisitante'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreEquipoObservado'] . "</td>";           
                $tabla .= "<td data-title=''>" . $listado['Modo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['Observacion'] . "</td>";
                if($idRol != 3){
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='ConfirmarAceptarObservacion(" . chr(34) . $listado['idPartido'] . chr(34) . "," . chr(34) . $listado['equipoObservado'] . chr(34) . ")'><i class='fas fa-thumbs-up'></i></button>";      
                $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='RechazarObservacion(".$listado['idPartido'].")'><i class='fas fa-thumbs-down'></i></button>";
                }
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "FiltrarObservaciones"){


    $idEquipo = $_POST["idEquipos"];
    $idCampeonato = $_POST["idCampeonato"];
    $condicion = "";

    if($idEquipo != 0){
        $condicion ="and p.equipoObservado = $idEquipo";

        $consultar = "SELECT p.id as idPartido, e1.nombreEquipo as equipoLocal, e2.nombreEquipo as esquipoVisitante, 
        s.nombreSede,c.nombre as nombreCampeonato,p.motivoRechazo,
        p.fechaPartido,p.Modo,p.Observacion,p.estadoObservacion FROM Partido as p 
        LEFT JOIN Equipo as e1 on e1.id = p.idEquipoLocal
        LEFT JOIN Equipo as e2 on e2.id = p.idEquipoVisitante
        LEFT JOIN Equipo as e3 on e3.id = p.equipoObservado
        LEFT join Campeonato as c on c.id = p.idCampeonato
        LEFT join Sede as s on s.id = p.idSede
        where p.idCampeonato = $idCampeonato and p.estadoObservacion != '' $condicion";
        $resultado1 = mysqli_query($conectar, $consultar);
    
            $tabla = "";
            $tabla .= '<table id="example" class="table table-bordered table-striped"  method="POST">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Partido</th>                            
                                <th>Sede</th>
                                <th>Torneo</th>
                                <th>Modo</th>
                                <th>Observación</th>
                                <th>Motivo Rechazo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody > ';
         
            $cont = 0;
            if ($resultado1) {
                while ($listado = mysqli_fetch_array($resultado1)) {
                    $cont++;
                    $tabla .= "<tr>";
                    $tabla .= "<td data-title=''>" .  $cont . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['fechaPartido'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['equipoLocal'] . " - " . $listado['esquipoVisitante'] . "</td>";               
                    $tabla .= "<td data-title=''>" . $listado['nombreSede'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['nombreCampeonato'] . "</td>";          
                    $tabla .= "<td data-title=''>" . $listado['Modo'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['Observacion'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['motivoRechazo'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['estadoObservacion'] . "</td>";
                    $tabla .= "</tr>";
                }
            }
        
            $tabla .= "</tbody>
                    
                    </table>";
    }
    else{
        $consultar = "SELECT p.id as idPartido, e1.nombreEquipo as equipoLocal, e2.nombreEquipo as esquipoVisitante, e3.nombreEquipo as nombreEquipoObservado,
        s.nombreSede,c.nombre as nombreCampeonato,p.motivoRechazo,
        p.fechaPartido,p.Modo,p.Observacion,p.estadoObservacion FROM Partido as p 
        LEFT JOIN Equipo as e1 on e1.id = p.idEquipoLocal
        LEFT JOIN Equipo as e2 on e2.id = p.idEquipoVisitante
        LEFT JOIN Equipo as e3 on e3.id = p.equipoObservado
        LEFT join Campeonato as c on c.id = p.idCampeonato
        LEFT join Sede as s on s.id = p.idSede
        where p.idCampeonato = $idCampeonato and p.estadoObservacion != '' $condicion";
        $resultado1 = mysqli_query($conectar, $consultar);
    
            $tabla = "";
            $tabla .= '<table id="example" class="table table-bordered table-striped"  method="POST">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Partido</th>
                                <th>Equipo Observado</th>
                                <th>Sede</th>
                                <th>Torneo</th>
                                <th>Modo</th>
                                <th>Observación</th>
                                <th>Motivo Rechazo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody > ';
         
            $cont = 0;
            if ($resultado1) {
                while ($listado = mysqli_fetch_array($resultado1)) {
                    $cont++;
                    $tabla .= "<tr>";
                    $tabla .= "<td data-title=''>" .  $cont . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['fechaPartido'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['equipoLocal'] . " - " . $listado['esquipoVisitante'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['nombreEquipoObservado'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['nombreSede'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['nombreCampeonato'] . "</td>";          
                    $tabla .= "<td data-title=''>" . $listado['Modo'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['Observacion'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['motivoRechazo'] . "</td>";
                    $tabla .= "<td data-title=''>" . $listado['estadoObservacion'] . "</td>";
                    $tabla .= "</tr>";
                }
            }
        
            $tabla .= "</tbody>
                    
                    </table>";
    }

   
        echo  $tabla;   
}
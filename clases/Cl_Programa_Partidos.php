<?php

session_start();
include("conexion.php");

$tipo = $_GET["op"];



if($tipo == "RegistrarProgramacionPartido"){

    $idEquipoLocal  = $_POST["idEquipoLocal"];
    $idEquipoVisitante = $_POST["idEquipoVisitante"];
    $fecha  = $_POST["fecha"];
    $Cancha = $_POST["Cancha"];
    $confirmacion = $_POST["confirmacion"];

    $registrar = "SELECT * FROM programacionPartidos where estado = 'Pendiente' and (codEquipoLocal = $idEquipoLocal or codEquipovisita = $idEquipoLocal)";
    $resultado = mysqli_query($conectar, $registrar);
    $row = mysqli_num_rows($resultado);
    if($row > 0 && $confirmacion == 0){
        echo "El equipo local ya tiene un partido programado";
    }
    else{

        $registrar = "SELECT * FROM programacionPartidos where estado = 'Pendiente' and  (codEquipoLocal = $idEquipoVisitante or codEquipovisita = $idEquipoVisitante)";
        $resultado = mysqli_query($conectar, $registrar);
        $row = mysqli_num_rows($resultado);
        if($row > 0 && $confirmacion == 0){
            echo "El equipo visitante ya tiene un partido programado";
        }
        else{
            $registrar = "INSERT INTO programacionPartidos (codEquipoLocal,codEquipoVisita,fecha,cancha,estado) values('$idEquipoLocal','$idEquipoVisitante','$fecha','$Cancha','Pendiente')";
            $resultado = mysqli_query($conectar, $registrar);
            
            if($resultado){
                echo '1';
            }
            else{
                echo 'error';
            }
        }       
    }
}


if($tipo == "ListaEquipos"){

    $registrarInventario = "SELECT p.codProgramacion,p.codEquipoLocal, e.nombreEquipo as equipoLocal,p.codEquipovisita, e2.nombreEquipo as equipoVisita,p.fecha,p.cancha	
    FROM programacionPartidos p 
    LEFT JOIN Equipo e on e.id = p.codEquipoLocal
    LEFT JOIN Equipo e2 on e2.id = p.codEquipovisita
    where p.estado = 'Pendiente'";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th>Cancha</th>
                        <th>Equipo Local</th>
                        <th>Equipo Visitante</th>
                        <th>Fecha</th>                      
                        <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
             
                $fecha = date('Y-m-d', strtotime($listado['fecha']));
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['cancha'] . "</td>";
                $tabla .= "<td data-title=''>".$listado['equipoLocal']."</td>";
                $tabla .= "<td data-title=''>" . $listado['equipoVisita'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";
                $tabla .= "<td data-title=''><button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ModalRegistrarPartido(". chr(34) .$listado['codProgramacion']. chr(34) .",". chr(34) .$listado['codEquipoLocal']. chr(34) .", ". chr(34) .$listado['codEquipovisita']. chr(34) .", ". chr(34) . $fecha. chr(34) .")'>Registrar</button>";      
                $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ModalEliminarPartido(". chr(34) .$listado['codProgramacion']. chr(34) .",". chr(34) .$listado['equipoLocal']. chr(34).", ". chr(34) .$listado['equipoVisita']. chr(34) .")'>Eliminar</button>";      
                // if($listado['estado'] == "Habilitado"){
                //     $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$listado['id'].")'>Deshabilitar</button>";                          
                // }else{
                //     $tabla .= " <button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".$listado['id'].")'>Habilitar</button>";                          
                // }
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}



if($tipo == "ListarPartidos"){

    $cancha = $_POST["cancha"];

    $registrarInventario = "SELECT p.codProgramacion,e.nombreEquipo as equipoLocal, e2.nombreEquipo as equipoVisita,
    DATE_FORMAT(p.fecha,'%H:%i') as hora,p.cancha	
    FROM programacionPartidos p 
    LEFT JOIN Equipo e on e.id = p.codEquipoLocal
    LEFT JOIN Equipo e2 on e2.id = p.codEquipovisita
    where p.estado = 'Pendiente' and p.cancha = '$cancha' order by p.fecha asc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

    if($cancha = "Cancha 1"){
        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                        <th>#</th>                     
                        <th>Equipo Local</th>
                        <th>Equipo Visitante</th>
                        <th>Hora</th>                     
                        </tr>
                    </thead>
                    <tbody > ';
    }
    else{
        $tabla = "";
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                        <th>#</th>                     
                        <th>Equipo Local</th>
                        <th>Equipo Visitante</th>
                        <th>Hora</th>                     
                        </tr>
                    </thead>
                    <tbody > ';
    }
       
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $nombre= utf8_encode($listado['nombreEquipo']);
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";              
                $tabla .= "<td data-title=''>".$listado['equipoLocal']."</td>";
                $tabla .= "<td data-title=''>" . $listado['equipoVisita'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['hora'] . "</td>";             
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "datosProgramacionPartidos"){

    $id = $_POST["id"];
    $datos = array();
    $Consultar = "SELECT DATE_FORMAT(fecha, '%Y-%m-%d') as fecha,cancha FROM programacionPartidos  where estado = 'Pendiente' limit 1";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    } 
}

if($tipo == "eliminarPartidoProgramado"){

    $codProgramacion = $_POST["codProgramacion"];
   
    $Consultar = "DELETE from programacionPartidos where codProgramacion = $codProgramacion";
    $resultado = mysqli_query($conectar, $Consultar);
  

    if($resultado){
        echo "1";
    }
    else{
        echo $Consultar;
    } 
}
<?php

use PhpParser\Node\Stmt\Else_;

session_start();
//include("conexion.php");
include("../conexion/conexion.php");
require_once '../conexion/parametros.php';
$parametro = new parametros();

$tipo = $_GET["op"];


if($tipo == "DatosEquipo"){

    $idPartido = $_POST["id"];

    $datos = array();
    $Consultar = "SELECT p.id,p.fechaPartido,e1.id as idEquipo1,e1.nombreEquipo as EquipoLocal,e2.id as idEquipo2, e2.nombreEquipo as EquipoVisitante from Partido as p
    LEFT JOIN Equipo as e1 on e1.id = p.idEquipoLocal
    LEFT JOIN Equipo as e2 on e2.id = p.idEquipoVisitante
    where p.id = $idPartido";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    } 
}

if($tipo == "ListaPartidos2"){

    $fecha = @$_POST["fecha"];
    $grupo = @$_POST["grupo"];

    $consultargoles2="";
    $condicion = "";

    if($grupo != 0)
    {
        $condicion = "and tp1.grupo = $grupo AND tp2.grupo = $grupo";
    }
    
    $resultado = $parametro->ListaPartidos2($fecha,$grupo); 

   

    $tabla = '';
   
    if ($resultado->RowCount() > 0) {
        $tabla .= '<div class="row">';
        $count = 0;  
   
        while (!$resultado->EndOfSeek()) {
            $listado = $resultado->Row();
            $count ++;  
            if($count == 1){
                $tabla .= '<div class="col-md-12 text-center ml-3 mr-3">           
                <h5 class="p-3">Fecha Partidos - '.$listado->fechaPartido.'</h5>  </div>                             
            ';
            }
            // $consultargoles1 = "SELECT count(hp.Equipo) as goles from acontecimientopartido as hp 
            //     LEFT JOIN Partido as p on p.id = hp.idPartido
            //     where hp.Equipo = '".$listado->EquipoLocal."' and p.fechaPartido = '$fecha' and hp.idAcontecimiento = 1 and p.idEquipoLocal =  ".$listado->idEquipo1." and p.idEquipoVisitante = ".$listado->idEquipo2."";
            //     $resultado2 = mysqli_query($conectar, $consultargoles1);
            //     $row = $resultado2->fetch_assoc();
                $dato = $parametro->obtenerGoles($listado->EquipoLocal,$listado->idEquipo1,$listado->idEquipo2,$listado->fechaPartido,$fecha);
                $goles1 = $dato->goles; 
        

                // $consultargoles2 = "SELECT count(hp.Equipo) as goles from acontecimientopartido as hp 
                // LEFT JOIN Partido as p on p.id = hp.idPartido
                // where hp.Equipo = '".$listado->EquipoVisitante."' and p.fechaPartido = '$fecha' and hp.idAcontecimiento = 1 and p.idEquipoLocal =  ".$listado->idEquipo1." and p.idEquipoVisitante = ".$listado->idEquipo2."";
                // $resultado3 = mysqli_query($conectar, $consultargoles2);
                // $row = $resultado3->fetch_assoc();
                $dato = $parametro->obtenerGoles($listado->EquipoVisitante,$listado->idEquipo1,$listado->idEquipo2,$listado->fechaPartido,$fecha);
                $goles2 = $dato->goles;                
                
            $tabla .= '<div class="card col-md-2 text-center ml-3 mr-3">           
                <h5 class="pt-4">'.$listado->EquipoLocal .' - '.$listado->EquipoVisitante.'</h5>
                <h5 class="pt-1"> '.$goles1.' - '.$goles2.'</h5><br>
                <button type="button" class="btn btn-success btn-sm mb-2"  onclick="ModalVerPartidos('.$listado->id.')">Ver detalles</button>                  
            </div> ';
        }
   

        $tabla .= ' </div>';

        if($count == 0){
            $tabla .='<div class="alert alert-warning text-center" role="alert">No se encontraron partidos en esa fecha</div>';
        }
   
    }
    else{
        $tabla .='<div class="alert alert-warning text-center" role="alert">No se encontraron partidos en esa fecha</div>';
    }

    echo  $tabla;   
    
}


if($tipo == "ListaPartidos"){

    $fecha = $_POST["fecha"];
    $grupo = $_POST["grupo"];

    $consultargoles2="";
    $condicion = "";

    if($grupo != 0)
    {
        $condicion = "and tp1.grupo = $grupo AND tp2.grupo = $grupo";
    }
    

    $consultar = "SELECT p.id,p.fechaPartido,e1.id as idEquipo1,e1.nombreEquipo as EquipoLocal,e2.id as idEquipo2, e2.nombreEquipo as EquipoVisitante from Partido as p
    LEFT JOIN TablaPosicion as tp1 on tp1.idEquipo = p.idEquipoLocal
    LEFT JOIN TablaPosicion as tp2 on tp2.idEquipo = p.idEquipoVisitante
    LEFT JOIN Equipo as e1 on e1.id = tp1.idEquipo
    LEFT JOIN Equipo as e2 on e2.id = tp2.idEquipo
    WHERE p.fechaPartido = '$fecha' $condicion
    GROUP by  p.id,p.fechaPartido,e1.id,e1.nombreEquipo,e2.id,e2.nombreEquipo";

    // "SELECT p.id,p.fechaPartido,e1.id as idEquipo1,e1.nombreEquipo as EquipoLocal,e2.id as idEquipo2, e2.nombreEquipo as EquipoVisitante from Partido as p
    // LEFT JOIN Equipo as e1 on e1.id = p.idEquipoLocal
    // LEFT JOIN Equipo as e2 on e2.id = p.idEquipoVisitante
    // WHERE p.fechaPartido = '$fecha'
    // GROUP by  p.id,p.fechaPartido,e1.id,e1.nombreEquipo,e2.id,e2.nombreEquipo";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha Partido</th>
                            <th>Equipo Local</th>
                            <th width="50px;">Resultados</th>
                            <th>Equipo Visitante</th>
                            <th>Acción</th>        
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {

                $consultargoles1 = "SELECT count(hp.Equipo) as goles from HechosPartido as hp 
                LEFT JOIN Partido as p on p.id = hp.idPartido
                where hp.Equipo = '".$listado['EquipoLocal']."' and p.fechaPartido = '$fecha' and hp.idHecho = 1 and p.idEquipoLocal =  ".$listado['idEquipo1']." and p.idEquipoVisitante = ".$listado['idEquipo2']."";
                $resultado2 = mysqli_query($conectar, $consultargoles1);
                $row = $resultado2->fetch_assoc();
                $goles1 = $row['goles'];
        

                $consultargoles2 = "SELECT count(hp.Equipo) as goles from HechosPartido as hp 
                LEFT JOIN Partido as p on p.id = hp.idPartido
                where hp.Equipo = '".$listado['EquipoVisitante']."' and p.fechaPartido = '$fecha' and hp.idHecho = 1 and p.idEquipoLocal =  ".$listado['idEquipo1']." and p.idEquipoVisitante = ".$listado['idEquipo2']."";
                $resultado3 = mysqli_query($conectar, $consultargoles2);
                $row = $resultado3->fetch_assoc();
                $goles2 = $row['goles'];

                $EquipoLocal = utf8_encode($listado['EquipoLocal']); 
                $EquipoVisitante = utf8_encode($listado['EquipoVisitante']); 
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaPartido'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['EquipoLocal'] . "</td>";
                $tabla .= "<td data-title=''>".$goles1." - ".$goles2."</td>";
                $tabla .= "<td data-title=''>" . $listado['EquipoVisitante'] . "</td>";
                $tabla .= "<td data-title=''><button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ModalVerPartidos(".$listado['id'].")'>Ver Partido</button>";  
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "detalleEquipo1"){

    $idPartido = $_POST["id"];
    $nombreEquipo= $_POST["nombreEquipo"];

    $consultar = "SELECT p.id, h.nombreHecho,concat(j.nombre, ' ',j.apellidos) as jugador,hp.Equipo from Partido as p
    left join HechosPartido as hp on hp.idPartido = p.id
    LEFT JOIN Hechos as h on h.id = hp.idHecho
    LEFT join Jugador as j on j.id =  hp.idJugador
    where p.id = $idPartido and hp.Equipo = '$nombreEquipo'";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jugador</th>
                            <th>Hecho</th>      
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
              
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['jugador'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreHecho'] . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "detalleEquipo2"){

    $idPartido = $_POST["id"];
    $nombreEquipo= $_POST["nombreEquipo"];

    $consultar = "SELECT p.id, h.nombreHecho,concat(j.nombre, ' ',j.apellidos) as jugador,hp.Equipo from Partido as p
    left join HechosPartido as hp on hp.idPartido = p.id
    LEFT JOIN Hechos as h on h.id = hp.idHecho
    LEFT join Jugador as j on j.id =  hp.idJugador
    where p.id = $idPartido  and hp.Equipo = '$nombreEquipo'";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example3" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jugador</th>
                            <th>Hecho</th>      
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
              
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['jugador'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreHecho'] . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}
<?php

session_start();
include("conexion.php");

$tipo = $_GET["op"];


if($tipo == "ListaGoleadores"){

    $registrarInventario = "SELECT j.nombre,j.apellidos,hp.Equipo,c.nombre as campeonato,count(h.nombreHecho) as goles FROM HechosPartido as hp
                            LEFT join Hechos as h on h.id = hp.idHecho
                            LEFT JOIN Jugador as j on j.id = hp.idJugador
                            LEFT JOIN Partido as p on p.id = hp.idPartido
                            LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                            where h.nombreHecho = 'Gol' and c.estado = 'En Curso'
                            GROUP BY j.nombre,j.apellidos,hp.Equipo,c.nombre
                            ORDER by goles desc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jugador</th>
                            <th>Equipo Actual</th>
                            <th>Total Goles</th>
                            <th>Campeonato</th>
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $Equipo= utf8_encode($listado['Equipo']);
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . " " . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>".$Equipo."</td>";                                      
                $tabla .= "<td data-title=''>" . $listado['goles'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";  
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "ListaTarjetasAmarillas"){

    $registrarInventario = "SELECT j.nombre,j.apellidos,hp.Equipo,c.nombre as campeonato,count(h.nombreHecho) as Amarillas FROM HechosPartido as hp
                            LEFT join Hechos as h on h.id = hp.idHecho
                            LEFT JOIN Jugador as j on j.id = hp.idJugador
                            LEFT JOIN Partido as p on p.id = hp.idPartido
                            LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                            where h.nombreHecho = 'Tarjeta Amarilla' and c.estado = 'En Curso'
                            GROUP BY j.nombre,j.apellidos,hp.Equipo,c.nombre
                            ORDER by Amarillas desc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jugador</th>
                            <th>Equipo Actual</th>
                            <th>Total Amarillas</th>
                            <th>Campeonato</th>
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $Equipo= utf8_encode($listado['Equipo']);
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . " " . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>".$Equipo."</td>";                                      
                $tabla .= "<td data-title=''>" . $listado['Amarillas'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";  
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "ListaTarjetasRoja"){

    $registrarInventario = "SELECT j.nombre,j.apellidos,hp.Equipo,c.nombre as campeonato,count(h.nombreHecho) as Roja FROM HechosPartido as hp
                            LEFT join Hechos as h on h.id = hp.idHecho
                            LEFT JOIN Jugador as j on j.id = hp.idJugador
                            LEFT JOIN Partido as p on p.id = hp.idPartido
                            LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                            where h.nombreHecho = 'Tarjeta Roja' and c.estado = 'En Curso'
                            GROUP BY j.nombre,j.apellidos,hp.Equipo,c.nombre
                            ORDER by Roja desc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example3" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jugador</th>
                            <th>Equipo Actual</th>
                            <th>Total Rojas</th>
                            <th>Campeonato</th>
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $Equipo= utf8_encode($listado['Equipo']);
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . " " . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>".$Equipo."</td>";                                      
                $tabla .= "<td data-title=''>" . $listado['Roja'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";  
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "FiltrarGoleadores"){

    $idEquipo = $_POST["idEquipo"];
    $nombreEquipo = $_POST["nombreEquipo"];
    $idCampeonato = $_POST["idCampeonato"];
    $nombreJugador = $_POST["nombreJugador"];
    $consulta = "";

    if($idEquipo != null && $idCampeonato != null && $nombreJugador != ""){
        //los 3 filtros seleccionados
        $consulta = "and hp.Equipo = '$nombreEquipo' and c.id = $idCampeonato and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
    }else{
        if($idEquipo == null && $idCampeonato != null && $nombreJugador != ""){
            //2 filtros seleccionados idCampeonato y nombreJugador
            $consulta = "and c.id = $idCampeonato and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
        }else{
            if($idEquipo != null && $idCampeonato != null && $nombreJugador == ""){
                //2 filtros seleccionados idCampeonato y idEquipoDestino
                $consulta = "and hp.Equipo = '$nombreEquipo' and c.id = $idCampeonato";
            }else{
                if($idEquipo != null && $idCampeonato == null && $nombreJugador != ""){
                    //2 filtros seleccionados nombreJugador y idEquipoDestino
                    $consulta = "and hp.Equipo = '$nombreEquipo' and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
                }else{
                    if($idEquipo != null && $idCampeonato == null && $nombreJugador == ""){
                        //1 filtros seleccionado idEquipoDestino
                        $consulta = "and hp.Equipo = '$nombreEquipo'";
                    }else{
                        if($idEquipo == null && $idCampeonato != null && $nombreJugador == ""){
                            //1 filtros seleccionado idCampeonato
                            $consulta = "and c.id = $idCampeonato";
                        }else{
                            if($idEquipo == null && $idCampeonato == null && $nombreJugador != ""){
                                //1 filtros seleccionado nombreJugador
                                $consulta = "and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
                            }
                        }
                    }
                }
            }
        }
    }

    $registrarInventario = "SELECT j.nombre,j.apellidos,hp.Equipo,c.nombre as campeonato,count(h.nombreHecho) as goles FROM HechosPartido as hp
                            LEFT join Hechos as h on h.id = hp.idHecho
                            LEFT JOIN Jugador as j on j.id = hp.idJugador
                            LEFT JOIN Partido as p on p.id = hp.idPartido
                            LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                            where h.nombreHecho = 'Gol' $consulta
                            GROUP BY j.nombre,j.apellidos,hp.Equipo,c.nombre
                            ORDER by goles desc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jugador</th>
                            <th>Equipo Actual</th>
                            <th>Total Goles</th>
                            <th>Campeonato</th>
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $Equipo= utf8_encode($listado['Equipo']);
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . " " . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>".$Equipo."</td>";                                      
                $tabla .= "<td data-title=''>" . $listado['goles'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";  
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "FiltrarTarjetasAmarillas"){

    $idEquipo = $_POST["idEquipo"];
    $nombreEquipo = $_POST["nombreEquipo"];
    $idCampeonato = $_POST["idCampeonato"];
    $nombreJugador = $_POST["nombreJugador"];
    $consulta = "";

    if($idEquipo != null && $idCampeonato != null && $nombreJugador != ""){
        //los 3 filtros seleccionados
        $consulta = "and hp.Equipo = '$nombreEquipo' and c.id = $idCampeonato and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
    }else{
        if($idEquipo == null && $idCampeonato != null && $nombreJugador != ""){
            //2 filtros seleccionados idCampeonato y nombreJugador
            $consulta = "and c.id = $idCampeonato and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
        }else{
            if($idEquipo != null && $idCampeonato != null && $nombreJugador == ""){
                //2 filtros seleccionados idCampeonato y idEquipoDestino
                $consulta = "and hp.Equipo = '$nombreEquipo' and c.id = $idCampeonato";
            }else{
                if($idEquipo != null && $idCampeonato == null && $nombreJugador != ""){
                    //2 filtros seleccionados nombreJugador y idEquipoDestino
                    $consulta = "and hp.Equipo = '$nombreEquipo' and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
                }else{
                    if($idEquipo != null && $idCampeonato == null && $nombreJugador == ""){
                        //1 filtros seleccionado idEquipoDestino
                        $consulta = "and hp.Equipo = '$nombreEquipo'";
                    }else{
                        if($idEquipo == null && $idCampeonato != null && $nombreJugador == ""){
                            //1 filtros seleccionado idCampeonato
                            $consulta = "and c.id = $idCampeonato";
                        }else{
                            if($idEquipo == null && $idCampeonato == null && $nombreJugador != ""){
                                //1 filtros seleccionado nombreJugador
                                $consulta = "and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
                            }
                        }
                    }
                }
            }
        }
    }

    $registrarInventario = "SELECT j.nombre,j.apellidos,hp.Equipo,c.nombre as campeonato,count(h.nombreHecho) as Amarillas FROM HechosPartido as hp
                            LEFT join Hechos as h on h.id = hp.idHecho
                            LEFT JOIN Jugador as j on j.id = hp.idJugador
                            LEFT JOIN Partido as p on p.id = hp.idPartido
                            LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                            where h.nombreHecho = 'Tarjeta Amarilla' $consulta
                            GROUP BY j.nombre,j.apellidos,hp.Equipo,c.nombre
                            ORDER by Amarillas desc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jugador</th>
                            <th>Equipo Actual</th>
                            <th>Total Amarillas</th>
                            <th>Campeonato</th>
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $Equipo= utf8_encode($listado['Equipo']);
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . " " . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>".$Equipo."</td>";                                      
                $tabla .= "<td data-title=''>" . $listado['Amarillas'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";  
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "FiltrarTarjetasRojas"){

    $idEquipo = $_POST["idEquipo"];
    $nombreEquipo = $_POST["nombreEquipo"];
    $idCampeonato = $_POST["idCampeonato"];
    $nombreJugador = $_POST["nombreJugador"];
    $consulta = "";

    if($idEquipo != null && $idCampeonato != null && $nombreJugador != ""){
        //los 3 filtros seleccionados
        $consulta = "and hp.Equipo = '$nombreEquipo' and c.id = $idCampeonato and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
    }else{
        if($idEquipo == null && $idCampeonato != null && $nombreJugador != ""){
            //2 filtros seleccionados idCampeonato y nombreJugador
            $consulta = "and c.id = $idCampeonato and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
        }else{
            if($idEquipo != null && $idCampeonato != null && $nombreJugador == ""){
                //2 filtros seleccionados idCampeonato y idEquipoDestino
                $consulta = "and hp.Equipo = '$nombreEquipo' and c.id = $idCampeonato";
            }else{
                if($idEquipo != null && $idCampeonato == null && $nombreJugador != ""){
                    //2 filtros seleccionados nombreJugador y idEquipoDestino
                    $consulta = "and hp.Equipo = '$nombreEquipo' and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
                }else{
                    if($idEquipo != null && $idCampeonato == null && $nombreJugador == ""){
                        //1 filtros seleccionado idEquipoDestino
                        $consulta = "and hp.Equipo = '$nombreEquipo'";
                    }else{
                        if($idEquipo == null && $idCampeonato != null && $nombreJugador == ""){
                            //1 filtros seleccionado idCampeonato
                            $consulta = "and c.id = $idCampeonato";
                        }else{
                            if($idEquipo == null && $idCampeonato == null && $nombreJugador != ""){
                                //1 filtros seleccionado nombreJugador
                                $consulta = "and j.nombre like '%$nombreJugador%' or j.apellidos like '%$nombreJugador%'";
                            }
                        }
                    }
                }
            }
        }
    }

    $registrarInventario = "SELECT j.nombre,j.apellidos,hp.Equipo,c.nombre as campeonato,count(h.nombreHecho) as Roja FROM HechosPartido as hp
                            LEFT join Hechos as h on h.id = hp.idHecho
                            LEFT JOIN Jugador as j on j.id = hp.idJugador
                            LEFT JOIN Partido as p on p.id = hp.idPartido
                            LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                            where h.nombreHecho = 'Tarjeta Roja' $consulta
                            GROUP BY j.nombre,j.apellidos,hp.Equipo,c.nombre
                            ORDER by Roja desc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example3" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jugador</th>
                            <th>Equipo Actual</th>
                            <th>Total Rojas</th>
                            <th>Campeonato</th>
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $Equipo= utf8_encode($listado['Equipo']);
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . " " . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>".$Equipo."</td>";                                      
                $tabla .= "<td data-title=''>" . $listado['Roja'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";  
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}



if($tipo == "FiltrarCampeones"){

    $idEquipo = $_POST["idEquipo"];
 
    $idCampeonato = $_POST["idCampeonato"];
  
    $consulta = "";

    if($idEquipo != null && $idEquipo != 0 && $idEquipo != ""){     
        $consulta .= " and ec.idEquipo = $idEquipo";
    }
    
    if($idCampeonato != null && $idCampeonato != 0 && $idCampeonato != ""){     
        $consulta .= " and ec.idCampeonato = $idCampeonato";
    }

    if($consulta == ""){
        $consulta = " and c.estado = 'En Curso'";
    }


    $registrarInventario = "SELECT ec.id,e.id as idEquipo, e.nombreEquipo,c.id as idCampeonato, c.nombre as campeonato,ec.Posicion FROM EquipoCampeon ec
    left JOIN Equipo e on e.id = ec.idEquipo
    LEFT JOIN Campeonato c on c.id = ec.idCampeonato
    where 1=1 $consulta order by ec.id asc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example5" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>                          
                            <th>Equipo</th>  
                            <th>Posición</th>                       
                            <th>Campeonato</th>
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $Equipo= utf8_encode($listado['Equipo']);
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreEquipo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['Posicion'] . "</td>";                                                    
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";  
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}
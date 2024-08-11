<?php

session_start();
include("../conexion/conexion.php");
include("../conexion/parametros.php");
$parametro = new parametros();
$tipo = $_GET["op"];
$idRol = $_SESSION['idRol'];   


if($tipo == "DetallePartidosEquipos2"){

    $idEquipo = $_POST["idEquipo"];
    $modo = $_POST["modo"];
    $nombreEquipo = $_POST["nombreEquipo"];    

    if($modo == "" || $modo == null){      

        $resultado1 = $parametro->DetallePartidosEquipos($idEquipo);          
    }
    else{
        $faseActual="";
        if($nombreClasificacion == 'Octavos'){
            $faseActual = '8vo de Final';
        }
        else{
            if($nombreClasificacion == 'Cuartos'){
                $faseActual = '4to de Final';
            }
            else{
                if($nombreClasificacion == 'Semifinal'){
                    $faseActual = 'Semifinal';
                }
                else{
                    if($nombreClasificacion == 'Final'){
                        $faseActual = 'Final';
                    }
                    else{
                        if($nombreClasificacion == 'Final2'){
                            $faseActual = '3er y 4to Lugar';
                        }
                    }
                }
            }
        }
        $consultar="SELECT * from Campeonato as c
        left join (select p.idCampeonato,p.fechaPartido,e1.id as idEquipo1,e1.nombreEquipo as EquipoLocal,e2.id as idEquipo2, e2.nombreEquipo as EquipoVisitante,p.Modo from Partido as p
            LEFT JOIN Equipo as e1 on e1.id = p.idEquipoLocal
            LEFT JOIN Equipo as e2 on e2.id = p.idEquipoVisitante
            WHERE e1.nombreEquipo = '$nombreEquipo' OR e2.nombreEquipo = '$nombreEquipo'
            GROUP by p.idCampeonato,p.fechaPartido,e1.id,e1.nombreEquipo,e2.id,e2.nombreEquipo,p.Modo
            order by p.fechaPartido ASC) as pa on pa.idCampeonato = c.id 
            where c.estado = 'En Curso' and pa.Modo='$faseActual'";
    }
    
    $tabla = "";
    $tabla .= '    <div class="row text-center">';
    if ($resultado1->RowCount() > 0) {
        while (!$resultado1->EndOfSeek()) {
            $listado = $resultado1->Row();
            $colorCard = '';
            $Observaciones = $parametro->verificarObservaciones($listado->idPartido,$listado->fechaPartido);
            $totalObservaciones = $Observaciones->RowCount();
            $equipoGanadorWalkover = $parametro->verificarEquipoGanadorWalkover($idEquipo,$listado->fechaPartido);
            if($totalObservaciones > 0){
                $colorCard = "style='border-top-color: red;'";
            }
            if($equipoGanadorWalkover > 0){
                $colorCard = "style='border-top-color: yellow;'";
            }
            $tabla .= '        <div class="col-md-4">';
            $tabla .= '            <div class="card info-box shadow-lg" '.$colorCard.'>';
            $tabla .= '                <div class="card-body">';  
                
                    $goles1 = $parametro->obtenerGolesEquipo($listado->EquipoLocal,$listado->fechaPartido,$listado->idEquipo1,$listado->idEquipo2);             
                    $goles2 = $parametro->obtenerGolesEquipo($listado->EquipoVisitante,$listado->fechaPartido,$listado->idEquipo1,$listado->idEquipo2);
                        $tabla .= '        <div class="row">';
                        $tabla .= '            <div class="col-md-12 text-center mb-3"><b>Fecha Partido: </b> '.date('d-m-Y',strtotime($listado->fechaPartido)).'</div><br>';
                        $tabla .= '            <div class="col-md-5 col-5">';
                        $estilo='';
                        if($listado->EquipoLocal == $nombreEquipo){
                            $estilo = "style='font-weight: bold;'";
                        }
                        $tabla .= '                <img src="../img/logoEquipo.png" alt="Sin Logo" width="100%"><br><span '.$estilo.'>'.$listado->EquipoLocal.'</span>';
                        $tabla .= '            </div>';
                        $tabla .= '            <div class="col-md-2  col-2 text-center pt-4">vs<br>'.$goles1.'-'.$goles2.'</div>';
                        $tabla .= '            <div class="col-md-5  col-5">';
                        $estilo2='';
                        if($listado->EquipoVisitante == $nombreEquipo){
                            $estilo2 = "style='font-weight: bold;'";
                        }
                        $tabla .= '                <img src="../img/logoEquipo.png" alt="Sin Logo"  width="100%"><br><span '.$estilo2.'>'.$listado->EquipoVisitante.'';
                        $tabla .= '            </div>';
                        if($totalObservaciones > 0){
                            $row = $Observaciones->Row();
                            if($row->estadoObservacion == 'Pendiente'){
                                $tabla .= "            <div class='col-md-12 text-center pt-2'> <div class='alert alert-warning' role='alert'>Observación Pendiente realizada a ".$row->equipoObservado."</div> </div>";
                            }
                            else{
                                $tabla .= "            <div class='col-md-12 text-center pt-2'><button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='verAprobacion(" . chr(34) . $row->castigo . chr(34) . "," . chr(34) . $row->equipoObservado . chr(34) . "," . chr(34) . $row->observacion . chr(34) . "," . chr(34) . $row->estadoObservacion . chr(34) . ")'>Ver Detalle Observación</div>";
                            }
                            
                        }
                        $tabla .= '        </div>';
            
            $tabla .= '                </div>';
            $tabla .= '            </div>';
            $tabla .= '        </div>';
        }
    }

    $tabla .= '    </div>';

        echo  $tabla;   
}

if($tipo == "DetallePartidosEquipos"){

    $idEquipo = $_POST["idEquipo"];
    $modo = $_POST["modo"];
    $nombreEquipo = $_POST["nombreEquipo"];    

    if($modo == "" || $modo == null){      

        $resultado1 = $parametro->DetallePartidosEquipos($idEquipo);          
    }
    else{
        $faseActual="";
        if($nombreClasificacion == 'Octavos'){
            $faseActual = '8vo de Final';
        }
        else{
            if($nombreClasificacion == 'Cuartos'){
                $faseActual = '4to de Final';
            }
            else{
                if($nombreClasificacion == 'Semifinal'){
                    $faseActual = 'Semifinal';
                }
                else{
                    if($nombreClasificacion == 'Final'){
                        $faseActual = 'Final';
                    }
                    else{
                        if($nombreClasificacion == 'Final2'){
                            $faseActual = '3er y 4to Lugar';
                        }
                    }
                }
            }
        }
        $consultar="SELECT * from Campeonato as c
        left join (select p.idCampeonato,p.fechaPartido,e1.id as idEquipo1,e1.nombreEquipo as EquipoLocal,e2.id as idEquipo2, e2.nombreEquipo as EquipoVisitante,p.Modo from Partido as p
            LEFT JOIN Equipo as e1 on e1.id = p.idEquipoLocal
            LEFT JOIN Equipo as e2 on e2.id = p.idEquipoVisitante
            WHERE e1.nombreEquipo = '$nombreEquipo' OR e2.nombreEquipo = '$nombreEquipo'
            GROUP by p.idCampeonato,p.fechaPartido,e1.id,e1.nombreEquipo,e2.id,e2.nombreEquipo,p.Modo
            order by p.fechaPartido ASC) as pa on pa.idCampeonato = c.id 
            where c.estado = 'En Curso' and pa.Modo='$faseActual'";
    }
   
    //$resultado1 = mysqli_query($conectar, $consultar);
        $tabla = "";
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha Partido</th>
                            <th>Equipo Local</th>
                            <th width="50px;">Resultados</th>
                            <th>Equipo Visitante</th>        
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1->RowCount() > 0) {
            while (!$resultado1->EndOfSeek()) {
                $listado = $resultado1->Row();
                $goles1 = $parametro->obtenerGolesEquipo($listado->EquipoLocal,$listado->fechaPartido,$listado->idEquipo1,$listado->idEquipo2);             
                $goles2 = $parametro->obtenerGolesEquipo($listado->EquipoVisitante,$listado->fechaPartido,$listado->idEquipo1,$listado->idEquipo2);
                              
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado->fechaPartido . "</td>";
                if($listado->EquipoLocal == $nombreEquipo){
                    $tabla .= "<td data-title=''><b>" . $listado->EquipoLocal . "</b></td>";
                }
                else{
                    $tabla .= "<td data-title=''>" . $listado->EquipoLocal . "</td>";
                }
                
                $tabla .= "<td data-title=''>".$goles1." - ".$goles2."</td>";
                if($listado->EquipoVisitante == $nombreEquipo){
                    $tabla .= "<td data-title=''><b>" . $listado->EquipoVisitante . "</b></td>";
                }
                else{
                    $tabla .= "<td data-title=''>" . $listado->EquipoVisitante . "</td>";
                }
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}

if($tipo == "DatosEquipoPartidoOctavos"){

    $idClasificacion = $_POST["id"];

    $datos = array();
    $Consultar = "SELECT idEquipo1,idEquipo2,nombreClasificacion FROM Clasificacion where id = $idClasificacion";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    } 
}


if($tipo == "SiguienteFaseOctavos"){

     //obtengo el id del campeonato
     $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
     $resultado2 = mysqli_query($conectar, $consultarEquipo);
     $row = $resultado2->fetch_assoc();
     $idCampeonato = $row['id'];

    //tabla del grupo 1 del puntero al colero
    $Consultar = "SELECT idEquipo,puntos,sum(golFavor - golContra) as diferencia FROM TablaPosicion as tp
                    LEFT JOIN Campeonato as c on c.id = tp.idCampeonato
                    WHERE c.estado = 'En Curso' and grupo = 1
                    GROUP by idEquipo,puntos
                    order by puntos DESC, diferencia DESC limit 8";
    $resultado = mysqli_query($conectar, $Consultar);
    

    //tabla del grupo 2 del colero al puntero 
    $Consultar1 = "SELECT idEquipo,puntos,diferencia from (select idEquipo,puntos,sum(golFavor - golContra) as diferencia FROM TablaPosicion as tp
                    LEFT JOIN Campeonato as c on c.id = tp.idCampeonato
                    WHERE c.estado = 'En Curso' and grupo = 2
                    GROUP by idEquipo,puntos
                    order by puntos DESC, diferencia DESC
                    limit 8) as grupo2
                    order by puntos ASC, diferencia ASC";
    $resultado1 = mysqli_query($conectar, $Consultar1);
    
    $cont= 0;

     while ($cont<8){
        $listado = mysqli_fetch_array($resultado);
         $listado1 = mysqli_fetch_array($resultado1);
      
       //  $newArray = array_reverse($listado1,true);

         $Consultar = "INSERT INTO Clasificacion(nombreClasificacion,idEquipo1,idEquipo2,idCampeoanto) VALUES('Octavos',".$listado['idEquipo'].",".$listado1['idEquipo'].",$idCampeonato)";
         $resultado3 = mysqli_query($conectar, $Consultar);   

         $cont++;
     }

     if($resultado3){
         echo 1;
     }
     else{
         echo $Consultar;
    }

}

if($tipo == "SiguienteFaseCuartos"){

      //obtengo el id del campeonato
      $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
      $resultado2 = mysqli_query($conectar, $consultarEquipo);
      $row = $resultado2->fetch_assoc();
      $idCampeonato = $row['id'];


   //lista de los equipos ganador de la ronde de octavos
   $Consultar1 = "SELECT * FROM Clasificacion as cl
                    left join Campeonato as c on c.id = cl.idCampeoanto
                    where c.estado = 'En Curso' and cl.nombreClasificacion = 'Octavos' order by cl.id";
   $resultado1 = mysqli_query($conectar, $Consultar1);
   $resultado3 ="";
   $idEquipo = "";

    while ($listado = mysqli_fetch_array($resultado1)){

        if($idEquipo != ""){
            $Consultar = "INSERT INTO Clasificacion(nombreClasificacion,idEquipo1,idEquipo2,idCampeoanto) VALUES('Cuartos',".$idEquipo.",".$listado['idEquipoGanador'].",$idCampeonato)";
            $resultado3 = mysqli_query($conectar, $Consultar); 
            $idEquipo = "";  
        }
        else{
            $idEquipo = $listado['idEquipoGanador'];
        }  
    }

    if($resultado3){
        echo 1;
    }
    else{
        echo 2;
   }

}


if($tipo == "SiguienteFaseSemifinal"){

    //obtengo el id del campeonato
    $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
    $resultado2 = mysqli_query($conectar, $consultarEquipo);
    $row = $resultado2->fetch_assoc();
    $idCampeonato = $row['id'];


 //lista de los equipos ganador de la ronda de cuartos
 $Consultar1 = "SELECT * FROM Clasificacion as cl
                  left join Campeonato as c on c.id = cl.idCampeoanto
                  where c.estado = 'En Curso' and cl.nombreClasificacion = 'Cuartos' order by cl.id";
 $resultado1 = mysqli_query($conectar, $Consultar1);
 $resultado3 ="";
 $idEquipo = "";

  while ($listado = mysqli_fetch_array($resultado1)){

      if($idEquipo != ""){
          $Consultar = "INSERT INTO Clasificacion(nombreClasificacion,idEquipo1,idEquipo2,idCampeoanto) VALUES('Semifinal',".$idEquipo.",".$listado['idEquipoGanador'].",$idCampeonato)";
          $resultado3 = mysqli_query($conectar, $Consultar); 
          $idEquipo = "";  
      }
      else{
          $idEquipo = $listado['idEquipoGanador'];
      }  
  }

  if($resultado3){
      echo 1;
  }
  else{
      echo 2;
 }

}

if($tipo == "SiguienteFaseFinal"){

    //obtengo el id del campeonato
    $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
    $resultado2 = mysqli_query($conectar, $consultarEquipo);
    $row = $resultado2->fetch_assoc();
    $idCampeonato = $row['id'];


    //lista de los equipos ganadores de la ronda de Semifinal
    $Consultar1 = "SELECT * FROM Clasificacion as cl
                    left join Campeonato as c on c.id = cl.idCampeoanto
                    where c.estado = 'En Curso' and cl.nombreClasificacion = 'Semifinal' order by cl.id";
    $resultado1 = mysqli_query($conectar, $Consultar1);
    $resultado3 ="";
    $idEquipo = "";

    while ($listado = mysqli_fetch_array($resultado1)){

      if($idEquipo != ""){
          $Consultar = "INSERT INTO Clasificacion(nombreClasificacion,idEquipo1,idEquipo2,idCampeoanto) VALUES('Final',".$idEquipo.",".$listado['idEquipoGanador'].",$idCampeonato)";
          $resultado3 = mysqli_query($conectar, $Consultar); 
          $idEquipo = "";  
         
        
      }
      else{
          $idEquipo = $listado['idEquipoGanador'];
        
      }  
    }

      //lista de los equipos perdedores de la ronda de Semifinal
      $Consultar1 = "SELECT * FROM Clasificacion as cl
      left join Campeonato as c on c.id = cl.idCampeoanto
      where c.estado = 'En Curso' and cl.nombreClasificacion = 'Semifinal' order by cl.id";
      $resultado = mysqli_query($conectar, $Consultar1);

      $idEquipo3er="";
      $idEquipo4to="";
    while ($listado1 = mysqli_fetch_array($resultado)){

        if($idEquipo != ""){
            $Consultar = "INSERT INTO Clasificacion(nombreClasificacion,idEquipo1,idEquipo2,idCampeoanto) VALUES('Final2',".$idEquipo.",".$listado1['idEquipoPerdedor'].",$idCampeonato)";
            $resultado3 = mysqli_query($conectar, $Consultar); 
            $idEquipo = "";
           
        }
        else{
            $idEquipo = $listado1['idEquipoPerdedor'];
          
        }  
    }

    if($resultado3){
        echo 1;
    }
    else{
        echo 2;
    }

}


if($tipo == "terminarTorneo"){
    $array_equipo = explode(",",$_POST["equipo"]);
    $array_posicion = ['Campeon','Subcampeon'];

    $Consultar = "SELECT * FROM Campeonato where estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $idCampeonato = $row['id'];

    $Consultar = "SELECT * FROM EquipoCampeon where idCampeonato = $idCampeonato";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $idCampeon = $row['id'];

    if($idCampeon != ""){
        echo 3;
        return false;
    }

    $resultado1 ="";
    for ($j=0; $j < 2; $j++){
        //obtengo el id del equipo
        $consultarEquipo = "SELECT id FROM Equipo where nombreEquipo = '$array_equipo[$j]'";
        $resultado2 = mysqli_query($conectar, $consultarEquipo);
        $row = $resultado2->fetch_assoc();
        $idEquipo = $row['id'];

        $registrar = "INSERT INTO EquipoCampeon VALUES(null,$idEquipo,$idCampeonato,'$array_posicion[$j]')";
        $resultado1 = mysqli_query($conectar, $registrar);
    }

    if($resultado1){
        echo 1;
    }
    else{
        echo 2;
    }

}


if($tipo == "ValidarBotonAñadir1"){
    

      //consultamos la cantidad de equipos inscritos
      $consultarEquipo = "SELECT * FROM Inscripcion as i left join Campeonato as c on c.id = i.idCampeonato where c.estado = 'En Curso'";
      $resultado2 = mysqli_query($conectar, $consultarEquipo);
      $CantInscritos = $resultado2->num_rows;
        $operacion = $CantInscritos /2;

        //consultamos la cantidad de equipos inscritos
      $consultar = "SELECT * FROM TablaPosicion as tp left join Campeonato as c on c.id = tp.idCampeonato where c.estado = 'En Curso' and tp.grupo = 1";
      $resultado = mysqli_query($conectar, $consultar);
      $CantEquiposGrupo1 = $resultado->num_rows;

     if($CantEquiposGrupo1 == $operacion){
        echo 1;
     }
     else{
        echo 2;
     }
}

if($tipo == "ValidarBotonAñadir2"){
    

    //consultamos la cantidad de equipos inscritos
    $consultarEquipo = "SELECT * FROM Inscripcion as i left join Campeonato as c on c.id = i.idCampeonato where c.estado = 'En Curso'";
    $resultado2 = mysqli_query($conectar, $consultarEquipo);
    $CantInscritos = $resultado2->num_rows;
      $operacion = $CantInscritos /2;

      //consultamos la cantidad de equipos inscritos
    $consultar = "SELECT * FROM TablaPosicion as tp left join Campeonato as c on c.id = tp.idCampeonato where c.estado = 'En Curso' and tp.grupo = 2";
    $resultado = mysqli_query($conectar, $consultar);
    $CantEquiposGrupo1 = $resultado->num_rows;

   if($CantEquiposGrupo1 == $operacion){
      echo 1;
   }
   else{
      echo 2;
   }
}




if($tipo == "AñadirEquipoGrupo"){
    
    $numeroGrupo = $_POST["nombreGrupo"];
    $idEquipo = $_POST["idEquipo"];

      //obtengo el id del campeonato
      $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
      $resultado2 = mysqli_query($conectar, $consultarEquipo);
      $row = $resultado2->fetch_assoc();
      $idCampeonato = $row['id'];

     $registrar = "UPDATE TablaPosicion SET grupo = $numeroGrupo where idEquipo = $idEquipo and idCampeonato = $idCampeonato";
     $resultado = mysqli_query($conectar, $registrar);

     if($resultado){
        echo 1;
     }
     else{
        echo 2;
     }
}

if($tipo == "ConfirmarSiguienteFase"){

    //validamos si ya se jugo los octavos
    $validar = "SELECT * FROM Clasificacion as cl
    left join Campeonato as c on c.id = cl.idCampeoanto
    where c.estado = 'En Curso' and cl.nombreClasificacion = 'Octavos'";
    $resultado = mysqli_query($conectar, $validar);   
    $num_rows= mysqli_num_rows($resultado);

    if($num_rows > 0){

        //validamos si se jugaron todos los partidos de octavos
        $validar = "SELECT * FROM Clasificacion as cl
        left join Campeonato as c on c.id = cl.idCampeoanto
        where c.estado = 'En Curso' and cl.nombreClasificacion = 'Octavos'  and idEquipoGanador is null";
        $resultado = mysqli_query($conectar, $validar);   
        $num_rows= mysqli_num_rows($resultado);

        if($num_rows == 0){

             //validamos si ya se jugo los cuartos
            $validar = "SELECT * FROM Clasificacion as cl
            left join Campeonato as c on c.id = cl.idCampeoanto
            where c.estado = 'En Curso' and cl.nombreClasificacion = 'Cuartos'";
            $resultado = mysqli_query($conectar, $validar);   
            $num_rows= mysqli_num_rows($resultado);

            if($num_rows > 0){
                 //validamos si se jugaron todos los partidos de cuartos
                $validar = "SELECT * FROM Clasificacion as cl
                left join Campeonato as c on c.id = cl.idCampeoanto
                where c.estado = 'En Curso' and cl.nombreClasificacion = 'Cuartos'  and idEquipoGanador is null";
                $resultado = mysqli_query($conectar, $validar);   
                $num_rows= mysqli_num_rows($resultado);

                if($num_rows == 0){
                      //validamos si ya se jugo la semifinal
                    $validar = "SELECT * FROM Clasificacion as cl
                    left join Campeonato as c on c.id = cl.idCampeoanto
                    where c.estado = 'En Curso' and cl.nombreClasificacion = 'Semifinal'";
                    $resultado = mysqli_query($conectar, $validar);   
                    $num_rows= mysqli_num_rows($resultado);

                    if($num_rows > 0){
                          //validamos si se jugaron todos los partidos de semifinal
                        $validar = "SELECT * FROM Clasificacion as cl
                        left join Campeonato as c on c.id = cl.idCampeoanto
                        where c.estado = 'En Curso' and cl.nombreClasificacion = 'Semifinal'  and idEquipoGanador is null";
                        $resultado = mysqli_query($conectar, $validar);   
                        $num_rows= mysqli_num_rows($resultado);

                        if($num_rows == 0){
                               //validamos si ya se jugo la final
                                $validar = "SELECT * FROM Clasificacion as cl
                                left join Campeonato as c on c.id = cl.idCampeoanto
                                where c.estado = 'En Curso' and cl.nombreClasificacion = 'Final'";
                                $resultado = mysqli_query($conectar, $validar);   
                                $num_rows= mysqli_num_rows($resultado);

                                if($num_rows > 0){
                                    echo 'Torneo Terminado';
                                }
                                else{
                                    echo 'Final';
                                }                       
                        }
                        else{
                            echo 'FaltanPartidos';
                        }
                    }
                    else{
                        echo 'Semifinal';
                    }
                }
                else{
                    echo 'FaltanPartidos';
                }
            }
            else{
                echo 'Cuartos';
            }
        }
        else{
            echo 'FaltanPartidos';
        }
       
    }
    else{
        echo 'Octavos';
    }
}


if($tipo == "ValidarSiguienteFase"){

     $registrar = "SELECT * FROM Clasificacion as cl
                    left join Campeonato as c on c.id = cl.idCampeoanto
                    where c.estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $registrar);
    $num_rows= mysqli_num_rows($resultado);
     if($resultado){
         if($num_rows == 0){
            echo 1;
         }else{
            $registrar = "SELECT cl.id,cl.nombreClasificacion,c.estado FROM Clasificacion as cl
            left join Campeonato as c on c.id = cl.idCampeoanto
            where c.estado = 'En Curso' order by cl.id desc";
            $resultado2 = mysqli_query($conectar, $registrar);
            $row = $resultado2->fetch_assoc();
            $nombreClasificacion = $row['nombreClasificacion'];
            echo $nombreClasificacion;
         }
            
     }
     else{
        echo 3;
     }
}

if($tipo == "RegistrarEquipoGanador"){

    $idEquipoGanador = $_POST["idEquipoGanador"];
    $idClasificacion = $_POST["idClasificacion"];

      //obtengo el id del campeonato
      $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
      $resultado2 = mysqli_query($conectar, $consultarEquipo);
      $row = $resultado2->fetch_assoc();
      $idCampeonato = $row['id'];


      //obtengo el id del equipo Perdedor
      $consultarEquipo = "SELECT idEquipo1,idEquipo2 FROM Clasificacion where id = $idClasificacion";
      $resultado2 = mysqli_query($conectar, $consultarEquipo);
      $row = $resultado2->fetch_assoc();
      $idEquipoPerdedor = $row['idEquipo1'];
      
      if($idEquipoGanador == $idEquipoPerdedor){
        $idEquipoPerdedor = $row['idEquipo2'];
      }

    $registrar = "UPDATE Clasificacion SET idEquipoGanador = $idEquipoGanador, idEquipoPerdedor = $idEquipoPerdedor where id = $idClasificacion";
    $resultado = mysqli_query($conectar, $registrar);
   
     //Validamos si es partido final para registrar al campeon
     $consultarEquipo = "SELECT * FROM Clasificacion where id = $idClasificacion";
     $resultado2 = mysqli_query($conectar, $consultarEquipo);
     $row = $resultado2->fetch_assoc();
     $nombreClasificacion = $row['nombreClasificacion'];

    if($nombreClasificacion == "Final"){

          //registramos equipo Campeon
     $registrarCampeon = "INSERT INTO EquipoCampeon VALUES(null,$idEquipoGanador,$idCampeonato,'Campeon')";
     $resultado = mysqli_query($conectar, $registrarCampeon);
 
     //registramos equipo SubCampeon
     $registrarSubCampeon = "INSERT INTO EquipoCampeon VALUES(null,$idEquipoPerdedor,$idCampeonato,'Subcampeon')";
     $resultado = mysqli_query($conectar, $registrarSubCampeon);

   
    }

    if($nombreClasificacion == "Final2"){

  //registramos equipo de 3er lugar
   $registrar3ro = "INSERT INTO EquipoCampeon VALUES(null,$idEquipoGanador,$idCampeonato,'3er Lugar')";
   $resultado = mysqli_query($conectar, $registrar3ro);

   //registramos equipo de 4to lugar
   $registrar4to = "INSERT INTO EquipoCampeon VALUES(null,$idEquipoPerdedor,$idCampeonato,'4to Lugar')";
   $resultado = mysqli_query($conectar, $registrar4to);
  
  }

    if($resultado){
       echo 1;
           
    }
    else{
       echo 3;
    }
}

if($tipo == "ElegirGanador"){

    $idClasificacion = $_POST["id"];

    $registrar = "SELECT cl.idEquipo1,e1.nombreEquipo as equipo1,cl.idEquipo2,e2.nombreEquipo as equipo2 FROM Clasificacion as cl
                   left join Campeonato as c on c.id = cl.idCampeoanto
                   left join Equipo as e1 on e1.id = cl.idEquipo1
                   left join Equipo as e2 on e2.id = cl.idEquipo2
                   where c.estado = 'En Curso' and cl.id = $idClasificacion";
    $resultado = mysqli_query($conectar, $registrar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    } 
}



if($tipo == "TablaPosiciones"){

     //obtengo el id del campeonato
     $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
     $resultado2 = mysqli_query($conectar, $consultarEquipo);
     $row = $resultado2->fetch_assoc();
     $idCampeonato = $row['id'];

     $resultado1 = $parametro->TablaPosiciones();

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th width="60px">POSICIÓN</th>
                            <th>NOMBRE EQUIPOS</th>
                            <th>PTS</th>
                            <th>J</th>
                            <th>G</th>
                            <th>E</th>                   
                            <th>P</th>
                            <th>GF</th>
                            <th>GC</th>
                            <th>GD</th>
                            <th width="120px">ACCIÓN</th>	             
                        </tr>
                    </thead>
                    <tbody id="RellenarTabla"> ';
     
        $cont = 0;
        if ($resultado1->RowCount() > 0 ) {
            while (!$resultado1->EndOfSeek()) {
                $listado = $resultado1->Row();
                $partidosJugados = $listado->partidosGanados + $listado->partidosEmpatados + $listado->partidosPerdidos;
                $golDiferencia = $listado->golFavor - $listado->golContra;
             
                //$totalObservaciones = $parametro->verificarObservaciones($listado->idEquipo);
             
                $cont++;              
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado->nombreEquipo . "</td>";
                $tabla .= "<td data-title=''>" . $listado->puntos . "</td>";
                $tabla .= "<td data-title=''>" . $partidosJugados . "</td>";
                $tabla .= "<td data-title=''>" . $listado->partidosGanados . "</td>";
                $tabla .= "<td data-title=''>" . $listado->partidosEmpatados . "</td>";
                $tabla .= "<td data-title=''>" . $listado->partidosPerdidos . "</td>";
                $tabla .= "<td data-title=''>" . $listado->golFavor . "</td>";
                $tabla .= "<td data-title=''>" . $listado->golContra . "</td>";
                $tabla .= "<td data-title=''>" . $golDiferencia . "</td>";
                $tabla .= "<td data-title=''><button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='verDetallesPartidos(".chr(34).$listado->idEquipo.chr(34).",".chr(34).$listado->nombreEquipo.chr(34).")'><i title= 'Ver Partidos' class='fas fa-search'></i></button>";      
                // if($totalObservaciones > 0){
                //     $tabla .= "&nbsp<button type='button' class='btn btn-warning btn-sm checkbox-toggle' onclick='verDetallesPartidos(".chr(34).$listado->idEquipo.chr(34).",".chr(34).$listado->nombreEquipo.chr(34).")'><i title= 'Ver Obervación' class='fas fa-comments'></i></button>";                    
                // }
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}

// para el campeon y subcampeon
if($tipo == "TablaFinal1"){

    //obtengo el id del campeonato
    $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
    $resultado2 = mysqli_query($conectar, $consultarEquipo);
    $row = $resultado2->fetch_assoc();
    $idCampeonato = $row['id'];


   $listarFinal = "SELECT c.id as idClasificacion ,c.idEquipo1,e1.nombreEquipo as equipo1,c.idEquipo2, e2.nombreEquipo as equipo2 FROM Clasificacion as c
   LEFT JOIN Equipo as e1 on e1.id = c.idEquipo1
   LEFT JOIN Equipo as e2 on e2.id = c.idEquipo2
   where c.nombreClasificacion = 'Final' and c.idCampeoanto = $idCampeonato";
   $resultado1 = mysqli_query($conectar, $listarFinal);

       $tabla = "";
       $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                   <thead>
                       <tr>
                        <th>#</th>
                        <th>EQUIPOS</th>                             
                        <th>ACCIÓN</th>	             
                       </tr>
                   </thead>
                   <tbody id="RellenarTabla"> ';
    
       $cont = 0;
       if ($resultado1) {
           while ($listado = mysqli_fetch_array($resultado1)) {
              
                //valido si ya se registro el partido de ida o vuelta
                $consultarEquipo = "SELECT * FROM Partido as p 
                LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                WHERE p.idEquipoLocal = ".$listado['idEquipo1']." and p.idEquipoVisitante = ".$listado['idEquipo2']." and c.estado='En Curso' and p.Modo = 'Final'";
                $resultado4 = mysqli_query($conectar, $consultarEquipo);
                $num_filas = mysqli_num_rows($resultado4);

                  //valido si ya se registro un equipo ganador
                  $consultarEquipo = "select cl.idEquipoGanador FROM Clasificacion as cl
                  LEFT JOIN Campeonato as c on c.id = cl.idCampeoanto
                  WHERE cl.idEquipo1 = ".$listado['idEquipo1']." and cl.idEquipo2 = ".$listado['idEquipo2']." and c.estado='En Curso' and cl.nombreClasificacion='Final'";
                  $resultado5 = mysqli_query($conectar, $consultarEquipo);
                  $row = $resultado5->fetch_assoc();
                  $idEquipoGanador = $row['idEquipoGanador'];

               $cont++;
              
               $tabla .= "<tr>";
               $tabla .= "<td data-title=''>" .  $cont . "</td>";
               $tabla .= "<td data-title=''>" . $listado['equipo1'] . "<b> VS </b>" . $listado['equipo2'] . "</td>";
               if($num_filas == 0 && $idRol != 3){
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='GenerarPartidoOctavos(".$listado['idClasificacion'].")'>Generar Partido</button>";      
               }
               else{
                // if($num_filas == 1){
                //     // $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='GenerarPartidoOctavos(".$listado['idClasificacion'].")'>Partido Vuelta</button>";
                //     $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                // }
                // else{
                    if($idEquipoGanador == "" || $idEquipoGanador == null){
                        $tabla .= "<td data-title=''>";
                        if($idRol != 3){                        
                        $tabla .= " <button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='ElegirGanador(".$listado['idClasificacion'].")'>Registrar Campeon</button>";
                        }
                        $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                    }
                    else{
                        $tabla .= "<td data-title=''>";
                        if($idRol != 3){      
                        $tabla .= " <button type='button' class='btn btn-primary btn-sm checkbox-toggle' disabled>Registrar Campeon</button>";
                        }
                        $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                    }
                    
               // }
               }
               
               $tabla .= "</td>";
               $tabla .= "</tr>";
           }
       }
   
       $tabla .= "</tbody>
               
               </table>";
       echo  $tabla;   
}


// para el 3er y 4to lugar
if($tipo == "TablaFinal2"){

    //obtengo el id del campeonato
    $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
    $resultado2 = mysqli_query($conectar, $consultarEquipo);
    $row = $resultado2->fetch_assoc();
    $idCampeonato = $row['id'];


   $listarFinal2 = "SELECT c.id as idClasificacion ,c.idEquipo1,e1.nombreEquipo as equipo1,c.idEquipo2, e2.nombreEquipo as equipo2 FROM Clasificacion as c
   LEFT JOIN Equipo as e1 on e1.id = c.idEquipo1
   LEFT JOIN Equipo as e2 on e2.id = c.idEquipo2
   where c.nombreClasificacion = 'Final2' and c.idCampeoanto = $idCampeonato";
   $resultado1 = mysqli_query($conectar, $listarFinal2);

       $tabla = "";
       $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                   <thead>
                       <tr>
                        <th>#</th>
                        <th>EQUIPOS</th>                             
                        <th>ACCIÓN</th>	             
                       </tr>
                   </thead>
                   <tbody id="RellenarTabla"> ';
    
       $cont = 0;
       if ($resultado1) {
           while ($listado = mysqli_fetch_array($resultado1)) {
              
                //valido si ya se registro el partido de ida o vuelta
                $consultarEquipo = "SELECT * FROM Partido as p 
                LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                WHERE p.idEquipoLocal = ".$listado['idEquipo1']." and p.idEquipoVisitante = ".$listado['idEquipo2']." and c.estado='En Curso' and p.Modo = '3er y 4to Lugar'";
                $resultado4 = mysqli_query($conectar, $consultarEquipo);
                $num_filas = mysqli_num_rows($resultado4);

                  //valido si ya se registro un equipo ganador
                  $consultarEquipo = "select cl.idEquipoGanador FROM Clasificacion as cl
                  LEFT JOIN Campeonato as c on c.id = cl.idCampeoanto
                  WHERE cl.idEquipo1 = ".$listado['idEquipo1']." and cl.idEquipo2 = ".$listado['idEquipo2']." and c.estado='En Curso' and cl.nombreClasificacion='Final2'";
                  $resultado5 = mysqli_query($conectar, $consultarEquipo);
                  $row = $resultado5->fetch_assoc();
                  $idEquipoGanador = $row['idEquipoGanador'];

               $cont++;
              
               $tabla .= "<tr>";
               $tabla .= "<td data-title=''>" .  $cont . "</td>";
               $tabla .= "<td data-title=''>" . $listado['equipo1'] . "<b> VS </b>" . $listado['equipo2'] . "</td>";
               if($num_filas == 0 && $idRol != 3){
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='GenerarPartidoOctavos(".$listado['idClasificacion'].")'>Generar Partido</button>";      
               }
               else{
                // if($num_filas == 1){
                //     // $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='GenerarPartidoOctavos(".$listado['idClasificacion'].")'>Partido Vuelta</button>";
                //     $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                // }
                // else{
                    if($idEquipoGanador == "" || $idEquipoGanador == null){
                        $tabla .= "<td data-title=''>";
                        if($idRol != 3){      
                        $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='ElegirGanador(".$listado['idClasificacion'].")'>Registrar Ganador</button>";
                        }
                        $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                    }
                    else{
                        $tabla .= "<td data-title=''>";
                        if($idRol != 3){      
                        $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' disabled>Registrar Ganador</button>";
                        }
                        $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                    }
                    
               // }
               }
               
               $tabla .= "</td>";
               $tabla .= "</tr>";
           }
       }
   
       $tabla .= "</tbody>
               
               </table>";
       echo  $tabla;   
}


if($tipo == "TablaSemifinal"){

    //obtengo el id del campeonato
    $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
    $resultado2 = mysqli_query($conectar, $consultarEquipo);
    $row = $resultado2->fetch_assoc();
    $idCampeonato = $row['id'];


   $listarSemifinal = "SELECT c.id as idClasificacion ,c.idEquipo1,e1.nombreEquipo as equipo1,c.idEquipo2, e2.nombreEquipo as equipo2 FROM Clasificacion as c
   LEFT JOIN Equipo as e1 on e1.id = c.idEquipo1
   LEFT JOIN Equipo as e2 on e2.id = c.idEquipo2
   where c.nombreClasificacion = 'Semifinal' and c.idCampeoanto = $idCampeonato";
   $resultado1 = mysqli_query($conectar, $listarSemifinal);

       $tabla = "";
       $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                   <thead>
                       <tr>
                        <th>#</th>
                        <th>EQUIPOS</th>                             
                        <th>ACCIÓN</th>	             
                       </tr>
                   </thead>
                   <tbody id="RellenarTabla"> ';
    
       $cont = 0;
       if ($resultado1) {
           while ($listado = mysqli_fetch_array($resultado1)) {
              
                //valido si ya se registro el partido de ida o vuelta
                $consultarEquipo = "SELECT * FROM Partido as p 
                LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                WHERE p.idEquipoLocal = ".$listado['idEquipo1']." and p.idEquipoVisitante = ".$listado['idEquipo2']." and c.estado='En Curso' and p.Modo = 'Semifinal'";
                $resultado4 = mysqli_query($conectar, $consultarEquipo);
                $num_filas = mysqli_num_rows($resultado4);

                  //valido si ya se registro un equipo ganador
                  $consultarEquipo = "select cl.idEquipoGanador FROM Clasificacion as cl
                  LEFT JOIN Campeonato as c on c.id = cl.idCampeoanto
                  WHERE cl.idEquipo1 = ".$listado['idEquipo1']." and cl.idEquipo2 = ".$listado['idEquipo2']." and c.estado='En Curso' and cl.nombreClasificacion='Semifinal'";
                  $resultado5 = mysqli_query($conectar, $consultarEquipo);
                  $row = $resultado5->fetch_assoc();
                  $idEquipoGanador = $row['idEquipoGanador'];

               $cont++;
              
               $tabla .= "<tr>";
               $tabla .= "<td data-title=''>" .  $cont . "</td>";
               $tabla .= "<td data-title=''>" . $listado['equipo1'] . "<b> VS </b>" . $listado['equipo2'] . "</td>";
               if($num_filas == 0 && $idRol != 3){
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='GenerarPartidoOctavos(".$listado['idClasificacion'].")'>Generar Partido</button>";      
               }
               else{
                // if($num_filas == 1){
                //     // $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='GenerarPartidoOctavos(".$listado['idClasificacion'].")'>Partido Vuelta</button>";
                //     $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                // }
                // else{
                    if($idEquipoGanador == "" || $idEquipoGanador == null){
                        $tabla .= "<td data-title=''>";
                        if($idRol != 3){      
                        $tabla .= " <button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='ElegirGanador(".$listado['idClasificacion'].")'>Pasar a la Final</button>";
                        }
                        $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                    }
                    else{
                        $tabla .= "<td data-title=''>";
                        if($idRol != 3){      
                        $tabla .= " <button type='button' class='btn btn-primary btn-sm checkbox-toggle' disabled>Pasar a la Final</button>";
                        }
                        $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                    }
                    
               // }
               }
               
               $tabla .= "</td>";
               $tabla .= "</tr>";
           }
       }
   
       $tabla .= "</tbody>
               
               </table>";
       echo  $tabla;   
}

if($tipo == "TablaCuartos"){

    //obtengo el id del campeonato
    $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
    $resultado2 = mysqli_query($conectar, $consultarEquipo);
    $row = $resultado2->fetch_assoc();
    $idCampeonato = $row['id'];


   $listarCuartos = "SELECT c.id as idClasificacion ,c.idEquipo1,e1.nombreEquipo as equipo1,c.idEquipo2, e2.nombreEquipo as equipo2 FROM Clasificacion as c
   LEFT JOIN Equipo as e1 on e1.id = c.idEquipo1
   LEFT JOIN Equipo as e2 on e2.id = c.idEquipo2
   where c.nombreClasificacion = 'Cuartos' and c.idCampeoanto = $idCampeonato";
   $resultado1 = mysqli_query($conectar, $listarCuartos);

       $tabla = "";
       $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                   <thead>
                       <tr>
                        <th>#</th>
                        <th>EQUIPOS</th>                             
                        <th>ACCIÓN</th>	             
                       </tr>
                   </thead>
                   <tbody id="RellenarTabla"> ';
    
       $cont = 0;
       if ($resultado1) {
           while ($listado = mysqli_fetch_array($resultado1)) {
              
                //valido si ya se registro el partido de ida o vuelta
                $consultarEquipo = "SELECT * FROM Partido as p 
                LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                WHERE p.idEquipoLocal = ".$listado['idEquipo1']." and p.idEquipoVisitante = ".$listado['idEquipo2']." and c.estado='En Curso' and p.Modo = '4to de Final'";
                $resultado4 = mysqli_query($conectar, $consultarEquipo);
                $num_filas = mysqli_num_rows($resultado4);

                  //valido si ya se registro un equipo ganador
                  $consultarEquipo = "select cl.idEquipoGanador FROM Clasificacion as cl
                  LEFT JOIN Campeonato as c on c.id = cl.idCampeoanto
                  WHERE cl.idEquipo1 = ".$listado['idEquipo1']." and cl.idEquipo2 = ".$listado['idEquipo2']." and c.estado='En Curso' and cl.nombreClasificacion='Cuartos'";
                  $resultado5 = mysqli_query($conectar, $consultarEquipo);
                  $row = $resultado5->fetch_assoc();
                  $idEquipoGanador = $row['idEquipoGanador'];

               $cont++;
              
               $tabla .= "<tr>";
               $tabla .= "<td data-title=''>" .  $cont . "</td>";
               $tabla .= "<td data-title=''>" . $listado['equipo1'] . "<b> VS </b>" . $listado['equipo2'] . "</td>";
               if($num_filas == 0 && $idRol != 3){
                    $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='GenerarPartidoOctavos(".$listado['idClasificacion'].")'>Partido Ida</button>";      
               }
               else{
                if($num_filas == 1 ){
                    $tabla .= "<td data-title=''>";
                    if($idRol != 3){      
                    $tabla .= " <button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='GenerarPartidoOctavos(".$listado['idClasificacion'].")'>Partido Vuelta</button>";
                    }
                    $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                }
                else{
                    if($idEquipoGanador == "" || $idEquipoGanador == null){
                        $tabla .= "<td data-title=''>";
                        if($idRol != 3){      
                        $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='ElegirGanador(".$listado['idClasificacion'].")'>Pasar a Semifinal</button>";
                        }
                        $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                    }
                    else{
                        $tabla .= "<td data-title=''>";
                        if($idRol != 3){      
                        $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' disabled>Pasar a Semifinal</button>";
                        }
                        $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
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


if($tipo == "TablaOctavos"){

    //obtengo el id del campeonato
    $consultarEquipo = "SELECT id FROM Campeonato where estado = 'En Curso'";
    $resultado2 = mysqli_query($conectar, $consultarEquipo);
    $row = $resultado2->fetch_assoc();
    $idCampeonato = $row['id'];


   $listarOctavos = "SELECT c.id as idClasificacion,c.idEquipo1,e1.nombreEquipo as equipo1,c.idEquipo2, e2.nombreEquipo as equipo2 
   FROM Clasificacion as c
   LEFT JOIN Equipo as e1 on e1.id = c.idEquipo1
   LEFT JOIN Equipo as e2 on e2.id = c.idEquipo2
   where c.idCampeoanto = $idCampeonato";
   $resultado1 = mysqli_query($conectar, $listarOctavos);

       $tabla = "";
       $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                   <thead>
                       <tr>
                        <th>#</th>
                        <th>EQUIPOS</th>                             
                        <th>ACCIÓN</th>	             
                       </tr>
                   </thead>
                   <tbody id="RellenarTabla"> ';
    
       $cont = 0;
       if ($resultado1) {
           while ($listado = mysqli_fetch_array($resultado1)) {
              
                //valido si ya se registro el partido de ida o vuelta
                $consultarEquipo = "SELECT * FROM Partido as p 
                LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                WHERE p.idEquipoLocal = ".$listado['idEquipo1']." and p.idEquipoVisitante = ".$listado['idEquipo2']." and c.estado='En Curso' and p.Modo = '8vo de Final'";
                $resultado4 = mysqli_query($conectar, $consultarEquipo);
                $num_filas = mysqli_num_rows($resultado4);

                  //valido si ya se registro un equipo ganador
                  $consultarEquipo = "select cl.idEquipoGanador FROM Clasificacion as cl
                  LEFT JOIN Campeonato as c on c.id = cl.idCampeoanto
                  WHERE cl.idEquipo1 = ".$listado['idEquipo1']." and cl.idEquipo2 = ".$listado['idEquipo2']." and c.estado='En Curso' and cl.nombreClasificacion='octavos'";
                  $resultado5 = mysqli_query($conectar, $consultarEquipo);
                  $row = $resultado5->fetch_assoc();
                  $idEquipoGanador = $row['idEquipoGanador'];

               $cont++;
              
               $tabla .= "<tr>";
               $tabla .= "<td data-title=''>" .  $cont . "</td>";
               $tabla .= "<td data-title=''>" . $listado['equipo1'] . "<b> VS </b>" . $listado['equipo2'] . "</td>";
               if($num_filas == 0 && $idRol != 3){
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='GenerarPartidoOctavos(".$listado['idClasificacion'].")'>Partido Ida</button>";      
               }
               else{
                if($num_filas == 1){
                    $tabla .= "<td data-title=''>";
                    if($idRol != 3){      
                    $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='GenerarPartidoOctavos(".$listado['idClasificacion'].")'>Partido Vuelta</button>";
                    }
                    $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                }
                else{
                    if($idEquipoGanador == "" || $idEquipoGanador == null){
                        $tabla .= "<td data-title=''>";
                        if($idRol != 3){      
                        $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='ElegirGanador(".$listado['idClasificacion'].")'>Pasar a Cuartos</button>";
                        }
                        $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
                    }
                    else{
                        $tabla .= "<td data-title=''>";
                        if($idRol != 3){      
                        $tabla .= "<button type='button' class='btn btn-primary btn-sm checkbox-toggle' disabled>Pasar a Cuartos</button>";
                        }
                        $tabla .= " <button type='button' class='btn btn-secondary btn-sm checkbox-toggle' onclick='verDetallesPartidosClasificados(".$listado['idEquipo1'].")'><i class='fas fa-search'></i></button>";
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


//lista de equipos sin asignar a un grupo de la fase de grupo
if($tipo == "ListadoEquiposDisponibles"){


   $registrarInventario = "SELECT tp.idEquipo, e.nombreEquipo,tp.grupo FROM TablaPosicion as tp 
                            LEFT JOIN Campeonato as c on c.id = tp.idCampeonato 
                            LEFT JOIN Equipo as e on e.id = tp.idEquipo
                            where c.estado = 'En curso'and tp.grupo is null";

   $resultado1 = mysqli_query($conectar, $registrarInventario);

       $tabla = "";
       $tabla .= '<table id="example3" class="table table-bordered table-striped"  method="POST">
                   <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre Equipo</th>
                            <th>Acción</th>      
                        </tr>
                   </thead>
                   <tbody id="RellenarTabla"> ';
    
       $cont = 0;
       if ($resultado1) {
           while ($listado = mysqli_fetch_array($resultado1)) {
              
               $cont++;
           
               $tabla .= "<tr>";
               $tabla .= "<td data-title=''>" .  $cont . "</td>";
               $tabla .= "<td data-title=''>" . $listado['nombreEquipo'] . "</td>";
               
               if($idRol != 3){      
               $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='añadirGrupo(".$listado['idEquipo'].")'>Añadir</button>";      
               }
               else{
                $tabla .= "<td data-title=''><button type='button' disabled class='btn btn-primary btn-sm checkbox-toggle'>Añadir</button>";      
               }
               $tabla .= "</td>";
               $tabla .= "</tr>";
           }
       }
   
       $tabla .= "</tbody>
               
               </table>";
       echo  $tabla;   
}


if($tipo == "TablaPosicionesGrupo1"){

  
   $registrarInventario = "SELECT tp.id,e.id as idEquipo,e.nombreEquipo,tp.puntos,tp.partidosGanados,tp.partidosEmpatados,tp.partidosPerdidos,tp.golFavor,tp.golContra, sum(tp.golFavor - tp.golContra) as diferencia 
   FROM TablaPosicion as tp 
   left join inscripcion i on i.id = tp.idEquipo
      LEFT join Equipo as e on e.id = i.idEquipo 
   LEFT join campeonato ca on ca.id = i.idCampeonato
   where ca.estado = 'en curso' and grupo = 1
      GROUP by tp.id,e.id,e.nombreEquipo,tp.puntos,tp.partidosGanados,tp.partidosEmpatados,tp.partidosPerdidos,tp.golFavor,tp.golContra
      ORDER by tp.puntos desc, diferencia desc";
   $resultado1 = mysqli_query($conectar, $registrarInventario);

       $tabla = "";
       $tabla .= '<table id="example4" class="table table-bordered table-striped"  method="POST">
                   <thead>
                       <tr>
                           <th width="60px">POSICIÓN</th>
                           <th>NOMBRE EQUIPOS</th>
                           <th>PTS</th>
                           <th>J</th>
                           <th>G</th>
                           <th>E</th>                   
                           <th>P</th>
                           <th>GF</th>
                           <th>GC</th>
                           <th>GD</th>
                           <th width="100px">ACCIÓN</th>	             
                       </tr>
                   </thead>
                   <tbody id="RellenarTabla"> ';
    
       $cont = 0;
       if ($resultado1) {
           while ($listado = mysqli_fetch_array($resultado1)) {
               $partidosJugados = $listado['partidosGanados'] + $listado['partidosEmpatados'] + $listado['partidosPerdidos'];
               $golDiferencia = $listado['golFavor'] - $listado['golContra'];
       
               $idEquipoObservado = $listado['idEquipo'];
               $observaciones = "SELECT * FROM Partido where equipoObservado = $idEquipoObservado and idCampeonato = $idCampeonato and estadoObservacion = 'Aceptado'";
               $resultado = mysqli_query($conectar, $observaciones);
               $num_filas = mysqli_num_rows($resultado);

               $cont++;
            
               $tabla .= "<tr>";
               $tabla .= "<td data-title=''>" .  $cont . "</td>";
               $tabla .= "<td data-title=''>" . $listado['nombreEquipo'] . "</td>";
               $tabla .= "<td data-title=''>" . $listado['puntos'] . "</td>";
               $tabla .= "<td data-title=''>" . $partidosJugados . "</td>";
               $tabla .= "<td data-title=''>" . $listado['partidosGanados'] . "</td>";
               $tabla .= "<td data-title=''>" . $listado['partidosEmpatados'] . "</td>";
               $tabla .= "<td data-title=''>" . $listado['partidosPerdidos'] . "</td>";
               $tabla .= "<td data-title=''>" . $listado['golFavor'] . "</td>";
               $tabla .= "<td data-title=''>" . $listado['golContra'] . "</td>";
               $tabla .= "<td data-title=''>" . $golDiferencia . "</td>";
               $tabla .= "<td data-title=''><button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='verDetallesPartidos(".chr(34).$listado['idEquipo'].chr(34).",".chr(34).$listado['nombreEquipo'].chr(34).")'><i title= 'Ver Partidos' class='fas fa-search'></i></button>";      
               if($num_filas > 0){
                    $tabla .= "&nbsp<button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='verDetallesPartidos(".chr(34).$listado['idEquipo'].chr(34).",".chr(34).$listado['nombreEquipo'].chr(34).")'><i title= 'Ver Obervación' class='fas fa-comments'></i></button>";                    
                }

               $tabla .= "</td>";
               $tabla .= "</tr>";
           }
       }
   
       $tabla .= "</tbody>
               
               </table>";
       echo  $tabla;   
}


if($tipo == "TablaPosicionesGrupo2"){

   $registrarInventario = "SELECT tp.id,e.id as idEquipo,e.nombreEquipo,tp.puntos,tp.partidosGanados,tp.partidosEmpatados,tp.partidosPerdidos,tp.golFavor,tp.golContra, sum(tp.golFavor - tp.golContra) as diferencia 
   FROM TablaPosicion as tp 
   left join inscripcion i on i.id = tp.idEquipo
      LEFT join Equipo as e on e.id = i.idEquipo 
   LEFT join campeonato ca on ca.id = i.idCampeonato
   where ca.estado = 'en curso' and grupo = 2
      GROUP by tp.id,e.id,e.nombreEquipo,tp.puntos,tp.partidosGanados,tp.partidosEmpatados,tp.partidosPerdidos,tp.golFavor,tp.golContra
      ORDER by tp.puntos desc, diferencia desc";
   $resultado1 = mysqli_query($conectar, $registrarInventario);

       $tabla = "";
       $tabla .= '<table id="example5" class="table table-bordered table-striped"  method="POST">
                   <thead>
                       <tr>
                           <th width="60px">POSICIÓN</th>
                           <th>NOMBRE EQUIPOS</th>
                           <th>PTS</th>
                           <th>J</th>
                           <th>G</th>
                           <th>E</th>                   
                           <th>P</th>
                           <th>GF</th>
                           <th>GC</th>
                           <th>GD</th>
                           <th width="100px">ACCIÓN</th>	             
                       </tr>
                   </thead>
                   <tbody id="RellenarTabla"> ';
    
       $cont = 0;
       if ($resultado1) {
           while ($listado = mysqli_fetch_array($resultado1)) {
               $partidosJugados = $listado['partidosGanados'] + $listado['partidosEmpatados'] + $listado['partidosPerdidos'];
               $golDiferencia = $listado['golFavor'] - $listado['golContra'];
       
               $idEquipoObservado = $listado['idEquipo'];
               $observaciones = "SELECT * FROM Partido where equipoObservado = $idEquipoObservado and idCampeonato = $idCampeonato and estadoObservacion = 'Aceptado'";
               $resultado = mysqli_query($conectar, $observaciones);
               $num_filas = mysqli_num_rows($resultado);

               $cont++;
           
               $tabla .= "<tr>";
               $tabla .= "<td data-title=''>" .  $cont . "</td>";
               $tabla .= "<td data-title=''>" . $listado['nombreEquipo'] . "</td>";
               $tabla .= "<td data-title=''>" . $listado['puntos'] . "</td>";
               $tabla .= "<td data-title=''>" . $partidosJugados . "</td>";
               $tabla .= "<td data-title=''>" . $listado['partidosGanados'] . "</td>";
               $tabla .= "<td data-title=''>" . $listado['partidosEmpatados'] . "</td>";
               $tabla .= "<td data-title=''>" . $listado['partidosPerdidos'] . "</td>";
               $tabla .= "<td data-title=''>" . $listado['golFavor'] . "</td>";
               $tabla .= "<td data-title=''>" . $listado['golContra'] . "</td>";
               $tabla .= "<td data-title=''>" . $golDiferencia . "</td>";
               $tabla .= "<td data-title=''><button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='verDetallesPartidos(".chr(34).$listado['idEquipo'].chr(34).",".chr(34).$listado['nombreEquipo'].chr(34).")'><i title= 'Ver Partidos' class='fas fa-search'></i></button>";      
               if($num_filas > 0){
                    $tabla .= "&nbsp<button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='verDetallesPartidos(".chr(34).$listado['idEquipo'].chr(34).",".chr(34).$listado['nombreEquipo'].chr(34).")'><i title= 'Ver Obervación' class='fas fa-comments'></i></button>";                    
                }
               $tabla .= "</td>";
               $tabla .= "</tr>";
           }
       }
   
       $tabla .= "</tbody>
               
               </table>";
       echo  $tabla;   
}

<?php

session_start();
include("../conexion/conexion.php");
include("../conexion/parametros.php");
$parametro = new parametros();
$tipo = $_GET["op"];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if($tipo=="ActualizarTablaPosiciones"){
   

    
    $equipo1 = $_POST["equipo1"];
    $equipo2 = $_POST["equipo2"];
    $equipo3 = $_POST["equipo3"];
    $walkover = $_POST["walkover"];
    $idCampeonato =$_POST["idCampeonato"];
    $idHecho = $_POST["idHecho"];
    
    if($walkover == "no"){
        $array_idHecho = explode(",",$_POST["idHecho"]);
        $array_equipo = explode(",",$_POST["equipo"]);

        
        $goles1 = 0;
        $goles2 = 0;
        $equipoGanador = 0;
   
        //obtengo quien es el equipo ganador
        for ($j=0; $j < count($array_idHecho); $j++){
            //obtengo el id del equipo
            $consultarEquipo = $parametro->TraerCodEquipo($array_equipo[$j]); 
            $idEquipo = $consultarEquipo->id;
          
            if($array_idHecho[$j] == 1){
                if( $idEquipo == $equipo1){
                    $goles1++;
                }
                else{
                    $goles2++;
                }
            }
        }

        if($goles1 > $goles2){
            $equipoGanador = $equipo1;   
        }
        else{
            if($goles2 > $goles1){
                $equipoGanador = $equipo2;
            }
        }

         $puntos1 = 0;
         $PartidosGanados1= 0;
         $PartidosEmpatados1= 0;
         $PartidosPerdidos1= 0;
         $golFavor1= 0;
         $golContra1= 0;

         $puntos2 = 0;
         $PartidosGanados2= 0;
         $PartidosEmpatados2= 0;
         $PartidosPerdidos2= 0;
         $golFavor2= 0;
         $golContra2= 0;


         if($equipoGanador == 0){
            $puntos1 = 1;
            $puntos2 = 1;
            $PartidosEmpatados2= 1;
            $PartidosEmpatados1= 1;
            $golFavor1= $goles1;
            $golContra1= $goles2;
            $golFavor2= $goles2;
            $golContra2= $goles1;
         }
         else{
            if($equipoGanador == $equipo1){
                //datos equipo ganador 
                $puntos1 = 3;
                $PartidosGanados1= 1;
                $golFavor1= $goles1;
                $golContra1= $goles2;

                 //datos equipo perdedor 
                 $puntos2 = 0;
                 $PartidosPerdidos2= 1;
                 $golFavor2= $goles2;
                 $golContra2= $goles1;
            }
            else{
                if($equipoGanador == $equipo2){
                 //datos equipo ganador 
                 $puntos2 = 3;
                 $PartidosGanados2= 1;
                 $golFavor2= $goles2;
                 $golContra2= $goles1;
 
                  //datos equipo perdedor 
                  $puntos1 = 0;
                  $PartidosPerdidos1= 1;
                  $golFavor1= $goles1;
                  $golContra1= $goles2;
                }
            }
        }

         //validar equipo1 en la tabla
         $idValidacionEquipo1 = $parametro->TraerEquipoTablaPosicion($equipo1,$idCampeonato); 
        // $idValidacionEquipo1 = $validarEquipo1->id;
     
          //validar equipo2 en la tabla
          $idValidacionEquipo2 = $parametro->TraerEquipoTablaPosicion($equipo2,$idCampeonato); 
          //$idValidacionEquipo2 = $validarEquipo2->id;
      

          if($idValidacionEquipo1 != "" || $idValidacionEquipo1 != null){
       //     $resultado = $parametro->ActualizarTablaPosicion($puntos1, $PartidosGanados1, $PartidosEmpatados1, $PartidosPerdidos1, $golFavor1, $golContra1, $idValidacionEquipo1);             
            $insertarEquipo1 = "UPDATE TablaPosicion SET puntos = puntos + $puntos1, partidosGanados = partidosGanados + $PartidosGanados1, partidosEmpatados = partidosEmpatados + $PartidosEmpatados1, partidosPerdidos  = partidosPerdidos + $PartidosPerdidos1, golFavor = golFavor + $golFavor1,golContra = golContra + $golContra1 where id = $idValidacionEquipo1";
            $resultado = mysqli_query($conectar, $insertarEquipo1);
          }
          else{
           // $resultado = $parametro->AgregarTablaPosicion($equipo1,$idCampeonato,$puntos1,$PartidosGanados1,$PartidosEmpatados1,$PartidosPerdidos1,$golFavor1,$golContra1); 
            $insertarEquipo1 = "INSERT INTO TablaPosicion(`idEquipo`, `puntos`, `partidosGanados`, `partidosEmpatados`, `partidosPerdidos`, `golFavor`, `golContra`) values($equipo1,$puntos1,$PartidosGanados1,$PartidosEmpatados1,$PartidosPerdidos1,$golFavor1,$golContra1)";
            $resultado = mysqli_query($conectar, $insertarEquipo1);
          }
        

          if($resultado){
            if($idValidacionEquipo2 != "" || $idValidacionEquipo2 != null){
                //$resultado2 = $parametro->ActualizarTablaPosicion($puntos2, $PartidosGanados2, $PartidosEmpatados2, $PartidosPerdidos2, $golFavor2, $golContra2, $idValidacionEquipo2); 
                $insertarEquipo2 = "UPDATE TablaPosicion SET puntos = puntos + $puntos2, partidosGanados = partidosGanados + $PartidosGanados2, partidosEmpatados = partidosEmpatados + $PartidosEmpatados2, partidosPerdidos  = partidosPerdidos + $PartidosPerdidos2, golFavor = golFavor + $golFavor2,golContra = golContra + $golContra2 where id = $idValidacionEquipo2";
                $resultado2 = mysqli_query($conectar, $insertarEquipo2);
              }
              else{
                //$resultado2 = $parametro->AgregarTablaPosicion($equipo2,$idCampeonato,$puntos2,$PartidosGanados2,$PartidosEmpatados2,$PartidosPerdidos2,$golFavor2,$golContra2); 
                $insertarEquipo2 = "INSERT INTO TablaPosicion(`idEquipo`, `puntos`, `partidosGanados`, `partidosEmpatados`, `partidosPerdidos`, `golFavor`, `golContra`) values($equipo2,$puntos2,$PartidosGanados2,$PartidosEmpatados2,$PartidosPerdidos2,$golFavor2,$golContra2)";
                $resultado2 = mysqli_query($conectar, $insertarEquipo2);
              }

              if($resultado2){
                  echo 'ok';
              }
              else{
                echo '2';
              }
          }
          else{
              echo '1';
          } 
    }
    else{

        $equipoGanadorWalkover = "";
        $equipoPerdedorWalkover = "";

        if( $equipo3 == $equipo1){
            $equipoGanadorWalkover = $equipo1;
            $equipoPerdedorWalkover = $equipo2;
        }
        else{
            $equipoGanadorWalkover = $equipo2;
            $equipoPerdedorWalkover = $equipo1;
        }

         //validar si se encuentra el equipo ganador en la tabla
         $idTablaPosicionEquipoGanadorWalkover = $parametro->TraerEquipoTablaPosicion($equipoGanadorWalkover,$idCampeonato); 
        // $equipoGanadorWalkover = $validarEquipo2->id;
       
        if($idTablaPosicionEquipoGanadorWalkover != "" || $idTablaPosicionEquipoGanadorWalkover != null){
        // $resultado = $parametro->ActualizarTablaPosicionWalkover($equipoGanadorWalkover,$idCampeonato); 
            $insertarEquipo1 = "UPDATE TablaPosicion SET puntos = puntos + 3, partidosGanados = partidosGanados + 1, golFavor = golFavor + 2 where id = $idTablaPosicionEquipoGanadorWalkover";
            $resultado = mysqli_query($conectar, $insertarEquipo1);
        }
        else{
            $insertarEquipo1 = "INSERT INTO TablaPosicion(`idEquipo`, `puntos`, `partidosGanados`, `partidosEmpatados`, `partidosPerdidos`, `golFavor`, `golContra`) values($equipoGanadorWalkover,3,1,0,0,2,0)";
            $resultado = mysqli_query($conectar, $insertarEquipo1);
        }

        if($resultado){
          //validar si se encuentra el equipo perdedor en la tabla
          $idTablaPosicionEquipoPerdedorWalkover = $parametro->TraerEquipoTablaPosicion($equipoPerdedorWalkover,$idCampeonato); 
         //$equipoPerdedorWalkover = $validarEquipo2->id;
      
         if($idTablaPosicionEquipoPerdedorWalkover != "" || $idTablaPosicionEquipoPerdedorWalkover != null){
          //  $resultado2 = $resultado = $parametro->ActualizarTablaPosicionWalkoverPerdedor($equipoPerdedorWalkover,$idCampeonato); 
          $insertarEquipo2 = "UPDATE TablaPosicion SET  partidosPerdidos = partidosPerdidos + 1,golContra = golContra + 2 where id = $idTablaPosicionEquipoPerdedorWalkover";
          $resultado2 = mysqli_query($conectar, $insertarEquipo2);
         }
         else{
            $insertarEquipo1 = "INSERT INTO TablaPosicion(`idEquipo`, `puntos`, `partidosGanados`, `partidosEmpatados`, `partidosPerdidos`, `golFavor`, `golContra`) values($equipoPerdedorWalkover,0,0,0,1,0,2)";
            $resultado2 = mysqli_query($conectar, $insertarEquipo1);
         }
          if($resultado2){
              echo 'ok';
          }
          else{
              echo '2';
          }
        }
        else{
          echo '1';
        }
    }
}



if($tipo== "RegistrarPartido"){
   

    
    $idPartido = $_POST["idPartido"];
    $idCampeonato =$_POST["idCampeonato"];
    $idSede = $_POST["idSede"];
    $equipo1 = $_POST["equipo1"];
    $equipo2 = $_POST["equipo2"];
    $idEquipoGanadorWalkover = $_POST["equipo3"];
    $fecha = $_POST["fecha"];
    $modo = $_POST["modo"];
    $observacion = $_POST["observacion"];
    $estadoObservacion = '';
    $idEquipoObservado = 'null';
    $PrecioObservacion = 'null';

    $idHecho = $_POST["idHecho"];
    $idJugador =$_POST["idJugador"];
    $equipo = $_POST["equipo"];
    $eswalkover = $_POST["walkover"];
   
    $codProgramacion = $_POST["codProgramacion"];

    $array_idHecho = explode(",",$idHecho);
    $array_idJugador = explode(",",$idJugador);
    $array_equipo = explode(",",$equipo);

    if($observacion != "" || $observacion != null){
        $estadoObservacion = "Pendiente";
        $idEquipoObservado = $_POST["idEquipoObservado"];
        $PrecioObservacion = $_POST["precioObservacion"];
    }

    if($idEquipoGanadorWalkover == ''){
        $idEquipoGanadorWalkover = 0;
    }

    $resp = $parametro->InsertarPartido($idPartido,$equipo1,$equipo2,$idSede,$fecha,$idCampeonato,$modo,$observacion,$estadoObservacion,$idEquipoObservado,$PrecioObservacion,$eswalkover,$idEquipoGanadorWalkover);


    // $insertarHechos = "INSERT INTO Partido(`id`,`idEquipoLocal`, `idEquipoVisitante`, `idSede`, `fechaPartido`, `idCampeonato`, `Modo`, `Observacion`, `estadoObservacion`, `idEquipoObservado`, `precioObservacion`,`walkover`,`idEquipoGanadorWalkover`) VALUES($idPartido,$equipo1,$equipo2,$idSede,'$fecha',$idCampeonato,'$modo','$observacion','$estadoObservacion',$idEquipoObservado,$PrecioObservacion,'$eswalkover',$idEquipoGanadorWalkover)";
    // $resultado2 = mysqli_query($conectar, $insertarHechos);
    if($resp == 'ok'){
        if($idHecho != ""){
            $insertarHechos = "";
            for ($i=0; $i < count($array_idHecho); $i++){
          
                $resp=$parametro->InsertarHechoPartido($array_idHecho[$i],$array_idJugador[$i],$array_equipo[$i],$idPartido);
                if($resp == 'error'){
                    echo 2;
                    return;
                }
                // $insertarHechos = "INSERT INTO acontecimientopartido( `idAcontecimiento`, `idJugador`, `Equipo`, `idPartido`, `precio`, `estado`) values($array_idHecho[$i],$array_idJugador[$i],'$array_equipo[$i]',$idPartido,0,'Pendiente')";
                // $resultado2 = mysqli_query($conectar, $insertarHechos);
            }
                       
        }

        if($codProgramacion != ''){
            $parametro->actualizarProgramacionPartidos($codProgramacion);                              
        }

        if($idEquipoGanadorWalkover != '' && $idEquipoGanadorWalkover != 0 || $idHecho == ''){
            $nombreEquipo = $parametro->DropDownBuscarNombreEquipos($idEquipoGanadorWalkover);    
            $resp=$parametro->InsertarHechoPartidoWalkover(1,$nombreEquipo,$idPartido);
            $resp=$parametro->InsertarHechoPartidoWalkover(1,$nombreEquipo,$idPartido);
        }

        echo 1;
    }
    else{
        echo 3;
    }

}



if($tipo == "TorneoFinalizado"){
    

    $resultado = $parametro->VerificarTorneoFinalizado(); 
    $idCampeon = $resultado->id;

   
        if($idCampeon == ""){
            echo "abierto";
        }
        else{
            echo "cerrado";
        }
   
}


if($tipo == "DatosJugador"){

    $idJugador = $_POST["idJugador"];

    $datos = array();
    $Consultar = "SELECT j.id as idJugador, concat(j.nombre,' ', j.apellidos) as nombreJugador, e.nombreEquipo from Jugador as j 
                    LEFT JOIN Equipo as e on e.id = j.idEquipo
                    where j.id =$idJugador";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    } 
}


if($tipo == "FiltroJugadoresEquipos"){

    $idEquipo1 = $_POST["idequipo1"];
    $idEquipo2 = $_POST["idequipo2"];
      
        $resultado = $parametro->TraerJugadoresxEquipos($idEquipo1); 
        $resultado2 = $resultado;
        $row2 = $resultado2->Row();
        $resultado->MoveFirst();
        $tabla = "";
        $tabla .= "<div class='row'>";
        $tabla .= "<div class='col-md-6'>";
        $tabla .= "<div class='row text-center p-3'>";            
            $tabla .= "<section class='card col-md-12 pr-4 pl-4'>";      
            $tabla .= "<br><h5>Equipo ".$row2->nombreEquipo."<h5><br>";         
                $tabla .= '<table id="example1" class="table table-bordered table-striped" style="font-size:15px;" method="POST">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nombre</th>
                                    
                                    <th>Nro</th>           
                                </tr>
                            </thead>
                        <tbody > ';                
                $cont = 0;
                
                while (!$resultado->EndOfSeek()) {
                    $row = $resultado->Row();
                        $tabla .= "<tr>";
                        $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='DatosJugador(".$row->idJugador.")'><i class='fas fa-check'></i></button></td>";
                        $tabla .= "<td data-title=''>" . $row->nombre . " " . $row->apellidos . "</td>";
                        //$tabla .= "<td data-title=''>" . $row->nombreEquipo . "</td>";
                        $tabla .= "<td data-title=''>" . $row->nroCamiseta . "</td>";
                        $tabla .= "</tr>";
                    }
                $tabla .= "</tbody></table>";
            $tabla .= "</section>";
            $tabla .= "</div>";
            $tabla .= "</div>";


            $resultado = $parametro->TraerJugadoresxEquipos($idEquipo2); 
            $resultado2 = $resultado;
            $row2 = $resultado2->Row();
            $resultado->MoveFirst();
           
            $tabla .= "<div class='col-md-6'>";
            $tabla .= "<div class='row text-center p-3'>";   
            $tabla .= "<section class='card col-md-12 pr-4 pl-4'>";  
            $tabla .= "<br><h5>Equipo ".$row2->nombreEquipo."<h5><br>";                   
                $tabla .= '<table id="example2" class="table table-bordered table-striped"  style="font-size:15px;" method="POST">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nombre</th>
                                 
                                    <th>Nro</th>           
                                </tr>
                            </thead>
                        <tbody > ';
                
                $cont = 0;
                
                while (!$resultado->EndOfSeek()) {
                    $row = $resultado->Row();
                        $tabla .= "<tr>";
                        $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='DatosJugador(".$row->idJugador.")'><i class='fas fa-check'></i></button></td>";
                        $tabla .= "<td data-title=''>" . $row->nombre . " " . $row->apellidos . "</td>";
                       // $tabla .= "<td data-title=''>" . $row->nombreEquipo . "</td>";
                        $tabla .= "<td data-title=''>" . $row->nroCamiseta . "</td>";
                        $tabla .= "</tr>";
                    }
                $tabla .= "</tbody></table>";

            $tabla .= "</section>";
            $tabla .= "</div>";
            $tabla .= "</div>";
        $tabla .= "</div>";
       echo  $tabla;   
}

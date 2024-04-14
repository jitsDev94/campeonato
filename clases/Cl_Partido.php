<?php

session_start();
include_once("conexion.php");
require_once 'parametros.php';

$tipo = $_GET["op"];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if($tipo=="ActualizarTablaPosiciones"){
   

    $parametro = new parametros();
    $equipo1 = $_POST["equipo1"];
    $equipo2 = $_POST["equipo2"];
    $equipo3 = $_POST["equipo3"];
    $walkover = $_POST["walkover"];
    $idCampeonato =$_POST["idCampeonato"];
    $idHecho = $_POST["idHecho"];
    
    if($idHecho != "" || $idHecho != null || $idHecho != 0){
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
            // $consultarEquipo = "SELECT id FROM Equipo where nombreEquipo = '$array_equipo[$j]'";
            // $resultado2 = mysqli_query($conectar, $consultarEquipo);
            // $row = $resultado2->fetch_assoc();
            // $idEquipo = $row['id'];


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

         //obtengo el id del equipo
        //  $consultarEquipo2 = $parametro->TraerCodEquipo($array_equipo[$i]); 
        //  $idEquipo = $consultarEquipo2->id;
        //  $consultarEquipo = "SELECT id FROM Equipo where nombreEquipo = '$array_equipo[$i]'";
        //  $resultado2 = mysqli_query($conectar, $consultarEquipo);
        //  $row = $resultado2->fetch_assoc();
        //  $idEquipo = $row['id'];


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
         $validarEquipo1 = $parametro->TraerEquipoTablaPosicion($equipo1,$idCampeonato); 
         $idValidacionEquipo1 = $validarEquipo1->id;
        //  $validarEquipo1 = "SELECT * FROM TablaPosicion where idEquipo = $equipo1 and idCampeonato = $idCampeonato ";
        //  $resultado3 = mysqli_query($conectar, $validarEquipo1);
        //  $row1 = $resultado3->fetch_assoc();
        //  $idValidacionEquipo1 = $row1['id'];

          //validar equipo2 en la tabla
          $validarEquipo2 = $parametro->TraerEquipoTablaPosicion($equipo2,$idCampeonato); 
          $idValidacionEquipo2 = $validarEquipo2->id;
        //   $validarEquipo2 = "SELECT * FROM TablaPosicion where idEquipo = $equipo2 and idCampeonato = $idCampeonato";
        //   $resultado4 = mysqli_query($conectar, $validarEquipo2);
        //   $row2 = $resultado4->fetch_assoc();
        //   $idValidacionEquipo2 = $row2['id'];


          if($idValidacionEquipo1 != "" || $idValidacionEquipo1 != null){
            $resultado = $parametro->ActualizarTablaPosicion($puntos1, $PartidosGanados1, $PartidosEmpatados1, $PartidosPerdidos1, $golFavor1, $golContra1, $idValidacionEquipo1); 
           
            // $insertarEquipo1 = "UPDATE TablaPosicion SET puntos = puntos + $puntos1, partidosGanados = partidosGanados + $PartidosGanados1, partidosEmpatados = partidosEmpatados + $PartidosEmpatados1, partidosPerdidos  = partidosPerdidos + $PartidosPerdidos1, golFavor = golFavor + $golFavor1,golContra = golContra + $golContra1 where id = $idValidacionEquipo1";
            // $resultado = mysqli_query($conectar, $insertarEquipo1);
          }
          else{
            $resultado = $parametro->AgregarTablaPosicion($equipo1,$idCampeonato,$puntos1,$PartidosGanados1,$PartidosEmpatados1,$PartidosPerdidos1,$golFavor1,$golContra1); 
            // $insertarEquipo1 = "INSERT INTO TablaPosicion values(null,$equipo1,$idCampeonato,$puntos1,$PartidosGanados1,$PartidosEmpatados1,$PartidosPerdidos1,$golFavor1,$golContra1,null)";
            // $resultado = mysqli_query($conectar, $insertarEquipo1);
          }
        

          if($resultado){
            if($idValidacionEquipo2 != "" || $idValidacionEquipo2 != null){
                $resultado2 = $parametro->ActualizarTablaPosicion($puntos2, $PartidosGanados2, $PartidosEmpatados2, $PartidosPerdidos2, $golFavor2, $golContra2, $idValidacionEquipo2); 
                // $insertarEquipo2 = "UPDATE TablaPosicion SET puntos = puntos + $puntos2, partidosGanados = partidosGanados + $PartidosGanados2, partidosEmpatados = partidosEmpatados + $PartidosEmpatados2, partidosPerdidos  = partidosPerdidos + $PartidosPerdidos2, golFavor = golFavor + $golFavor2,golContra = golContra + $golContra2 where id = $idValidacionEquipo2";
                // $resultado2 = mysqli_query($conectar, $insertarEquipo2);
              }
              else{
                $resultado2 = $parametro->AgregarTablaPosicion($equipo2,$idCampeonato,$puntos2,$PartidosGanados2,$PartidosEmpatados2,$PartidosPerdidos2,$golFavor2,$golContra2); 
                // $insertarEquipo2 = "INSERT INTO TablaPosicion values(null,$equipo2,$idCampeonato,$puntos2,$PartidosGanados2,$PartidosEmpatados2,$PartidosPerdidos2,$golFavor2,$golContra2,null)";
                // $resultado2 = mysqli_query($conectar, $insertarEquipo2);
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
         $validarEquipo2 = $parametro->TraerEquipoTablaPosicion($equipoGanadorWalkover,$idCampeonato); 
         $equipoGanadorWalkover = $validarEquipo2->id;
        //  $validarEquipo2 = "SELECT * FROM TablaPosicion where idEquipo = $equipoGanadorWalkover and idCampeonato = $idCampeonato";
        //  $resultado4 = mysqli_query($conectar, $validarEquipo2);
        //  $row2 = $resultado4->fetch_assoc();
        //  $equipoGanadorWalkover = $row2['id'];

        $resultado = $parametro->ActualizarTablaPosicionWalkover($equipoGanadorWalkover,$idCampeonato); 
        // $insertarEquipo1 = "UPDATE TablaPosicion SET puntos = puntos + 3, partidosGanados = partidosGanados + 1 where id = $equipoGanadorWalkover";
        // $resultado = mysqli_query($conectar, $insertarEquipo1);

        if($resultado){
          //validar si se encuentra el equipo perdedor en la tabla
          $validarEquipo2 = $parametro->TraerEquipoTablaPosicion($equipoPerdedorWalkover,$idCampeonato); 
          $equipoPerdedorWalkover = $validarEquipo2->id;
        //   $validarEquipo2 = "SELECT * FROM TablaPosicion where idEquipo = $equipoPerdedorWalkover and idCampeonato = $idCampeonato";
        //   $resultado4 = mysqli_query($conectar, $validarEquipo2);
        //   $row2 = $resultado4->fetch_assoc();
        //   $equipoPerdedorWalkover = $row2['id'];

            $resultado2 = $resultado = $parametro->ActualizarTablaPosicionWalkoverPerdedor($equipoPerdedorWalkover,$idCampeonato); 
        //   $insertarEquipo2 = "UPDATE TablaPosicion SET  partidosPerdidos = partidosPerdidos + 1 where id = $equipoPerdedorWalkover";
        //   $resultado2 = mysqli_query($conectar, $insertarEquipo2);

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
   

    $parametro = new parametros();
    $idPartido = $_POST["idPartido"];
    $idCampeonato =$_POST["idCampeonato"];
    $idSede = $_POST["idSede"];
    $equipo1 = $_POST["equipo1"];
    $equipo2 = $_POST["equipo2"];
    $fecha = $_POST["fecha"];
    $modo = $_POST["modo"];
    $observacion = $_POST["observacion"];
    $estadoObservacion = '';
    $idEquipoObservado = 'null';
    $PrecioObservacion = 'null';

    $idHecho = $_POST["idHecho"];
    $idJugador =$_POST["idJugador"];
    $equipo = $_POST["equipo"];
    $walkover = $_POST["walkover"];

    $codProgramacion = $_POST["codProgramacion"];

    $array_idHecho = explode(",",$idHecho);
    $array_idJugador = explode(",",$idJugador);
    $array_equipo = explode(",",$equipo);

    $Consultar = $parametro->TraerEquipoCampeon($idCampeonato); 
    //$idCampeon = $Consultar->id;
    // $Consultar = "SELECT * FROM EquipoCampeon where idCampeonato = $idCampeonato";
    // $resultado3 = mysqli_query($conectar, $Consultar);
    // $row = $resultado3->fetch_assoc();
    // $idCampeon = $row['id'];

    if($Consultar > 0){
        echo 4;
        return false;
    }
 

    if($observacion != "" || $observacion != null){
        $estadoObservacion = "Pendiente";
        $idEquipoObservado = $_POST["idEquipoObservado"];
        $PrecioObservacion = $_POST["precioObservacion"];
    }

    $resultado = $parametro->InsertarPartido($idPartido,$equipo1,$equipo2,$idSede,$fecha,$idCampeonato,$modo,$observacion,$estadoObservacion,$idEquipoObservado,$PrecioObservacion); 
    // $insertar = "INSERT INTO Partido VALUES($idPartido,$equipo1,$equipo2,$idSede,'$fecha',$idCampeonato,'$modo','$observacion','$estadoObservacion','',$idEquipoObservado,$PrecioObservacion)";
    // $resultado = mysqli_query($conectar, $insertar);


    if($resultado){
        if($idHecho != "" || $idHecho != null || $idHecho != 0){
            $insertarHechos = "";
            for ($i=0; $i < count($array_idHecho); $i++){
                $resultado2 = $parametro->InsertarHechoPartido($array_idHecho[$i],$array_idJugador[$i],$array_equipo[$i],$idPartido);
                // $insertarHechos = "INSERT INTO HechosPartido values(null,$array_idHecho[$i],$array_idJugador[$i],'$array_equipo[$i]',$idPartido,0,'Pendiente')";
                // $resultado2 = mysqli_query($conectar, $insertarHechos);
            }
    
            if($resultado2){

                $parametro->actualizarProgramacionPartidos($codProgramacion);
                // $insertar = "UPDATE programacionPartidos SET estado ='Completado' where codProgramacion = $codProgramacion ";
                // $resultado = mysqli_query($conectar, $insertar);
                echo 1;
            }
            else{
                $parametro->eliminarPartido($idPartido);
                // $eliminar = "DELETE FROM Partido where id = $idPartido";
                // $resultado = mysqli_query($conectar, $eliminar);
                echo 2;
            }
        }
        else{
            echo 1;
        }
    }
    else
    {
        echo 3;
    }
}


// if($tipo == "UltimoPartido"){

//     $id = $_POST["id"];
//     $datos = array();
//     $Consultar = "SELECT * FROM Partido order by id desc";
//     $resultado = mysqli_query($conectar, $Consultar);
//     $datos = mysqli_fetch_assoc($resultado);

//     if($resultado){
//         echo json_encode($datos,JSON_UNESCAPED_UNICODE);
//     }
//     else{
//         echo 'error';
//     } 
// }

// if($tipo == "UltimoTorneo"){


//     $datos = array();
//     $Consultar = "SELECT * FROM Campeonato where estado = 'En Curso'";
//     $resultado = mysqli_query($conectar, $Consultar);
//     $datos = mysqli_fetch_assoc($resultado);

//     if($resultado){
//         echo json_encode($datos,JSON_UNESCAPED_UNICODE);
//     }
//     else{
//         echo 'error';
//     } 
// }

if($tipo == "TorneoFinalizado"){
    

    // $Consultar = "SELECT * FROM Campeonato where estado = 'En Curso'";
    // $resultado = mysqli_query($conectar, $Consultar);
    // $row = $resultado->fetch_assoc();
    // $idCampeonato = $row['id'];

    // $Consultar = "SELECT * FROM EquipoCampeon where idCampeonato = $idCampeonato";
    // $resultado3 = mysqli_query($conectar, $Consultar);
    // $row = $resultado3->fetch_assoc();
    // $idCampeon = $row['id'];
 
    $parametro = new parametros();
    $resultado = $parametro->VerificarTorneoFinalizado(); 
    $idCampeon = $resultado->id;

   
        if($idCampeon == ""){
            echo "abierto";
        }
        else{
            echo "cerrado";
        }
   
}

// if($tipo == "UltimoSede"){

//     $datos = array();
//     $Consultar = "SELECT * FROM Sede where estado = 'Habilitado'";
//     $resultado = mysqli_query($conectar, $Consultar);
//     $datos = mysqli_fetch_assoc($resultado);

//     if($resultado){
//         echo json_encode($datos,JSON_UNESCAPED_UNICODE);
//     }
//     else{
//         echo 'error';
//     } 
// }

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


        // $registrar = "SELECT j.id as idJugador,j.nombre,j.apellidos,j.nroCamiseta,e.nombreEquipo FROM Jugador as j 
        // left join Equipo as e on e.id = j.idEquipo 
        // where e.id = $idEquipo1 or e.id = $idEquipo2
        // order by e.nombreEquipo,j.nombre ASC";
        // $resultado1 = mysqli_query($conectar, $registrar);
      
        $parametro = new parametros();
      
        $resultado = $parametro->TraerJugadoresEquipos($idEquipo1,$idEquipo2); 
        $resultado->MoveFirst();
       $tabla = "";
       $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                   <thead>
                       <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Equipo</th>
                        <th>Nro</th>           
                       </tr>
                   </thead>
                   <tbody > ';
    
       $cont = 0;
     
       while (!$resultado->EndOfSeek()) {
        $row = $resultado->Row();
            $tabla .= "<tr>";
            $tabla .= "<td data-title=''><button type='button' class='btn btn-white btn-sm checkbox-toggle' onclick='DatosJugador(".$row->idJugador.")'><i class='fas fa-check'></i></button></td>";
            $tabla .= "<td data-title=''>" . $row->nombre . " " . $row->apellidos . "</td>";
            $tabla .= "<td data-title=''>" . $row->nombreEquipo . "</td>";
            $tabla .= "<td data-title=''>" . $row->nroCamiseta . "</td>";
            $tabla .= "</tr>";
        }
       $tabla .= "</tbody>
               
               </table>";
       echo  $tabla;   
}

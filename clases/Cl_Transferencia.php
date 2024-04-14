<?php

session_start();
include("conexion.php");

require_once 'parametros.php';
$parametro = new parametros();

$tipo = $_GET["op"];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if($tipo == "BuscarJugador"){

    $idEquipo = $_POST["id"];

    $Consultar = "SELECT id,nombre,apellidos FROM Jugador where idEquipo = $idEquipo";
    $resultado = mysqli_query($conectar, $Consultar);
    $options="";
    while ($row=$resultado->fetch_array(MYSQLI_ASSOC)) { 
        $options.="<option value=\"$row[id]\">$row[nombre] $row[apellidos]</option>"; 
    }

    if($resultado){
        echo $options;
    }
    else{
        echo 'error';
    } 
}

if($tipo == "RegistrarTransferencia"){

    $idJugador  = $_POST["idJugador"];
    $idEquipoOrigen  = $_POST["idEquipoOrigen"];
    $idEquipoDestino  = $_POST["idEquipoDestino"];
    $fecha  = $_POST["fecha"];
    $precio  = $_POST["precio"];
    $idCampeonato = $_POST["idCampeonato"];
    
    //Validamos que no se repide el mismo numero de camiseta de un juegador en el mismo equipo
    $ConsultarNumeroCamiseta = $parametro->validarNroJugador($idEquipoDestino,$idJugador); 
  
    // $ConsultarNumeroCamiseta = "SELECT * FROM Jugador WHERE idEquipo = $idEquipoDestino and nroCamiseta = (SELECT nroCamiseta from Jugador where id = $idJugador)";
    // $resultado3 = mysqli_query($conectar, $ConsultarNumeroCamiseta);
    // $row = $resultado3->fetch_assoc();
    // $totalJugadores = $row['id'];
    
    if($ConsultarNumeroCamiseta > 0){
        echo 4;      
    }
    else{
        $resultado = $parametro->RegistrarTransferencia($idJugador,$idEquipoOrigen,$idEquipoDestino,$fecha,$precio,$idCampeonato); 
        // $registrar = "INSERT INTO Transferencia values(null,$idJugador,$idEquipoOrigen,$idEquipoDestino,'$fecha',$precio,$idCampeonato)";
        // $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){

            $resultado2 = $parametro->actualizarEquipoJugador($idJugador,$idEquipoDestino); 
            // $actualizar = "UPDATE Jugador SET idEquipo = $idEquipoDestino where id = $idJugador";
            // $resultado2 = mysqli_query($conectar, $actualizar);

            if($resultado2){
                echo '1';
            }
            else{
                //eliminar la ultima transferencia porque no se pude actualizar el nuevo equipo del jugador
                // $eliminar = "DELETE FROM Transferencia where idJugador = $idJugador and fecha = '$fecha'";
                // $resultado = mysqli_query($conectar, $eliminar);
                $db->TransactionRollback();
                $db->Kill();
                echo '2';
            }
        }
        else{
            echo '3';
        }
    }
}

if($tipo == "ListarTransferencias"){

    $idEquipoDestino = $_POST["idEquipo"];
    $idCampeonato = $_POST["idCampeonato"];
    $nombreJugador = $_POST["nombreJugador"];
   

    $resultado1 = $parametro->ListarTransferencias($nombreJugador,$idCampeonato,$idEquipoDestino); 
   
    $resultado1->MoveFirst();
    // $registrarInventario = "SELECT j.nombre,j.apellidos, e1.nombreEquipo as EquipoInicial, e.nombreEquipo as EquipoDestino,c.nombre as Campeonato,
    //                         t.fecha, t.precioTransferencia
    //                         FROM Transferencia as t 
    //                         left join Jugador as j on j.id = t.idJugador
    //                         left join Equipo as e on e.id = t.idEquipo
    //                         left join Equipo as e1 on e1.id = t.EquipoInicial
    //                         left join Campeonato as c on c.id = t.idCampeonato
    //                         where c.estado = 'En Curso'
    //                         ";
    // $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jugador</th>
                            <th>Equipo Inicial</th>
                            <th>Equipo Destino</th>
                            <th>Campeonato</th>
                            <th>Fecha Traspaso</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        //if ($resultado1) {
            while (!$resultado1->EndOfSeek()) {
                $row = $resultado1->Row();
                $cont++;
                $EquipoDestino= utf8_encode($row->EquipoDestino);
                $EquipoInicial= utf8_encode($row->EquipoInicial);
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $row->nombre . " " . $row->apellidos . "</td>";
                $tabla .= "<td data-title=''>".$row->EquipoInicial."</td>";  
                $tabla .= "<td data-title=''>".$row->EquipoDestino."</td>";             
                $tabla .= "<td data-title=''>" . $row->Campeonato . "</td>";                
                $tabla .= "<td data-title=''>" . $row->fecha . "</td>";
                $tabla .= "<td data-title=''>" . $row->precioTransferencia . "</td>";
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
      //  }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}



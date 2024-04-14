<?php

session_start();
include("conexion.php");

$tipo = $_GET["op"];


if($tipo == "UltimoTorneo"){

    $datos = array();
    $Consultar = "SELECT * FROM Campeonato where estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    } 
}

if($tipo == "RegistrarInscripcion"){

    $idEquipo  = $_POST["idEquipo"];
    $monto = $_POST["monto"];
    $fecha  = $_POST["fecha"];
    $idCampeonato = $_POST["idCampeonato"];
    
    $gestion = explode("-", $fecha); 

      //Validamos que un equipo no este registrado 2 veces en el mismo campeonato
      $consultarEquipo = "SELECT id FROM Inscripcion where idCampeonato = $idCampeonato and idEquipo = $idEquipo";
      $resultado2 = mysqli_query($conectar, $consultarEquipo);
      $row = $resultado2->fetch_assoc();
      $totalEquipo = $row['id'];

        if($totalEquipo == ""){

            $registrar = "INSERT INTO Inscripcion values(null,$idCampeonato,$idEquipo,$monto,'$fecha')";
            $resultado = mysqli_query($conectar, $registrar);

            $registrar = "INSERT INTO TablaPosicion values(null,$idEquipo,$idCampeonato,0,0,0,0,0,0,null)";
            $resultado = mysqli_query($conectar, $registrar);
        
            if($resultado){
                echo '1';
            }
            else{
                echo '2';
            }
        }
        else{
            echo '3';
        }

}

if($tipo == "precioInscripcion"){

    $consultar = "SELECT precio FROM configuracionCobros where motivo = 'Inscripcion'";
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


if($tipo == "EditarEquipoInscrito"){

    $idEquipo  = $_POST["idEquipo"];
    $monto = $_POST["monto"];
    $fecha  = $_POST["fecha"];
    $idCampeonato = $_POST["idCampeonato"];
    $id = $_POST["idInscripcion"];

        $registrar = "UPDATE Inscripcion SET idEquipo = $idEquipo, inscripcion = $monto, fecha ='$fecha' where id = $id";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}





if($tipo == "DatoEquipoInscrito"){

    $id = $_POST["id"];
    $datos = array();
    $Consultar = "SELECT * FROM Inscripcion where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    }   
}



if($tipo == "ListaEquiposInscritos"){

    $consultar = "SELECT i.id as idInscripcion,c.nombre as campeonato,e.nombreEquipo,i.fecha,i.inscripcion FROM Inscripcion as i
                LEFT JOIN Campeonato as c on c.id = i.idCampeonato
                LEFT join Equipo as e on e.id = i.idEquipo
                where c.estado = 'En Curso' order by i.fecha desc";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Equipo</th>
                            <th>Torneo</th>
                            <th>Inscripción</th>
                            <th>Fecha Pago</th>
                            <th>Accion</th>        
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreEquipo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['inscripcion'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";              
                $tabla .= "<td data-title=''><button type='button' class='btn btn-success btn-sm' onclick='ModalEditarInscripcion(" . $listado['idInscripcion'] . ")'>Editar</button></td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "FiltrarInscripciones"){


    $idEquipo = $_POST["idEquipo"];
    $idCampeonato = $_POST["idCampeonato"];

    $consulta ="";

    if($idCampeonato != "todos" && $idEquipo != "todos"){
        $consulta = "where i.idEquipo = $idEquipo and i.idCampeonato = $idCampeonato";
    }
    else{
        if($idCampeonato == "todos"){
            $consulta = "where i.idEquipo = $idEquipo";
        }
        else{
            if($idEquipo == "todos"){
                $consulta = "where i.idCampeonato = $idCampeonato";
            }
        }
    }

    $consultar = "SELECT i.id as idInscripcion,c.nombre as campeonato,e.nombreEquipo,i.fecha,i.inscripcion FROM Inscripcion as i
                LEFT JOIN Campeonato as c on c.id = i.idCampeonato
                LEFT join Equipo as e on e.id = i.idEquipo $consulta order by c.nombre,e.nombreEquipo asc";
                
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Equipo</th>
                            <th>Torneo</th>
                            <th>Inscripción</th>
                            <th>Fecha Pago</th>                                
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreEquipo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['inscripcion'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";
               
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}
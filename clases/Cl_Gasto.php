<?php

session_start();
$idRol = $_SESSION['idRol'];   
$idEquipoDelegado = $_SESSION['idEquipo'];

include("conexion.php");

$tipo = $_GET["op"];


if($tipo == "RegistrarPago"){

    $MotivoPago  = $_POST["MotivoPago"];
    $totalPago  = $_POST["totalPago"];
    $fechaPago = $_POST["fechaPago"];
    $idCampeonato  = $_POST["idCampeonato"];


        $registrar = "INSERT INTO Gasto VALUES(null,'$MotivoPago','$fechaPago',0,0,$totalPago,$idCampeonato)";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo 1;
        }
        else{
            echo 2;
        }
}

if($tipo == "RegistrarGasto"){

    $MotivoGasto  = $_POST["MotivoGasto"];
    $otros = $_POST["otros"];
    $totalGasto  = $_POST["totalGasto"];
    $fechaGasto = $_POST["fechaGasto"];
    $idCampeonato2  = $_POST["idCampeonato2"];
    $cantidad = $_POST["cantidad"];
    $precio  = $_POST["precio"];

    if($MotivoGasto == "Otros"){
        $MotivoGasto = $otros;
    }
    
        $registrar = "INSERT INTO Gasto VALUES(null,'$MotivoGasto','$fechaGasto',$cantidad,$precio,$totalGasto,$idCampeonato2)";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo '1';
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


if($tipo == "TotalPagoCancha"){

    $idCampeonato = $_POST["idCampeonato"];
    $condicion = "";
    if($idCampeonato == "" || $idCampeonato == null){
        $condicion = "c.estado = 'En Curso'";
    }
    else{
        $condicion = "c.id = $idCampeonato";
    }
       
        $Consultar = "SELECT SUM(g.total) as total FROM Gasto as g 
        LEFT JOIN Campeonato as c on c.id = g.idCampeonato
        where  motivoGasto = 'Cancha' and $condicion";
        $resultado = mysqli_query($conectar, $Consultar);
        $row = $resultado->fetch_assoc();
        $totalGastoCancha = $row['total'];

        if($resultado){
            echo $totalGastoCancha;
        }
        else{
            echo 'error';
        }
}


if($tipo == "TotalPagoArbitraje"){

    $idCampeonato = $_POST["idCampeonato"];
    $condicion = "";
    if($idCampeonato == "" || $idCampeonato == null){
        $condicion = "c.estado = 'En Curso'";
    }
    else{
        $condicion = "c.id = $idCampeonato";
    }
       
    $Consultar = "SELECT SUM(g.total) as total FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where motivoGasto = 'Arbitraje' and $condicion";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalGastoArbitraje = $row['total'];

    if($resultado){
        echo $totalGastoArbitraje;
    }
    else{
        echo 'error';
    }
}


if($tipo == "TotalGastosInternos"){

    $idCampeonato = $_POST["idCampeonato"];
    $condicion = "";
    if($idCampeonato == "" || $idCampeonato == null){
        $condicion = "c.estado = 'En Curso'";
    }
    else{
        $condicion = "c.id = $idCampeonato";
    }
       
    $Consultar = "SELECT SUM(g.total) as total FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where motivoGasto != 'Cancha' and motivoGasto != 'Arbitraje' and $condicion";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalGastosInternos = $row['total'];

    if($resultado){
        echo $totalGastosInternos;
    }
    else{
        echo 'error';
    }
}

if($tipo == "TotalGastado"){

    $idCampeonato = $_POST["idCampeonato"];
    $condicion = "";
    if($idCampeonato == "" || $idCampeonato == null){
        $condicion = "c.estado = 'En Curso'";
    }
    else{
        $condicion = "c.id = $idCampeonato";
    }

    $Consultar = "SELECT SUM(g.total) as total FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where motivoGasto = 'Cancha'and $condicion";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalGastoCancha = $row['total'];

    $Consultar = "SELECT SUM(g.total) as total FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where motivoGasto = 'Arbitraje' and $condicion";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalGastoArbitraje = $row['total'];
    
    
    $Consultar = "SELECT SUM(g.total) as total FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where motivoGasto != 'Cancha' and motivoGasto != 'Arbitraje' and $condicion";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalGastosInternos = $row['total'];


    if($resultado){
        $total = $totalGastoCancha + $totalGastoArbitraje + $totalGastosInternos;
        echo $total;
    }
    else{
        echo 'error';
    }
}


if($tipo == "ListarGastos"){

  
    $consultar = "SELECT g.*,c.nombre as nombreCampeonato FROM Gasto as g 
                    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
                    where c.estado = 'En Curso' AND g.motivoGasto != 'Cancha' and g.motivoGasto != 'Arbitraje'
                    order by g.fecha DESC";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Motivo</th>
                    <th>Fecha</th>
                    <th>Cant.</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['motivoGasto'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['cantidad'] . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['precio'] . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['total'] . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "ListarPagos"){

    $consultar = "SELECT * from (SELECT * FROM Gasto where motivoGasto = 'Cancha' or motivoGasto = 'Arbitraje') as consulta
                    LEFT JOIN Campeonato as c on c.id = consulta.idCampeonato
                    where c.estado = 'En Curso'  order by consulta.fecha desc";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";

            $tabla .= '<table id="example3" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Motivo</th>
                    <th>Fecha</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['motivoGasto'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['total'] . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "FiltrarGastosInternos"){

    $idCampeonato = $_POST["idCampeonato"];

    $consultar = "SELECT * from (SELECT * FROM Gasto where motivoGasto != 'Cancha' and motivoGasto != 'Arbitraje' order by fecha DESC) as consulta where idCampeonato = $idCampeonato";
    $resultado1 = mysqli_query($conectar, $consultar);
      
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Motivo</th>
                            <th>Fecha</th>
                            <th>Cant.</th>
                            <th>Precio</th>
                            <th>Total</th>     
                        </tr>
                    </thead>
                    <tbody > ';
    
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['motivoGasto'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['cantidad'] . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['precio'] . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['total'] . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo $tabla;
}

if($tipo == "FiltrarPagosCancha"){

    $idCampeonato = $_POST["idCampeonato"];

    $consultar = "SELECT * from (SELECT * FROM Gasto where motivoGasto = 'Cancha' or motivoGasto = 'Arbitraje' order by fecha DESC) as consulta
                 where idCampeonato = $idCampeonato";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";

            $tabla .= '<table id="example3" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Motivo</th>
                    <th>Fecha</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['motivoGasto'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['total'] . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}




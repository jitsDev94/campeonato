<?php

session_start();
include("conexion.php");

$tipo = $_GET["op"];



if($tipo == "RegistrarTorneo"){

    $nombre  = $_POST["nombre"];
    $fecha = $_POST["fecha"];
    $tipoCampeonato = $_POST["tipoCampeonato"];
    
        $actualizar = "UPDATE Campeonato SET estado = 'Concluido'";
        $resultado = mysqli_query($conectar, $actualizar);

        if($resultado){

            $registrar = "INSERT INTO Campeonato values(null,'$nombre','$tipoCampeonato','$fecha','En Curso')";
            $resultado1 = mysqli_query($conectar, $registrar);
        
            if($resultado1){
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

if($tipo == "TorneoFinalizado"){
    
    $Consultar = "SELECT * FROM Campeonato where estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $idCampeonato = $row['id'];

    $Consultar = "SELECT * FROM EquipoCampeon where idCampeonato = $idCampeonato";
    $resultado3 = mysqli_query($conectar, $Consultar);
    $row = $resultado3->fetch_assoc();
    $idCampeon = $row['id'];

    if($resultado){
        if($idCampeon == ""){
            echo "abierto";
        }
        else{
            echo "cerrado";
        }
    }
    else{
        echo 'error';
    } 
}


if($tipo == "RegistrarDirectiva"){

    $nombre  = $_POST["nombre"];
    $fecha = $_POST["fecha"];
    $cargo  = $_POST["cargo"];
    $idCampeonato = $_POST["idCampeonato"];
    
    $gestion = explode("-", $fecha); 

        $registrar = "INSERT INTO Directiva values(null,'$nombre','$cargo',$gestion[0],'$fecha',$idCampeonato,'Vigente')";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}


if($tipo == "EditarMiembroDirectiva"){

    $nombre  = $_POST["nombre"];
    $fecha = $_POST["fecha"];
    $cargo  = $_POST["cargo"];
    $id = $_POST["id"];

        $registrar = "UPDATE Directiva SET nombre = '$nombre', cargo = '$cargo', fechaNombramiento ='$fecha' where id = $id";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}


if($tipo == "ReiniciarDirectiva"){

        $actualizar = "UPDATE Directiva SET estado = 'Concluido'";
        $resultado = mysqli_query($conectar, $actualizar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}


if($tipo == "DatosMiembrosDirectiva"){

    $id = $_POST["id"];
    $datos = array();
    $Consultar = "SELECT * FROM Directiva where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    }   
}


if($tipo == "EliminarDirectiva"){

    $id  = $_POST["id"];
    
        $registrar = "DELETE FROM Directiva where id = $id";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}



if($tipo == "ListaTorneo"){

   // $consultar = "SELECT * FROM Campeonato order by fechaInicio desc";
    $consultar = "SELECT camp.id,camp.nombre,camp.tipo,camp.fechaInicio,camp.estado,(SELECT sum(precio) as GanaciaTarjetas FROM HechosPartido  as hp
    LEFT JOIN Partido as p on p.id = hp.idPartido
    LEFT JOIN Campeonato as c on c.id = p.idCampeonato
    where c.id = camp.id) as GanaciaTarjetas, 
    (SELECT sum(i.inscripcion) as gananciaInscripcion FROM Inscripcion as i
    LEFT JOIN Campeonato as c on c.id = i.idCampeonato
    WHERE c.id = camp.id) as gananciaInscripcion,
    (SELECT sum(pa.total) as totalArbitraje FROM PagoArbitraje as pa
    LEFT JOIN Campeonato as c on c.id = pa.idCampeonato
    WHERE  c.id = camp.id) as totalArbitraje,
    (SELECT sum(m.total) as totalMultas FROM Multa as m
    LEFT JOIN Campeonato as c on c.id = m.idCampeonato
    WHERE c.id = camp.id and m.estado = 'Pagado') as totalMultas,
    (SELECT sum(p.precioObservacion) as totalPrecioObservacion FROM Partido as p
    LEFT JOIN Campeonato as c on c.id = p.idCampeonato
    where c.id = camp.id and p.estadoObservacion = 'Rechazado') as totalPrecioObservacion,
    (SELECT sum(precioTransferencia) as GanaciaTransferencia FROM Transferencia t  
    LEFT JOIN Campeonato as c on c.id = t.idCampeonato
    where c.id = camp.id) as GanaciaTransferencia,
    (SELECT SUM(g.total) as totalGasto FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where c.id = camp.id) as totalGasto
    FROM Campeonato camp";
    $resultado1 = mysqli_query($conectar, $consultar);
   
   

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre Torneo</th>
                            <th>Tipo Torneo</th>
                            <th>Fecha Inicio</th> 
                            <th>Ganancia</th>
                            <th>Estado</th>	             
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $ganancia = 0;
                $GanaciaTarjetas =0;
                $gananciaInscripcion =0;
                $totalArbitraje =0;
                $totalMultas =0;
                $totalPrecioObservacion =0;
                $GanaciaTransferencia =0;
                $totalGasto =0;
                $tabla .= "<tr>";
                if($listado['GanaciaTarjetas']  != null && $listado['GanaciaTarjetas'] != ''){
                    $GanaciaTarjetas =$listado['GanaciaTarjetas'];
                }
                if($listado['gananciaInscripcion']  != null && $listado['gananciaInscripcion'] != ''){
                    $gananciaInscripcion =$listado['gananciaInscripcion'];
                }
                if($listado['totalArbitraje']  != null && $listado['totalArbitraje'] != ''){
                    $totalArbitraje =$listado['totalArbitraje'];
                }
                if($listado['totalMultas']  != null && $listado['totalMultas'] != ''){
                    $totalMultas =$listado['totalMultas'];
                }
                if($listado['totalPrecioObservacion']  != null && $listado['totalPrecioObservacion'] != ''){
                    $totalPrecioObservacion =$listado['totalPrecioObservacion'];
                }
                if($listado['GanaciaTransferencia']  != null && $listado['GanaciaTransferencia'] != ''){
                    $GanaciaTransferencia =$listado['GanaciaTransferencia'];
                }
                if($listado['totalGasto']  != null && $listado['totalGasto'] != ''){
                    $totalGasto =$listado['totalGasto'];
                }

                $ganancia = ($GanaciaTarjetas + $gananciaInscripcion + $totalArbitraje + $totalMultas + $totalPrecioObservacion + $GanaciaTransferencia) - $totalGasto ;
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['tipo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaInicio'] . "</td>";
                $tabla .= "<td data-title=''>" . number_format($ganancia,2) . "</td>";
                $tabla .= "<td data-title=''>" . $listado['estado'] . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "ListaDirectiva"){

    $consultar = "SELECT d.*,c.nombre as campeonato FROM Directiva as d left join Campeonato as c on c.id = d.idCampeonato where d.estado = 'Vigente'";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Torneo</th>
                            <th width="100px">Acción</th>         
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['cargo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";
                $tabla .= "<td data-title=''><button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='modalEditarDirectiva(".$listado['id'].")'><i title= 'Editar' class='fas fa-pen'></i></button>";      
                $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarEliminar(".$listado['id'].")'><i title= 'Eliminar' class='fas fa-trash'></i></button>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "FiltrarDirectivas"){


    $nombre = $_POST["nombre"];
    $idCampeonato = $_POST["idCampeonato"];

    $consulta ="";

    if($nombre != "" && $idCampeonato != null){
        $consulta = "where d.nombre like '%$nombre%' and idCampeonato = $idCampeonato";
    }
    else{
        if($nombre != ""){
            $consulta = "where d.nombre like '%$nombre%'";
        }
        else{
            if($idCampeonato != null){
                $consulta = "where d.idCampeonato = $idCampeonato";
            }
        }
    }

    $consultar = "SELECT d.*,c.nombre as campeonato FROM Directiva as d left join Campeonato as c on c.id = d.idCampeonato $consulta";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Torneo</th>
                            <th>Gestion</th> 
                            <th>Fecha Inicio</th>        
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['cargo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['gestion'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaNombramiento'] . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}






if($tipo == "Totales"){

    $id = $_POST["id"];
    $datos = array();
    $Consultar = "SELECT * FROM Directiva where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    }   
}

if($tipo == "TotalGanacias"){

    $id = $_POST["id"];
    $datos = array();
    $Consultar = "SELECT * FROM Directiva where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    }   
}

if($tipo == "T"){

    $id = $_POST["id"];
    $datos = array();
    $Consultar = "SELECT * FROM Directiva where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    }   
}


if($tipo == "TotalIngresos"){
   
    //ganacia total en tarjeta rojas y amarillas
    $Consultar = "SELECT sum(precio) as GanaciaTarjetas FROM HechosPartido  as hp
                    LEFT JOIN Partido as p on p.id = hp.idPartido
                    LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                    where c.estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $GanaciaTarjetas = $row['GanaciaTarjetas'];


     //ganacia total en incripcion
    $Consultar = "SELECT sum(i.inscripcion) as gananciaInscripcion FROM Inscripcion as i
                    LEFT JOIN Campeonato as c on c.id = i.idCampeonato
                    WHERE c.estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $gananciaInscripcion = $row['gananciaInscripcion'];

    //ganacia total en arbitraje
    $Consultar = "SELECT sum(pa.total) as totalArbitraje FROM PagoArbitraje as pa
                    LEFT JOIN Campeonato as c on c.id = pa.idCampeonato
                    WHERE c.estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalArbitraje = $row['totalArbitraje'];

      //ganacia total en multas
    $Consultar = "SELECT sum(m.total) as totalMultas FROM Multa as m
                LEFT JOIN Campeonato as c on c.id = m.idCampeonato
                WHERE c.estado = 'En Curso' and m.estado = 'Pagado'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalMultas = $row['totalMultas'];

      //ganacia total en observaciones rechazadas
    $Consultar = "SELECT sum(p.precioObservacion) as totalPrecioObservacion FROM Partido as p
                LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                where c.estado = 'En Curso' and p.estadoObservacion = 'Rechazado'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalPrecioObservacion = $row['totalPrecioObservacion'];

      //ganacia total en Transferencia
    $Consultar = "SELECT sum(precioTransferencia) as GanaciaTransferencia FROM Transferencia t  
      LEFT JOIN Campeonato as c on c.id = t.idCampeonato
      where c.estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $GanaciaTransferencia = $row['GanaciaTransferencia'];

    $total = $GanaciaTarjetas + $gananciaInscripcion + $totalArbitraje + $totalMultas + $totalPrecioObservacion + $GanaciaTransferencia;


    if($resultado){
        echo $total;
    }
    else{
        echo 'error';
    }   
}

if($tipo == "TotalGastos"){
   
    //gastos totales en cancha, arbitraje y gastos internos
    $Consultar1 = "SELECT SUM(g.total) as totales FROM Gasto as g 
            LEFT JOIN Campeonato as c on c.id = g.idCampeonato
            where c.estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar1);
    $row = $resultado->fetch_assoc();
    $totales = $row['totales'];

    if($resultado){
        echo $totales;
    }
    else{
        echo 'error';
    }   
}


//total ganancia del campeonato actual
if($tipo == "TotalGananciaActual"){
   
    //ganacia total en tarjeta rojas y amarillas
    $Consultar = "SELECT sum(precio) as GanaciaTarjetas FROM HechosPartido  as hp
                    LEFT JOIN Partido as p on p.id = hp.idPartido
                    LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                    where c.estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $GanaciaTarjetas = $row['GanaciaTarjetas'];


     //ganacia total en incripcion
    $Consultar = "SELECT sum(i.inscripcion) as gananciaInscripcion FROM Inscripcion as i
                    LEFT JOIN Campeonato as c on c.id = i.idCampeonato
                    WHERE c.estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $gananciaInscripcion = $row['gananciaInscripcion'];

    //ganacia total en incripcion
    $Consultar = "SELECT sum(pa.total) as totalArbitraje FROM PagoArbitraje as pa
                    LEFT JOIN Campeonato as c on c.id = pa.idCampeonato
                    WHERE c.estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalArbitraje = $row['totalArbitraje'];

      //ganacia total en multas
    $Consultar = "SELECT sum(m.total) as totalMultas FROM Multa as m
                    LEFT JOIN Campeonato as c on c.id = m.idCampeonato
                    WHERE c.estado = 'En Curso' and m.estado = 'Pagado'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalMultas = $row['totalMultas'];

      //ganacia total en observaciones rechazadas
    $Consultar = "SELECT sum(p.precioObservacion) as totalPrecioObservacion FROM Partido as p
      LEFT JOIN Campeonato as c on c.id = p.idCampeonato
      where c.estado = 'En Curso' and p.estadoObservacion = 'Rechazado'";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalPrecioObservacion = $row['totalPrecioObservacion'];

       //ganacia total en Transferencia
       $Consultar = "SELECT sum(precioTransferencia) as GanaciaTransferencia FROM Transferencia t  
       LEFT JOIN Campeonato as c on c.id = t.idCampeonato
       where c.estado = 'En Curso'";
     $resultado = mysqli_query($conectar, $Consultar);
     $row = $resultado->fetch_assoc();
     $GanaciaTransferencia = $row['GanaciaTransferencia'];



     //gastos totales en cancha, arbitraje y gastos internos
    $Consultar1 = "SELECT SUM(g.total) as totales FROM Gasto as g 
            LEFT JOIN Campeonato as c on c.id = g.idCampeonato
            where c.estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $Consultar1);
    $row = $resultado->fetch_assoc();
    $totalGastos = $row['totales'];

    $total = $GanaciaTarjetas + $gananciaInscripcion + $totalArbitraje + $totalMultas + $totalPrecioObservacion +$GanaciaTransferencia - $totalGastos;


    if($resultado){
        echo $total;
    }
    else{
        echo 'error';
    }   
}


//total ganancia de todos los campeonatos
if($tipo == "TotalGanancia"){
   
    //ganacia total en tarjeta rojas y amarillas
    $Consultar = "SELECT sum(precio) as GanaciaTarjetas FROM HechosPartido";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $GanaciaTarjetas = $row['GanaciaTarjetas'];


     //ganacia total en incripcion
    $Consultar = "SELECT sum(inscripcion) as gananciaInscripcion FROM Inscripcion";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $gananciaInscripcion = $row['gananciaInscripcion'];

    //ganacia total en incripcion
    $Consultar = "SELECT sum(total) as totalArbitraje FROM PagoArbitraje";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalArbitraje = $row['totalArbitraje'];

      //ganacia total en multas
    $Consultar = "SELECT sum(total) as totalMultas FROM Multa WHERE estado = 'Pagado'";          
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalMultas = $row['totalMultas'];

    //ganacia total en observaciones rechazadas
    $Consultar = "SELECT sum(precioObservacion) as totalPrecioObservacion FROM Partido where estadoObservacion = 'Rechazado'";     
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalPrecioObservacion = $row['totalPrecioObservacion'];

    //ganacia total en Transferencia
    $Consultar = "SELECT sum(precioTransferencia) as GanaciaTransferencia FROM Transferencia";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $GanaciaTransferencia = $row['GanaciaTransferencia'];

     //gastos totales en cancha, arbitraje y gastos internos
    $Consultar1 = "SELECT SUM(total) as totales FROM Gasto";
    $resultado = mysqli_query($conectar, $Consultar1);
    $row = $resultado->fetch_assoc();
    $totalGastos = $row['totales'];

    $total = $GanaciaTarjetas + $gananciaInscripcion + $totalArbitraje + $totalMultas + $totalPrecioObservacion + $GanaciaTransferencia - $totalGastos;


    if($resultado){
        echo $total;
    }
    else{
        echo 'error';
    }   
}


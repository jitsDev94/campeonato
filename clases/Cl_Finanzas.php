<?php

session_start();
include("conexion.php");

$tipo = $_GET["op"];

if($tipo == "DetallePagoCancha"){
   
    $codCampeonato = $_POST["codCampeonato"];
   
    $consulta = "";

    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    $consultar = "SELECT g.*,c.nombre as nombreCampeonato FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where g.motivoGasto = 'Cancha' $consulta
    order by g.fecha DESC";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Motivo Gasto</th>
                    <th>Fecha Partido</th>
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
                $tabla .= "<td data-title=''>Pago de la cancha</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";   
                $tabla .= "<td data-title=''>Bs. " . $listado['total'] . "</td>";      
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "DetallePagoArbitraje"){
   
    $codCampeonato = $_POST["codCampeonato"];
   
    $consulta = "";

    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    $consultar = "SELECT g.*,c.nombre as nombreCampeonato FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where  g.motivoGasto = 'Arbitraje' $consulta
    order by g.fecha DESC";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Motivo Gasto</th>
                    <th>Fecha Partido</th>
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
                $tabla .= "<td data-title=''>Pago al arbitro</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";   
                $tabla .= "<td data-title=''>Bs. " . $listado['total'] . "</td>";      
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "DetalleGastosInternos"){
   
    $codCampeonato = $_POST["codCampeonato"];
   
    $consulta = "";

    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    $consultar = "SELECT g.*,c.nombre as nombreCampeonato FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where g.motivoGasto != 'Cancha' and g.motivoGasto != 'Arbitraje' $consulta
    order by g.fecha DESC";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Motivo Gasto</th>
                    <th>Fecha</th>
                    <th>Cantidad</th>
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


if($tipo == "DetalleTransferencia"){

    $codCampeonato = $_POST["codCampeonato"];
   
    $consulta = "";

    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }
   
    $consultar = "SELECT t.id,concat(j.nombre,j.apellidos) as jugador,e1.nombreEquipo as EquipoInicial, e2.nombreEquipo AS EquipoDestino,
    t.precioTransferencia,t.fecha FROM Transferencia as t  
    LEFT JOIN Campeonato as c on c.id = t.idCampeonato
    LEFT JOIN Jugador as j on j.id = t.idJugador
    LEFT JOIN Equipo as e1 on e1.id = t.EquipoInicial
    LEFT JOIN Equipo as e2 on e2.id = t.idEquipo
    where 1=1 $consulta";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jugador</th>
                    <th>Equipo Inicial</th>
                    <th>Equipo Destino</th>
                    <th>Precio</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $EquipoInicial = utf8_encode($listado['EquipoInicial']); 
                $EquipoDestino = utf8_encode($listado['EquipoDestino']); 
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['jugador'] . "</td>";
                $tabla .= "<td data-title=''>" . $EquipoInicial . "</td>";
                $tabla .= "<td data-title=''>" . $EquipoDestino . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['precioTransferencia'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";          
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "DetalleObservaciones"){
   
    $codCampeonato = $_POST["codCampeonato"];
   
    $consulta = "";

    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    $consultar = "SELECT p.id as idPartido, p.fechaPartido,p.Observacion,e3.nombreEquipo as nombreEquipoObservado,p.precioObservacion FROM Partido as p 
    LEFT JOIN Equipo as e1 on e1.id = p.idEquipoLocal
    LEFT JOIN Equipo as e2 on e2.id = p.idEquipoVisitante
    LEFT JOIN Equipo as e3 on e3.id = p.equipoObservado
    LEFT join Campeonato as c on c.id = p.idCampeonato
    LEFT join Sede as s on s.id = p.idSede
    where  p.estadoObservacion = 'Rechazado' $consulta";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Equipo Observado</th>
                    <th>Motivo Observación</th>
                    <th>Precio</th>
                    <th width="100px">Fecha Partido</th>
                </tr>
            </thead>
            <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $equipos = utf8_encode($listado['nombreEquipoObservado']); 
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $equipos . "</td>";
                $tabla .= "<td data-title=''>" . $listado['Observacion'] . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['precioObservacion'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaPartido'] . "</td>";        
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "DetalleMulta"){
   
    $codCampeonato = $_POST["codCampeonato"];
   
    $consulta = "";

    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }
   
    $consultar = "SELECT m.id,m.motivoMulta,e.nombreEquipo,m.total,m.fecha FROM Multa as m
    LEFT JOIN Campeonato as c on c.id = m.IdCampeonato
    LEFT JOIN Equipo as e on e.id = m.idEquipo
    where  m.estado = 'Pagado' $consulta";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Equipo</th>
                    <th>Motivo</th>
                    <th>Precio</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $equipos = utf8_encode($listado['nombreEquipo']); 
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $equipos . "</td>";
                $tabla .= "<td data-title=''>" . $listado['motivoMulta'] . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['total'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";        
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "DetalleInscripcion"){
      
    $codCampeonato = $_POST["codCampeonato"];
   
    $consulta = "";

    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    $consultar = "SELECT i.id as idInscripcion,c.nombre as campeonato,e.nombreEquipo,i.fecha,i.inscripcion FROM Inscripcion as i
    LEFT JOIN Campeonato as c on c.id = i.idCampeonato
    LEFT join Equipo as e on e.id = i.idEquipo
    where 1=1 $consulta order by i.fecha DESC";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Equipo</th>
                    <th>Precio</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $equipos = utf8_encode($listado['nombreEquipo']); 
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $equipos . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['inscripcion'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";        
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "DetalleArbitraje"){
   
    $codCampeonato = $_POST["codCampeonato"];
   
    $consulta = "";

    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    $consultar = "SELECT p.id as idPagoArbitraje, p.fecha,p.total,e.nombreEquipo FROM PagoArbitraje as p 
    left join Campeonato as c on c.id = p.idCampeonato 
    left join Equipo as e on e.id = p.idEquipo 
    where 1=1 $consulta order by p.fecha desc";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Equipo</th>
                    <th>Precio</th>
                    <th>Fecha Partido</th>
                </tr>
            </thead>
            <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $equipos = utf8_encode($listado['nombreEquipo']); 
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $equipos . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['total'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";        
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "DetalleTarjetasRojas"){
   
    $codCampeonato = $_POST["codCampeonato"];
   
    $consulta = "";

    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    $consultar = "SELECT concat(j.nombre, ' ' ,j.apellidos) as nombreJugador,hp.precio,c.nombre as nombreCampeonato,hp.Equipo,p.fechaPartido,
    hp.estado FROM HechosPartido  as hp
    LEFT JOIN Partido as p on p.id = hp.idPartido
    LEFT JOIN Campeonato as c on c.id = p.idCampeonato
    LEFT JOIN Jugador as j on j.id = hp.idJugador
    where  hp.idHecho = 3 and hp.estado = 'Pagado' $consulta order by p.fechaPartido ASC";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jugador</th>
                    <th>Equipo</th>
                    <th>Precio</th>
                    <th>Fecha Partido</th>
                </tr>
            </thead>
            <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $equipos = utf8_encode($listado['Equipo']); 
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreJugador'] . "</td>";
                $tabla .= "<td data-title=''>" . $equipos . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['precio'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaPartido'] . "</td>";          
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "DetalleTarjetasAmarillas"){
   
    $codCampeonato = $_POST["codCampeonato"];
   
    $consulta = "";

    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    $consultar = "SELECT concat(j.nombre, ' ' ,j.apellidos) as nombreJugador,hp.precio,c.nombre as nombreCampeonato,hp.Equipo,p.fechaPartido,
    hp.estado FROM HechosPartido  as hp
    LEFT JOIN Partido as p on p.id = hp.idPartido
    LEFT JOIN Campeonato as c on c.id = p.idCampeonato
    LEFT JOIN Jugador as j on j.id = hp.idJugador
    where hp.idHecho = 2 and hp.estado = 'Pagado' $consulta order by p.fechaPartido ASC";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jugador</th>
                    <th>Equipo</th>
                    <th>Precio</th>
                    <th>Fecha Partido</th>
                </tr>
            </thead>
            <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $equipos = utf8_encode($listado['Equipo']); 
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreJugador'] . "</td>";
                $tabla .= "<td data-title=''>" . $equipos . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['precio'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaPartido'] . "</td>";          
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "TarjetasAmarillas"){
   
    $codCampeonato = $_POST["codCampeonato"];
   
    $consulta = "";

    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    //ganacia total en tarjeta rojas y amarillas
    $Consultar = "SELECT sum(precio) as GanaciaTarjetas FROM HechosPartido  as hp
                    LEFT JOIN Partido as p on p.id = hp.idPartido
                    LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                    where  hp.idHecho = 2 $consulta";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $GanaciaTarjetas = $row['GanaciaTarjetas'];

    if($resultado){
        echo $GanaciaTarjetas;
    }
    else{
        echo 'error';
    }   
}

if($tipo == "TarjetasRojas"){

    $codCampeonato = $_POST["codCampeonato"];
   
    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
        $consulta = " and c.estado = 'En Curso'";
    }

     //ganacia total en tarjeta rojas y amarillas
     $Consultar = "SELECT sum(precio) as GanaciaTarjetas FROM HechosPartido  as hp
     LEFT JOIN Partido as p on p.id = hp.idPartido
     LEFT JOIN Campeonato as c on c.id = p.idCampeonato
     where hp.idHecho = 3  $consulta";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $GanaciaTarjetas = $row['GanaciaTarjetas'];

    if($resultado){
        echo $GanaciaTarjetas;
    }
    else{
        echo 'error';
    }   

}

if($tipo == "inscripcion"){

    $codCampeonato = $_POST["codCampeonato"];
   
    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
        $consulta = " and c.estado = 'En Curso'";
    }

     //ganacia total en incripcion
     $Consultar = "SELECT sum(i.inscripcion) as gananciaInscripcion FROM Inscripcion as i
     LEFT JOIN Campeonato as c on c.id = i.idCampeonato
     WHERE 1=1  $consulta";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $gananciaInscripcion = $row['gananciaInscripcion'];

    if($resultado){
        echo $gananciaInscripcion;
    }
    else{
        echo 'error';
    }   
}

if($tipo == "arbitraje"){

    $codCampeonato = $_POST["codCampeonato"];
   
    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    //ganacia total en arbitraje
    $Consultar = "SELECT sum(pa.total) as totalArbitraje FROM PagoArbitraje as pa
                    LEFT JOIN Campeonato as c on c.id = pa.idCampeonato
                    WHERE 1=1  $consulta";
    $resultado = mysqli_query($conectar, $Consultar);
    $row = $resultado->fetch_assoc();
    $totalArbitraje = $row['totalArbitraje'];

    if($resultado){
        echo $totalArbitraje;
    }
    else{
        echo 'error';
    }   
}

if($tipo == "multas"){

    $codCampeonato = $_POST["codCampeonato"];
   
    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

      //ganacia total en multas
      $Consultar = "SELECT sum(m.total) as totalMultas FROM Multa as m
      LEFT JOIN Campeonato as c on c.id = m.idCampeonato
      WHERE  m.estado = 'Pagado'  $consulta";
        $resultado = mysqli_query($conectar, $Consultar);
        $row = $resultado->fetch_assoc();
        $totalMultas = $row['totalMultas'];

        if($resultado){
                echo $totalMultas;
            }
            else{
                echo 'error';
            }   
}

if($tipo == "observaciones"){

    $codCampeonato = $_POST["codCampeonato"];
   
    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

       //ganacia total en observaciones rechazadas
       $Consultar = "SELECT sum(p.precioObservacion) as totalPrecioObservacion FROM Partido as p
       LEFT JOIN Campeonato as c on c.id = p.idCampeonato
       where  p.estadoObservacion = 'Rechazado'  $consulta ";
        $resultado = mysqli_query($conectar, $Consultar);
        $row = $resultado->fetch_assoc();
        $totalPrecioObservacion = $row['totalPrecioObservacion'];

        if($resultado){
                echo $totalPrecioObservacion;
            }
            else{
                echo 'error';
            }   
}

if($tipo == "Transferencia"){

    $codCampeonato = $_POST["codCampeonato"];
   
    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
          $consulta = " and c.estado = 'En Curso'";
    }

    //ganacia total en Transferencias
    $Consultar = "SELECT sum(precioTransferencia) as GanaciaTransferencia FROM Transferencia t  
    LEFT JOIN Campeonato as c on c.id = t.idCampeonato
    where 1=1  $consulta";
   $resultado = mysqli_query($conectar, $Consultar);
   $row = $resultado->fetch_assoc();
   $GanaciaTransferencia = $row['GanaciaTransferencia'];

   if($resultado){
       echo $GanaciaTransferencia;
   }
   else{
       echo 'error';
   }   

}

//GASTOS

if($tipo == "PagoCancha"){
    
    $codCampeonato = $_POST["codCampeonato"];
   
    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    $Consultar = "SELECT SUM(g.total) as total FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where  motivoGasto = 'Cancha' $consulta";
     $resultado = mysqli_query($conectar, $Consultar);
     $row = $resultado->fetch_assoc();
     $totalpagocancha = $row['total'];

     if($resultado){
             echo $totalpagocancha;
         }
         else{
             echo 'error';
         }   
}

if($tipo == "PagoArbitro"){
    
    $codCampeonato = $_POST["codCampeonato"];
   
    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    $Consultar = "SELECT SUM(g.total) as total FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where motivoGasto = 'Arbitraje'  $consulta";
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

if($tipo == "PagoInterno"){
    
    $codCampeonato = $_POST["codCampeonato"];
   
    if($codCampeonato != null && $codCampeonato != "0" && $codCampeonato != ""){
        $consulta = " and c.id = $codCampeonato";
    }
    else{
         $consulta = " and c.estado = 'En Curso'";
    }

    $Consultar = "SELECT SUM(g.total) as total FROM Gasto as g 
    LEFT JOIN Campeonato as c on c.id = g.idCampeonato
    where motivoGasto != 'Cancha' and motivoGasto != 'Arbitraje'  $consulta";
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


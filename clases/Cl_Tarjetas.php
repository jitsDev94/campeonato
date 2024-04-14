<?php

session_start();
$idRol = $_SESSION['idRol'];   
$idEquipoDelegado = $_SESSION['idEquipo'];

include("conexion.php");

$tipo = $_GET["op"];


if($tipo == "RegistrarPagoTarjeta"){

    $precio  = $_POST["precio"];
    $id = $_POST["id"];


        $registrar = "UPDATE HechosPartido SET  precio = $precio ,estado = 'Pagado' where id = $id";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}

if($tipo == "precioTarjetaAmarilla"){

    $consultar = "SELECT precio FROM configuracionCobros where motivo = 'Tarjetas Amarillas'";
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

if($tipo == "precioTarjetaRoja"){

    $consultar = "SELECT precio FROM configuracionCobros where motivo = 'Tarjetas Rojas'";
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


if($tipo == "EliminarTarjetas"){

    $id  = $_POST["id"];
    
        $registrar = "UPDATE HechosPartido SET estado = 'Eliminado' where id = $id";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}


if($tipo == "ListaTarjetasRojas"){

    $condicion = "";

    if($idRol == 3){
        $condicion = "and e.id = $idEquipoDelegado";
    }
    $consultar = "SELECT hp.id as idHp, j.nombre,j.apellidos,hp.Equipo,p.fechaPartido FROM HechosPartido as hp 
                LEFT JOIN Hechos as h on h.id = hp.idHecho 
                LEFT JOIN Jugador as j on j.id = hp.idJugador 
                LEFT JOIN Partido as p on p.id = hp.idPartido
                LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                LEFT JOIN Equipo as e on e.nombreEquipo = hp.Equipo
                where hp.estado = 'Pendiente' and h.nombreHecho = 'Tarjeta Roja' and c.estado = 'En Curso' $condicion
                order by p.fechaPartido desc";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        if($idRol == 3){
            $tabla .= '<table id="example31" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Equipo</th>
                    <th>Fecha</th>    
                </tr>
            </thead>
            <tbody > ';
        }
        else{
            $tabla .= '<table id="example3" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Equipo</th>
                    <th width="80px">Fecha</th>
                    <th width="65px">Acción</th>      
                </tr>
            </thead>
            <tbody > ';
        }
      
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . " " . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['Equipo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaPartido'] . "</td>";
                if($idRol != 3){
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='modalCobrarTarjetaRoja(".$listado['idHp'].")'><i title= 'Regitrar Pago' class='fas fa-hand-holding-usd'></i></button>";      
                $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarEliminar(".$listado['id'].")'><i title= 'Eliminar' class='fas fa-trash'></i></button>";
                }
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "ListaTarjetasAmarillas"){

    $condicion = "";

    if($idRol == 3){
        $condicion = "and e.id = $idEquipoDelegado";
    }

    $consultar = "SELECT hp.id as idHp, j.nombre,j.apellidos,hp.Equipo,p.fechaPartido FROM HechosPartido as hp 
                    LEFT JOIN Hechos as h on h.id = hp.idHecho 
                    LEFT JOIN Jugador as j on j.id = hp.idJugador 
                    LEFT JOIN Partido as p on p.id = hp.idPartido
                    LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                    LEFT JOIN Equipo as e on e.nombreEquipo = hp.Equipo
                    where hp.estado = 'Pendiente' and h.nombreHecho = 'Tarjeta Amarilla' and c.estado = 'En Curso' $condicion
                    order by p.fechaPartido desc";
    $resultado1 = mysqli_query($conectar, $consultar);

        $tabla = "";
        if($idRol == 3){
            $tabla .= '<table id="example21" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Equipo</th>
                    <th>Fecha</th> 
                </tr>
            </thead>
            <tbody > ';
        }
        else{
            $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Equipo</th>
                    <th width="80px">Fecha</th>
                    <th width="65px">Acción</th>    
                </tr>
            </thead>
            <tbody > ';
        }
      
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . " " . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['Equipo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaPartido'] . "</td>";
                if($idRol != 3){
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='modalCobrarTarjetaAmarilla(".$listado['idHp'].")'><i title= 'Regitrar Pago' class='fas fa-hand-holding-usd'></i></button>";      
                $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarEliminar(".$listado['id'].")'><i title= 'Eliminar' class='fas fa-trash'></i></button>";
                }
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "FiltrarTarjetas"){


    $tarjetas = $_POST["tarjetas"];
    $idCampeonato = $_POST["idCampeonato"];
       
    $condicion = "";

    if($idRol == 3){
        $condicion = "and e.id = $idEquipoDelegado";
    }


    $consultar = "SELECT hp.id as idHp, j.nombre,j.apellidos,hp.Equipo,p.fechaPartido,c.nombre as campeonato,hp.estado FROM HechosPartido as hp 
                LEFT JOIN Hechos as h on h.id = hp.idHecho 
                LEFT JOIN Jugador as j on j.id = hp.idJugador 
                LEFT JOIN Partido as p on p.id = hp.idPartido
                LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                LEFT JOIN Equipo as e on e.nombreEquipo = hp.Equipo
                where h.nombreHecho = '$tarjetas' and p.idCampeonato = $idCampeonato $condicion
                order by p.fechaPartido desc";
    $resultado1 = mysqli_query($conectar, $consultar);
  
       if($tarjetas == "Tarjeta Amarilla"){
        $tabla = "";
        $tabla .= '<table id="example2" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Equipo</th>
                            <th>Torneo</th>
                            <th>Fecha</th>        
                            <th>Estado</th>        
                        </tr>
                    </thead>
                    <tbody > ';
       }
       else{
        $tabla = "";
        $tabla .= '<table id="example3" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Equipo</th>
                            <th>Torneo</th>
                            <th>Fecha</th> 
                            <th>Estado</th>        
                        </tr>
                    </thead>
                    <tbody > ';
       }
    
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombre'] . " " . $listado['apellidos'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['Equipo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['campeonato'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaPartido'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['estado'] . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo $tabla;
}




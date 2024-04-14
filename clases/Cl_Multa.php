<?php

session_start();
include("conexion.php");

$idRol = $_SESSION['idRol'];
$idEquipoDelegado = $_SESSION['idEquipo'];
$tipo = $_GET["op"];


if($tipo == "RegistrarMulta"){

    $motivoMulta  = $_POST["motivoMulta"];
    $fecha = $_POST["fecha"];
    $total  = $_POST["total"];
    $idEquipo = $_POST["idEquipo"];
    
    $consulta = "SELECT * from Campeonato where estado = 'En Curso'";
    $ejecutar = mysqli_query($conectar, $consulta) or die(mysqli_error($conectar));
    $row = $ejecutar->fetch_assoc();
    $idCampeonato = $row['id'];


        $registrar = "INSERT INTO Multa values(null,'$motivoMulta',$idEquipo,$total,'$fecha',$idCampeonato,'Pendiente')";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo 1;
        }
        else{
            echo 2;
        }
}

if($tipo == "EliminarMulta"){

    $idMulta  = $_POST["id"];

        $eliminar = "DELETE FROM Multa where id = $idMulta";
        $resultado = mysqli_query($conectar, $eliminar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}

if($tipo == "CobrarMulta"){

    $idMulta  = $_POST["id"];

        $eliminar = "UPDATE Multa SET estado = 'Pagado' where id = $idMulta";
        $resultado = mysqli_query($conectar, $eliminar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}


if($tipo == "ListarMultas"){

    $condicion = "";
    if($idRol == 3){
        $condicion = "and e.id = $idEquipoDelegado";
    }

    $registrarInventario = "SELECT m.id,m.motivoMulta,e.nombreEquipo,m.total,m.total,m.fecha,c.nombre as nombreCampeonato FROM Multa as m
                            LEFT JOIN Campeonato as c on c.id = m.IdCampeonato
                            LEFT JOIN Equipo as e on e.id = m.idEquipo
                            where c.estado = 'En Curso' and m.estado = 'Pendiente' $condicion";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        if($idRol != 3){
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Equipo</th>
                            <th>Total</th>
                            <th>Torneo</th>
                            <th width="170px">Acción</th>
                        </tr>
                    </thead>
                    <tbody > ';
        }
        else{
            $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Motivo Multa</th>
                    <th>Equipo</th>
                    <th>Total</th>
                    <th>Torneo</th>
                   
                </tr>
            </thead>
            <tbody > ';
        }
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $nombre= utf8_encode($listado['nombreEquipo']);
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['motivoMulta'] . "</td>";
                $tabla .= "<td data-title=''>".$listado['nombreEquipo']."</td>";
                $tabla .= "<td data-title=''>" . $listado['total'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreCampeonato'] . "</td>";
                if($idRol != 3){
                    $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='ConfirmarCobrarMulta(".$listado['id'].")'><i title= 'Editar' class='fas fa-hand-holding-usd'></i> Cobrar</button>";  
                    $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarEliminar(".$listado['id'].")'><i title= 'Editar' class='fas fa-trash'></i> Eliminar</button>";                          
                }
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}

if($tipo == "precioMulta"){

    $consultar = "SELECT precio FROM configuracionCobros where motivo = 'Multas'";
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


if($tipo == "FiltrarMultas"){

    $idCampeonato = $_POST["idCampeonato"];
    $idEquipo = $_POST["idEquipo"];

    $condicion = "";
    if($idRol == 3){
        $condicion = "and c.id = $idCampeonato and e.id = $idEquipoDelegado";
    }
    else{
        if($idCampeonato != "" && $idEquipo != ""){
            //cuando se busca por los 2 filtros
            $condicion = "and c.id = $idCampeonato and e.id = $idEquipo";
        }
        else{
            if($idCampeonato != ""){
                //cuando se busco por el filtro de campeonato
                $condicion = "and c.id = $idCampeonato";
            }
            else{
                if($idEquipo != ""){
                    //cuando se busco por el filtro de equipo
                    $condicion = "and e.id = $idEquipo";
                }
            }
        }
    }

    $registrarInventario = "SELECT m.id,m.motivoMulta,e.nombreEquipo,m.total,m.total,m.fecha,c.nombre as nombreCampeonato FROM Multa as m
                            LEFT JOIN Campeonato as c on c.id = m.IdCampeonato
                            LEFT JOIN Equipo as e on e.id = m.idEquipo
                            where m.estado = 'Pagado' $condicion";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Motivo Multa</th>
                    <th>Equipo</th>
                    <th>Total</th>
                    <th>Torneo</th>             
                </tr>
            </thead>
            <tbody > ';

        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                $nombre= utf8_encode($listado['nombreEquipo']);
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['motivoMulta'] . "</td>";
                $tabla .= "<td data-title=''>".$listado['nombreEquipo']."</td>";
                $tabla .= "<td data-title=''>" . $listado['total'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['nombreCampeonato'] . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}

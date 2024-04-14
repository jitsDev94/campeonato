<?php

session_start();
include("conexion.php");

$tipo = $_GET["op"];


if($tipo== "RegistrarPago"){

    $idCampeonato =$_POST["idCampeonato"];
    $fecha = $_POST["fecha"];
    $precio = $_POST["precio"];
    $idEquipo = $_POST["idEquipo"];

    $insertar = "SELECT pa.idEquipo From PagoArbitraje as pa left join Campeonato as c on c.id = pa.idCampeonato 
    where pa.idEquipo = $idEquipo and pa.fecha = '$fecha' and c.estado = 'En Curso'";
    $resultado = mysqli_query($conectar, $insertar);
    $row = $resultado->fetch_assoc();
    $id = $row['idEquipo'];

    if($id == ""){
        $insertar = "INSERT INTO PagoArbitraje values(null,'$fecha',$idEquipo,$precio,$idCampeonato)";
        $resultado1 = mysqli_query($conectar, $insertar);

        if($resultado1){
            echo 1;
        }
        else{
            echo 2;
        } 
    }
    else{
        echo 3;
    }
}

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


if($tipo == "EliminarPago"){

    $id = $_POST["id"];

    $Consultar = "DELETE FROM PagoArbitraje where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);


    if($resultado){
        echo 1;
    }
    else{
        echo 2;
    } 
}


if($tipo == "ListaPagos"){

    $registrarInventario = "SELECT p.id as idPagoArbitraje, p.fecha,p.total,e.nombreEquipo FROM PagoArbitraje as p 
    left join Campeonato as c on c.id = p.idCampeonato left join Equipo as e on e.id = p.idEquipo where c.estado='En Curso' order by p.fecha desc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Equipo</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Acción</th>          
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
                $tabla .= "<td data-title=''>" . $listado['nombreEquipo'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['fecha'] . "</td>";
                $tabla .= "<td data-title=''>Bs. " . $listado['total'] . "</td>";
                $tabla .= "<td data-title=''><button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$listado['idPagoArbitraje'].")'>Eliminar Pago</button>";       
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}

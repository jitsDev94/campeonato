<?php

session_start();
include("conexion.php");

$tipo = $_GET["op"];


if($tipo == "RegistrarEquipo"){

    $nombre  = $_POST["nombre"];
    $fecha = $_POST["fecha"];
    
        $registrar = "INSERT INTO Equipo values(null,'$nombre','$fecha','Habilitado')";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}

if($tipo == "EditarEquipo"){

    $id  = $_POST["id"];
    $nombre  = $_POST["nombre"];
    $fechaRegistro = $_POST["fecha"];
    
        $registrar = "UPDATE Equipo SET nombreEquipo = '$nombre', fechaRegistro = '$fechaRegistro'  where id = $id";
        $resultado = mysqli_query($conectar, $registrar);
    
        if($resultado){
            echo '1';
        }
        else{
            echo '2';
        }
}


if($tipo == "ListaEquipos"){

    $registrarInventario = "SELECT * FROM Equipo order by nombreEquipo asc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Equipo</th>
                            <th>Fecha Registro</th>
                            <th>Estado</th>
                            <th width="150px;">Acción</th>   
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
                $tabla .= "<td data-title=''>".$listado['nombreEquipo']."</td>";
                $tabla .= "<td data-title=''>" . $listado['fechaRegistro'] . "</td>";
                $tabla .= "<td data-title=''>" . $listado['estado'] . "</td>";
                $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='modalEditarEquipo(".$listado['id'].")'><i title= 'Editar' class='fas fa-pen'></i></button>";      
                if($listado['estado'] == "Habilitado"){
                    $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$listado['id'].")'>Deshabilitar</button>";                          
                }else{
                    $tabla .= " <button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".$listado['id'].")'>Habilitar</button>";                          
                }
                $tabla .= "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}




if($tipo == "DatosEquipo"){

    $id = $_POST["id"];
    $datos = array();
    $Consultar = "SELECT * FROM Equipo where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    }   
}


if($tipo == "EstadoEquipo"){

    $id = $_POST["id"];
    $estado = $_POST["estado"];

    $Consultar = "UPDATE Equipo SET estado = '$estado' where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);

    if($resultado){
        echo  '1';
    }
    else{
        echo '2';
    }   
}
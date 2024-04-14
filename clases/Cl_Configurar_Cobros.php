<?php

session_start();
include("conexion.php");

$tipo = $_GET["op"];


if($tipo == "ActualizarPrecio"){

    $precio = $_POST["precio"];
    $id = $_POST["id"];

    $Consultar = "UPDATE configuracionCobros SET precio = $precio where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
  
    if($resultado){
        echo 1;
    }
    else{
        echo 2;
    } 
}


if($tipo == "NombreMotivo"){

    $id = $_POST["id"];

    $datos = array();
    $Consultar = "SELECT * FROM configuracionCobros where id = $id";
    $resultado = mysqli_query($conectar, $Consultar);
    $datos = mysqli_fetch_assoc($resultado);

    if($resultado){
        echo json_encode($datos,JSON_UNESCAPED_UNICODE);
    }
    else{
        echo 'error';
    } 
}


if($tipo == "ListarCobros"){

    $registrarInventario = "SELECT * from configuracionCobros order by motivo asc";
    $resultado1 = mysqli_query($conectar, $registrarInventario);

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Motivo Cobro</th>
                            <th>Precio</th>
                            <th width="200px">Acción</th>
                        </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1) {
            while ($listado = mysqli_fetch_array($resultado1)) {
                $cont++;
                
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $listado['motivo'] . "</td>";                                            
                if($listado['precio'] == null){
                    $tabla .= "<td data-title=''>Bs. 0.00</td>";
                    $tabla .= "<td data-title=''><button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='NombreMotivo(".$listado['id'].")'>Agregar Precio</button></td>";  
                }
                else{
                    $tabla .= "<td data-title=''>Bs. " . $listado['precio'] . ".00</td>";
                    $tabla .= "<td data-title=''><button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='NombreMotivo(".$listado['id'].")'>Modificar Precio</button></td>";  
                }
                
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}

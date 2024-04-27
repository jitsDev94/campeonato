<?php

session_start();
require_once '../conexion/parametros.php';
$parametro = new parametros();

$tipo = $_GET["op"];


if($tipo == "RegistrarEquipo"){

    $nombre  = $_POST["nombre"];
 
    $resultado = $parametro->RegistrarEquipo($nombre); 

    echo $resultado;
}

if($tipo == "EditarEquipo"){

    $id  = $_POST["id"];
    $nombre  = $_POST["nombre"];
   
    $resultado = $parametro->EditarEquipo($id,$nombre); 
    echo $resultado;
}


if($tipo == "ListaEquipos"){
   
    $resultado1 = $parametro->ListaEquipos(); 

        $tabla = "";
        $tabla .= '<table id="example1" class="table table-bordered table-striped"  method="POST">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Equipo</th>
                            <th>Fecha Registro</th>
                            <th>Estado</th>';
                            if($parametro->verificarPermisos($_SESSION['idUsuario'],'20,21') > 0){
                $tabla .= '<th width="200px;">Acción</th>';
                            }
               $tabla .= ' </tr>
                    </thead>
                    <tbody > ';
     
        $cont = 0;
        if ($resultado1->RowCount() > 0) {
            $resultado1->MoveFirst();
            while (!$resultado1->EndOfSeek()) {
                $listado = $resultado1->Row();
                $cont++;
              
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>".$listado->nombreEquipo."</td>";
                $tabla .= "<td data-title=''>" . $listado->fechaRegistro . "</td>";
                $tabla .= "<td data-title=''>" . $listado->estado . "</td>";
                if($parametro->verificarPermisos($_SESSION['idUsuario'],'20,21') > 0){
                    $tabla .= "<td data-title=''>";
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],20) > 0){
                        $tabla .= " <button type='button' class='btn btn-primary btn-sm checkbox-toggle' onclick='modalEditarEquipo(".chr(34).$listado->id.chr(34).",".chr(34).$listado->nombreEquipo.chr(34).")'><i title= 'Editar' class='fas fa-pen'></i></button>";      
                    }
                    if($parametro->verificarPermisos($_SESSION['idUsuario'],21) > 0){
                        if($listado->estado == "Habilitado"){
                            $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarDeshabilitar(".$listado->id.")'>Deshabilitar</button>";                          
                        }else{
                            $tabla .= " <button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='ConfirmarHabilitar(".$listado->id.")'>Habilitar</button>";                          
                        }
                    }
                    $tabla .= "</td>";
                }
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}



if($tipo == "EstadoEquipo"){

    $id = $_POST["id"];
    $estado = $_POST["estado"];

    $resultado = $parametro->EstadoEquipo($id,$estado); 
    echo $resultado;

  
}
<?php

session_start();
include("../conexion/conexion.php");
require_once '../conexion/parametros.php';
$parametro = new parametros();


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
    
    // $Consultar = "SELECT * FROM Campeonato where estado = 'En Curso'";
    // $resultado = mysqli_query($conectar, $Consultar);
    // $row = $resultado->fetch_assoc();
    // $idCampeonato = $row['id'];

    // $Consultar = "SELECT * FROM EquipoCampeon where idCampeonato = $idCampeonato";
    // $resultado3 = mysqli_query($conectar, $Consultar);
    // $row = $resultado3->fetch_assoc();
    // $idCampeon = $row['id'];

    $resultado1 = $parametro->validarCampeonExistente();   

    if($resultado1->RowCount() > 0){
        $row = $resultado1->Row();
        if($row->id == ""){
            echo "abierto";
        }
        else{
            echo "cerrado";
        }
    }
    else{
        echo 'abierto';
    } 
}


if($tipo == "RegistrarDirectiva"){

    $nombre  = $_POST["nombre"];
    $fecha = $_POST["fecha"];
    $cargo  = $_POST["cargo"];
    $idCampeonato = $_POST["idCampeonato"];
    
    $resultado = $parametro->RegistrarDirectiva($nombre,$fecha,$cargo,$idCampeonato);

  
        if($resultado == 'ok'){
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

    $resultado = $parametro->EditarMiembroDirectiva($id,$nombre,$fecha,$cargo);
     
    
        if($resultado == 'ok'){
            echo '1';
        }
        else{
            echo '2';
        }
}

if($tipo == "EliminarDirectiva"){

    $id  = $_POST["id"];
    
    $resultado = $parametro->ReiniciarDirectiva($id);
     
        if($resultado == 'ok'){
            echo '1';
        }
        else{
            echo '2';
        }
}

if($tipo == "ReiniciarDirectiva"){

    $resultado = $parametro->ReiniciarDirectiva();       
    
        if($resultado == 'ok'){
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






if($tipo == "ListaTorneo"){

   // $consultar = "SELECT * FROM Campeonato order by fechaInicio desc";
    $resultado1 = $parametro->ListaTorneo();   
   
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
        if ($resultado1->RowCount() > 0) {
            while (!$resultado1->EndOfSeek()) {
                $row = $resultado1->Row();
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
                if($row->GanaciaTarjetas  != null && $row->GanaciaTarjetas != ''){
                    $GanaciaTarjetas =$row->GanaciaTarjetas;
                }
                if($row->gananciaInscripcion  != null && $row->gananciaInscripcion != ''){
                    $gananciaInscripcion =$row->gananciaInscripcion;
                }
                if($row->totalArbitraje  != null && $row->totalArbitraje != ''){
                    $totalArbitraje =$row->totalArbitraje;
                }
                if($row->totalMultas  != null && $row->totalMultas != ''){
                    $totalMultas =$row->totalMultas;
                }
                if($row->totalPrecioObservacion  != null && $row->totalPrecioObservacion != ''){
                    $totalPrecioObservacion =$row->totalPrecioObservacion;
                }
                if($row->GanaciaTransferencia != null && $row->GanaciaTransferencia != ''){
                    $GanaciaTransferencia =$row->GanaciaTransferencia;
                }
                if($row->totalGasto  != null && $row->totalGasto != ''){
                    $totalGasto =$row->totalGasto;
                }

                $ganancia = ($GanaciaTarjetas + $gananciaInscripcion + $totalArbitraje + $totalMultas + $totalPrecioObservacion + $GanaciaTransferencia) - $totalGasto ;
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $row->nombre . "</td>";
                $tabla .= "<td data-title=''>" . $row->tipo . "</td>";
                $tabla .= "<td data-title=''>" . $row->fechaInicio . "</td>";
                $tabla .= "<td data-title=''>" . number_format($ganancia,2) . "</td>";
                $tabla .= "<td data-title=''>" . $row->estado . "</td>";
                $tabla .= "</tr>";
            }
        }
    
        $tabla .= "</tbody>
                
                </table>";
        echo  $tabla;   
}


if($tipo == "ListaDirectiva"){

    $nombre = $_POST["nombre"];
    $idCampeonato = $_POST["idCampeonato"];


    $resultado1 = $parametro->ListaDirectiva($nombre,$idCampeonato);     

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
        if ($resultado1->RowCount() > 0) {
            while (!$resultado1->EndOfSeek()) {
                $row = $resultado1->Row();
                $cont++;
                $tabla .= "<tr>";
                $tabla .= "<td data-title=''>" .  $cont . "</td>";
                $tabla .= "<td data-title=''>" . $row->nombre . "</td>";
                $tabla .= "<td data-title=''>" . $row->cargo . "</td>";
                $tabla .= "<td data-title=''>" . $row->campeonato . "</td>";
                $tabla .= "<td data-title=''><button type='button' class='btn btn-success btn-sm checkbox-toggle' onclick='modalEditarDirectiva(".$row->id.")'><i title= 'Editar' class='fas fa-pen'></i></button>";      
                $tabla .= " <button type='button' class='btn btn-danger btn-sm checkbox-toggle' onclick='ConfirmarEliminar(".$row->id.")'><i title= 'Eliminar' class='fas fa-trash'></i></button>";
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
   
    $row = $parametro->TotalIngresos('actual');    
    $GanaciaTarjetas = $row->GanaciaTarjetas;

    $row = $parametro->totalInscripcion('actual');
    $gananciaInscripcion = $row->gananciaInscripcion;

  
    $row = $parametro->totalArbitraje('actual');
    $totalArbitraje = $row->totalArbitraje;

   
    $row = $parametro->gananciasMultas('actual');
    $totalMultas = $row->totalMultas;

     
    $row = $parametro->gananciasObservaciones('actual');
    $totalPrecioObservacion = $row->totalPrecioObservacion;

      
    $row = $parametro->totalTransferencia('actual');
    $GanaciaTransferencia = $row->GanaciaTransferencia;

    $total = $GanaciaTarjetas + $gananciaInscripcion + $totalArbitraje + $totalMultas + $totalPrecioObservacion + $GanaciaTransferencia;


    
        echo $total;
   
}

if($tipo == "TotalGastos"){
   
   

    $row = $parametro->TotalGastos();    
    $totales = $row->totales;

   
    echo $totales;
} 


//total ganancia del campeonato actual
if($tipo == "TotalGananciaActual"){
   
  


    $row = $parametro->TotalIngresos('actual');    
    $GanaciaTarjetas = $row->GanaciaTarjetas;

    $row = $parametro->totalInscripcion('actual');
    $gananciaInscripcion = $row->gananciaInscripcion;
    
    $row = $parametro->totalArbitraje('actual');
    $totalArbitraje = $row->totalArbitraje;


    $row = $parametro->gananciasMultas('actual');
    $totalMultas = $row->totalMultas;

    $row = $parametro->gananciasObservaciones('actual');
    $totalPrecioObservacion = $row->totalPrecioObservacion;
  
    $row = $parametro->totalTransferencia('actual');
    $GanaciaTransferencia = $row->GanaciaTransferencia;

 
    $row = $parametro->TotalGastos('actual');    
    $totalGastos = $row->totales;

    $total = $GanaciaTarjetas + $gananciaInscripcion + $totalArbitraje + $totalMultas + $totalPrecioObservacion +$GanaciaTransferencia - $totalGastos;

    echo $total;
    
}


//total ganancia de todos los campeonatos
if($tipo == "TotalGanancia"){
   
  
    $row = $parametro->TotalIngresos();    
    $GanaciaTarjetas = $row->GanaciaTarjetas;

    $row = $parametro->totalInscripcion();
    $gananciaInscripcion = $row->gananciaInscripcion;
    
    $row = $parametro->totalArbitraje();
    $totalArbitraje = $row->totalArbitraje;


    $row = $parametro->gananciasMultas();
    $totalMultas = $row->totalMultas;

    $row = $parametro->gananciasObservaciones();
    $totalPrecioObservacion = $row->totalPrecioObservacion;
  
    $row = $parametro->totalTransferencia();
    $GanaciaTransferencia = $row->GanaciaTransferencia;

    $row = $parametro->TotalGastos('actual');    
    $totalGastos = $row->totales;

    $total = $GanaciaTarjetas + $gananciaInscripcion + $totalArbitraje + $totalMultas + $totalPrecioObservacion + $GanaciaTransferencia - $totalGastos;
   
    echo $total;
   
}


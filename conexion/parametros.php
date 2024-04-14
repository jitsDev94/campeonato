<?php
include_once("conexionBd.php");

class parametros
{

    public function ListarRoles(){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

                                 
        $consulta = "SELECT * FROM rol";
        if(!$db->Query($consulta)) {
            return 0;
        }      
        return $db;
    }

    public function EstadoRol($id, $estado)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }           

        $consulta = "UPDATE rol SET estado = '$estado' where id=$id";
            
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'error';
        }
       
        return 'ok';
      
    }


    public function RegistrarRol($nombre)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }
      
        $consulta= "SELECT * from rol where nombreRol = '$nombre' ";
        $db->Query($consulta);
        if($db->RowCount() > 0){
            return 'existe';
        }

        $consulta = "INSERT INTO rol(nombreRol,estado) values('$nombre','Habilitado')";
            
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'Ha ocurrido un error al registrar el rol.';
        }
       
        return 'ok';
      
    }

    public function RegistrarUsuario($idRol,$idEquipo,$usuario ,$contra)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }

        $consulta= "SELECT * from Usuario where usuario = '$usuario' ";
        $db->Query($consulta);
        if($db->RowCount() > 0){
            return 'Ya existe un usuario con ese nombre, intente con otro nombre';
        }


        $equipoNombre = '';
        $equipoValor = '';
        if($idEquipo != "" && $idEquipo != null){
            $equipoNombre = 'idEquipo,';
            $equipoValor = "$idEquipo,";
        }

        $consulta = "insert INTO Usuario(idRol,$equipoNombre usuario,contrasena,estado) values($idRol, $equipoValor '$usuario','$contra','Habilitado')";
      
      
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 0;
        }
       
        return 'ok';
      
    }

    public function ListarUsuarios(){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

                                 
        $consulta = "SELECT u.id as idUsuario,r.nombreRol,e.nombreEquipo,u.usuario,u.estado FROM Usuario as u
        LEFT JOIN Rol as r on r.id = u.idRol
      LEFT join Equipo as e on e.id = u.idEquipo";
        if(!$db->Query($consulta)) {
            return 0;
        }      
        return $db;
    }


    public function TotalJugadores($idEquipoDelegado,$idRol){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

        $condicion = "";
        if($idRol == 3){
            $condicion = "where idEquipo = $idEquipoDelegado";
        }                                 
        $consulta = "SELECT count(id) as totalJugadores FROM Jugador $condicion";
        if(!$db->Query($consulta)) {
            return 0;
        }
        $db->MoveFirst();
        $row = $db->Row();		

        return $row->totalJugadores;
    }

    public function autenticacionUsuario($usuario,$password)
	{       

        $consulta = "SELECT u.id as idUsuario,u.idRol,r.nombreRol,u.usuario,u.contrasena,e.nombreEquipo,u.idEquipo,u.estado as estadoUsuario from Usuario as u
        LEFT JOIN Equipo as e on e.id = u.idEquipo
        left join Rol as r on r.id = u.idRol where u.usuario ='$usuario' and u.contrasena = '$password'";


        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

        if (!$db->Query($consulta)) {
           return 0;
        }
      
        return $db;
      
    }

    public function DropDownListarRoles()
	{      

        $consulta = "SELECT * FROM Rol where estado = 'Habilitado'";

        echo "<option value='0'>Seleccionar</option>";
        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->id . "'>" . $row->nombreRol . "</option>";
		}
    }

    public function DropDownListarEquiposInscritos()
	{      

        $consulta = "SELECT e.id,e.nombreEquipo FROM Inscripcion as i
                                left join Equipo as e on e.id = i.idEquipo
                                left join Campeonato as c on c.id = i.idCampeonato
                                where c.estado = 'En Curso'";


        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();		
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->id . "'>" . $row->nombreEquipo . "</option>";
		}
    }


    public function DropDownBuscarCampeonatos()
	{      

        $consulta = "SELECT * FROM Campeonato where estado = 'Concluido'";


        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		echo "<option value='0'>Seleccionar</option>";
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->id . "'>" . $row->nombre . "</option>";
		}
    }

    public function DropDownBuscarEquipos()
	{      

        $consulta = "SELECT e.id as idEquipo,e.nombreEquipo FROM Inscripcion as i
        LEFT join Equipo as e on e.id = i.idEquipo
        LEFT join Campeonato as c on c.id = i.idCampeonato
        where c.estado= 'En Curso' order by e.nombreEquipo asc";


        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		echo "<option value='0'>Seleccionar</option>";
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->idEquipo . "'>" . $row->nombreEquipo . "</option>";
		}
    }

    public function DropDownBuscarEquipos2($codEquipo2,$codEquipo1)
	{      

        $condicion = '';
        if($codEquipo2 != '' && $codEquipo1 != ''){
            $condicion = " and i.idEquipo in ($codEquipo2,$codEquipo1)";
        }
        $consulta = "SELECT e.id as idEquipo,e.nombreEquipo FROM Inscripcion as i
        LEFT join Equipo as e on e.id = i.idEquipo
        LEFT join Campeonato as c on c.id = i.idCampeonato
        where c.estado= 'En Curso' $condicion order by e.nombreEquipo asc";


        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		echo "<option value='0'>Seleccionar</option>";
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->idEquipo . "'>" . $row->nombreEquipo . "</option>";
		}
    }

    public function DropDownBuscarAllEquipos()
	{      

        $consulta = "SELECT * FROM Equipo where estado = 'Habilitado' order by nombreEquipo asc";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		echo "<option value='0'>Seleccionar Equipo..</option>";
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->id . "'>" . $row->nombreEquipo . "</option>";
		}
    }


    public function DropDownHechosPartido()
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query("SELECT * FROM Hechos")) $db->Kill();
		$db->MoveFirst();
		echo "<option value='0'>Seleccionar</option>";
		while (!$db->EndOfSeek()) {
			$row = $db->Row();
		
			echo "<option value='" . $row->id . "'>" . $row->nombreHecho . "</option>";
		}      
    }


    public function traerPrecio($motivo)
	{       

        $consulta = "SELECT * FROM configuracionCobros where motivo = '$motivo'";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }

    function TraerUltimoTorneo()
	{       

        $consulta = "SELECT * FROM Campeonato where estado = 'En Curso'";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }

    function TraerUltimoPartido()
	{       

        $consulta = "SELECT * FROM Partido order by id desc";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        $ultimoPartido = $row->id +1;
        return $ultimoPartido;
      
    }

    function TraerUltimoSede()
	{       

        $consulta = "SELECT * FROM Sede where estado = 'Habilitado'";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }


    public function ListarTransferencias($nombreJugador,$idCampeonato,$idEquipoDestino)
	{       

        $consulta = "";

        if($idEquipoDestino != '' && $idEquipoDestino != 0){ 
            $consulta .= " and t.idEquipo = $idEquipoDestino";
        }
        if($idCampeonato != '' && $idCampeonato != 0){ 
            $consulta .= " and t.idCampeonato = $idCampeonato";
        }
        if($nombreJugador != ""){ 
            $consulta .= " and j.nombre like '%$nombreJugador%'";
        }
        if($consulta == ''){
            $consulta = " and c.estado = 'En Curso'";
        }
        $consulta = "SELECT j.nombre,j.apellidos, e1.nombreEquipo as EquipoInicial, e.nombreEquipo as EquipoDestino,c.nombre as Campeonato,
                    t.fecha, t.precioTransferencia
                    FROM Transferencia as t 
                    left join Jugador as j on j.id = t.idJugador
                    left join Equipo as e on e.id = t.idEquipo
                    left join Equipo as e1 on e1.id = t.EquipoInicial
                    left join Campeonato as c on c.id = t.idCampeonato
                    where 1=1 $consulta";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }


    public function TraerJugadoresEquipos($idEquipo1,$idEquipo2)
	{       

        $consulta = "SELECT j.id as idJugador,j.nombre,j.apellidos,j.nroCamiseta,e.nombreEquipo FROM Jugador as j 
        left join Equipo as e on e.id = j.idEquipo 
        where e.id = $idEquipo1 or e.id = $idEquipo2
        order by e.nombreEquipo,j.nombre ASC";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }

    public function TraerEquipoCampeon($idCampeonato)
	{       

        $consulta = "SELECT * FROM EquipoCampeon where idCampeonato =$idCampeonato";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->RowCount();
        return $row;
      
    }


    public function validarNroJugador($idEquipoDestino,$idJugador)
	{       

        $consulta = "SELECT * FROM Jugador WHERE idEquipo = $idEquipoDestino and nroCamiseta = (SELECT nroCamiseta from Jugador where id = $idJugador)";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->RowCount();
        return $row;
      
    }
    
    public function TraerCodEquipo($nombreEquipo)
	{       

        $consulta = "SELECT * FROM Equipo where nombreEquipo = '$nombreEquipo'";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }

    public function TraerEquipoTablaPosicion($CodEquipo,$codCampeonato)
	{       

        $consulta = "SELECT * FROM TablaPosicion where idEquipo = $CodEquipo and idCampeonato = $codCampeonato";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }

    public function VerificarTorneoFinalizado()
	{       

        $consulta = "SELECT * FROM EquipoCampeon where idCampeonato =(SELECT id FROM Campeonato where estado = 'En Curso')";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }


    public function RegistrarTransferencia($idJugador,$idEquipoOrigen,$idEquipoDestino,$fecha,$precio,$idCampeonato)
	{       

        $consulta = "INSERT INTO Transferencia values(null,$idJugador,$idEquipoOrigen,$idEquipoDestino,'$fecha',$precio,$idCampeonato)";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function actualizarEquipoJugador($idJugador,$idEquipoDestino)
	{       

        $consulta = "UPDATE Jugador SET idEquipo = $idEquipoDestino where id = $idJugador";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function ActualizarTablaPosicion($puntos, $PartidosGanados, $PartidosEmpatados, $PartidosPerdidos, $golFavor, $golContra, $idValidacionEquipo)
	{       

        $consulta = "UPDATE TablaPosicion SET puntos = puntos + $puntos, partidosGanados = partidosGanados + $PartidosGanados, partidosEmpatados = partidosEmpatados + $PartidosEmpatados, partidosPerdidos  = partidosPerdidos + $PartidosPerdidos, golFavor = golFavor + $golFavor,golContra = golContra + $golContra where id = $idValidacionEquipo";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function AgregarTablaPosicion($equipo,$idCampeonato,$puntos,$PartidosGanados,$PartidosEmpatados,$PartidosPerdidos,$golFavor,$golContra)
	{       

        $consulta = "INSERT INTO TablaPosicion values(null,$equipo,$idCampeonato,$puntos,$PartidosGanados,$PartidosEmpatados,$PartidosPerdidos,$golFavor,$golContra,null)";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function ActualizarTablaPosicionWalkover($equipoGanadorWalkover,$codCampeonato)
	{       

        $consulta = "UPDATE TablaPosicion SET puntos = puntos + 3, partidosGanados = partidosGanados + 1 where id = $equipoGanadorWalkover and idCampeonato = $codCampeonato";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function ActualizarTablaPosicionWalkoverPerdedor($equipoPerdedorWalkover,$codCampeonato)
	{       

        $consulta = "UPDATE TablaPosicion SET  partidosPerdidos = partidosPerdidos + 1 where id = $equipoPerdedorWalkover and idCampeonato = $codCampeonato";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function InsertarPartido($idPartido,$equipo1,$equipo2,$idSede,$fecha,$idCampeonato,$modo,$observacion,$estadoObservacion,$idEquipoObservado,$PrecioObservacion)
	{       

        $consulta = "INSERT INTO Partido VALUES($idPartido,$equipo1,$equipo2,$idSede,'$fecha',$idCampeonato,'$modo','$observacion','$estadoObservacion','',$idEquipoObservado,$PrecioObservacion)";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function InsertarHechoPartido($idHecho,$idJugador,$equipo,$idPartido)
	{       

        $consulta = "INSERT INTO HechosPartido values(null,$idHecho,$idJugador,'$equipo',$idPartido,0,'Pendiente')";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function actualizarProgramacionPartidos($codProgramacion)
	{       

        $consulta = "UPDATE programacionPartidos SET estado ='Completado' where codProgramacion = $codProgramacion";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }

    public function eliminarPartido($idPartido)
	{       

        $consulta = "DELETE FROM Partido where id = $idPartido";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       
        return $success;
      
    }
}
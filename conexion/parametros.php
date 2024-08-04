<?php
include_once("conexionBd.php");

class parametros
{

    public function verificarPermisos($idUsuario, $idpermiso)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }           

        $consulta = "SELECT u.id as idUsuario,u.usuario,rp.idRol,r.nombreRol, rp.idPermiso,p.nombrePermiso FROM rolpermiso rp 
        LEFT join rol r on r.id = rp.idRol
        LEFT join usuario u on u.idRol = r.id
        LEFT join permisos p on p.idPermiso = rp.idPermiso
        where u.id = $idUsuario and rp.idPermiso in ($idpermiso) and p.baja=0";

        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'error';
        }
       
        return $db->RowCount();
      
    }

    public function PermisoAsignado($idPermiso,$idRol)
	{       

        $consulta = "SELECT * FROM `rolpermiso` where idPermiso = $idPermiso and idRol = $idRol and baja = 0";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
        return $db->RowCount();
      
    }   

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

    public function guardarPermisoRol($idPermiso,$idRol)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }
      
        $consulta = "SELECT * FROM `rolpermiso` where idPermiso = $idPermiso and idRol = $idRol";
        $db->Query($consulta);
        if($db->RowCount() > 0){
            $row=$db->Row();

            $consulta = "DELETE FROM `rolpermiso` where idRolPermiso = $row->idRolPermiso";
            $db->Query($consulta);

            return 'ok';
        }

        $consulta = "INSERT INTO rolpermiso(idPermiso,idRol,fechaAsignacion,baja) values('$idPermiso','$idRol',sysdate(),0)";
            
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'Ha ocurrido un error al registrar el rol.';
        }
       
        return 'ok';
      
    }

    public function EditarRol($idRol,$nombre)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }
             

        $consulta = "UPDATE rol set nombreRol = '$nombre' where id=$idRol";
            
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'Ha ocurrido un error al registrar el rol.';
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

    public function RegistrarInscripcion($idCampeonato,$idEquipo,$monto)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }
      
        $consulta = "SELECT id FROM Inscripcion where idCampeonato = $idCampeonato and idEquipo = $idEquipo";
        $db->Query($consulta);
        if($db->RowCount() > 0){
           
            return 'inscrito';
        }

        $consulta = "INSERT INTO Inscripcion(idCampeonato,idEquipo,inscripcion,fecha) values($idCampeonato,$idEquipo,$monto,curdate())";
            
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'Ha ocurrido un error al registrar la insripcion.';
        }
       
        return 'ok';
      
    }

    public function EditarEquipoInscrito($idInscripcion,$idEquipo,$idCampeonato)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }
      
        $consulta = "SELECT id FROM Inscripcion where idCampeonato = $idCampeonato and idEquipo = $idEquipo";
        $db->Query($consulta);
        if($db->RowCount() > 0){
           
            return 'inscrito';
        }

        $consulta = "UPDATE Inscripcion SET idEquipo = $idEquipo where id = $idInscripcion";
            
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'Ha ocurrido un error al registrar la insripcion.';
        }
       
        return 'ok';
      
    }


    public function ObtenerPecioCobros($motivo){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

                                 
        $consulta = "SELECT precio FROM configuracionCobros where motivo = '$motivo' and estado ='Habilitado'";
        if(!$db->Query($consulta)) {
            return 0;
        }    
        if($db->RowCount() > 0){
            $db->MoveFirst();
            $row = $db->Row();
            return $row->precio;
        }
        else{
            return 0;
        }
      
    }

    public function ListarPermisos2(){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

                                 
        $consulta = "SELECT * FROM permisos where baja = 0 order by menu asc";
        if(!$db->Query($consulta)) {
            return 0;
        }      
        return $db;
    }

    public function ListarPermisos(){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

                                 
        $consulta = "SELECT * FROM permisos order by menu asc";
        if(!$db->Query($consulta)) {
            return 0;
        }      
        return $db;
    }

    public function TablaPosiciones(){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

                                 
        $consulta = "SELECT tp.id,e.id as idEquipo,e.nombreEquipo,tp.puntos,tp.partidosGanados,tp.partidosEmpatados,tp.partidosPerdidos,tp.golFavor,tp.golContra, sum(tp.golFavor - tp.golContra) as diferencia 
        FROM TablaPosicion as tp 
        left join inscripcion i on i.id = tp.idEquipo
           LEFT join Equipo as e on e.id = i.idEquipo 
        LEFT join campeonato ca on ca.id = i.idCampeonato
        where ca.estado = 'en curso' and tp.grupo is null
           GROUP by tp.id,e.id,e.nombreEquipo,tp.puntos,tp.partidosGanados,tp.partidosEmpatados,tp.partidosPerdidos,tp.golFavor,tp.golContra
           ORDER by tp.puntos desc, diferencia desc";

        if(!$db->Query($consulta)) {
            return 0;
        }      
        return $db;
    }

    public function DetallePartidosEquipos($idEquipo){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

                                 
        $consulta = "SELECT ca.*,p.idCampeonato,p.fechaPartido,e1.id as idEquipo1,e1.nombreEquipo as EquipoLocal,e2.id as idEquipo2, e2.nombreEquipo as EquipoVisitante 
        from Partido as p
        LEFT join inscripcion i on i.id = p.idEquipoLocal
         LEFT join inscripcion i2 on i2.id = p.idEquipoVisitante
          LEFT join equipo e1 on e1.id = i.idEquipo
        LEFT join equipo e2 on e2.id = i2.idEquipo
        LEFT join campeonato ca on ca.id = i.idCampeonato or ca.id = i2.idCampeonato
        WHERE i.id =$idEquipo OR i2.id = $idEquipo  and ca.estado = 'en curso'
        GROUP by p.idCampeonato,p.fechaPartido,e1.id,e1.nombreEquipo,e2.id,e2.nombreEquipo
        order by p.fechaPartido desc";

        if(!$db->Query($consulta)) {
            return 0;
        }      
        return $db;
    }

    public function verificarObservaciones($idEquipoObservado,$fechaPartido =''){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

        $condicion = '';
        if($fechaPartido != ''){
            $condicion = " and fechaPartido = '$fechaPartido'";
        }
        $consulta = "SELECT * FROM Partido p 
        LEFT join inscripcion i on i.id = p.idEquipoObservado
        LEFT join equipo e on e.id = i.idEquipo
        LEFT join campeonato ca on ca.id = i.idCampeonato
        where idEquipoObservado =  $idEquipoObservado and ca.estado = 'en curso' $condicion and estadoObservacion = 'Aceptado'";

        if(!$db->Query($consulta)) {
            return 0;
        }      
        return $db->RowCount();
    }

    public function verificarEquipoGanadorWalkover($idEquipoGanadorWalkover,$fechaPartido =''){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $consulta = "SELECT * FROM `partido` where idEquipoGanadorWalkover = $idEquipoGanadorWalkover and fechaPartido = '$fechaPartido';";

        if(!$db->Query($consulta)) {
            return 0;
        }      
        return $db->RowCount();
    }

    public function obtenerGolesEquipo($EquipoLocal,$fechaPartido,$idEquipo1,$idEquipo2){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

                                 
        $consulta = "SELECT count(hp.Equipo) as goles from acontecimientoPartido as hp 
        LEFT JOIN Partido as p on p.id = hp.idPartido
        where hp.Equipo = '".$EquipoLocal."' and p.fechaPartido = '".$fechaPartido."' and hp.idAcontecimiento = 1  and p.idEquipoLocal =  ".$idEquipo1." and p.idEquipoVisitante = ".$idEquipo2."";
        
        if(!$db->Query($consulta)) {
            return 0;
        } 

        $row = $db->Row();
        $row->goles;  

        return $row->goles;
    }

    public function RegistrarPermiso($nombre,$menu,$nombreMenu)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }
      
        $nombreMenuFinal = $menu;
        if($menu == 'otro'){
            $nombreMenuFinal = $nombreMenu;
        }
        $consulta= "SELECT * from permisos where nombrePermiso = '$nombre' and menu = '$nombreMenuFinal'";
        $db->Query($consulta);
        if($db->RowCount() > 0){
            return 'existe';
        }

        $consulta = "INSERT INTO permisos(nombrePermiso,menu,baja) values('$nombre','$nombreMenuFinal',0)";
            
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'Ha ocurrido un error al registrar el rol.';
        }
       
        return 'ok';
      
    }

    public function EditarPermiso($id,$nombre,$menu,$nombreMenu)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }
      
        $nombreMenuFinal = $menu;
        if($menu == 'otro'){
            $nombreMenuFinal = $nombreMenu;
        }
        $consulta= "SELECT * from permisos where nombrePermiso = '$nombre' and menu = '$nombreMenuFinal'";
        $db->Query($consulta);
        if($db->RowCount() > 0){
            return 'existe';
        }

        $consulta = "UPDATE permisos set nombrePermiso = '$nombre' , menu = '$nombreMenuFinal' where idPermiso = $id";
            
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'Ha ocurrido un error al registrar el rol.';
        }
       
        return 'ok';
      
    }


    public function ReiniciarDirectiva($id='')
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }           
       
        $condicion = '';
        if($id!= ''){
            $condicion = " and id= $id";
        }
        $consulta = "UPDATE Directiva SET estado = 'Concluido' where 1=1 $condicion";

           
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'error';
        }
       
        return 'ok';
      
    }

    public function EditarMiembroDirectiva($id,$nombre,$fecha,$cargo)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }           
       
        $consulta = "UPDATE Directiva SET nombre = '$nombre', cargo = '$cargo', fechaNombramiento ='$fecha' where id = $id";

           
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'error';
        }
       
        return 'ok';
      
    }


    public function RegistrarDirectiva($nombre,$fecha,$cargo,$idCampeonato)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }           
        $gestion = explode("-", $fecha); 

        $consulta = "INSERT INTO Directiva values(null,'$nombre','$cargo',$gestion[0],'$fecha',$idCampeonato,'Vigente')";

           
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'error';
        }
       
        return 'ok';
      
    }

    public function EstadoPermiso($id, $estado)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }           

        $consulta = "UPDATE permisos SET baja = '$estado' where idPermiso=$id";
            
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'error';
        }
       
        return 'ok';
      
    }

    public function EditarUsuario($idRol,$idEquipo,$idUsuario)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }
     
      
        $update = '';
        if($idEquipo != "" && $idEquipo != null){
        
            $update = ", idEquipo = $idEquipo";
        }

        $consulta = "UPDATE Usuario set idRol = $idRol $update where id = $idUsuario";
      
      
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 0;
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

                                 
        $consulta = "SELECT u.id as idUsuario,u.idRol,r.nombreRol,e.nombreEquipo,u.usuario,u.estado,u.idEquipo FROM Usuario as u
        LEFT JOIN Rol as r on r.id = u.idRol
      LEFT join Equipo as e on e.id = u.idEquipo";
        if(!$db->Query($consulta)) {
            return 0;
        }      
        return $db;
    }

    public function totalInscritos(){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

                                      
        $consulta = "SELECT count(i.id) as totalInscritos FROM Inscripcion as i
        LEFT JOIN Campeonato as c on c.id = i.idCampeonato
        where c.estado = 'En Curso'";
        if(!$db->Query($consulta)) {
            return 0;
        }
        $db->MoveFirst();
        $row = $db->Row();		

        return $row->totalInscritos;
    }
     
    public function totalEquipos(){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

                                      
        $consulta = "SELECT count(id) as totalEquipos from Equipo where estado = 'Habilitado'";
        if(!$db->Query($consulta)) {
            return 0;
        }
        $db->MoveFirst();
        $row = $db->Row();		

        return $row->totalEquipos;
    }

    public function TotalObservaciones($idEquipoDelegado,$idRol){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

        $condicion = "";
        if($idRol != 1 && $idRol != 2){
            $condicion = "and p.idEquipoObservado = $idEquipoDelegado";
        }                                 
        $consulta = "SELECT COUNT(p.id) as tarjetas 
        FROM Partido as p 
        LEFT join Campeonato as c on c.id = p.idCampeonato
        where p.estadoObservacion = 'Pendiente' and c.estado = 'En Curso' $condicion";
        if(!$db->Query($consulta)) {
            return 0;
        }
        $db->MoveFirst();
        $row = $db->Row();		

        return $row->tarjetas;
    }

    public function TotalTarjetas($idEquipoDelegado,$idRol,$permiso){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

        $condicion = "";

        if($permiso == 0){
            $condicion = "and p.idEquipoObservado = $idEquipoDelegado";
        }                                 
        $consulta = "SELECT COUNT(hp.id) as tarjetas FROM acontecimientopartido as hp
                                         LEFT JOIN Partido as p on p.id = hp.idPartido
                                         LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                                         LEFT JOIN Equipo as e on e.nombreEquipo = hp.Equipo
                                         where hp.estado = 'pendiente' and hp.idAcontecimiento != 1 and c.estado = 'En Curso'  $condicion";
        if(!$db->Query($consulta)) {
            return 0;
        }
        $db->MoveFirst();
        $row = $db->Row();		

        return $row->tarjetas;
    }

    public function TotalMultas($idEquipoDelegado,$idRol){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

        $condicion = "";
        if($idRol != 1 && $idRol != 2){
            $condicion = "and i.idEquipo = $idEquipoDelegado";
        }                                 
        $consulta = "SELECT count(m.motivoMulta) as totalMultas FROM Multa as m
        LEFT join inscripcion i on i.inscripcion = m.idEquipo
        LEFT JOIN Equipo as e on e.id = i.idEquipo
        LEFT JOIN Campeonato as c on c.id = i.idCampeonato
        where c.estado = 'En Curso' and m.estado ='Pendiente' $condicion";
        if(!$db->Query($consulta)) {
            return 0;
        }
        $db->MoveFirst();
        $row = $db->Row();		

        return $row->totalMultas;
    }

    public function TotalJugadores($idEquipoDelegado,$idRol){

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }

        $condicion = "";
        if($idRol != 1 && $idRol != 2){
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


    public function obtenerListadoMultas($idCampeonato,$idEquipo,$idRol,$idEquipoDelegado)
	{       

        $condicion = "";
        if($idRol != 1 && $idRol != 2){
            $condicion .= " and c.id = (select id from campeonato where estado = 'En Curso') and e.id = $idEquipoDelegado";
        }
        
         
        if($idCampeonato != "" && $idCampeonato != 0){
            //cuando se busco por el filtro de campeonato
            $condicion .= " and c.id = $idCampeonato";
        }
        
        if($idEquipo != "" && $idEquipo != 0){
            //cuando se busco por el filtro de equipo
            $condicion .= " and e.id = $idEquipo";
        }
        

        $consulta = "SELECT m.id,m.motivoMulta,e.nombreEquipo,m.total,m.fecha,c.nombre as nombreCampeonato,m.estado
                            FROM Multa as m
                            LEFT JOIN Campeonato as c on c.id = m.IdCampeonato
                            LEFT JOIN Equipo as e on e.id = m.idEquipo
                            where 1=1 $condicion";


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

    public function RegistrarMulta($motivoMulta,$fecha,$total,$idEquipo)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }
             

        $consulta = "INSERT INTO Multa(motivoMulta,idEquipo,idCampeonato,total,fecha,estado) values('$motivoMulta',$idEquipo,(select id from campeonato where estado = 'En Curso'),$total,'$fecha','Pendiente')";
            
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'Ha ocurrido un error al registrar la multa.';
        }
       
        return 'ok';
      
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

    public function DropDownBuscarJugadores($idEquipo)
	{      

        $consulta = "SELECT id,concat(nombre,' ',apellidos) as nombreJugador FROM Jugador where idEquipo = $idEquipo";

       
        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->id . "'>" . $row->nombreJugador . "</option>";
		}
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

    public function DropDownListarEquiposInscritosMutas()
	{      

        $consulta = "SELECT e.id,e.nombreEquipo FROM Inscripcion as i
                                left join Equipo as e on e.id = i.idEquipo
                                left join Campeonato as c on c.id = i.idCampeonato
                                where c.estado = 'En Curso' order by e.nombreEquipo ASC";


        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();		
        echo "<option value='0'>Seleccionar..</option>";
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->id . "'>" . $row->nombreEquipo . "</option>";
		}
    }

    public function DropDownListarEquiposInscritos()
	{      

        $consulta = "SELECT i.id,e.nombreEquipo FROM Inscripcion as i
                                left join Equipo as e on e.id = i.idEquipo
                                left join Campeonato as c on c.id = i.idCampeonato
                                where c.estado = 'En Curso' order by e.nombreEquipo ASC";


        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();		
        echo "<option value='0'>Seleccionar..</option>";
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->id . "'>" . $row->nombreEquipo . "</option>";
		}
    }

    public function listadoMenus()
	{      

        $consulta = "select DISTINCT menu from permisos order by menu asc";

       
        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		echo "<option value=''>Seleccionar.. </option>";
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->menu . "'>" . $row->menu . "</option>";
		}
        echo "<option value='otro'>Otro</option>";
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


    
    public function DropDownListarTorneos()
	{      

        $consulta = "SELECT * FROM Campeonato";


        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		echo "<option value='0'>Seleccionar.. </option>";
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->id . "'>" . $row->nombre  . "</option>";
		}
    }


    public function DropDownBuscarEquipos()
	{      

        $consulta = "SELECT * FROM Equipo where estado = 'Habilitado' order by nombreEquipo asc";


        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		echo "<option value='0'>Seleccionar Equipo</option>";
		while (!$db->EndOfSeek()) {
			$row = $db->Row();		
			echo "<option value='" . $row->id . "'>" . $row->nombreEquipo . "</option>";
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
        where c.estado= 'En Curso' $condicion";


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

    public function DropDownBuscarNombreEquipos($codEquipo)
	{      
      
        $consulta = "SELECT e.id as idEquipo,e.nombreEquipo FROM Inscripcion as i
        LEFT join Equipo as e on e.id = i.idEquipo
        LEFT join Campeonato as c on c.id = i.idCampeonato
        where c.estado= 'En Curso' and i.idEquipo = $codEquipo";


        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
        $row = $db->Row();		
        return $row->nombreEquipo;
    }




    public function DropDownHechosPartido()
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query("SELECT * FROM acontecimiento")) $db->Kill();
		$db->MoveFirst();
		echo "<option value='0'>Seleccionar</option>";
		while (!$db->EndOfSeek()) {
			$row = $db->Row();
		
			echo "<option value='" . $row->id . "'>" . $row->nombreAcontecimiento . "</option>";
		}      
    }

    public function DropDownTraerUltimoTorneo()
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query("SELECT * FROM Campeonato where estado = 'En Curso'")) $db->Kill();
		$db->MoveFirst();
		
		while (!$db->EndOfSeek()) {
			$row = $db->Row();
		
			echo "<option value='" . $row->id . "'>" . $row->nombre . "</option>";
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
        if($db->RowCount() > 0){
            $db->MoveFirst();
            $row = $db->Row();
            $ultimoPartido = $row->id +1;
            return $ultimoPartido;
        }
        else{
            return 1 ;
        }
	
      
    }

    function TraerUltimoSede()
	{       

        $consulta = "SELECT * FROM sede";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }

    public function eliminarPartidoProgramado($codProgramacion)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }
      
       

        $consulta = "DELETE from programacionPartidos where codProgramacion = $codProgramacion";       
        if (!$db->Query($consulta)) {
            $db->Kill();
            return 'error';
        }
       
       return 'ok' ;
      
    }

    public function RegistrarProgramacionPartido($idEquipoLocal,$idEquipoVisitante,$fecha,$Cancha,$confirmacion)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return 'error';
        }
      
        $respuesta = array('request' => 'ok','message' => '');

        if($idEquipoLocal == $idEquipoVisitante){
         
            return array('request' => 'error','message' => 'El equipo local no puede ser el mismo que el equipo visitante');
            
        }

        $consulta = "SELECT * FROM programacionPartidos where estado = 'Pendiente' and fecha = '$fecha' and cancha = '$Cancha'";
        $db->Query($consulta);
        if($db->RowCount() > 0  && $confirmacion == 0){
         
            return array('request' => 'error','message' => 'Ya existe un partido programado a la fecha, favor seleccionar otro horario');
            
        }

        $arrayfecha = explode('T',$fecha);
        $fechaSimple = $arrayfecha[0]; 
        $consulta = "SELECT * FROM programacionPartidos where (codEquipoLocal = $idEquipoLocal or codEquipoVisita = $idEquipoLocal) and date(fecha) = '$fechaSimple'";
       
        $db->Query($consulta);
        if($db->RowCount() > 0  && $confirmacion == 0){
         
            return array('request' => 'error','message' => 'El Equipo local ya tiene programado un partido en la fecha, favor seleccionar otro equipo');
            
        }

        $consulta = "SELECT * FROM programacionPartidos where (codEquipoLocal = $idEquipoVisitante or codEquipoVisita = $idEquipoVisitante) and date(fecha) = '$fechaSimple'";
        $db->Query($consulta);
        if($db->RowCount() > 0  && $confirmacion == 0){
         
            return array('request' => 'error','message' => 'El Equipo visitante ya tiene programado un partido en la fecha y hora indicada, favor seleccionar otro equipo');
            
        }

        $consulta = "SELECT date(fecha) as fecha FROM programacionPartidos where estado = 'Pendiente' GROUP BY date(fecha)";
        $db->Query($consulta);
        if($db->RowCount() > 0  && $confirmacion == 0){
            $fila = $db->Row();
            if(date('Y-m-d',strtotime($fecha)) != $fila->fecha){
                return array('request' => 'error','message' => 'En la fecha '.date('d-m-Y',strtotime($fila->fecha)).' hay partidos programados que aun no fueron completados. Favor terminar de cerrar todos esos partidos para poder registrar otra fecha.');
            }
            
        }

        $consulta = "SELECT * FROM programacionPartidos where estado = 'Pendiente' and (codEquipoLocal = $idEquipoLocal or codEquipovisita = $idEquipoLocal)";
        $db->Query($consulta);
        if($db->RowCount() > 0  && $confirmacion == 0){
           
            return array('request' => 'error','message' => 'El equipo local ya tiene un partido programado');
        }

        $consulta = "SELECT * FROM programacionPartidos where estado = 'Pendiente' and (codEquipoLocal = $idEquipoVisitante or codEquipovisita = $idEquipoVisitante)";
        $db->Query($consulta);
        if($db->RowCount() > 0  && $confirmacion == 0){
           
            return array('request' => 'error','message' => 'El equipo visitante ya tiene un partido programado');
        }

        $consulta = "INSERT INTO programacionPartidos (codEquipoLocal,codEquipoVisita,fecha,cancha,estado) values('$idEquipoLocal','$idEquipoVisitante','$fecha','$Cancha','Pendiente')";       
        if (!$db->Query($consulta)) {
            $db->Kill();
            return  array('request' => 'error','message' => 'error al registrar la programacion. ' . $consulta);
        }
       
       return $respuesta ;
      
    }

    public function validarCampeonExistente()   
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
    
        $consulta="SELECT * FROM equipocampeon ec
        LEFT join inscripcion i on i.id = ec.id
        LEFT join campeonato c on c.id = i.idCampeonato
        where c.estado = 'En Curso'";
      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }

    public function TotalGastos($estado ='')    
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
        $condicion = '';
        if($estado != ''){
            $condicion = " and c.estado = 'En Curso'";
        }
            
        $consulta="SELECT SUM(g.total) as totales FROM Gasto as g 
            LEFT JOIN Campeonato as c on c.id = g.idCampeonato
            where 1=1 $condicion";
      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db->row();
      
    }

    public function totalTransferencia($estado ='') 
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
        $condicion = '';
        if($estado != ''){
            $condicion = " and c.estado = 'En Curso'";
        }
            
        $consulta="SELECT sum(precioTransferencia) as GanaciaTransferencia FROM Transferencia t  
        LEFT JOIN Campeonato as c on c.id = t.idCampeonato
        where 1=1 $condicion";
      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db->row();
      
    }

    public function gananciasObservaciones($estado ='') 
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
        $condicion = '';
        if($estado != ''){
            $condicion = " and c.estado = 'En Curso'";
        }
            
        $consulta="SELECT sum(p.precioObservacion) as totalPrecioObservacion FROM Partido as p
                 LEFT JOIN Campeonato as c on c.id = p.idCampeonato
                 where p.estadoObservacion = 'Rechazado' $condicion";
      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db->row();
      
    }

    public function gananciasMultas($estado ='')    
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
        $condicion = '';
        if($estado != ''){
            $condicion = " and c.estado = 'En Curso'";
        }
            
        $consulta="SELECT sum(m.total) as totalMultas FROM Multa as m
                 LEFT JOIN Campeonato as c on c.id = m.idCampeonato
                 WHERE m.estado = 'Pagado' $condicion";
              
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db->row();
      
    }


    public function totalArbitraje($estado ='') 
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
        $condicion = '';
        if($estado != ''){
            $condicion = " and c.estado = 'En Curso'";
        }
            
        $consulta="SELECT sum(pa.total) as totalArbitraje FROM PagoArbitraje as pa
                LEFT JOIN Campeonato as c on c.id = pa.idCampeonato
                WHERE 1=1 $condicion";
      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db->row();
      
    }

   

    public function totalInscripcion($estado ='')   
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
        $condicion = '';
        if($estado != ''){
            $condicion = " and c.estado = 'En Curso'";
        }
            
        $consulta="SELECT sum(i.inscripcion) as gananciaInscripcion FROM Inscripcion as i
                    LEFT JOIN Campeonato as c on c.id = i.idCampeonato
                    WHERE 1=1 $condicion";
      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db->row();
      
    }

    public function TotalIngresos($estado ='')  
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
        $condicion = '';
        if($estado != ''){
            $condicion = " and c.estado = 'En Curso'";
        }
            
        $consulta="SELECT sum(precio) as GanaciaTarjetas FROM acontecimientopartido  as hp
                    LEFT JOIN Partido as p on p.id = hp.idPartido
                    LEFT JOIN campeonato as c on c.id = p.idCampeonato
                    where c.estado = 'En Curso'";
      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db->row();
      
    }

    public function ListaDirectiva($nombre,$idCampeonato)
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();

        $condicion = '';
        if($nombre != ''){
            $condicion .= " and d.nombre like '%$nombre%'";
        }
        if($idCampeonato != '0'){
            $condicion .= " and idCampeonato = $idCampeonato";
        }
        if($idCampeonato == '0' && $nombre == ''){
            $condicion .= " and d.estado = 'Vigente'";
        }

        $consulta="SELECT d.*,c.nombre as campeonato FROM Directiva as d left join Campeonato as c on c.id = d.idCampeonato where 1=1  $condicion ";
      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }

    public function ListaTorneo()
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
            
        $consulta="SELECT camp.id,camp.nombre,camp.tipo,camp.fechaInicio,camp.estado,(SELECT sum(precio) as GanaciaTarjetas FROM acontecimientopartido  as hp
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
      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }


    public function ListarPartidosProgramados($cancha = '')
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
      
        $condicion = '';

        if($cancha != ''){
            $condicion = " and p.cancha = '$cancha' ";
        }
        $consulta="SELECT p.codProgramacion,i.id as codEquipoLocal, e.nombreEquipo as equipoLocal,
        i2.id as codEquipovisita , e2.nombreEquipo as equipoVisita,
        DATE_FORMAT(p.fecha,'%H:%i') as hora,p.cancha,	p.fecha
        FROM programacionPartidos p
        LEFT JOIN inscripcion i on i.id =p.codEquipoLocal
        LEFT JOIN Equipo e on e.id = i.idEquipo
        LEFT JOIN inscripcion i2 on i2.id =p.codEquipovisita
        LEFT JOIN Equipo e2 on e2.id = p.codEquipovisita
        where p.estado = 'Pendiente' $condicion 
        order by p.fecha asc";

      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }

    public function ObtenerFechaPartidosProgramados()
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
      
        $consulta="SELECT p.codProgramacion,i.id as codEquipoLocal, e.nombreEquipo as equipoLocal,
        i2.id as codEquipovisita , e2.nombreEquipo as equipoVisita,
        DATE_FORMAT(p.fecha,'%H:%i') as hora,p.cancha,	p.fecha
        FROM programacionPartidos p
        LEFT JOIN inscripcion i on i.id =p.codEquipoLocal
        LEFT JOIN Equipo e on e.id = i.idEquipo
        LEFT JOIN inscripcion i2 on i2.id =p.codEquipovisita
        LEFT JOIN Equipo e2 on e2.id = p.codEquipovisita
        where p.estado = 'Pendiente' 
        group by date(p.fecha)";

      
		if (!$db->Query($consulta)) $db->Kill();
       // if($db->RowCount() > 0){
            //$db->MoveFirst();
            return $db;
        // }
		// else{
        //     return 0;
        // }
      
    }

    public function ListaEquipos()
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
      
        $consulta="SELECT * FROM Equipo order by nombreEquipo asc";

      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }

    public function RegistrarEquipo($nombre)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "SELECT * from Equipo where nombreEquipo = '$nombre'";
        $db->Query($consulta);

        if($db->RowCount() > 0){
            return 'nombre';
        }      

        $consulta = "INSERT INTO Equipo(nombreEquipo,fechaRegistro,estado) values('$nombre',sysdate(),'Habilitado')";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }


    public function EditarEquipo($id,$nombre)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "SELECT * from Equipo where nombreEquipo = '$nombre'";
        $db->Query($consulta);

        if($db->RowCount() > 0){
            return 'nombre';
        }      

        $consulta = "UPDATE Equipo SET nombreEquipo = '$nombre', fechaRegistro = sysdate() where id = $id";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }

    public function EstadoEquipo($id,$estado)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
      
        $consulta = "UPDATE Equipo SET estado = '$estado' where id = $id";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }

        return 'ok';
      
    }

    public function ListaEquiposInscritos($idCampeonato,$idEquipo)
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
        $condicion = "";     
   
        if($idCampeonato != "" && $idCampeonato != 0){
            $condicion .= " and i.idCampeonato = $idCampeonato";
        }
    
        if($idEquipo != '' && $idEquipo != 0){
            $condicion .= " and i.idEquipo = $idEquipo";
        }
    

        $consulta="SELECT i.id as idInscripcion,i.idEquipo,c.nombre as campeonato,e.nombreEquipo,i.fecha,i.inscripcion,i.estado FROM Inscripcion as i
        LEFT JOIN Campeonato as c on c.id = i.idCampeonato
        LEFT join Equipo as e on e.id = i.idEquipo 
        where 1=1 $condicion order by c.nombre,e.nombreEquipo asc";

      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }

    public function EstadoInscripcion($id,$estado)
    {       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "UPDATE inscripcion SET estado = '$estado' where id = $id";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
    
    }
    public function ListaJugadores($idEquipoDelegado,$idRol,$nombreJugadorFiltro,$idEquipoFiltro)
	{       

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
        
        $condicion = "";

        if($idRol != 1 && $idRol != 2){

            $condicion .= " and j.idEquipo = $idEquipoDelegado";
        }
   
        if($nombreJugadorFiltro != ""){
            $condicion .= " and j.nombre like '%$nombreJugadorFiltro%' or j.apellidos like '%$nombreJugadorFiltro%'";
        }
    
        if($idEquipoFiltro != 0){
            $condicion .= " and j.idEquipo = $idEquipoFiltro";
        }
    

        $consulta="SELECT j.id as idJugador,j.nombre,j.apellidos,j.ci,j.fechaNacimiento,j.nroCamiseta,e.nombreEquipo,j.estado,j.idEquipo
            FROM Jugador as j 
            left join Equipo as e on e.id = j.idEquipo where 1=1 $condicion order by j.idEquipo,j.nombre asc";

      
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }


    
    public function RegistrarJugador($nombre,$apellidos,$carnet,$nacimiento,$nroCamiseta,$idEquipo)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "SELECT * from Jugador where nroCamiseta = $nroCamiseta and idEquipo = $idEquipo and estado = 'Habilitado'";
        $db->Query($consulta);

        if($db->RowCount() > 0){
            return 'nrocamiseta';
        }


        $consulta = "SELECT * from Jugador where ci = '$carnet'";
        $db->Query($consulta);

        if($db->RowCount() > 0){
            return 'carnet';
        }


        $consulta = "INSERT INTO Jugador(nombre,apellidos,ci,fechaNacimiento,nroCamiseta,idEquipo,estado) values('$nombre','$apellidos','$carnet','$nacimiento','$nroCamiseta',$idEquipo,'Habilitado')";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }

    public function EditarJugador($id,$nombre,$apellidos,$carnet,$nacimiento,$nroCamiseta,$idEquipo)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "SELECT * from Jugador where nroCamiseta = $nroCamiseta and idEquipo = $idEquipo and estado = 'Habilitado'";
        $db->Query($consulta);

        if($db->RowCount() > 0){
            return 'nrocamiseta';
        }

        $consulta = "SELECT * from Jugador where ci = '$carnet' and id != $id";
        $db->Query($consulta);

        if($db->RowCount() > 0){
            return 'carnet';
        }

        $consulta = "UPDATE Jugador SET nombre = '$nombre', apellidos = '$apellidos', ci = '$carnet', fechaNacimiento = '$nacimiento',  nroCamiseta = '$nroCamiseta' where id = $id";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }


    public function EstadoJugador($id,$estado)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "UPDATE Jugador SET estado = '$estado' where id = $id";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }

    public function EliminarTarjetas($id)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "UPDATE acontecimientopartido SET  estado = 'Eliminado' where id = $id";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }

    public function RegistrarPagoTarjeta($id,$precio)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "UPDATE acontecimientopartido SET  precio = $precio ,estado = 'Pagado' where id = $id";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }

    public function ListaTarjetasRojas($idRol,$idEquipoDelegado,$permisos)
	{       

        $condicion = "";

       // if($idRol != 1 && $idRol != 2){
       if($permisos == 0){
            $condicion = " and e.id = $idEquipoDelegado";
        }
        $consulta = "SELECT hp.id as idHp, j.nombre,j.apellidos,hp.Equipo,p.fechaPartido FROM acontecimientopartido as hp 
                LEFT JOIN acontecimiento as h on h.id = hp.idAcontecimiento 
                LEFT JOIN Jugador as j on j.id = hp.idJugador 
                LEFT JOIN Partido as p on p.id = hp.idPartido
                LEFT JOIN Campeonato as c on c.id = p.idCampeonato                
                LEFT JOIN Equipo as e on e.nombreEquipo = hp.Equipo
                where hp.estado = 'Pendiente' and h.nombreAcontecimiento = 'Tarjeta Roja' and c.estado = 'En Curso' $condicion
                order by p.fechaPartido desc";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }

    public function ListaTarjetasAmarillas($idRol,$idEquipoDelegado,$permisos)
	{       

        $condicion = "";

        //if($idRol != 1 && $idRol != 2){
        if($permisos == 0){
            $condicion = " and e.id = $idEquipoDelegado";
        }
        $consulta = "SELECT hp.id as idHp, j.nombre,j.apellidos,hp.Equipo,p.fechaPartido FROM acontecimientopartido as hp 
                LEFT JOIN acontecimiento as h on h.id = hp.idAcontecimiento 
                LEFT JOIN Jugador as j on j.id = hp.idJugador 
                LEFT JOIN Partido as p on p.id = hp.idPartido
                LEFT JOIN Campeonato as c on c.id = p.idCampeonato                
                LEFT JOIN Equipo as e on e.nombreEquipo = hp.Equipo
                where hp.estado = 'Pendiente' and h.nombreAcontecimiento = 'Tarjeta Amarilla' and c.estado = 'En Curso' $condicion
                order by p.fechaPartido desc";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
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
        $consulta = "SELECT j.nombre,j.apellidos, e1.nombreEquipo as EquipoInicial, e.nombreEquipo as EquipoDestino,c.nombre as campeonato,
        t.fecha, t.precioTransferencia
        FROM Transferencia as t 
        left join Jugador as j on j.id = t.idJugador                   
        left join Equipo as e1 on e1.id = t.idEquipoInicial                   
        LEFT join inscripcion i on i.id = t.idEquipo
          left join Equipo as e on e.id = i.idEquipo
          LEFT join campeonato c on c.id = i.idCampeonato
                    
                    where 1=1 $consulta";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }

    public function TraerJugadoresxEquipos($idEquipo1)
	{       

        $consulta = "SELECT j.id as idJugador,j.nombre,j.apellidos,j.nroCamiseta,e.nombreEquipo FROM Jugador as j 
        left join Equipo as e on e.id = j.idEquipo 
        where e.id = $idEquipo1
        order by e.nombreEquipo,j.nombre ASC";

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

        //$consulta = "SELECT * FROM Equipo where nombreEquipo = '$nombreEquipo'";
        $consulta="SELECT i.id,e.nombreEquipo FROM inscripcion i 
        LEFT join equipo e on e.id = i.idEquipo
        LEFT join campeonato ca on ca.id = i.idCampeonato
        where ca.estado = 'en curso' and e.nombreEquipo = '$nombreEquipo'";
        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		$db->MoveFirst();
		$row = $db->Row();
        return $row;
      
    }

    public function TraerEquipoTablaPosicion($CodEquipo,$codCampeonato)
	{       

       // $consulta = "SELECT * FROM TablaPosicion where idEquipo = $CodEquipo and idCampeonato = $codCampeonato";
        $consulta = "SELECT t.* FROM TablaPosicion t 
        LEFT join inscripcion i on i.id = t.idEquipo
        where i.id = $CodEquipo and i.idCampeonato = $codCampeonato;";
        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
        if($db->RowCount() > 0){
            $db->MoveFirst();
            $row = $db->Row();
            return $row->id;
        }
		else{
            return '';
        }
      
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

    public function configuracionCobrosPendientes()
	{       

        $consulta = "SELECT * FROM configuracionCobros where precio is null or precio = 0";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db->RowCount();
      
    }

    public function ListarCobros()
	{       

        $consulta = "SELECT * from configuracionCobros order by motivo asc";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }


    public function CrearPrecio($motivo,$precio)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        

        $consulta = "SELECT * from configuracionCobros where motivo = '$motivo'";      
		if (!$db->Query($consulta)) $db->Kill();

        if($db->RowCount() > 0){
            return 'existe';
        }

        $consulta = "INSERT INTO configuracionCobros(motivo,precio,estado) values('$motivo',$precio,'Habilitado')";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }

    public function ActualizarPrecio($id,$precio)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "UPDATE configuracionCobros SET precio = $precio where id = $id";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }


    public function EstadoPrecio($id,$estado)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "UPDATE configuracionCobros SET estado = '$estado' where id = $id";                            

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }

    public function listarAnunciosVigentes()
	{       

        $consulta = "SELECT id,titulo,detalle,fechaPublicacion,fechaLimite FROM anuncios where fechaLimite >= sysdate() and estado = 'Habilitado'";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }

    public function listarAnuncios()
	{       

        $consulta = "SELECT * FROM anuncios";

        $db = new MySQL();
		if ($db->Error()) $db->Kill();
		if (!$db->Query($consulta)) $db->Kill();
		
        return $db;
      
    }

    public function DeshabilitarAnunciosAntiguos()
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $fechaActual = date('Y-m-d');
        $consulta = "UPDATE anuncios SET estado = 'Deshabilitado' where fechaLimite < '$fechaActual'";                            

    
        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }


    public function QuitarAnuncio($id)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "UPDATE anuncios SET estado = 'Deshabilitado' where id = $id";
                      

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }

    public function RegistrarAnuncio($titulo,$detalle,$fechaLimite)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "INSERT INTO anuncios(titulo,detalle,fechaPublicacion,fechaLimite,estado) VALUES('$titulo','$detalle',sysdate(),'$fechaLimite','Habilitado')";
                      

        if (!$db->Query($consulta)) {       
            return 'error';
        }


        return 'ok';
      
    }

    public function EditarAnuncio($id,$titulo,$detalle,$fechaLimite)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "UPDATE anuncios SET titulo ='$titulo', detalle = '$detalle', estado = 'Habilitado', fechaLimite= '$fechaLimite' where id = $id";
                      
        $db->Query($consulta);
        
        return 'ok';
      
    }

    public function RegistrarTransferencia($idJugador,$idEquipoOrigen,$idEquipoDestino,$fecha,$precio,$idCampeonato)
	{       

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
        
        $consulta = "INSERT INTO Transferencia(idJugador,idEquipoInicial,idEquipo,fecha,precioTransferencia) values($idJugador,$idEquipoOrigen,$idEquipoDestino,'$fecha',$precio)";

        
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
        }
       

        $consulta = "SELECT e.id as idEquipo,e.nombreEquipo FROM Inscripcion as i
        left join Equipo as e on e.id = i.idEquipo                                
        where i.id = $idEquipoDestino";
        $db->Query($consulta);
        $row = $db->Row();                                

        $consulta = "UPDATE Jugador SET idEquipo = $row->idEquipo where id = $idJugador";

        file_put_contents('./datos.log',$consulta);

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

        $consulta = "UPDATE TablaPosicion SET puntos = puntos + 3, partidosGanados = partidosGanados + 1 where id = $equipoGanadorWalkover";

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

        $consulta = "UPDATE TablaPosicion SET  partidosPerdidos = partidosPerdidos + 1 where id = $equipoPerdedorWalkover";

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

    public function InsertarPartido($idPartido,$equipo1,$equipo2,$idSede,$fecha,$idCampeonato,$modo,$observacion,$estadoObservacion,$idEquipoObservado,$PrecioObservacion,$eswalkover,$idEquipoGanadorWalkover)
	{       

        $consulta = "INSERT INTO Partido(`id`,`idEquipoLocal`, `idEquipoVisitante`, `idSede`, `fechaPartido`, `idCampeonato`, `Modo`, `Observacion`, `estadoObservacion`, `idEquipoObservado`, `precioObservacion`,`walkover`,`idEquipoGanadorWalkover`) VALUES($idPartido,$equipo1,$equipo2,$idSede,'$fecha',$idCampeonato,'$modo','$observacion','$estadoObservacion',$idEquipoObservado,$PrecioObservacion,'$eswalkover',$idEquipoGanadorWalkover)";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;
            return 'Error al registrar el partido';
        }
       
        return 'ok';
      
    }

    public function InsertarHechoPartido($idAcontecimiento,$idJugador,$equipo,$idPartido)
	{       

        $consulta = "INSERT INTO acontecimientopartido( `idAcontecimiento`, `idJugador`, `Equipo`, `idPartido`, `precio`, `estado`) values($idAcontecimiento,$idJugador,'$equipo',$idPartido,0,'Pendiente')";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;            
            return 'error';
        }
       
        return 'ok';
      
    }

    public function InsertarHechoPartidoWalkover($idAcontecimiento,$equipo,$idPartido)
	{       

        $consulta = "INSERT INTO acontecimientopartido( `idAcontecimiento`,  `Equipo`, `idPartido`, `precio`, `estado`) values($idAcontecimiento,'$equipo',$idPartido,0,'Pendiente')";

        $db = new MySQL();
        if ($db->Error()) {
            $db->Kill();
            return false;
        }
       
        $success = true;

        if (!$db->Query($consulta)) {
            $success = false;            
            return 'error';
        }
       
        return 'ok';
      
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
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}




	public function peticion($orden){
		if( stristr($orden, 'update') or stristr($orden, 'insert')  or stristr($orden, 'create') ){
			$q = $this->db->query($orden);
			return $q;			

		}else{
			$q = $this->db->query($orden)->result();
			$result = json_decode( json_encode( $q ), true );	
			return $result;			
		}
	}




	public function last($tabla){
		$orden = "SELECT * FROM $tabla order by id_$tabla desc limit 1";
		$q = $this->db->query($orden)->result();
		$r = json_decode( json_encode( $q ), true );
		if( count($r) == 0 ) return;

		$result = $r[0];

		return $result;
	}





	#
	#    XXXXX   XXXXXX    XXXX    X     X
	#    X       X    X    X   X   X X X X
	#    XXXXX   X    X    X   X   X  X  X 
	#    X       X    X    XXX     X     X
	#    X       X    X    X  X    X     X   
	#    X       XXXXXX    X   X   X     X
	#
	#

	public function form($tabla,$id = "new", $action = ""){

		/*
		$tabla = string 
		$id = interger
		$action = string
		*/

		################### CANCELAMOS SI NO ES U=1   #############################################################
		# tablas que el usuario puede agregar o editar:
		//$u = $this->session->userdata('u');
		//$arr = ['juicio','actor','movi','u','pago','preferencias','testigo','escritos','usuario'];
		//if( !in_array($tabla, $arr) and  $u!=1 ) return;
		################### RETORNAMOS SI EL USUARIO SOLO PUEDE VER  ##############################################		
		/*$u_cat = $this->session->userdata('u_cat');
		if($u_cat == 2 ){
			$fields = array();
			return $fields;	
		} 
		*/

		################### SE SANEA LAS VARIABLES    #############################################################
		$result = $this->db->query("Show tables")->result();
		$valor  = 'Tables_in_'.DATABASE; 
		foreach ($result as $v) $tables[] = $v->{$valor};
		if( !in_array($tabla, $tables) ) return;
		if( is_null($id) ) $id ='new';
		if( !is_numeric($id) and $id!='new') return;

		

		############### LISTAR COL UMN AS DE LA TABLA ############################################################
		$q = $this->db->query("SELECT COLUMN_NAME,COLUMN_TYPE,COLUMN_COMMENT,COLUMN_DEFAULT,DATA_TYPE,NUMERIC_SCALE
		FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".DATABASE."' and TABLE_NAME = '$tabla' ")->result();
		$t = json_decode( json_encode( $q ), true );
		if(empty($t) )return;
		
		$atributes = '';
		$style = array();

		if($action=='') $action = URL.'tables/'.$tabla;

		################### SE GENERA EL PRIMER ARRAY #############################################################
		$var = array(
		'element'=>'input',
		'type'=>'hidden',
		'name'=>'tabla',
		'value'=>$tabla,
		'col'=>0,
		'id'=>'tabla',
		'display'=>'hidden',
		'action'=>$action,
		'style'=>$style
		);
		$fields['tabla'] = $var;
		############### GUARDA LOS VALORES DEL RESGISTRO #############################################
		$c = '';
		if($id!='new' ):
			$q = $this->db->query("SELECT * FROM $tabla where id_$tabla = $id")->result();
			$c = json_decode( json_encode( $q ), true );
		endif;
		if( empty($c) and $id!='new' )
			return;
		############### CLICLO FOR QUE LEE LA TABLA  #################################################
		for ($i=0; $i <count($t) ; $i++) { 

			################### DEFINICION DE VARIABLES PRINCIPALES ##################################
			$colName    = $t[$i]['COLUMN_NAME']; 
			$colType    = $t[$i]['COLUMN_TYPE']; 
			$colComment = $t[$i]['COLUMN_COMMENT']; 
			$colDefault = $t[$i]['COLUMN_DEFAULT']; 
			$colDataType = $t[$i]['DATA_TYPE']; 
			$colNumericScale = $t[$i]['NUMERIC_SCALE']; 

			################### LABEL ################################################################
		    $colLabel = str_replace('_id', '', $colName); # reemplaza _id con "nada", osea borra
			$colLabel = str_ireplace('_'.$tabla, ' ', $colLabel); # para que no aparezca asi: id_tabla
		    $colLabel = str_ireplace('_', ' ', $colLabel); # reemplaza _ con un espacio
		    $colLabel = ucwords($colLabel); # pone la Primera Letra en Mayuscula

			################### ELEMENT TYPE #########################################################
			// Default element type
			$element = 'input';
			$type = '';
			if(stristr($colType, 'varchar')) $type = 'text';
			if(stristr($colType, 'int')) $type = 'number';
			if(stristr($colName, '_id')) $element = 'select';
			if(stristr($colName, 'id_')) $type = 'hidden';
			if(stristr($colName, 'file_') or $colName=='file') $type = 'file';
			if(stristr($colComment, 'checkbock')) $element = 'checkbox';
			if(stristr($colComment, 'datalist')) $element = 'datalist';
			if($colDataType=='decimal') $type = 'number';
			if($colType=='date') $type = 'date';
			if($colType=='time') $type = 'time';
			if($colType=='datetime') $type = 'datetime';
			if($colType=='timestamp') $type = 'hidden';
			if($colType=='longtext') $element = 'textarea';
			if($colType=='mediumtext') $element = 'textarea';
			if($colType=='text' ) $element = 'textarea';


			################### VALUE #################################################################
			if($id == 'new' ){
				$value = $colDefault;
				if($colName == 'id_'.$tabla) $value = 'new';
				if($colType == 'date' )   $value = date("Y-m-d");
			}else{
				$value = $c[0][$colName];
			}

			################### OPTION ################################################################
			$option = '';
			$ot = str_replace('_id', '', $colName);
			if($element=='select' or $element=='datalist'){
				$orden = "SELECT * FROM $ot ";
				$q = $this->db->query($orden)->result();
				$option = json_decode( json_encode( $q ), true );	
			}
			################### DISPLAY HIDDEN #########################################################
			$display = '';
			if(stristr($colComment, 'hidden'))$display = 'hidden';
	
			################### PLACEHOLDER ###########################################################
			# placeholder: Hola que Haces, required, col
			$placeholder = '';
			if(stristr($colComment, 'placeholder')):
				$a = explode(',', $colComment);
				foreach ($a as $key => $val):
					if(stristr($val, 'placeholder'))
						$placeholder = str_replace('placeholder:', '', $val);
				endforeach;
			endif;
			################### COL - ANCHO DE ########################################################
			if(stristr($colComment, 'col:')):
				$a = explode(',', $colComment);
				foreach ($a as $key => $val):
					if(stristr($val, 'col'))
						$col = str_replace('col:', '', $val);
						$col = trim($col);
				endforeach;
			else:
				$col = 3;
				if($type   == 'number' )$col = 2;
				if($type   == 'date' )  $col = 2;
				if($type   == 'time' )  $col = 2;
				if($element=='checkbox')$col = 1;
				if($type   == 'hidden' )$col = 0;
			endif;
			################### ATRIBUTES #############################################################
			$atributes = '';
			################### REQUIERED #############################################################
			if(stristr($colComment, 'required')) $atributes.= ' required ';
			if(stristr($colName, '_id'))  $atributes.= ' required ';

			################### AUTOFOCUS #############################################################
			if(stristr($colComment, 'autofocus')) $atributes.= ' autofocus ';
			################### DECIMAL   #############################################################
			if(stristr($colDataType, 'decimal')){
				if( $colNumericScale == 1 ) $atributes.= '  step=0.1 ';
				if( $colNumericScale == 2 ) $atributes.= '  step=0.01 ';
				if( $colNumericScale == 3 ) $atributes.= '  step=0.001 ';
				if( $colNumericScale == 4 ) $atributes.= '  step=0.0001 ';
				if( $colNumericScale == 5 ) $atributes.= '  step=0.00001 ';
			}
			################### USERS #############################################################
			if($colName=='users_id'){
				$display = 'hidden';
				$value = $_SESSION['id_users'];
			} 

			################### SE GENERA EL ARRAY CON LOS DATOS ######################################
		    $var = array(
		    'label'=>$colLabel,
		    'element'=>$element,
		    'type'=>$type,
		    'name'=>$colName,
		    'value'=>$value,
		    'option'=>$option,
		    'placeholder'=>$placeholder,
		    'display'=>$display,
		    'atributes'=>$atributes,
		    'col'=>$col,
		    'colName'=>$colName,
		    'style'=>$style
		    );

		    if($colType!='timestamp' )
			    $fields[$colName] = $var; 
		}
		################### SE GENERA SUBMIT #############################################################
		$var = array(
	    'label'=>'Enviar',
		'element'=>'input',
		'type'=>'submit',
		'name'=>'submit',
		'value'=>'Guardar',
		'col'=>2,
		'id'=>'submit',
		'display'=>'',
		'style'=>$style
		);
		$fields['submit'] = $var;

		return $fields;
	}






	#
	#    XXXXX   XXXXXX    X    XXXX
	#    X       X    X    X    X   X
	#    X       X   X     X    X   X 
	#    X  XX   XXXX      X    X   X
	#    X   X   X   X     X    X   X
	#    XXXXX   X    X    X    XXXX
	#
	#

	public function grid($tabla,$where='',$orderBy='') { 

		#2 SETEAMOS el where y el order by
		if($where!='')
			$where   = ' WHERE 1=1 and '.$where;
		else
			$where   = ' WHERE 1=1 ';

		if($orderBy=='')
			$orderBy = ' ORDER BY id_'.$tabla;
		else
			$orderBy = ' ORDER BY '.$orderBy;


		#3 ########### LISTAR COLU MNAS DE LA TABLA #################################################
		$q = "SELECT COLUMN_NAME,DATA_TYPE,COLUMN_KEY
		FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".DATABASE."' and TABLE_NAME = '$tabla'";
		$t = $this->db->query($q)->result();
		#############################################################################################

		#4 empezamos a crear el query con el seteo inicial y el recorrido del array $t
		$q = 'SELECT ';
		$from = ' FROM '.$tabla.' ';

		foreach ($t as $key => $value):

	        $colName = $value->COLUMN_NAME;
	        $colType = $value->DATA_TYPE;
	        $colKey  = $value->COLUMN_KEY;

	        if($key>0) $q.=', ';

	        if( stristr($colName,'_id') ){

	        	$a = str_replace('_id', '', $colName);
				$qt = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
				WHERE  table_schema =  '".DATABASE."' and TABLE_NAME = '".$a."' and ORDINAL_POSITION=2";
				$r = $this->db->query($qt)->result();
				$tn = json_decode( json_encode( $r ), true );
				$name = $tn[0]['COLUMN_NAME'];

	        	$q.= $a.'.'.$name. ' as '.$a;
	        	$q.= ','. $tabla.'.'.$colName.' as '.$colName;
	        	$from.=', '.$a;
	        	$where.=' and '.$a.'.id_'.$a.' = '.$tabla.'.'.$a.'_id';
	        	$colName = $a;
	        }else{
	        	if($colType=='date'){
					$q.= "DATE_FORMAT($tabla.$colName,'%d/%m/%Y') as $colName";
					//$q.= $tabla.'.'.$colName.' as '.$colName;
	        	}else{
		        	$q.= $tabla.'.'.$colName.' as '.$colName;
	        	}
	        }

			$forLabel = str_replace('_id', '', $colName); # reemplaza _id con "nada", osea borra
			$forLabel = str_replace('_'.$tabla, '', $forLabel); # reemplaza el nom bre de la tabla, osea borra
			$forLabel = str_replace('_', ' ', $forLabel); # reemplaza _ con un espacio
			$forLabel = ucwords($forLabel); # pone la Primera Letra en Mayuscula

		    $count_t = count($t);
		    $count_t--;
			if( $key == 0 ){
				$col['abrir'] = ["label"=>'📂',"name"=>'abrir',"width"=>'20'];
				$title['abrir'] = '📂';
			}

			
			$title[$colName]  = $forLabel;
			

				$arr = ['label'=>$forLabel,'name'=>$colName,'width'=>'40','sortype'=>'varchar','align'=>'left' ];
			if($colType=='date') 
				$arr = ['label'=>$forLabel,'name'=>$colName,'width'=>'40','sortype'=>'date', 'datefmt'=>'d-m-Y'];

			if( $colKey!='MUL' and $colType=='int'){
				$wt = '50';
				if( $colName == 'id_'.$tabla ) $wt = '15';
				$arr = ['label'=>$forLabel,'name'=>$colName,'width'=>$wt,'sortype'=>'int','align'=>'right'];
			}

			$col[$colName] = $arr;			

			if( $key == $count_t ){
				$col['editar'] = ["label"=>'✎',"name"=>'editar',"width"=>'20'];
				$title['editar'] = '✎';
			}
		/*
        {name:'invid',index:'invid', width:55, sorttype:'int'}, 
        {name:'invdate',index:'invdate', width:90, sorttype:'date', datefmt:'Y-m-d'}, 
        {name:'amount',index:'amount', width:80, align:'right',sorttype:'float'}, 
        {name:'tax',index:'tax', width:80, align:'right',sorttype:'float'}, 
        {name:'total',index:'total', width:80,align:'right',sorttype:'float'}, 
        {name:'note',index:'note', width:150, sortable:false}
		*/
		
		endforeach; // termina el recorrido de t

		#5 hacemos el query
		$q = $q.$from.$where.$orderBy;
		
		$re = $this->db->query($q)->result();
		$r = json_decode( json_encode( $re ), true );

		#6 agregamos los botones de abrir y editar al contenido
		$path = URL.$tabla;
		if( $this->uri->segment(1) == 'tables' ) $path = URL.'tables/'.$tabla;;

		/*
		images options:
		
		UNICODE: 📂 ✎

		CDN <img src="https://maxcdn.icons8.com/office/PNG/16/Very_Basic/open_folder-16.png" title="Open Folder" width="16" height="16">
		CDN <img src="https://maxcdn.icons8.com/office/PNG/16/Very_Basic/edit-16.png" title="Edit" width="16" height="16">

		*/ 

		for ($i=0; $i <count($r); $i++) { 
			$reg = $r[$i];
	        $id = $r[$i]['id_'.$tabla];
		    $abrir  = ['abrir'=> '<a href="'.$path.'/show/'.$id.'" class="btn btn-link btn-xs">📂</a>'];
		    $editar = ['editar'=> '<a href="'.$path.'/edit/'.$id.'">✎</a>'];

			$reg = $abrir+$reg;				
			$reg = $reg+$editar;
			$r[$i] = $reg;
		}
		//pre($q);

		$return['meta']['tabla'] = $tabla;
		$return['meta']['where'] = $where;
		$return['meta']['orderBy'] = $orderBy;

		$return['titulo'] = $title;
		$return['columna'] = $col;
		$return['contenido'] = $r;

		return $return;
	}



	

	#
	#   XXX   XXXXX  XXXXXX     XX   XX
	#    X      X    X          X X X X
	#    X      X    X          X  X  X 
	#    X      X    XXXXXX     X  X  X   
	#    X      X    X          X     X
	#    X      X    X          X     X
	#   XXX     X    XXXXXX     X     X
	#
	#

	public function item($tabla,$id) { 
		#3 ########### LISTAR COLU MNAS DE LA TABLA #################################################
		$q = "SELECT COLUMN_NAME,DATA_TYPE,COLUMN_COMMENT,COLUMN_DEFAULT,COLUMN_KEY
		FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".DATABASE."' and TABLE_NAME = '$tabla'";
		$r = $this->db->query($q)->result();
		$t = json_decode( json_encode( $r ), true );
		#############################################################################################
		# verificamos si es 
		$where   = " WHERE id_$tabla = $id ";
		if( !is_numeric($id) )
			$where = " WHERE ".$id;
		#4 empezamos a crear el query con el seteo inicial y el recorrido del array $t
		$q = 'SELECT ';
		$from = ' FROM '.$tabla.' ';
		for ($i=0; $i <count($t) ; $i++):
	        $colName = $t[$i]['COLUMN_NAME'];
	        $colKey = $t[$i]['COLUMN_KEY'];
	        $colType = $t[$i]['DATA_TYPE'];

	        if($i>0) $q.=', ';

	        if( stristr($colName,'_id') ){
				$tf = $tabla_foranea = str_replace('_id', '', $colName);
				$query_tabla = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
				WHERE  table_schema =  '".DATABASE."' and TABLE_NAME = '".$tf."' and ORDINAL_POSITION=2";
				$r = $this->db->query($query_tabla)->result();
				$tn = json_decode( json_encode( $r ), true );
				$name = $tn[0]['COLUMN_NAME'];
				$q.= $tf.'.'.$name. ' as '.$tf;
				$q.= ','. $tabla.'.'.$colName.' as '.$colName;
				$from.=', '.$tf;
				$where.=' and '.$tf.'.id_'.$tf.' = '.$tabla.'.'.$tf.'_id';
				$colName = $tf;
	        }else{
	        	if($colType=='date')
					$q.= "DATE_FORMAT($tabla.$colName,'%d/%m/%Y') as $colName";
	        	else
		        	$q.= $tabla.'.'.$colName.' as '.$colName;
	        }
		endfor; // termina el recorrido de t
		# hacemos el query
		$q = $q.$from.$where;
		$re = $this->db->query($q)->result();
		$r = json_decode( json_encode( $re ), true );
		# si encuentra
		$return = '';
		if( count($r)>0 )
			$return = $r[0];
		return $return;
	}





	#
	#    X   X  XXXXXX  XXXX     XXXXX   XXXXX  XXXXXX 
	#    X   X  X    X  X   X   X     X    X    X      
	#    X   X  X    X  X    X  X     X    X    X       
	#    X   X  XXXXXX  X    X  XXXXXXX    X    XXXXXX    
	#    X   X  X       X    X  X     X    X    X      
	#    X   X  X       X   X   X     X    X    X      
	#    XXXXX  X       XXXX    X     X    X    XXXXXX 
	#
	#


	public function update($tabla='',$valores=''){


		if( empty($_POST) ){

			if( empty($tabla) ) return;
			
			if( !isset($valores['id']) )
				$id = 'new';			

		}else{
			if( !isset( $_POST['tabla'] ) ) return;
			$valores = $_POST;
			$tabla = $valores['tabla'];
			$id    = $valores['id_'.$tabla];
		}


		
		$re = $orden = '';

		$v='update';
		if( $id=='new' ) $v='insert';

		####################################################
		# OBTENEMOS LAS COLUMNAS DE LA TABLA
		####################################################
		$q=$this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema='".DATABASE."' and TABLE_NAME='$tabla'")->result();
		$r = json_decode( json_encode( $q ), true );
		for ($i=0; $i <count($r) ; $i++) $colName[] = $r[$i]['COLUMN_NAME'];

		# LIMPIAMOS LOS INPUT TYPE NUMBER
		foreach ($valores as $key => $value):
			$$key = $value;
			for ($i=0; $i < count($r); $i++)
				if($key == $r[$i]['COLUMN_NAME'] and $r[$i]['DATA_TYPE'] == 'int' )
					$valores[$key] = str_replace([',','.'], ['',''], $value);
		endforeach;


		# INSERT METHOD ############################3
		if( $v=='insert' ):
			$clave = $valor = '';
			foreach ($valores as $key => $value):
			  $$key = $value;
			  if( in_array($key, $colName) ){
			  	if($key=='users_id') $value = $_SESSION['id_users'];

			    $clave.="`".$key."`,";
			  	$value = str_ireplace("'", "\'", $value);
			    $valor.="'$value',";
			  }
			endforeach;
			// elimina la ultima , del conjunto de clave y valor
		    $clave = substr ($clave, 0, -1);
		    $valor = substr ($valor, 0, -1);
		    
			$orden = "INSERT INTO $tabla (".$clave.") values (".$valor.")";
		endif;

		# UPDATE METHOD ############################3
		if( $v=='update' ):
			$orden = "UPDATE `$tabla` set ";
			foreach ($valores as $key => $value):
			  if( in_array($key, $colName) ){
			  	// users_id = $_SESSION['id_users']
			  	if($key=='users_id') $value = $_SESSION['id_users'];

			  	$value = str_ireplace("'", "\'", $value);
			    $orden.=($key=='id_'.$tabla)?" `$key` = '$value'":", `$key` = '$value' ";
			  }
			endforeach;
			$orden.= " WHERE `id_$tabla` = '$id' ";
		endif;

		# P E T I C I O N  METHOD ############################3
		  $this->db->query($orden);
		  $id_original = $id;
		  if($id=='new'){
			$q = $this->db->query("SELECT last_insert_id() as id")->result();
			$a = json_decode( json_encode( $q ), true );
			$id = $a[0]['id'];
		  }







		#########################################################################################################
		############### OTRAS ACTUALIZACIONES SIMULTANEAS #######################################################
		#########################################################################################################


		if($tabla=='proyecto'):
			// ESTADO DE PROYECTOS
			$query_ite = "SELECT proyecto_estado.name_proyecto_estado as estado, count(*) as cantidad from proyecto, proyecto_estado
			where proyecto_estado.id_proyecto_estado = proyecto.proyecto_estado_id AND proyecto.ver_id=1
			Group By proyecto.proyecto_estado_id";
			$estado = $this -> Request_model -> peticion($query_ite); 
			$this->session->set_userdata('estado', $estado);

		endif;

		if($tabla=='movimiento'):
			$q = 'SELECT id_movimiento from movimiento limit 1';
			// PTE
			if($tipo_movimiento_id== 2)	$q = "UPDATE proyecto set proyecto_estado_id = 1 where id_proyecto={$proyecto_id}";
			// EBP
			if($tipo_movimiento_id== 5)	$q = "UPDATE proyecto set proyecto_estado_id = 2 where id_proyecto={$proyecto_id}";
			// DIA
			if($tipo_movimiento_id==19)	$q = "UPDATE proyecto set nro_de_licencia= '{$numero}', fecha_de_licencia='{$fecha_registro}', proyecto_estado_id = 4 where id_proyecto={$proyecto_id}";
			// RES
			if($tipo_movimiento_id==21) $q = "UPDATE proyecto set nro_de_resolucion= '{$numero}', fecha_resolucion='{$fecha_registro}', proyecto_estado_id = 5 where id_proyecto={$proyecto_id}";
			// ME
			if($tipo_movimiento_id== 8) 
				$q = "UPDATE proyecto set 
				mesa_entrada= '{$numero}', 
				fecha_mesa_entrada='{$fecha_registro}', 
				proyecto_estado_id = 3 
				where id_proyecto={$proyecto_id}";
			$tm = $tipo_movimiento_id;
				
			if($tm == 2 or $tm == 5 or $tm == 19 or $tm == 21 or $tm == 8 ){
				// ESTADO DE PROYECTOS
				$query_ite = "SELECT proyecto_estado.name_proyecto_estado as estado, count(*) as cantidad from proyecto, proyecto_estado
				where proyecto_estado.id_proyecto_estado = proyecto.proyecto_estado_id AND proyecto.ver_id=1
				Group By proyecto.proyecto_estado_id";
				$estado = $this -> Request_model -> peticion($query_ite); 
				$this->session->set_userdata('estado', $estado);

			}
			
			$this->db->query($q);

		endif;

		if($tabla=='movimiento'):
			// PTE
			if($tipo_movimiento_id== 2){
				$a = 'SELECT * from movimiento where tipo_movimiento_id=2 order by id_movimiento DESC limit 2';
				$b = $this->db->query($a)->result();
				$c = json_decode( json_encode( $b ), true );

				$last = intval($c[1]['numero']);
				$last++;

				$q = "UPDATE movimiento set 
				numero= '{$last}'
				where id_movimiento={$id}";

				$this->db->query($q);
			}
		endif;

		if($tabla=='pte' and $id_original == 'new'):
			/*
			movimiento
			id_movimiento
			nombre_movimiento
			tecnico_cga_id
			proyecto_id
			tipo_estudio_id
			tipo_movimiento_id
			detalle
			numero
			fecha_registro
			timestamp
			users_id
			alerta
			file_archivo
			ver_id
			*/
			$query = "INSERT INTO movimiento (
			'tecnico_cga_id',
			'proyecto_id',
			'tipo_estudio_id',
			'tipo_movimiento_id',
			'detalle',
			'users_id',
			) values (
			{$tecnico_cga_id},
			{$proyecto_id},
			{$tipo_estudio_id},
			'2',
			'Generado desde PTE',
			{$_SESSION['id_users']}
			)";
			$this->db->query($q);

		endif;


		# MOVE UPLOADED FILE ############################3
		if( isset($_FILES) ){
			$folder = 'content/';
			foreach ($_FILES as $key => $value) {
				if($value['error']==4){
					$re.='<br>NINGUN ARCHIVO SUBIDO<br>';
				}else{
					$path   = FCPATH.$folder.'files';
					if(!is_dir($path)){ 
						mkdir($path, 0777, true);
						chmod($path, 0777);
					}
					$path   = FCPATH.$folder.'files/'.$tabla;
					if(!is_dir($path)){ 
						mkdir($path, 0777, true);
						chmod($path, 0777);
					}
					$path   = FCPATH.$folder.'files/'.$tabla.'/'.$key;
					if(!is_dir($path)){ 
						mkdir($path, 0777, true);
						chmod($path, 0777);
					}
					if( $_FILES[$key]["type"]=='image/jpeg' )      $type = '.jpg';
					if( $_FILES[$key]["type"]=='image/png' )       $type = '.png';
					if( $_FILES[$key]["type"]=='application/pdf' ) $type = '.pdf';

					$target_file = $path.'/'.$id.$type;
					if ( move_uploaded_file($_FILES[$key]["tmp_name"], $target_file) )
						$re.='<br>UPLOADED FILE: '.$target_file;

					// elimina los demas archivos si existen
					if( file_exists($path.'/'.$id.'.jpg') and $type!='.jpg' ) unlink($path.'/'.$id.'.jpg');
					if( file_exists($path.'/'.$id.'.png') and $type!='.png' ) unlink($path.'/'.$id.'.png');
					if( file_exists($path.'/'.$id.'.pdf') and $type!='.pdf' ) unlink($path.'/'.$id.'.pdf');
				}
			} //END FOREACH
			// /pre($_FILES);
		}// END MOVE UPLOADED FILE ############################3




		# DELETE FILES
		foreach ($_POST as $key => $value) {
			if( strstr($key, 'del__')){
				$path = str_replace('del__', '', $key);
				$path = str_replace('_jpg', '.jpg', $path);
				$path = str_replace('_png', '.png', $path);
				$path = str_replace('_pdf', '.pdf', $path);
				if( file_exists($path) ) unlink($path);
			}
		}

		$_POST = array();
		$re.= $orden;

		$respuesta = update($re);
		
		return $respuesta;
	}


} // END CLASS //

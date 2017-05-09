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
		$u = $this->session->userdata('u');
		if(empty($u)) return;

		$orden = "SELECT * FROM $tabla where u_id = $u order by id_$tabla desc limit 1";
		$q = $this->db->query($orden)->result();
		$r = json_decode( json_encode( $q ), true );
		if( count($r) == 0 ) return;

		$result = $r[0];

		return $result;
	}

	public function form($tabla,$id, $action = ""){

		/*
		$tabla = string 
		$id = interger
		$action = string
		*/
		################### CANCELAMOS SI NO ES U=1   #############################################################
		# tablas que el usuario puede agregar o editar:
		$u = $this->session->userdata('u');
		$arr = ['juicio','actor','movi','u','pago','preferencias','testigo','escritos','usuario'];
		if( !in_array($tabla, $arr) and  $u!=1 ) return;
		################### RETORNAMOS SI EL USUARIO SOLO PUEDE VER  ##############################################		
		$u_cat = $this->session->userdata('u_cat');
		if($u_cat == 2 ){
			$fields = array();
			return $fields;	
		} 

		################### SE SANEA LAS VARIABLES    #############################################################
		$q = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '".DATABASE."' ")->result();
		$r = json_decode( json_encode( $q ), true );
		for ($i=0; $i <count($r) ; $i++) $tables[] = $r[$i]['TABLE_NAME'];
		if( !in_array($tabla, $tables) ) return;
		if( !is_numeric($id) ) if( $id!='new') return;

		

		############### LISTAR COL UMN AS DE LA TABLA ############################################################
		$q = $this->db->query("SELECT COLUMN_NAME,COLUMN_TYPE,COLUMN_COMMENT,COLUMN_DEFAULT,DATA_TYPE,NUMERIC_SCALE
		FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".DATABASE."' and TABLE_NAME = '$tabla' ")->result();
		$t = json_decode( json_encode( $q ), true );
		if(empty($t) )return;
		
		$atributes = '';
		$style = array();

		if($action=='') $action = URL.$tabla.'/show/'.$id;

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
			if(stristr($colType, 'int')) $type = 'number';
			if(stristr($colName, '_id')) $element = 'select';
			if(stristr($colName, 'id_')) $type = 'hidden';
			if(stristr($colComment, 'checkbock')) $element = 'checkbox';
			if($colDataType=='decimal') $type = 'number';
			if($colType=='date') $type = 'date';
			if($colType=='time') $type = 'time';
			if($colType=='datetime') $type = 'datetime';
			if($colType=='timestamp') $type = 'hidden';
			if($colType=='mediumtext') $element = 'textarea';
			if($colType=='text' ) $element = 'textarea';
			if(stristr($colType, 'varchar')) $type = 'text';


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
			if($element=='select'){
				$varcampos = 	"id_$ot,nombre_$ot";
				# BUSCAMOS SI TIENE EL CAMPO U_ID
				$u = $this->session->userdata('u');
				$usu = $this->session->userdata('usu');
				if(empty($u)) return;

				$q = $this->db->query("
				SELECT 
					COLUMN_NAME
				FROM 
					INFORMATION_SCHEMA.COLUMNS 
				WHERE 
					table_schema = '".DATABASE."' and 
					TABLE_NAME = '$ot' and 
					COLUMN_NAME = 'u_id' 
				")->result();
				$varuid = json_decode( json_encode( $q ), true );
				$where_u_id = '';
				if( !empty($varuid) )
					$where_u_id = "WHERE u_id = $u";
				$orden = "SELECT $varcampos FROM $ot $where_u_id";
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
			################### REQUIERED #############################################################
			$atributes = '';
			if(stristr($colComment, 'required')) $atributes.= ' required ';
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

		    if($colType!='timestamp' and $colName!='u_id'  and $colName!='usuario_id' )
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
		$q = "SELECT COLUMN_NAME,DATA_TYPE,COLUMN_COMMENT,COLUMN_DEFAULT,COLUMN_KEY
		FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".DATABASE."' and TABLE_NAME = '$tabla'";
		$r = $this->db->query($q)->result();
		$t = json_decode( json_encode( $r ), true );
		#############################################################################################

		#4 empezamos a crear el query con el seteo inicial y el recorrido del array $t
		$q = 'SELECT ';
		$from = ' FROM '.$tabla.' ';

		# seteamos el unierso
		$u = $this->session->userdata('u');
		if(empty($u)) return;

		for ($i=0; $i <count($t) ; $i++):
	        $colName = $t[$i]['COLUMN_NAME'];
	        $colKey = $t[$i]['COLUMN_KEY'];
	        $colType = $t[$i]['DATA_TYPE'];

	        if($i>0) $q.=', ';

	        if( stristr($colName,'_id') ){
	        	$a = str_replace('_id', '', $colName);
	        	$q.= $a.'.nombre_'.$a. ' as '.$a;
	        	$q.= ','. $tabla.'.'.$colName.' as '.$colName;
	        	$from.=', '.$a;
	        	$where.=' and '.$a.'.id_'.$a.' = '.$tabla.'.'.$a.'_id';
	        	if($colName=='u_id'){
	        		$where.=' and '.$tabla.'.u_id = '.$u;
	        	}
	        	$colName = $a;
	        }else{
	        	if($colType=='date'){
					$q.= "DATE_FORMAT($tabla.$colName,'%d/%m/%Y') as $colName";
	        	}else{
		        	$q.= $tabla.'.'.$colName.' as '.$colName;
	        	}
	        }

			$forLabel = str_replace('_id', '', $colName); # reemplaza _id con "nada", osea borra
			$forLabel = str_replace('_'.$tabla, '', $forLabel); # reemplaza el nombre de la tabla, osea borra
			$forLabel = str_replace('_', ' ', $forLabel); # reemplaza _ con un espacio
			$forLabel = ucwords($forLabel); # pone la Primera Letra en Mayuscula

		    $count_t = count($t);
		    $count_t--;
			if( $i == 0 ){
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

			if( $i == $count_t ){
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
		
		endfor; // termina el recorrido de t

		#5 hacemos el query
		$q = $q.$from.$where.$orderBy;
		$re = $this->db->query($q)->result();
		$r = json_decode( json_encode( $re ), true );

		#6 agregamos los botones de abrir y editar al contenido
		for ($i=0; $i <count($r); $i++) { 
			$reg = $r[$i];
	        $id = $r[$i]['id_'.$tabla];
		    $abrir  = ['abrir'=> '<a href="'.URL.$tabla.'/show/'.$id.'">📂</a>'];
		    $editar = ['editar'=> '<a href="'.URL.$tabla.'/edit/'.$id.'">✎</a>'];

			$reg = $abrir+$reg;				
			$reg = $reg+$editar;
			$r[$i] = $reg;
		}
		//pre($q);

		$return['titulo'] = $title;
		$return['columna'] = $col;
		$return['contenido'] = $r;

		return $return;
	}

	public function item($tabla,$id) { 
		#3 ########### LISTAR COLU MNAS DE LA TABLA #################################################
		$q = "SELECT COLUMN_NAME,DATA_TYPE,COLUMN_COMMENT,COLUMN_DEFAULT,COLUMN_KEY
		FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".DATABASE."' and TABLE_NAME = '$tabla'";
		$r = $this->db->query($q)->result();
		$t = json_decode( json_encode( $r ), true );
		#############################################################################################
		# SANEAMOS TABLA E ID
		if( !is_numeric($id) )
			return;
		#4 empezamos a crear el query con el seteo inicial y el recorrido del array $t
		$where   = " WHERE id_$tabla = $id ";
		$q = 'SELECT ';
		$from = ' FROM '.$tabla.' ';

		# seteamos el unierso
		$u = $this->session->userdata('u');
		if(empty($u)) return;

		for ($i=0; $i <count($t) ; $i++):
	        $colName = $t[$i]['COLUMN_NAME'];
	        $colKey = $t[$i]['COLUMN_KEY'];
	        $colType = $t[$i]['DATA_TYPE'];

	        if($i>0) $q.=', ';

	        if( stristr($colName,'_id') ){
	        	$a = str_replace('_id', '', $colName);
	        	$q.= $a.'.nombre_'.$a. ' as '.$a;
	        	$q.= ','. $tabla.'.'.$colName.' as '.$colName;
	        	$from.=', '.$a;
	        	$where.=' and '.$a.'.id_'.$a.' = '.$tabla.'.'.$a.'_id';
	        	if($colName=='u_id'){
	        		$where.=' and '.$tabla.'.u_id = '.$u;
	        	}
	        	$colName = $a;
	        }else{
	        	if($colType=='date'){
					$q.= "DATE_FORMAT($tabla.$colName,'%d/%m/%Y') as $colName";
	        	}else{
		        	$q.= $tabla.'.'.$colName.' as '.$colName;
	        	}
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
		//echo "<br><br><br><br><br><br><br><br><pre>"; print_r($r); echo "</pre>";
		return $return;
	}
	public function json($tabla,$id) { 
		#3 ########### LISTAR COLU MNAS DE LA TABLA #################################################
		$q = "SELECT COLUMN_NAME,DATA_TYPE,COLUMN_COMMENT,COLUMN_DEFAULT,COLUMN_KEY
		FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".DATABASE."' and TABLE_NAME = '$tabla'";
		$r = $this->db->query($q)->result();
		$t = json_decode( json_encode( $r ), true );
		#############################################################################################
		# SANEAMOS TABLA E ID
		if( !is_numeric($id) )
			return;
		#4 empezamos a crear el query con el seteo inicial y el recorrido del array $t
		$where   = " WHERE id_$tabla = $id ";
		$q = 'SELECT ';
		$from = ' FROM '.$tabla.' ';

		# seteamos el unierso
		$u = $this->session->userdata('u');
		if(empty($u)) return;

		for ($i=0; $i <count($t) ; $i++):
	        $colName = $t[$i]['COLUMN_NAME'];
	        $colKey = $t[$i]['COLUMN_KEY'];
	        $colType = $t[$i]['DATA_TYPE'];

	        if($i>0) $q.=', ';

	        if( stristr($colName,'_id') ){
	        	$a = str_replace('_id', '', $colName);
	        	$q.= $a.'.nombre_'.$a. ' as '.$a;
	        	$q.= ','. $tabla.'.'.$colName.' as '.$colName;
	        	$from.=', '.$a;
	        	$where.=' and '.$a.'.id_'.$a.' = '.$tabla.'.'.$a.'_id';
	        	if($colName=='u_id'){
	        		$where.=' and '.$tabla.'.u_id = '.$u;
	        	}
	        	$colName = $a;
	        }else{
	        	if($colType=='date'){
					$q.= "DATE_FORMAT($tabla.$colName,'%d/%m/%Y') as $colName";
	        	}else{
		        	$q.= $tabla.'.'.$colName.' as '.$colName;
	        	}
	        }
		endfor; // termina el recorrido de t

		# hacemos el query
		$q = $q.$from.$where;
		
		$re = $this->db->query($q)->result();
		# si encuentra
		$return = '';
		if( count($re)>0 )
			$return = $re[0];
		//echo "<br><br><br><br><br><br><br><br><pre>"; print_r($r); echo "</pre>";
		return $return;
	}	

	public function update(){
		if( empty($_POST) ) return;
		if( !isset( $_POST['tabla'] ) ) return;

		$valores = $_POST;
		$tabla = $valores['tabla'];
		$id    = $valores['id_'.$tabla];

		$v='update';
		if( $id=='new' ) $v='insert';

		//pre($_POST);

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
				if($key == $r[$i]['COLUMN_NAME'] and $r[$i]['DATA_TYPE'] == 'int' )$valores[$key] = str_replace(',', '', $value);
		endforeach;

		# SETEAMOS DESDE LA SESION EL U_ID
		$u = $this->session->userdata('u');
		$usu = $this->session->userdata('usu');
		$vencimiento = $this->session->userdata('vencimiento');

		$key_u_id = $val_u_id = $update_u_id = '';
		if (in_array("u_id", $colName)){
			if($tabla=='usuario'){
				$key_u_id = " `u_id` ";
				$val_u_id = " '$u' ";
				$update_u_id = " and `u_id` = '$u' ";
			}else{
				$key_u_id = " `usuario_id`, `u_id` ";
				$val_u_id = " '$usu', '$u' ";
				$update_u_id = " and `u_id` = '$u' and `usuario_id` = '$usu' ";

			}
		}

		# INSERT METHOD ############################3
		if( $v=='insert' ):
			$clave = $valor = '';
			foreach ($valores as $key => $value):
			  $$key = $value;
			  if( in_array($key, $colName) ){
			    $clave.="`".$key."`,";
			  	$value = str_ireplace("'", "\'", $value);
			    $valor.="'$value',";
			  }
			endforeach;
			if( $key_u_id!='' ){
				$clave = $clave.$key_u_id;
				$valor = $valor.$val_u_id;
			}else{
			    $clave = substr ($clave, 0, -1);
			    $valor = substr ($valor, 0, -1);
			}
			$orden = "INSERT INTO $tabla (".$clave.") values (".$valor.")";
		endif;

		# UPDATE METHOD ############################3
		if( $v=='update' ):
			$orden = "UPDATE `$tabla` set ";
			foreach ($valores as $key => $value):
			  if( in_array($key, $colName) ){
			  	$value = str_ireplace("'", "\'", $value);
			    $orden.=($key=='id_'.$tabla)?" `$key` = '$value'":", `$key` = '$value'";
			  }
			endforeach;
			$orden.= " WHERE `id_$tabla` = '$id' $update_u_id";
		endif;

		#if( $_SESSION['usu'] == 1 )
			//echo "<br><br><br><br><br><br><br><br><br>".$orden;


		# P E T I C I O N  METHOD ############################3
		  $this->db->query($orden);
		  $id_original = $id;
		  if($id=='new'){
			$q = $this->db->query("SELECT last_insert_id() as id")->result();
			$a = json_decode( json_encode( $q ), true );
			$id = $a[0]['id'];
		  }

		  //OTRAS ACTUALIZACIONES SIMULTANEAS
		  
			  if($tabla == 'movi' ) {
			  	$a = $this->db->query("SELECT * from m where id_m = $m_id")->result();
				$b = json_decode( json_encode( $a ), true );	
			  	$c = $b[0]['nombre_m'];
			  	$d = explode('-', $c);
			  	$siglas = $d[0];
			  }

			  if($tabla == 'movi' and $id_original=='new'){
				$q = "INSERT INTO `escritos` 
				(`juicio_id`, `siglas`, `usuario_id`, `u_id` ) 
				VALUES 
				('$juicio_id','$siglas','$usu','$u' );";
			    $this->db->query($q);
			  }

			  if( $tabla == 'movi' and $id!='new' ){
				$q = "
				UPDATE `escritos` SET
				`texto_escritos` = ''
				WHERE `juicio_id` = '$juicio_id' and `siglas` = '$siglas'
				";
			    $this->db->query($q);			  	
			  }
			  # PAGO (actualizamos la fecha de vencimiento)
			  ##################################################################
			  if($tabla=='pago'){
				# SETEAMOS DESDE LA SESION EL U_ID
				$u = $this->session->userdata('u');
				$usu = $this->session->userdata('usu');
				$plan_u = $this->session->userdata('plan');
				$vencimiento = $this->session->userdata('vencimiento');

				if($periodicidad_id==10) $periodicidad_id = 12;

				$sm = $periodicidad_id*$cantidad_id;

			  	if( $plan_u != $plan_id )
					$vencimiento = date('Y-m-d');

				$nuevafecha = strtotime ( '+'.$sm.' month' , strtotime ( $vencimiento ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );

				/*
			    pre($plan_u);
			    pre($plan_id);
			    pre($periodicidad_id);
			    pre($cantidad_id);
			    pre($nuevafecha);
				*/
		  	
			  	$q = "UPDATE `u` SET `plan_id` = '$plan_id',`vencimiento` = '$nuevafecha' WHERE `id_u` = '$u' ";
			    $this->db->query($q);			  	

				$this->session->set_userdata('plan', $plan_id);
				$this->session->set_userdata('vencimiento', $nuevafecha);


			  	/*
			  	simplemente actualizar el plan de la tabla pago, al plan de la tabla u
			  	si el plan es 11, el vencimiento de u debe ser hoy() y sumarle lo de abajo
				si el pago es mensual, sumar la cantidad y multiplicarlo por 31, el resultado sumarle al vencimiento del u
				si el pago es anual, sumar la cantidad y multiplicarlo por 365, el resultado sumarle al vencimiento del u
					[tabla] => pago
				    [id_pago] => new
				    [nombre_pago] => 
				    [plan_id] => 2
				    [periodicidad_id] => 1
				    [cantidad_id] => 1
				    [monto_pago] => 70000
				    [fecha_pago] => 2017-03-25
				    [forma_de_pago_id] => 1
				    [transaccion_boleta] => 234567899
				    [detalle_pago] => 
				    [verificado_id] => 1
			  	*/	
				    //pre($valores);
			  }


		# MOVE UPLOADED FILE ############################3
		if( isset($_FILES['file']) ){
		  if( $_FILES['file']['error']!=4 ){
			$target_dir = "../contenido/$tabla/";
			$file = FCPATH."contenido/$tabla/";

			if( $_FILES["file"]["type"]=='image/jpeg' ) $type = '.jpg';
			if( $_FILES["file"]["type"]=='image/png' ) $type = '.png';
			if( $_FILES["file"]["type"]=='application/pdf' ) $type = '.pdf';
			$target_file = $file .$id.$type;
			//echo "<br><br><br>ruta y nombre del archivo: ".$target_file;
			//pre($_FILES);
			if ( move_uploaded_file($_FILES["file"]["tmp_name"], $target_file) )

				if( file_exists(FCPATH.'/contenido/'.$tabla.'/'.$id.'.jpg') and $type!='.jpg' ) unlink(FCPATH.'/contenido/'.$tabla.'/'.$id.'.jpg');
				if( file_exists(FCPATH.'/contenido/'.$tabla.'/'.$id.'.png') and $type!='.png' ) unlink(FCPATH.'/contenido/'.$tabla.'/'.$id.'.png');
				if( file_exists(FCPATH.'/contenido/'.$tabla.'/'.$id.'.pdf') and $type!='.pdf' ) unlink(FCPATH.'/contenido/'.$tabla.'/'.$id.'.pdf');

		  }
		}

		$_POST = array();

		$orden = update($orden);
		
		return $orden;
	}


} // END CLASS //

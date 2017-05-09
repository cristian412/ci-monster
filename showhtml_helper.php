<?php
function listaSimple($lista,$js='dataTables',$id_dom=''){
    if( isset($lista['contenido']) ){
      if($id_dom == ''){
        # id_dom
        next($lista['columna']); //llevamos el puntero del array al siguiente elmento
        $a = key($lista['columna']); //obtenemos el key que necesitamos ej. id_tabla
        $id_dom = str_replace('id_', '', $lista['columna'][$a]['name'] ); // guardamos el nombre de la tabla como $id_dom
      }
      // recorremos las columnas para guardar los titulos en la variable t
      foreach ($lista['columna'] as $key => $value)
        if($key!='u_id') $t[$key] = $value['label']; // se carga si no es u_id
      // guardamos el contenido en la variable c
      $c = $lista['contenido'];
    }else{
      $t = $lista[0];
      $c = $lista['contenido'] = $lista;
      foreach ($t as $key) 
        $t[$key] = ucwords( str_replace('_', ' ', $key) );
    }

    // empieza a crear la tabla  
    $r = '<table id="grid_'.$id_dom.'" class="table table-striped table-hover table-bordered table-responsive">';
    // Table head
    $r.= '<thead class="thead-inverse">';
    foreach ($t as $value) $r.= "<th>".$value."</th>";
    $r .= '</thead>';
    // Table Body
    $r .= '<tbody>';
    foreach ($c as $value):
      $r .= "<tr>";
      foreach ($t as $k => $v) $r.= "<td>".$value[$k]."</td>";
      $r.= "</tr>";
    endforeach;
    $r .= '</tbody>';
    $r .= '</table>';
    // Script DataTables
    if($js=='dataTables')
	$r.= '
	    <script>
	    $("#grid_'.$id_dom.'").DataTable( {
		"language": { "url":     "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json" }
	    } );
	    </script>
	    ';
    // Empty Message
    if( count($lista['contenido']) == 0 )
      $r = '<div class="alert alert-dismissible alert-warning">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Atención!</h4>
            <p>No existen elementos para mostrar</p>
          </div>
          ';

	return $r;
}

function consola($q){
  echo "<script>console.log('Mostrar en consola: ".$q."')</script>";
}
function console($q){
  echo "<script>console.log('Mostrar en consola: ".$q."')</script>";
}
function pre($v){
  echo "<div style='margin:20px;border:1px solid gray'><pre>";
  print_r($v);
  echo"</pre></div>";
}

function listaJqgrid($lista){
  if( !isset($lista['columna']) ){
      $t = array();
      @$t = $lista[0];
      foreach ($t as $key => $value) {
        $colName  = "$key";
        $v = "$key";
        $v = str_replace('_', ' ', $v);
        $v = ucwords($v);
        @$t[$key] = ['label'=>$v,'name'=>$colName,'width'=>'40','sortype'=>'varchar','align'=>'left'];
      }
    $lista['columna'] = $t;
    $lista['contenido'] = $lista;
  }

  unset($lista['columna']['u']);
  $lista['columna'] = array_values($lista['columna']);
  $t = json_encode($lista['columna']);
  $c = json_encode($lista['contenido']);
  $r ='
  <script>
    var titulo = '.$t.';
    var contenido = '.$c.';
  </script>
  <table class="table" id="jqGrid"></table>
  <div id="jqGridPager" style="height:50px;"></div>
  <script>
    $("#jqGrid").jqGrid({
          data: contenido,
          datatype: "local",
          page: 1,
          colModel: titulo,
          loadonce: true,
          viewrecords: true,
          rowNum: 10,
          pager: "#jqGridPager",
          hoverrows: true,
          autowidth: true,
          height: "auto"
      });
      $("#jqGrid").navGrid("#jqGridPager", {                
          search: true, add: false,edit: false, del: false, refresh: true},
          {}, // edit options
          {}, // add options
          {}, // delete options
          { multipleSearch: false }
          );

  </script>';
  return $r;

}
function formbasico($fields,$id_dom=''){
  if(empty($fields))
    return;
	$action = $fields['tabla']['action'];
  $tabla  = $fields['tabla']['value'];
	$id     = $fields['id_'.$tabla]['value'];

  if($id_dom == '') $id_dom = "form_".$tabla;

  $result = "<div class='row'>
  <form role='form' class='form' enctype='multipart/form-data' action='$action' method='post' id='$id_dom' >";
	    foreach ($fields as $v):
        
	      #### CREA LAS VARIABLES INDIVIDUALES 
	      foreach ($v as $key => $var) $$key = $var;

          // BACKGROUND & PRE POST
          // En el array $style['divbg'=>'','labelbg'=>'','pre'=>'','post'=>''];
          $divbg = $labelbg = $pre = $post = '';
          if( array_key_exists('divbg', $style) ) $divbg = ' style="background-color: '.$style['divbg'].';" ';
          if( array_key_exists('labelbg', $style) ) $labelbg = ' style="background-color: '.$style['labelbg'].';" ';
          if( array_key_exists('pre', $style) ) $pre = $style['pre'];
          if( array_key_exists('post', $style) ) $post = $style['post'];


          $abreDiv   = "<div class='col-md-$col $display' id='div_$name'  $divbg > $pre";
          $cierraDiv = "$post </div>";


		  if( $type == 'hidden' ) $abreDiv   = '';
		  if( $type == 'hidden' ) $cierraDiv   = '';
	      $result .= $abreDiv;

	      # INPUT METHOD XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	      if($element=='input'):
          $addCommas = '';
          if($type=='number' and !stristr($atributes, 'step') ){
            $addCommas = " onkeyup=\"this.value=addCommas(this.value);\" ";
            $type = 'text';
          }
	        if($type=='hidden')
				    $result .= "<input type='hidden' name='$name' value='$value' >"; 

	        if($type!='hidden' and $element!='checkbox' and $type!='submit'):
	            $result .=
	            "<div class='form-group form-group-sm' $labelbg >
	              <label for='$name'>$label</label>
	              <input class='form-control inputGris'
                  $addCommas
	                id='$name'
	                onFocus=\"this.style.backgroundColor='#FFFFBB'\"
	                type='$type' 
	                name='$name'
	                value='$value'
	                placeholder='$placeholder'
                  $atributes
                   />
	            </div>";
	        endif;
          if($type == 'submit'):
              $result .=
              "<div class='form-group form-group-sm' $labelbg >
               <label for='$name'>$label</label><br>
                <button class='btn btn-primary' type='submit' id='$name'>$value</button>
              </div>";


          endif;
	      endif; 

	      # TEXT AREA METHOD XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	      if($element == 'textarea'):
	        $result .= "
	          <div class='form-group form-group-sm' $labelbg >
	            <label for='$name'>$label </label>
	            <textarea rows='4' cols='100' style='width:100%'
	              id='$name'
	              onFocus=\"this.style.backgroundColor='#FFFFBB'\"
	              name='$name'
	                $autofocus
	                $required 
	            >$value</textarea>
	          </div>";
	      endif;

	      # CHECKBOX METHOD TRUE O FALSE XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	      if($element == 'checkbox'):
	        $ckd = '';
		    if($value == 1) $ckd=' checked ';
	        $result .= "<div class='form-group form-group-sm' $labelbg>";
			$result .= "<label for='$name'>$label</label>";
            $result .= "<input type='checkbox' class='form-control' name='$name' id='$name value='1' $ckd/></div>";
	      endif; 


	      # SELECT METHOD XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	      if($element == 'select'):
	        $result .= "
	          <div class='form-group form-group-sm' $labelbg>
	            <label for='$name'>$label</label>
	            <select name='$name' class='form-control' id='$name' $atributes
	            onFocus=\"this.style.backgroundColor='#FFFFBB'\" >
	              <option value=''>Seleccione</option>";

	              $vt = str_ireplace('_id', '', $name);
	              for ($i=0; $i < count($option); $i++): 
	                $optionId = $option[$i]['id_'.$vt];
	                $optionTx = $option[$i]['nombre_'.$vt];
	                $selected = '';
	                if( $optionId == $value ) $selected = ' selected ';
	                $result .= "<option value='$optionId' $selected >$optionTx</option>";
	              endfor;
	            $result .= "</select></div>";
	      endif;

	      $result .= $cierraDiv;

	    endforeach; # END FOREACH

	  $result .= "
	</form></div>";
  $result .= '<script>function addCommas(x){
    //remove commas
    retVal = x ? parseFloat(x.replace(/,/g, "")) : 0;

    //apply formatting
    return retVal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

}</script>';

	return $result;
}
function update($update){
    $update = str_replace(',', ', ', $update);
    $update = '
    <script>
    setTimeout(function() {
        $.bootstrapGrowl(
        "<span style=\"color:white;font-size:2em;\">UPDATE SUCCESSFULL</span><p>'.$update.'</p>", {
  ele: "body", // which element to append to
  type: "success", // (null, "info", "danger", "success")
  offset: {from: "top", amount: 35}, // "top", or "bottom"
  align: "right", // ("left", "right", or "center")
  width: 550, // (integer, or "auto")
  height: 350, // (integer, or "auto")
  delay: 4000, // Time while the message will be displayed. It"s not equivalent to the *demo* timeOut!
  allow_dismiss: true // If true then will display a cross to close the popup.
        });
    }, 500);
    </script>
    ';
  return $update;
}
function modal($data,$title='MODAL'){
  if( $title == 'MODAL' and stristr($data, "id='form_" ) )
    $title = 'EDITAR ITEM';
  $id_dom = str_replace(' ', '-', strtolower($title) );
  $html = '
  <div class="modal fade"  data-backdrop="static" data-keyboard="false" tabindex="-1" id="'.$id_dom.'" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">'.$title.'</h4>
        </div>
        <div class="modal-body">'.
          $data.
        '</div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>
  ';
  return $html;
}

function montol($monton){
  global $montol, $longitud;  $cadena=(string)$monton; //  17750000 => 
  $c = preg_split('//', $cadena, -1, PREG_SPLIT_NO_EMPTY); // 1 7 7 5 0 0 0
  $caracter = array_reverse($c);  $longitud = strlen($cadena);  // 0 0 0 5 7 7 1 LONGITUD = 7

  /* array CARACTER
  $CARACTER[0] = 0
  $CARACTER[1] = 0
  $CARACTER[2] = 0
  $CARACTER[3] = 5
  $CARACTER[4] = 7
  $CARACTER[5] = 7
  $CARACTER[6] = 1
  */
  $uni='';$decena='';$centena='';$unidaddemil='';$decenademil='';$centenademil=''; $unidaddemillon=''; $decenademillon='';$centenademillon='';

  //UNIDAD ------------------------------------------------------------------------------------------------------------   
  if ($longitud==1 || $longitud>1) {  $valor=$caracter[0]; 
  if ($valor=='1') {$unidad='un';};    if ($valor=='2') {$unidad='dos';};  if ($valor=='3') {$unidad='tres';};
  if ($valor=='4') {$unidad='cuatro';};if ($valor=='5') {$unidad='cinco';};if ($valor=='6') {$unidad='seis';};
  if ($valor=='7') {$unidad='siete';}; if ($valor=='8') {$unidad='ocho';}; if ($valor=='9') {$unidad='nueve';};
  if ($valor=='0') {$unidad='';}; $uni=$unidad; 
  if ($longitud==2 || $longitud>2){if($caracter[1]==1 || $caracter[1]==2) {$uni='';}; };
  if ($longitud==2 || $longitud>2){if(!$caracter[1]==0 & !$caracter[0]==0 & !$caracter[1]==1 & !$caracter[1]==2) {$uni=' y '. $unidad;};  };  };

  //DECENA ------------------------------------------------------------------------------------------------------------   
  if ($longitud==2 || $longitud>2) {  $valor=$caracter[1]; // DECENA
  if ($valor=='1') {$unidad='diez';};     if ($valor=='2') {$unidad='veinte';};   if ($valor=='3') {$unidad='treinta';};
  if ($valor=='4') {$unidad='cuarenta';}; if ($valor=='5') {$unidad='cincuenta';};if ($valor=='6') {$unidad='sesenta';};
  if ($valor=='7') {$unidad='setenta';};  if ($valor=='8') {$unidad='ochenta';};  if ($valor=='9') {$unidad='noventa';};
  if ($valor=='0') {$unidad='';};$decena=$unidad; 
  if($valor=='1' & $caracter[0]==0){$decena='diez';};         if($valor=='1' & $caracter[0]==1){$decena='once';};
  if($valor=='1' & $caracter[0]==2){$decena='doce';};         if($valor=='1' & $caracter[0]==3){$decena='trece';};
  if($valor=='1' & $caracter[0]==4){$decena='catorce';};      if($valor=='1' & $caracter[0]==5){$decena='quince';};
  if($valor=='1' & $caracter[0]==6){$decena='dieciséis';};    if($valor=='1' & $caracter[0]==7){$decena='diecisiete';};
  if($valor=='1' & $caracter[0]==8){$decena='dieciocho';};    if($valor=='1' & $caracter[0]==9){$decena='diecinueve';};
  if($valor=='2' & $caracter[0]==0){$decena='veinte';};       if($valor=='2' & $caracter[0]==1){$decena='veintiuno';};
  if($valor=='2' & $caracter[0]==2){$decena='veintidos';};    if($valor=='2' & $caracter[0]==3){$decena='veintitres';};
  if($valor=='2' & $caracter[0]==4){$decena='veinticuatro';}; if($valor=='2' & $caracter[0]==5){$decena='veinticinco';};
  if($valor=='2' & $caracter[0]==6){$decena='veintiséis';};   if($valor=='2' & $caracter[0]==7){$decena='veintisiete';};
  if($valor=='2' & $caracter[0]==8){$decena='veintiocho';};   if($valor=='2' & $caracter[0]==9){$decena='veintinueve';};      };

  // CENTENA ------------------------------------------------------------------------------------------------------------ 
  if ($longitud==3 || $longitud>3) {  $valor=$caracter[2]; // CENTENA
  if ($valor=='1') {$unidad='cien';};         if ($valor=='2') {$unidad='doscientos';};   if ($valor=='3') {$unidad='trescientos';};
  if ($valor=='4') {$unidad='cuatrocientos';};if ($valor=='5') {$unidad='quinientos';};   if ($valor=='6') {$unidad='seiscientos';};
  if ($valor=='7') {$unidad='setecientos';};  if ($valor=='8') {$unidad='ochocientos';};  if ($valor=='9') {$unidad='novecientos';};
  if ($valor=='0') {$unidad='';}; $centena=$unidad; if ($valor=='1') {if (!$caracter[0]==0 || !$caracter[1]==0) { $centena='ciento';} };      };

  //UNIDAD DE MIL------------------------------------------------------------------------------------------------------------ 
  if ($longitud==4 || $longitud>4) {  $valor=$caracter[3]; 
  if ($valor=='1') {$unidad='un';};    if ($valor=='2') {$unidad='dos';};  if ($valor=='3') {$unidad='tres';};
  if ($valor=='4') {$unidad='cuatro';};if ($valor=='5') {$unidad='cinco';};if ($valor=='6') {$unidad='seis';};
  if ($valor=='7') {$unidad='siete';}; if ($valor=='8') {$unidad='ocho';}; if ($valor=='9') {$unidad='nueve';};
  if ($valor=='0') {$unidad='';}; $unidaddemil=$unidad.' mil '; if($valor=='0'){$unidaddemil=$unidad;};
  if ($longitud==5 || $longitud>5)
  {if($caracter[4]==1 || $caracter[4]==2) {$unidaddemil=''.' mil ';}; };
  if ($longitud==5 || $longitud>5)
  {if(!$caracter[4]==0 & !$caracter[4]==1 &!$caracter[4]==2 & !$caracter[3]==0) {$unidaddemil=' y '. $unidad.' mil ';};   };  };

  //DECENA DE MIL------------------------------------------------------------------------------------------------------------ 
  if ($longitud==5 || $longitud>5) {  $valor=$caracter[4]; // DECENA
  if ($valor=='1') {$unidad='diez';};     if ($valor=='2') {$unidad='veinte';};   if ($valor=='3') {$unidad='treinta';};
  if ($valor=='4') {$unidad='cuarenta';}; if ($valor=='5') {$unidad='cincuenta';};if ($valor=='6') {$unidad='sesenta';};
  if ($valor=='7') {$unidad='setenta';};  if ($valor=='8') {$unidad='ochenta';};  if ($valor=='9') {$unidad='noventa';};
  if ($valor=='0') {$unidad='';};if($caracter[3]==0 ){$decenademil=$unidad.' mil ';}else{$decenademil=$unidad;};
  if($caracter[3]==0 & $caracter[4]==0 ){$decenademil=$unidad;}
  if($valor=='1' & $caracter[3]==0){$decenademil='diez';};        if($valor=='1' & $caracter[3]==1){$decenademil='once';};
  if($valor=='1' & $caracter[3]==2){$decenademil='doce';};        if($valor=='1' & $caracter[3]==3){$decenademil='trece';};
  if($valor=='1' & $caracter[3]==4){$decenademil='catorce';};     if($valor=='1' & $caracter[3]==5){$decenademil='quince';};
  if($valor=='1' & $caracter[3]==6){$decenademil='dieciséis';};   if($valor=='1' & $caracter[3]==7){$decenademil='diecisiete';};
  if($valor=='1' & $caracter[3]==8){$decenademil='dieciocho';};   if($valor=='1' & $caracter[3]==9){$decenademil='diecinueve';};
  if($valor=='2' & $caracter[3]==0){$decenademil='veinte';};      if($valor=='2' & $caracter[3]==1){$decenademil='veintiuno';};
  if($valor=='2' & $caracter[3]==2){$decenademil='veintidos';};   if($valor=='2' & $caracter[3]==3){$decenademil='veintitres';};
  if($valor=='2' & $caracter[3]==4){$decenademil='veinticuatro';};if($valor=='2' & $caracter[3]==5){$decenademil='veinticinco';};
  if($valor=='2' & $caracter[3]==6){$decenademil='veintiséis';};  if($valor=='2' & $caracter[3]==7){$decenademil='veintisiete';};
  if($valor=='2' & $caracter[3]==8){$decenademil='veintiocho';};  if($valor=='2' & $caracter[3]==9){$decenademil='veintinueve';};     };

  // CENTENA DE MIL------------------------------------------------------------------------------------------------------------   
  if ($longitud==6 || $longitud>6) {  $valor=$caracter[5]; // CENTENA
  if ($valor=='1') {$unidad='cien';};         if ($valor=='2') {$unidad='doscientos';};   if ($valor=='3') {$unidad='trescientos';};
  if ($valor=='4') {$unidad='cuatrocientos';};if ($valor=='5') {$unidad='quinientos';};   if ($valor=='6') {$unidad='seiscientos';};
  if ($valor=='7') {$unidad='setecientos';};  if ($valor=='8') {$unidad='ochocientos';};  if ($valor=='9') {$unidad='novecientos';};
  if ($valor=='0') {$unidad='';};if($caracter[3]==0 & $caracter[4]==0 ){$centenademil=$unidad.' mil ';}else{$centenademil=$unidad;}; 
  if ($valor=='1') {  if (!$caracter[3]==0 || !$caracter[4]==0) { $centenademil='ciento';} }; 
  if ($caracter[3]==0 & $caracter[4]==0 & $caracter[5]==0) {  $centenademil='';} };      

  //UNIDAD DE MILLON ------------------------------------------------------------------------------------------------------------ 
  if ($longitud==7 || $longitud>7) {  $valor=$caracter[6]; 
  if ($valor=='1') {$unidad='un';};    if ($valor=='2') {$unidad='dos';};  if ($valor=='3') {$unidad='tres';};
  if ($valor=='4') {$unidad='cuatro';};if ($valor=='5') {$unidad='cinco';};if ($valor=='6') {$unidad='seis';};
  if ($valor=='7') {$unidad='siete';}; if ($valor=='8') {$unidad='ocho';}; if ($valor=='9') {$unidad='nueve';};
  if ($valor=='0') {$unidad='';}; $unidaddemillon=$unidad.' millones '; 
  if ($longitud==8 || $longitud>8){if($caracter[7]==1 || $caracter[7]==2) {$unidaddemillon=''.' millones ';}; };
  if ($longitud==8 || $longitud>8){if(!$caracter[7]==0 & !$caracter[7]==1 &!$caracter[7]==2 & !$caracter[6]==0) {$unidaddemillon=' y '. $unidad.' millones ';};   };
  if ($longitud==7 & $caracter[6]==1 ){$unidaddemillon= $unidad.' millón ' ;};    };

  //DECENA DE MILLON------------------------------------------------------------------------------------------------------------  
  if ($longitud==8 || $longitud>8) {  $valor=$caracter[7]; // DECENA
  if ($valor=='1') {$unidad='diez';};     if ($valor=='2') {$unidad='veinte';};   if ($valor=='3') {$unidad='treinta';};
  if ($valor=='4') {$unidad='cuarenta';}; if ($valor=='5') {$unidad='cincuenta';};if ($valor=='6') {$unidad='sesenta';};
  if ($valor=='7') {$unidad='setenta';};  if ($valor=='8') {$unidad='ochenta';};  if ($valor=='9') {$unidad='noventa';};
  if ($valor=='0') {$unidad='';};if($caracter[6]==0){$decenademillon=$unidad.' millones ';}else{$decenademillon=$unidad;};
  if($valor=='1' & $caracter[6]==0){$decenademillon='diez';};         if($valor=='1' & $caracter[6]==1){$decenademillon='once';};
  if($valor=='1' & $caracter[6]==2){$decenademillon='doce';};         if($valor=='1' & $caracter[6]==3){$decenademillon='trece';};
  if($valor=='1' & $caracter[6]==4){$decenademillon='catorce';};      if($valor=='1' & $caracter[6]==5){$decenademillon='quince';};
  if($valor=='1' & $caracter[6]==6){$decenademillon='dieciséis';};    if($valor=='1' & $caracter[6]==7){$decenademillon='diecisiete';};
  if($valor=='1' & $caracter[6]==8){$decenademillon='dieciocho';};    if($valor=='1' & $caracter[6]==9){$decenademillon='diecinueve';};
  if($valor=='2' & $caracter[6]==0){$decenademillon='veinte';};       if($valor=='2' & $caracter[6]==1){$decenademillon='veintiuno';};
  if($valor=='2' & $caracter[6]==2){$decenademillon='veintidos';};    if($valor=='2' & $caracter[6]==3){$decenademillon='veintitres';};
  if($valor=='2' & $caracter[6]==4){$decenademillon='veinticuatro';}; if($valor=='2' & $caracter[6]==5){$decenademillon='veinticinco';};
  if($valor=='2' & $caracter[6]==6){$decenademillon='veintiséis';};   if($valor=='2' & $caracter[6]==7){$decenademillon='veintisiete';};
  if($valor=='2' & $caracter[6]==8){$decenademillon='veintiocho';};   if($valor=='2' & $caracter[6]==9){$decenademillon='veintinueve';};      };

  // CENTENA DE MILLON------------------------------------------------------------------------------------------------------------    
  if ($longitud==9 || $longitud>9) {  $valor=$caracter[8]; // CENTENA
  if ($valor=='1') {$unidad='cien';};         if ($valor=='2') {$unidad='doscientos';};   if ($valor=='3') {$unidad='trescientos';};
  if ($valor=='4') {$unidad='cuatrocientos';};if ($valor=='5') {$unidad='quinientos';};   if ($valor=='6') {$unidad='seiscientos';};
  if ($valor=='7') {$unidad='setecientos';};  if ($valor=='8') {$unidad='ochocientos';};  if ($valor=='9') {$unidad='novecientos';};
  if ($valor=='0') {$unidad='';};if($caracter[6]==0 & $caracter[7]==0 ){$centenademillon=$unidad.' millones ';}else{$centenademillon=$unidad;}; 
  if ($valor=='1') {  if (!$caracter[6]==0 || !$caracter[7]==0) { $centenademillon='ciento';} };      };

  $montol="$centenademillon $decenademillon $unidaddemillon $centenademil $decenademil $unidaddemil $centena $decena $uni";

  return($montol);

};
function letras ($num) { 
  $unidad = ['un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once','doce', 'trece','catorce','quince']; 
  $decena = ['dieci','veinti','treinta','cuarenta','cincuenta','sesenta','setenta','ochenta','noventa']; 
  $centena = ['ciento','doscientos','trescientos','cuatrocientos','quinientos','seiscientos','setecientos','ochocientos','novecientos']; 
  $linea = ""; 
  $cen = (int) ($num / 100); 
  $doble = $num - ($cen*100); 
  $dec = (int)($num / 10) - ($cen*10); 
  $uni = $num - ($dec*10) - ($cen*100); 
  if ($cen > 0) $linea = $centena[$cen-1].' '; 
  if ($doble>0): 
    if ($doble == 20) $linea .= " veinte"; 
    else{ 
      if ($doble < 16 ) $linea .= $unidad[$doble-1]; 
      else{ 
        $linea .=' '. $decena[$dec-1]; 
        if ($dec>2 and $uni<>0) $linea .=' y '; 
        if ($uni>0) $linea.=$unidad[$uni-1]; 
      } 
    } 
  endif; 
  return $linea; 
} 

function montolnew($monton){
  $cadena=(string)$monton; //  17750000 => 
  $c = preg_split('//', $cadena, -1, PREG_SPLIT_NO_EMPTY); // 1 7 7 5 0 0 0
  $caracter = array_reverse($c);  
  $longitud = strlen($cadena);  // 0 0 0 5 7 7 1 LONGITUD = 7

  /* array CARACTER
  $CARACTER[0] = 0
  $CARACTER[1] = 0
  $CARACTER[2] = 0
  $CARACTER[3] = 5
  $CARACTER[4] = 7
  $CARACTER[5] = 7
  $CARACTER[6] = 1
  */
  $a = ['uni','decena','centena','unidaddemil','decenademil','centenademil',
  'unidaddemillon','decenademillon','centenademillon'];
  foreach ($a as $v) $$v = '';
  $n = ['0','1','2','3','4','5','6','7','8','9'];
  $l = ['','un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve'];
  //UNIDAD ------------------------------------------------------------------------------------------------------------   
  $v = $caracter[0];
  for ($i=0; $i < 10 ; $i++){
    if( $v==$i ) $unidad = $l[$i];
  }
  $uni=$unidad; 

  if ($longitud>=2){
    if($caracter[1]==1 || $caracter[1]==2) 
      $uni='';
    if(!$caracter[1]==0 & !$caracter[0]==0 & !$caracter[1]==1 & !$caracter[1]==2)
      $uni=' y '. $unidad;
  };
  //DECENA ------------------------------------------------------------------------------------------------------------   
  if ($longitud==2 || $longitud>2) {  $valor=$caracter[1]; // DECENA
  if ($valor=='1') {$unidad='diez';};     if ($valor=='2') {$unidad='veinte';};   if ($valor=='3') {$unidad='treinta';};
  if ($valor=='4') {$unidad='cuarenta';}; if ($valor=='5') {$unidad='cincuenta';};if ($valor=='6') {$unidad='sesenta';};
  if ($valor=='7') {$unidad='setenta';};  if ($valor=='8') {$unidad='ochenta';};  if ($valor=='9') {$unidad='noventa';};
  if ($valor=='0') {$unidad='';};$decena=$unidad; 
  if($valor=='1' & $caracter[0]==0){$decena='diez';};         if($valor=='1' & $caracter[0]==1){$decena='once';};
  if($valor=='1' & $caracter[0]==2){$decena='doce';};         if($valor=='1' & $caracter[0]==3){$decena='trece';};
  if($valor=='1' & $caracter[0]==4){$decena='catorce';};      if($valor=='1' & $caracter[0]==5){$decena='quince';};
  if($valor=='1' & $caracter[0]==6){$decena='dieciséis';};    if($valor=='1' & $caracter[0]==7){$decena='diecisiete';};
  if($valor=='1' & $caracter[0]==8){$decena='dieciocho';};    if($valor=='1' & $caracter[0]==9){$decena='diecinueve';};
  if($valor=='2' & $caracter[0]==0){$decena='veinte';};       if($valor=='2' & $caracter[0]==1){$decena='veintiuno';};
  if($valor=='2' & $caracter[0]==2){$decena='veintidos';};    if($valor=='2' & $caracter[0]==3){$decena='veintitres';};
  if($valor=='2' & $caracter[0]==4){$decena='veinticuatro';}; if($valor=='2' & $caracter[0]==5){$decena='veinticinco';};
  if($valor=='2' & $caracter[0]==6){$decena='veintiséis';};   if($valor=='2' & $caracter[0]==7){$decena='veintisiete';};
  if($valor=='2' & $caracter[0]==8){$decena='veintiocho';};   if($valor=='2' & $caracter[0]==9){$decena='veintinueve';};      };

  // CENTENA ------------------------------------------------------------------------------------------------------------ 
  if ($longitud==3 || $longitud>3) {  $valor=$caracter[2]; // CENTENA
  if ($valor=='1') {$unidad='cien';};         if ($valor=='2') {$unidad='doscientos';};   if ($valor=='3') {$unidad='trescientos';};
  if ($valor=='4') {$unidad='cuatrocientos';};if ($valor=='5') {$unidad='quinientos';};   if ($valor=='6') {$unidad='seiscientos';};
  if ($valor=='7') {$unidad='setecientos';};  if ($valor=='8') {$unidad='ochocientos';};  if ($valor=='9') {$unidad='novecientos';};
  if ($valor=='0') {$unidad='';}; $centena=$unidad; if ($valor=='1') {if (!$caracter[0]==0 || !$caracter[1]==0) { $centena='ciento';} };      };

  //UNIDAD DE MIL------------------------------------------------------------------------------------------------------------ 
  if ($longitud==4 || $longitud>4) {  $valor=$caracter[3]; 
  if ($valor=='1') {$unidad='un';};    if ($valor=='2') {$unidad='dos';};  if ($valor=='3') {$unidad='tres';};
  if ($valor=='4') {$unidad='cuatro';};if ($valor=='5') {$unidad='cinco';};if ($valor=='6') {$unidad='seis';};
  if ($valor=='7') {$unidad='siete';}; if ($valor=='8') {$unidad='ocho';}; if ($valor=='9') {$unidad='nueve';};
  if ($valor=='0') {$unidad='';}; $unidaddemil=$unidad.' mil '; if($valor=='0'){$unidaddemil=$unidad;};
  if ($longitud==5 || $longitud>5)
  {if($caracter[4]==1 || $caracter[4]==2) {$unidaddemil=''.' mil ';}; };
  if ($longitud==5 || $longitud>5)
  {if(!$caracter[4]==0 & !$caracter[4]==1 &!$caracter[4]==2 & !$caracter[3]==0) {$unidaddemil=' y '. $unidad.' mil ';};   };  };

  //DECENA DE MIL------------------------------------------------------------------------------------------------------------ 
  if ($longitud==5 || $longitud>5) {  $valor=$caracter[4]; // DECENA
  if ($valor=='1') {$unidad='diez';};     if ($valor=='2') {$unidad='veinte';};   if ($valor=='3') {$unidad='treinta';};
  if ($valor=='4') {$unidad='cuarenta';}; if ($valor=='5') {$unidad='cincuenta';};if ($valor=='6') {$unidad='sesenta';};
  if ($valor=='7') {$unidad='setenta';};  if ($valor=='8') {$unidad='ochenta';};  if ($valor=='9') {$unidad='noventa';};
  if ($valor=='0') {$unidad='';};if($caracter[3]==0 ){$decenademil=$unidad.' mil ';}else{$decenademil=$unidad;};
  if($caracter[3]==0 & $caracter[4]==0 ){$decenademil=$unidad;}
  if($valor=='1' & $caracter[3]==0){$decenademil='diez';};        if($valor=='1' & $caracter[3]==1){$decenademil='once';};
  if($valor=='1' & $caracter[3]==2){$decenademil='doce';};        if($valor=='1' & $caracter[3]==3){$decenademil='trece';};
  if($valor=='1' & $caracter[3]==4){$decenademil='catorce';};     if($valor=='1' & $caracter[3]==5){$decenademil='quince';};
  if($valor=='1' & $caracter[3]==6){$decenademil='dieciséis';};   if($valor=='1' & $caracter[3]==7){$decenademil='diecisiete';};
  if($valor=='1' & $caracter[3]==8){$decenademil='dieciocho';};   if($valor=='1' & $caracter[3]==9){$decenademil='diecinueve';};
  if($valor=='2' & $caracter[3]==0){$decenademil='veinte';};      if($valor=='2' & $caracter[3]==1){$decenademil='veintiuno';};
  if($valor=='2' & $caracter[3]==2){$decenademil='veintidos';};   if($valor=='2' & $caracter[3]==3){$decenademil='veintitres';};
  if($valor=='2' & $caracter[3]==4){$decenademil='veinticuatro';};if($valor=='2' & $caracter[3]==5){$decenademil='veinticinco';};
  if($valor=='2' & $caracter[3]==6){$decenademil='veintiséis';};  if($valor=='2' & $caracter[3]==7){$decenademil='veintisiete';};
  if($valor=='2' & $caracter[3]==8){$decenademil='veintiocho';};  if($valor=='2' & $caracter[3]==9){$decenademil='veintinueve';};     };

  // CENTENA DE MIL------------------------------------------------------------------------------------------------------------   
  if ($longitud==6 || $longitud>6) {  $valor=$caracter[5]; // CENTENA
  if ($valor=='1') {$unidad='cien';};         if ($valor=='2') {$unidad='doscientos';};   if ($valor=='3') {$unidad='trescientos';};
  if ($valor=='4') {$unidad='cuatrocientos';};if ($valor=='5') {$unidad='quinientos';};   if ($valor=='6') {$unidad='seiscientos';};
  if ($valor=='7') {$unidad='setecientos';};  if ($valor=='8') {$unidad='ochocientos';};  if ($valor=='9') {$unidad='novecientos';};
  if ($valor=='0') {$unidad='';};if($caracter[3]==0 & $caracter[4]==0 ){$centenademil=$unidad.' mil ';}else{$centenademil=$unidad;}; 
  if ($valor=='1') {  if (!$caracter[3]==0 || !$caracter[4]==0) { $centenademil='ciento';} }; 
  if ($caracter[3]==0 & $caracter[4]==0 & $caracter[5]==0) {  $centenademil='';} };      

  //UNIDAD DE MILLON ------------------------------------------------------------------------------------------------------------ 
  if ($longitud==7 || $longitud>7) {  $valor=$caracter[6]; 
  if ($valor=='1') {$unidad='un';};    if ($valor=='2') {$unidad='dos';};  if ($valor=='3') {$unidad='tres';};
  if ($valor=='4') {$unidad='cuatro';};if ($valor=='5') {$unidad='cinco';};if ($valor=='6') {$unidad='seis';};
  if ($valor=='7') {$unidad='siete';}; if ($valor=='8') {$unidad='ocho';}; if ($valor=='9') {$unidad='nueve';};
  if ($valor=='0') {$unidad='';}; $unidaddemillon=$unidad.' millones '; 
  if ($longitud==8 || $longitud>8){if($caracter[7]==1 || $caracter[7]==2) {$unidaddemillon=''.' millones ';}; };
  if ($longitud==8 || $longitud>8){if(!$caracter[7]==0 & !$caracter[7]==1 &!$caracter[7]==2 & !$caracter[6]==0) {$unidaddemillon=' y '. $unidad.' millones ';};   };
  if ($longitud==7 & $caracter[6]==1 ){$unidaddemillon= $unidad.' millón ' ;};    };

  //DECENA DE MILLON------------------------------------------------------------------------------------------------------------  
  if ($longitud==8 || $longitud>8) {  $valor=$caracter[7]; // DECENA
  if ($valor=='1') {$unidad='diez';};     if ($valor=='2') {$unidad='veinte';};   if ($valor=='3') {$unidad='treinta';};
  if ($valor=='4') {$unidad='cuarenta';}; if ($valor=='5') {$unidad='cincuenta';};if ($valor=='6') {$unidad='sesenta';};
  if ($valor=='7') {$unidad='setenta';};  if ($valor=='8') {$unidad='ochenta';};  if ($valor=='9') {$unidad='noventa';};
  if ($valor=='0') {$unidad='';};if($caracter[6]==0){$decenademillon=$unidad.' millones ';}else{$decenademillon=$unidad;};
  if($valor=='1' & $caracter[6]==0){$decenademillon='diez';};         if($valor=='1' & $caracter[6]==1){$decenademillon='once';};
  if($valor=='1' & $caracter[6]==2){$decenademillon='doce';};         if($valor=='1' & $caracter[6]==3){$decenademillon='trece';};
  if($valor=='1' & $caracter[6]==4){$decenademillon='catorce';};      if($valor=='1' & $caracter[6]==5){$decenademillon='quince';};
  if($valor=='1' & $caracter[6]==6){$decenademillon='dieciséis';};    if($valor=='1' & $caracter[6]==7){$decenademillon='diecisiete';};
  if($valor=='1' & $caracter[6]==8){$decenademillon='dieciocho';};    if($valor=='1' & $caracter[6]==9){$decenademillon='diecinueve';};
  if($valor=='2' & $caracter[6]==0){$decenademillon='veinte';};       if($valor=='2' & $caracter[6]==1){$decenademillon='veintiuno';};
  if($valor=='2' & $caracter[6]==2){$decenademillon='veintidos';};    if($valor=='2' & $caracter[6]==3){$decenademillon='veintitres';};
  if($valor=='2' & $caracter[6]==4){$decenademillon='veinticuatro';}; if($valor=='2' & $caracter[6]==5){$decenademillon='veinticinco';};
  if($valor=='2' & $caracter[6]==6){$decenademillon='veintiséis';};   if($valor=='2' & $caracter[6]==7){$decenademillon='veintisiete';};
  if($valor=='2' & $caracter[6]==8){$decenademillon='veintiocho';};   if($valor=='2' & $caracter[6]==9){$decenademillon='veintinueve';};      };

  // CENTENA DE MILLON------------------------------------------------------------------------------------------------------------    
  if ($longitud==9 || $longitud>9) {  $valor=$caracter[8]; // CENTENA
  if ($valor=='1') {$unidad='cien';};         if ($valor=='2') {$unidad='doscientos';};   if ($valor=='3') {$unidad='trescientos';};
  if ($valor=='4') {$unidad='cuatrocientos';};if ($valor=='5') {$unidad='quinientos';};   if ($valor=='6') {$unidad='seiscientos';};
  if ($valor=='7') {$unidad='setecientos';};  if ($valor=='8') {$unidad='ochocientos';};  if ($valor=='9') {$unidad='novecientos';};
  if ($valor=='0') {$unidad='';};if($caracter[6]==0 & $caracter[7]==0 ){$centenademillon=$unidad.' millones ';}else{$centenademillon=$unidad;}; 
  if ($valor=='1') {  if (!$caracter[6]==0 || !$caracter[7]==0) { $centenademillon='ciento';} };      };

  $montol="$centenademillon $decenademillon $unidaddemillon $centenademil $decenademil $unidaddemil $centena $decena $uni";

  return($montol);

};

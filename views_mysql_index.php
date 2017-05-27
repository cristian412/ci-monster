<?php
/*
creartabla.php
CREADOR DE TABLAS RELACIONALES PARA MYSQL

PALABLAS CLAVES PARA NOMBRE DE COLUMNAS:
================================================

PALABLA           GENERA
++++++++++++++++++++++++++++++++++++++++++++++++++++++
1.- id_        => PRIMARY KEY AUTOINCREMENT
2.- _id        => FOREING KEY
3.- fecha      => DATE
4.- fecha_hora => DATETIME 
5.- _nro       => INT
6.- _texto     => TEXT
*/

// generamos las opciones del select para elegir la tabla
$li_tabla = '';
foreach($table_name as $k => $v):
  $li_tabla .= "<option value=$v>$v</option>";
endforeach;

$showtables = '<div class="row">';
foreach ($show_tables as $key => $value):
  $showtables.= '<div class="col-md-2" style="border: 1px solid gray; margin:3px"><b>'.$key.'</b>';
  foreach ($value as $v):
    $showtables.= '<br>'.$v['COLUMN_NAME'];
  endforeach;
  $showtables.= '</div>';
endforeach;
$showtables.= '</div>';

// creamos los alerts
$a = ['success','warning','info'];
$b = [$create_message, $alter_message,$files_message];
$c = ['CREATE TABLE','ALTER TABLE','CONTROLERS AND VIEWS'];
$alerts = '';
for ($i=0; $i <3 ; $i++) { 
	if($b[$i]!=''){
		$alerts.='
		<div class="alert alert-dismissible alert-'.$a[$i].'">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <p>'.$c[$i].':<br>'.$b[$i].'  </p>
		</div>
		';
	}
}

##################################################################################################
/*
$q = "SELECT * FROM usuario ";
$r = $this -> Peticiones_model -> peticion($q);

//echo "<div style='margin: 100px 20px 10px 300px'>count(r)".count($r)."<pre>"; print_r($r);	echo "</pre></div>";

$banderita = 0;
for ($i=0; $i < count($r); $i++) { 
	$id = $r[$i]['id_usuario'];
	$a  = $r[$i]['nombre_apellido'];
	$b = explode(' ', $a);
	$nombre = $b[0];
	$q = "UPDATE usuario set nombre_usuario = '$nombre' where id_usuario = '$id';";
	//$r = $this -> Peticiones_model -> peticion($q);
	echo $q;
	$banderita++;

	if($banderita==1000)	{
		$banderita = 0;
		sleep(1);
	}

}

*/
##################################################################################################


?>
<div class="content-wrapper">
  <section class="content">

	<?php #echo "<pre>"; print_r($tabla); echo "</pre>"; ?>
	<?=$alerts?>


	  <H1>CREADOR DE TABLAS RELACIONALES PARA MYSQL</H1>

	  <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
	    <li class="active"><a href="#red" data-toggle="tab">Datos Generales</a></li>
	    <li><a href="#orange" data-toggle="tab">Agregar Tabla</a></li>
	    <li><a href="#yellow" data-toggle="tab">Agregar Columna</a></li>
	    <li><a href="#green" data-toggle="tab">Ver tablas</a></li>
	  </ul>
	<div id="my-tab-content" class="tab-content">
		<div class="tab-pane active" id="red">
			<div class="alert alert-default" role="alert">
			  <!-- PRIMERA PARTE - TITULOS -->
			  <H2>PALABLAS CLAVES PARA NOMBRE DE COLUMNAS:</H2>
			  <p>El nombre de columnas debe estar separado por comas. 
			  La primera columna debe ser necesariamente id_::nombre_de_la_tabla::</p>
			  ================================================
			  <table>
			  <thead><th>PALABRA QUE CONTENGA</th><th>GENERA</th></thead>
			    <tr><td>id_</td><td>PRIMARY KEY AUTOINCREMENT</td></tr>
			    <tr><td>_id</td><td>FOREING KEY (genera otra tabla con id_tabla,nombre,detalle</td></tr>
			    <tr><td>fecha</td><td>DATE</td></tr>
			    <tr><td>_nro</td><td>INT</td></tr>
			    <tr><td>_texto</td><td>TEXT</td></tr>
			  </table>
			</div>
		</div>
		<div class="tab-pane" id="orange">
			<div class="alert alert-default" role="alert">
			  <!-- SEGUNDA PARTE - AGREGAR TABLA -->

			    <form action="<?=URL?>mysql/tables" method="post">
			      <textarea name="creartabla" cols="100" rows="6"></textarea>
			      <br>
			      <button type="submit">Aceptar</button>
			    </form>
			</div>
		</div>
		<div class="tab-pane" id="yellow">
			<div class="alert alert-primary" role="alert">
			  <!-- TERCERA PARTE - AGREGAR COLUMNA -->
			    <form action="<?=URL?>mysql/tables" method="post">
			      <label>TABLE </label>  <select name="agrcol_tabla"  id="agrcol_tabla" ><option>Select</option><?=$li_tabla?></select>
			      <label>COLUMNAME</label>  <input name="agrcol_col"  id="agrcol_col" required> 
			      <label>AFTER</label>  <select  name="agrcol_after" id="agrcol_after">  </select>
			      <br><br>
			      <button type="submit">Aceptar</button>
			    </form>

			</div>
		</div>

		<div class="tab-pane" id="green">
		  <h1>tablas</h1>
		  <?=$showtables?>
		</div>
	</div>
  </div>
</div>


<script>
window.addEventListener('load',init);
function init(){

	tablas = <?php echo json_encode($show_tables)?>;
	$('select#agrcol_tabla').on('change',function(){
	    var valor = $(this).val();
	    console.log('valor 1: '+valor);
	    //console.log('tabla: '+ tablas.valor.COLUMN_NAME  );
	    $( "#agrcol_after" ).empty();
	    eval('var js = tablas.'+valor+';');
	    $.each(js, function(i,item){
	      console.log("valor "+i+" - "+js[i].COLUMN_NAME);
	      $('#agrcol_after').append('<option>'+js[i].COLUMN_NAME+'</option>');
	    })
	});
};// end document.ready
</script>

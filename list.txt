<?php
$tabla = $lista['meta']['tabla'];

# SETEAMOS LA LISTA
//unset($lista['columna']['id_juicio']);
$li = listasimple($lista);



# SETEAR EL FORMULARIO
//$fields['nro']['col'] = '1';

$formulario = formbasico($fields);
$modal = modal($formulario);
?>
  <?=$update?>
  <h1>
    Lista <?=ucwords($tabla)?>
    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#editar-item">Agregar con Modal</a>
    <a href="<?=URL.$tabla?>/edit/new" class="btn btn-success">Agregar</a>
  </h1>
  <?//=pre($lista)?>

  <?=$li?>

<?=$modal?>

<?php
$li = '<div class="row">';
$count = 0;

foreach ($tables as $key => $value) {
	$atributes = '';
	for ($i=0; $i < count($value) ; $i++) { 
		$atributes.='<br>'.$value[$i]['COLUMN_NAME'];
	}
	$li.='
        <div class="col-md-3">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><a href="'.URL.'tables/'.$key.'">'.$key.'</a></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              '.$atributes.'
            </div>
          </div>
        </div>
	';
	$count++;
	if($count==4) $li.='</div><div class="row">';
}
$li.='</div>';

?>


<h1>Home <small>Dashboard</small></h1>

<?//=pre($tables)?>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Last Session Hash</span>
              <span class="info-box-number"><?=$_SESSION['__ci_last_regenerate']?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-user"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Username</span>
              <span class="info-box-number"><?=$_SESSION['username']?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black">&#127758;</span>
            <div class="info-box-content">
              <span class="info-box-text">Website</span>
              <span class="info-box-number"><a href="www.cgambiental.com.py/basement">cgambiental.com.py/ basement</a></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-light-blue"><i class="fa fa-sign-out"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Sing Out</span>
              <span class="info-box-number"><a href="<?=URL?>users/sing_out">link</a></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>




        <a href="<?=URL?>mysql/tables"><h2>Tables <small> Database</small></h2></a>
        <div class="row">
        	<div class="col-md-12">
	        <?=$li?>
	        </div>
        </div>
        <hr>

<!-- 

 [__ci_last_regenerate] => 1495905086
    [id_users] => 1
    [username] => admin
    [password] => admin
    [tables] => Array
        (
            [0] => home_background
            [1] => menu
            [2] => nosotros
            [3] => users
            [4] => usuario
            [5] => visible
        )

)
-->

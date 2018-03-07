<?php
if ( ! $this->session->has_userdata('id_users') ) redirect( URL.'users/sign_in' );

if(!isset($yield_data)) $yield_data = '<h1>Yield Data is Empty</h1>';
if(!isset($yield_navbar)) $yield_navbar = '<li><a href="#" data-toggle="control-sidebar"><span>YIELD_NAVBAR</span></a></li>';
if(!isset($yield_sidevar)) $yield_sidebar = '<li> <a href="#"><i class="fa fa-th"></i> <span>YIELD_SIDEBAR</span></a>';

// LISTA DE TABLAS
$li_tablas = '';
$tablas = $this->session->userdata('tables');
foreach ($tablas as $value) {
  $li_tablas.='
  <div  style="float:left; margin:2px;">
    <a href="'.URL.'tables/'.$value.'" class="btn btn-primary btn-xs">'
    .ucwords($value).
    '</a>
  </div> ';
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=APPNAME?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="manifest" href="manifest.json">
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="apple-touch-icon" href="<?=URL?>apple-touch-icon.png">
  <link rel="icon" href="<?=URL?>favicon.ico" type="image/x-icon" />
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/cerulean/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/dataTables.bootstrap.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/AdminLTE.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/skins/skin-blue.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-growl/1.0.0/jquery.bootstrap-growl.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.6/fastclick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/js/app.min.js"></script>

  <style>
    .saltopagina {
    PAGE-BREAK-AFTER: always;
    }
  </style>
  <style type="text/css" media="print">

    @media print {
       #cliente-show-acciones,a,footer { display: none !important; }
       table{ font-size:0.5em; padding:1px;}
       tr,td{ padding:1px;}       
    }
    div.dataTables_wrapper div.dataTables_filter input {
      margin-left: 0.5em;
      display: inline-block;
      width: 400px !important;
      background-color:lightsteelblue !important; 
    }
         
  </style>

</head>
<body class="hold-transition skin-blue sidebar-mini fixed">
<div class="wrapper">
  <header class="main-header">
    <a href="<?=URL?>" class="logo">
      <span class="logo-mini"><b>A</b>dmin</span>
      <span class="logo-lg"><b><?=APPNAME?></b></span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <ul class="nav navbar-nav">
        <?=$yield_navbar?>
      </ul>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- BOTON IMPRIMIR Y LISTA DE TABLAS -->
          <li><a href="#" onclick="window.print();"> <span  style="color:LightGreen "><i class="fa fa-print"></i> Imprimir</span></a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> 
            <i class="fa fa-th-list"></i> Tablas <span class="caret"></span></a>
            <div class="dropdown-menu" role="menu">
              <div style="padding:5px;width: 600px; background-color: orange">
                <p><a href="<?=URL?>mysql/tables"><button class="btn btn-danger btn-sm">MySql</button></a></p>
                <?=$li_tablas?>
              </div>
            </div>
          </li>
          <li><a href="<?=URL?>users/sign_out" ><i class="fa fa-sign-out"></i> Sing Out</a></li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- =============================================== -->
  <aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
          <li> <a href="#"><i class="fa fa-th"></i> <span>Option</span></a>
                    <?=$yield_sidebar?>

        </li>
      </ul>
    </section>
  </aside>
  <!-- =============================================== -->
  <div class="content-wrapper">
    <section class="content">
          <?=$yield_data?>
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2016 <a href="http://almsaeedstudio.com" target="_blank">Almsaeed Studio</a>.</strong>  reserved.
    <div class="pull-right hidden-xs"> <b>Version</b> 2.3.8 </div>
  </footer>
</div><!-- ./wrapper -->


</body>
</html>

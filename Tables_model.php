<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tables_model extends CI_Model {

    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->helper('file');

    }

    public function create(){
    ######## RECIBE EL POST Y CREA EL TEXTO PARA LA TABLA ################################
      $alter = '';
      $crear = '';
      $creartablas = '';

      $a = '';
      if(!empty($_POST)){
        if( isset($_POST['creartabla']) )
          $a = $_POST['creartabla'];
      }
      if($a!=''):
        $array = explode(',', $a);
        $crear = '';
        $tablas = array();
        foreach ($array as $v): 
          $v = trim($v);
          $inic = $crear;
          if( stristr($v, 'id_') ){ 
            $tabla = str_replace('id_', '', $v);
            $crear.= 'CREATE TABLE  IF NOT EXISTS '.$tabla.' ( ';
            $crear.= '  id_'.$tabla.' INTEGER(10) PRIMARY KEY AUTO_INCREMENT ';
          }
          if( stristr( $v, '_id') ){
            $tablas[] = str_replace('_id', '', $v);
            $crear.= ','.$v.' INTEGER(10)';     
          }  
          if( stristr($v, '_fecha' ) )     $crear.= ','.$v.' DATE';   
          if( stristr($v, '_nro' ) )       $crear.= ','.$v.' INTEGER(10)';  
          if( stristr($v, '_texto' ) )     $crear.= ','.$v.' TEXT'; 
          if($inic==$crear)                $crear.= ','.$v.' VARCHAR(255)'; 

        endforeach;

        $fk = '';
        $creartablas ='';
        foreach ($tablas as $value):
          $fk.=', FOREIGN KEY('.$value.'_id) REFERENCES '.$value.'(id_'.$value.') '; 
          $creartablas.= 'CREATE TABLE  IF NOT EXISTS '.$value.' ( ';
          $creartablas.= '  id_'.$value.' INTEGER(10) PRIMARY KEY AUTO_INCREMENT ';
          $creartablas.= ',  name_'.$value.' VARCHAR(99) ';
          $creartablas.= ',  detail_'.$value.' VARCHAR(99) ';
          $creartablas.= ') ENGINE = InnoDB; ';
        endforeach;
        $crear .= $fk.') ENGINE = InnoDB;';

        $a = $creartablas.$crear;
        $b = explode(';', $a);
        $re = 'INICIO DE PETICION<BR>#####################';
        $this->db->trans_start();
        foreach ($b as $value) {
          if($value!='')
            $this->db->query($value);
          $re.= "<BR>Peticion: ".$value.'';
        }
        $this->db->trans_complete();
        $re.= '#####################<br>Fin de las peticiones';
        $tables = $this -> Tables_model -> table_name();
        $this->session->set_userdata('tables', $tables);
        return $re;
      endif;
  }

  public function alter(){
    ############### RECIBE EL POST Y CREA EL SCRIPT PARA AGREGAR UNA COLUMNA #######################
    $tabla = '';
    if(!empty($_POST)){
      if( isset( $_POST['agrcol_tabla'] ) ):
        $tabla = $_POST['agrcol_tabla'];
        $col   = $_POST['agrcol_col'];
        $after = $_POST['agrcol_after'];
      endif;
    } 

    if($tabla!=''):
      $inic = $col;
      $tablas = '';
      $type = '';
      $fk = '';
      $creartablas='';

      if( stristr($col, '_id'   ) ) $tablas = str_replace('_id', '', $col);
      if( stristr($col, '_id'   ) ) $type = ' INTEGER(10)';         
      if( stristr($col, '_fecha') ) $type = ' DATE';   
      if( stristr($col, '_nro'  ) ) $type = ' INTEGER(10)';  
      if( stristr($col, '_texto') ) $type = ' TEXT'; 
      if($type=='')                 $type = ' VARCHAR(255)'; 

      $alter = 'ALTER TABLE '.$tabla.' ADD '.$col.' '.$type.' AFTER '.$after.';';

      if($tablas!=''):
        $creartablas.= 'CREATE TABLE  IF NOT EXISTS '.$tablas.' ( ';
        $creartablas.= '  id_'.$tablas.' INTEGER(10) PRIMARY KEY AUTO_INCREMENT ';
        $creartablas.= ', name_'.$tablas.' VARCHAR(99) ';
        $creartablas.= ', detail_'.$tablas.' VARCHAR(99) ';
        $creartablas.= '); ';
        $alter.= 'ALTER TABLE '.$tabla.' ADD FOREIGN KEY ('.$tablas.'_id) REFERENCES '.$tablas.'(id_'.$tablas.');';
      endif;
      $a = $creartablas.$alter;
      $b = explode(';', $a);
      $re = 'INICIO DE PETICION<BR>#####################';
      $this->db->trans_start();
      foreach ($b as $value) {
        if($value!='')
          $this->db->query($value);
        $re.= "<BR>Peticion: ".$value.'';
      }
      $this->db->trans_complete();
      $re.= '#####################<br>Fin de las peticiones';
      return $re;
    endif; 
  }

  
  public function files(){
    $orden = "SHOW FULL TABLES FROM ".DATABASE;
    $a = file_get_contents("https://raw.githubusercontent.com/cristian412/ci-monster/master/files_text.txt");
    $files_text = explode('&&&', $a);
    $re = '';
    $tablas = $this->Request_model->peticion($orden);
    for ($i=0; $i <count($tablas) ; $i++):
      $tabla = $tablas[$i]['Tables_in_'.DATABASE];
      $Tabla = ucwords($tabla);

           $path   = FCPATH.'application/controllers/tables';
           if(!is_dir($path)){
            mkdir($path, 0777, true);
            chmod($path, 0777);
           }
           $path   = FCPATH.'application/views/tables';
           if(!is_dir($path)){
            mkdir($path, 0777, true);
            chmod($path, 0777);
           }
           $path   = FCPATH.'application/views/tables/'.$tabla;
           if(!is_dir($path)){ 
            mkdir($path, 0777, true);
            chmod($path, 0777);
           }
           
      $arr = ['0'=>'controller','1'=>'edit','2'=>'list','3'=>'show'];
      foreach ($arr as $key => $value) {
        $data = str_replace(['xxx','XXX'], [$tabla,$Tabla], $files_text[$key]);
        $path = FCPATH."application/views/tables/".$tabla."/".$value.".php";
        if($value=='controller')
          $path = FCPATH."application/controllers/tables/".$Tabla.".php";
        if(!file_exists($path)):
          if ( ! write_file($path, $data)){
            $re.= 'Unable to write the file'.$path.'<br>';
          }else { 
                chmod($path, 0777);
            $re.= 'File written! in '.$path.'<br>';
          }
          sleep(0.1);
        else:
          //$re.= "ya existe el archivo ".$value." <br>";
        endif;
      }
    endfor;
    return $re;
  }

  public function table_name(){
    ############### LISTAR TABLAS DE LA BASE DE DATOS ############################################
    $q  = "SHOW FULL TABLES FROM ".DATABASE;
    $re = $this->Request_model->peticion($q);
    $tabla = array();
    for ($i=0; $i <count($re) ; $i++):
      $db = 'Tables_in_'.DATABASE;
      $value = $re[$i][$db];
      $tabla[] =  $value;
    endfor;    
    return $tabla;
  }

  public function show_tables(){
    $tabla = $this -> Tables_model -> table_name();

    foreach($tabla as $k => $v):
      $q = $this->db->query("SELECT COLUMN_NAME,COLUMN_TYPE,COLUMN_COMMENT,COLUMN_DEFAULT 
      FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '".DATABASE."' and TABLE_NAME = '$v' ")->result();
      $columna = json_decode( json_encode( $q ), true );
      $tablas[$v] = $columna;
    endforeach;
    return $tablas;
  }
}




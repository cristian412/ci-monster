<?
defined('BASEPATH') OR exit('No direct script access allowed');
/*
CREAR PROYECTO EN GOOGLE

console.developers.google.com

Crear Proyecto

Crear Credenciales -> ID de cliente de OAuth

URIs de redirección autorizados
Para usarse con las peticiones de un servidor web. Es la ruta de la aplicación a la que se redirecciona a los usuarios después de autenticarse en Google. A dicha ruta se añadirá el código de autorización de acceso. Debe tener un protocolo. No puede incluir fragmentos de URL ni rutas relativas. No puede ser una dirección IP pública.

http://DOMINIO.com/admin/users/check

*/

$config['googleplus']['application_name'] = 'Nombre-de-Aplicacion';
$config['googleplus']['client_id']        = 'ID de cliente';
$config['googleplus']['client_secret']    = 'secret-key';
$config['googleplus']['redirect_uri']     = 'http://dominio.com/inside/users/check';
$config['googleplus']['api_key']          = '';
$config['googleplus']['scopes']           = array();

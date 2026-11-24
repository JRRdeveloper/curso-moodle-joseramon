<?php  // Moodle configuration file (Plantilla de desarrollo sin secretos)

unset($CFG);
global $CFG;
$CFG = new stdClass();

// Conexión a PostgreSQL local (sustituir por credenciales locales reales).
$CFG->dbtype    = 'pgsql';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'moodledev';
$CFG->dbuser    = 'moodledev';
$CFG->dbpass    = 'CAMBIAR_POR_TU_PASSWORD_LOCAL';
$CFG->prefix    = 'mdl_';

// Rutas locales (WSL2 port-forwarding y dataroot fuera del DocumentRoot).
$CFG->wwwroot   = 'http://localhost:8080';
$CFG->dataroot  = '/var/www/moodledata-dev';
$CFG->admin     = 'admin';

// Permisos octales sin bit SGID para compatibilidad con WSL2.
$CFG->directorypermissions = 0777;

// Nivel máximo de depuración para desarrollo de plugins.
@error_reporting(E_ALL | E_STRICT);
@ini_set('display_errors', '1');
$CFG->debug = (E_ALL | E_STRICT);
$CFG->debugdisplay = 1;

require_once(__DIR__ . '/lib/setup.php');

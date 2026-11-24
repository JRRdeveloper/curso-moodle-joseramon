<?php
// Script CLI de prueba para la Database API ($DB) de Moodle.
// Uso: copiar temporalmente a la raíz de Moodle (junto a config.php) y ejecutar:
// sudo -u www-data php consultas_t3.php
define('CLI_SCRIPT', true);
require(__DIR__ . '/config.php');

global $DB;

$courseid = 2;
$sql = "SELECT ue.id, u.username, e.enrol AS metodo
          FROM {user_enrolments} ue
          JOIN {enrol} e ON e.id = ue.enrolid
          JOIN {user} u ON u.id = ue.userid
         WHERE e.courseid = :courseid
      ORDER BY u.lastname";

$DB->set_debug(true);
$matriculas = $DB->get_records_sql($sql, ['courseid' => $courseid]);
$DB->set_debug(false);

foreach ($matriculas as $m) {
    mtrace("{$m->username}\t{$m->metodo}");
}
mtrace('Total: ' . count($matriculas));

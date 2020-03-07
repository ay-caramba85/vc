<?php
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helper.php'; /* beinhaltet Datenbank-Anfragen und Daten fuer die View */

$num_of_ip_addresses = ModVCHelper::get_num_of_ip_addresses(); /* Daten aus dem Helper in einer Liste speichern  */
ModVCHelper::delete_records();
$color = $params->get('fontColor');
$alignment = $params->get('alignment');
$fontSize = $params->get('fontSize');

require JModuleHelper::getLayoutPath('mod_vc', $params->get('layout', 'default'));
/* Standard Layout der View laden, die sich in /tmpl/default.php befindet */

?>

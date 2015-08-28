<?php

require "lib/configuration.php";
require "lib/schematicHandler.php";
header('Content-Type: application/json');
# Check validity of schematic ##########################################################################################
$schematic_settings = Config::get('schematic');
$schematic = new SchematicHandler($schematic_settings['folder']);
$schematic_info = $schematic -> check_validity();
if(!$schematic_info['status']) {
    die;
}
unset($schematic_info);

$schematics = array();
if(!isset($_POST['method'])) {
    $method = 'id';
} else {
    $method = getFiltered('method');
}
if($method === 'id') {
    $schematics = $schematic -> getSchematicFromId(getFiltered('id'));
} else if($method === 'username') {
    $schematics = $schematic -> getSchematicFromName(getFiltered('username'));
} else {
    echo json_encode("POST(method) must be either username or id");
    return;
}

$real = array();

foreach($schematics as $file) {
    $real[] = $schematic -> getSchematicObject($file) -> toArray();
}

$format = 0;
if(defined('JSON_PRETTY_PRINT')) {
    echo json_encode($real, JSON_PRETTY_PRINT);
} else {
    echo json_encode($real);
}

function getFiltered($id) {
    return filter_input(INPUT_POST, $id);
}
?>
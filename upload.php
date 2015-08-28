<?php

require "lib/schematicHandler.php";
require "lib/configuration.php";

$schematic_settings = Config::get('schematic');

$target_dir = $schematic_settings['up_dir'] . "/";
$target_dir = $target_dir . basename( $_FILES["uploadFile"]["name"]);

$schematic = new SchematicHandler($schematic_settings['folder']);
if (!$schematic_settings['upload']) {
    echo "Schematics disabled";
    die;
}
if (!$schematic) {
    header( "Location: index.php?failed" );
    die;
}
if ($_FILES["uploadFile"]["size"] > $schematic_settings['max_size']) {
    header( "Location: index.php?large" );
    die;
}
function endsWith($str1, $str2)
{
    return $str2 === "" || substr($str1, -strlen($str2)) === $str2;
}
if (!(endsWith($_FILES["uploadFile"]["name"], ".schematic"))) {
    header( "Location: index.php?notsch" );
    die;
}
if (file_exists($target_dir)) {
    header( "Location: index.php?exists" );
    die;
}
if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_dir)) {
    header( "Location: index.php?success" );
    die;
}
header( "Location: index.php?failed" );
?>
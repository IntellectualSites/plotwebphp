<?php
########################################################################################################################
# Copyright:                                                                                                           #
#   Citymonstret & Empire92                                                                                            #
#                                                                                                                      #
# Created to be used with PlotSquared.                                                                                 #
#                                                                                                                      #
# All rights reserved.                                                                                                 #
########################################################################################################################

# Important libraries ##################################################################################################
require "lib/schematicHandler.php";
require "lib/configuration.php";
require 'lib/h2o/h2o.php';
########################################################################################################################

# Check validity of schematic ##########################################################################################
$schematic_settings = Config::get('schematic');
$schematic = new SchematicHandler($schematic_settings['folder']);
$schematic_info = $schematic -> check_validity();
if(!$schematic_info['status']) {
    echo $schematic_info['message'];
    die;
}
try {
unset($schematic_info);
########################################################################################################################

########################################################################################################################
$page = 'search.plotsquared.html'; # Default page
if(isset($_GET['page']) || strlen($_SERVER['QUERY_STRING']) > 1) {
    $page = filter_input(INPUT_GET, 'page');
    if($page === null || strlen($page) < 1) {
        if (strpos($_SERVER['QUERY_STRING'], '=') !== FALSE) {
            $arr = explode('=',$_SERVER['QUERY_STRING'], 2);
            $page = $arr[0];
        }
        else {
            echo "no search";
        }
    }
    $page = str_replace(array('..', '/', '.'), '', $page);
}
# Should uploads be disabled?
if(!$schematic_settings['upload'] && $page === 'upload') {
    echo "Uploads are disabled";
    die;
}
# Get the actual page
$file = __DIR__ . "/template/$page.plotsquared.html";
if(!file_exists($file)) {
    $file = __DIR__ . '/template/search.plotsquared.html';
}
# Create the template
    $h2o = new h2o($file);
} catch(Exception $e) {
    $h2o = new h2o( __DIR__ . '/template/search.plotsquared.html');
}
# Set some information
$page = array(
  'heading' => 'PlotSquared web interface',
  'style' => './style/style_' . Config::get('theme') . '.css',
  'upload' => $schematic_settings['upload'],
  'up_dir' => $schematic_settings['up_dir']
);
# Render
echo $h2o->render(compact('page'));
//No need for a closing php tag ;)
?>
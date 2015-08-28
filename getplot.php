<?php

require "lib/configuration.php";
require "lib/schematicHandler.php";
# get MySQL stuff
$mysql_settings = Config::get('mysql');

$schematic_settings = Config::get('schematic');
$schematic = new SchematicHandler($schematic_settings['folder']);
$schematic_info = $schematic -> check_validity();

$host=$mysql_settings['host'];
$username=$mysql_settings['username'];
$password=$mysql_settings['password'];
$db_name=$mysql_settings['database'];
$tbl_name=$mysql_settings['prefix'] . 'plot';

function getName($uuid) {
    $url = "http://intellectualsites.com/minecraft.php?user=".$uuid;
    $ctx = stream_context_create(array(
            'http' => array(
                'timeout' => 5
            )
        )
    );
    $ret = file_get_contents($url, 0, $ctx);
    if(isset($ret) && $ret != null && $ret != false) {
        $data = json_decode($ret, true);
        return $data[$uuid]['username'];
    }else {
        return null;
    }
}

function getUUID($uuid) {
    return str_replace("-", "", $uuid);
}

$x = $_GET["x"];
$z = $_GET["z"];
$world = $_GET["world"];
$user = $_GET["user"];

$con = mysqli_connect("$host", "$username", "$password")or trigger_error('Query failed: ' . mysql_error($db), E_USER_ERROR); 
mysqli_select_db($con,"$db_name")or trigger_error('Cannot select DB: ' . mysql_error($db), E_USER_ERROR);
$sql="SELECT * FROM $tbl_name WHERE plot_id_x=$x AND plot_id_z=$z AND world='$world';";

$result=mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($result);
if($row['id']==null) {
    echo "<h2>ID:</h2><h4 id=box>$x;$z</h4>";
    echo "<h2>World:</h2><h4 id=box>$world ?</h4>";
    echo "<h2>Owner:</h2><h4 id=box>$user ?</h4>";


    echo "<h2>Date Claimed:</h2><h4 id=box>Unknown</h4>";

    echo "<h2>Helpers:</h2><h4 id=box>Unknown</h4>";
    echo "<h2>Trusted:</h2><h4 id=box>Unknown</h4>";
    echo "<h2>Denied:</h2><h4 id=box>Unknown</h4>";
    if(!$schematic_info['status']) {
        die;
    }
    $schematics = $schematic -> getSchematicFromFile($x . ";" . $z . "," . $world . "," . $user);
    if (count($schematics)==1) {
        echo "<h2>Schematic:</h2><h4 id=box><div id=download><a download='plot.schematic' href='" . $schematic_settings['up_dir'] . "/$x;$z,$world,$user.schematic' id=dlbutton>Download</a></div>";
    }
    return;
}
else {
    $id = intval($row['id']);
}
$uuid = getUUID($row['owner']);
$player = getName($uuid);
$time = $row['timestamp'];

// helpers
$tbl_name=$mysql_settings['prefix'] . 'plot_helpers';
$sql="SELECT * FROM $tbl_name WHERE plot_plot_id=$id;";
$result=mysqli_query($con,$sql);
$helpers = array();
if ($result != false) {
    while($r = mysqli_fetch_assoc($result)) {
        $helpers[] = getName(getUUID($r['user_uuid']));
    }
}

// trusted
$tbl_name=$mysql_settings['prefix'] . 'plot_trusted';
$sql="SELECT * FROM $tbl_name WHERE plot_plot_id=$id;";
$result=mysqli_query($con,$sql);
$trusted = array();
if ($result != false) {
    while($r = mysqli_fetch_assoc($result)) {
        $trusted[] = getName(getUUID($r['user_uuid']));
    }
}

// denied
$tbl_name=$mysql_settings['prefix'] . 'plot_denied  ';
$sql="SELECT * FROM $tbl_name WHERE plot_plot_id=$id;";
$result=mysqli_query($con,$sql);
$denied = array();
if ($result != false) {
    while($r = mysqli_fetch_assoc($result)) {
        $denied[] = getName(getUUID($r['user_uuid']));
    }
}
echo "<h2>ID:</h2><h4 id=box>$x;$z</h4>";
echo "<h2>World:</h2><h4 id=box>$world</h4>";
echo "<h2>Owner:</h2><h4 id=box>$player</h4>";


echo "<h2>Date Claimed:</h2><h4 id=box>$time</h4>";

echo "<h2>Helpers:</h2><h4 id=box>" . implode("<br>", $helpers) ."</h4>";
echo "<h2>Trusted:</h2><h4 id=box>" . implode("<br>", $trusted) ."</h4>";
echo "<h2>Denied:</h2><h4 id=box>" . implode("<br>", $denied) ."</h4>";

if(!$schematic_info['status']) {
    die;
}
$schematics = $schematic -> getSchematicFromFile($x . ";" . $z . "," . $world . "," . $player);
if (count($schematics)==1) {
    echo "<h2>Schematic:</h2><h4 id=box><div id=download><a download='plot.schematic' href='" . $schematic_settings['up_dir'] . "/$x;$z,$world,$player.schematic' id=dlbutton>Download</a></div>";
}
?>
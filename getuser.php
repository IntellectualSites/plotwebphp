<?php

require "lib/configuration.php";
require "lib/schematicHandler.php";
# get MySQL stuff
$mysql_settings = Config::get('mysql');

$host=$mysql_settings['host'];
$username=$mysql_settings['username'];
$password=$mysql_settings['password'];
$db_name=$mysql_settings['database'];
$tbl_name=$mysql_settings['prefix'] . 'plot';

function getUUID($usr) {
    if (strlen($usr)<2) {
        return null;
    }
    $url = 'https://api.mojang.com/profiles/page/1';
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => '{"name":"'.$usr.'","agent":"minecraft"}',
            'timeout' => 5
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if(isset($result) && $result != null && $result != false) {
        $ress = json_decode($result, true);
        $ress = $ress["profiles"][0];
        $res = Array("usr" =>  $ress['name'], "uuid" => $ress['id']);
        return $res['uuid'];
    }
    else {
        return null;
    }
}

$player = $_GET["user"];
$uuid = getUUID($player);
$con = @mysqli_connect("$host", "$username", "$password");
if (!$con) {
    die;
}
mysqli_select_db($con,"$db_name")or die;
$sql="SELECT * FROM $tbl_name WHERE replace(owner, '-', '') LIKE '$uuid';";

$result=mysqli_query($con,$sql);

$num_rows = mysqli_num_rows($result);

$rows = array();
while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
echo json_encode($rows,JSON_PRETTY_PRINT);

?>
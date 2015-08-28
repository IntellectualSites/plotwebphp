<?php
$timeout = 5;
$url = 'https://api.mojang.com/profiles/page/1';
$options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => '{"name":"'.$username.'","agent":"minecraft"}',
        'timeout' => $timeout
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if(isset($result) && $result != null && $result != false) {
    $ress = json_decode($result, true);
    $ress = $ress["profiles"][0];
    $res = Array("username" =>  $ress['name'], "uuid" => $ress['id']);
    return $res['uuid'];
}
else {
    return null;
}
?>
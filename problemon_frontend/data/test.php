<?php 

print $_SERVER['DOCUMENT_ROOT'] . "\n";
$host_url="http://loopback:3000/api/people"; //"http://gamificacion_backend:3000/api/"; 
print $host_url . "\n";
$ch = curl_init($host_url);
print curl_exec($ch) ."\n";


?>

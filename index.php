<?php
echo "<pre>";
require_once 'lib/ApiHandler.php';
$api=new ApiHandler();
print_r($api->UserInq('081234567890'));
?>

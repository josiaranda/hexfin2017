<?php
echo "<pre>";
echo "aaa<br>";
require_once 'lib/ApiHandler.php';
$api=new ApiHandler();
print_r($api->UserInq('082155627063'));
?>

<?php
$_record = $_POST;
sleep(2);
//$_record["csrf"] = $_POST["csrf"];
$result["JSON"] = json_encode($_record);
echo json_encode($result);
?>
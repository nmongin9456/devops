<?php
require 'inc/bootstrap.php';
$task = new Psexec($_POST);
print_r($task->runAction());
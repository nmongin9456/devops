<?php
define('DS', DIRECTORY_SEPARATOR);
define('WWW_ROOT',dirname(dirname(__FILE__)) . DS);

$dir = basename(WWW_ROOT);
$url = explode($dir, $_SERVER['REQUEST_URI']);
define('WEBROOT', $url[0] . $dir . '/');
define('IMAGES', WWW_ROOT . '/img');


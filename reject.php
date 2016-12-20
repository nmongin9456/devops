<?php 

require 'inc/bootstrap.php';

Session::getInstance()->setFlash('danger', "Vous n'êtes pas autorisé à accèder à ce site...");

include 'partials/header.php';
include 'partials/footer.php'; 

?>

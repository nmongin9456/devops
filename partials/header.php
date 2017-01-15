<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta content="IE=edge" http-equiv="X-UA-Compatible">
		<title> Mes Machines Virtuelles </title>
		<link rel="stylesheet" href="./css/bootstrap.min.css">
		<link rel="stylesheet" href="./css/app.css">
		
		<script src="./js/jquery-3.1.1.js"></script>
		<script src="./js/bootstrap.min.js"></script>
		<script src="./js/main.js"></script>
	</head>
	<body>
		<div class="container"><br>
			<?php if(Session::getInstance()->hasFlashes()): ?>
				<?php foreach(Session::getInstance()->getFlashes() as $type => $message): ?>
					<div class="alert alert-<?=$type; ?>" style="padding: 20px; width: 100%;">
						<?=$message; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

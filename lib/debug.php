<div class="row">
	<div class="col-sm-12">
		<h2>SESSION</h2>
		<pre>
<?php var_dump($_SESSION); ?>
		</pre>
	</div>
	<div class="col-sm-12">
		<h2>$_POST</h2>
		<pre>
<?php var_dump($_POST); ?>
		</pre>
	</div>
	<div class="col-sm-12">
		<h2>SERVER</h2>
		<pre>
<?php var_dump($_SERVER); ?>
		</pre>
	</div>
	<div class="col-sm-12">
		<h2>CONSTANTE</h2>
		<pre>
<?php var_dump(get_defined_constants()); ?>
		</pre>
	</div>
</div>
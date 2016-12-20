<?php
require "class/collection.php";

$post = new Collection([
		['name' => 'Jean', 'note' => 20],
		['name' => 'Marc', 'note' => 13],
		['name' => 'Emilie', 'note' => 15]
	]);


var_dump($post->extract('note')->join(', '));
var_dump($post->extract('note')->max());
var_dump($post->max('note'));

echo $post->count();
echo "<br>";
var_dump($post->first());
var_dump($post->last());



<?php

use \view\view;
$template = new view(__DIR__.'/templates', __DIR__.'/../storage/cache');
$posts = [
	['id' => 0, 'name' => '@non-san', 'post' => 'ur a fa&get'],
	['id' => 1, 'name' => '@non-kun', 'post' => 'try me'],
	['id'=> 2, 'name' => '<b>SUpA H@ca', 'post' => 'XSS LOL<script src="ur a fagit">']
	];
$data = ['posts' => $posts];
$template->draw('thread', $data);

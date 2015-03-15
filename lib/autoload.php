<?php
$autoload = [
	'view\view' => 'view/view',
	'view\compiler' => 'view/compiler',
	'view\waycompiler' => 'view/waycompiler',
	'model\AbstractModel' => 'model/AbstractModel',
	'model\ModelException' => 'model/ModelException',
	'model\ModelInterface' => 'model/ModelInterface',
	'model\Model' => 'model/Model'
];
spl_autoload_register(function ($class) use ($autoload){
	$file =  __DIR__.'/'.$autoload[$class].'.php';
	if(file_exists($file))
		include($file);
});

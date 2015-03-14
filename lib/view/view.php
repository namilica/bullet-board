<?php namespace view;

class view{
	protected $filePath;
	protected $cachePath;
	public function __construct($filePath, $cachePath){
		$this->filePath = $filePath;
		$this->cachePath = $cachePath;
	}
	protected $data;
	protected $file;
	public function draw($file, $data = []){
		$compiler = new waycompiler($this->filePath, $this->cachePath);
		if($compiler->isExpired($file))
			$compiler->compile($file);
		$this->data = $data;
		$this->file = $compiler->compilePath($file);
		$this->drawTemplate();
	}
	private function drawTemplate(){
		if(!empty($this->data))
			foreach($this->data as $key => $value)
				$$key = $value;
		include($this->file);
	}
}

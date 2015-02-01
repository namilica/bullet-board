<?php namespace view;

abstract class compiler{
	protected $templateExtension = "html";
	protected $filePath;
	protected $cachePath;
	public function __construct($filePath, $cachePath){
		$this->filePath = $filePath;
		$this->cachePath = $cachePath;
	}
	public function compilePath($file){
		return $this->cachePath.'/'.md5($file);
	}
	public function templatePath($file){
		return $this->filePath.'/'.$file.'.'.$this->templateExtension;
	}
	public function isExpired($file){
		$templatePath = $this->templatePath($file);
		$compiledPath = $this->compilePath($file);
		if(!file_exists($compiledPath))
			return true;
		else
			return (filemtime($templatePath)>=filemtime($compiledPath));
	}
}

<?php namespace view;

class waycompiler extends compiler{
	public function compile($file){
		$templatePath = $this->templatePath($file);
		$compiledPath = $this->compilePath($file);
		file_put_contents($compiledPath, $this->compileString(file_get_contents($templatePath)));
	}
	public function compileString($template){
		$result = '';
		foreach (token_get_all($template) as $token){
			$result .= is_array($token) ? $this->parsePHPToken($token) : $token;
		}
		return $result;
	}
	protected function parsePHPToken($token){
		list($id, $content) = $token;
		if ($id == T_INLINE_HTML){
			foreach ($this->compilers as $type)
			{
				$content = $this->{"compile{$type}"}($content);
			}
		}
		return $content;
	}
	protected $delimiter = ['\{\{', '\}\}'];
	protected $escapedDelimiter = ['\{\{\{', '\}\}\}'];
	protected $controlDelimiter = ['\{%', '%\}'];
	protected $compilers = [
		'Comment',
		'Echo',
		'Control'
	];
	protected function compileComment($template){
		$pattern = sprintf('/%s--(.*?)--%s/', $this->delimiter[0], $this->delimiter[1]);
		return preg_replace($pattern, '<?php /*$1*/ ?>', $template);
	}
	protected function compileEcho($template){
		if(strlen($this->escapedDelimiter[0]) < strlen($this->delimiter[0]))
			return $this->compileEchoEscaped($this->compileEchoUnescaped($template));
		else
			return $this->compileEchoUnescaped($this->compileEchoEscaped($template));
	}
	protected function compileEchoUnescaped($template){
		$pattern = sprintf('/%s\s*(.+)\s*%s/', $this->delimiter[0], $this->delimiter[1]);
		return preg_replace($pattern, '<?php echo $1; ?>', $template);
	}
	protected function compileEchoEscaped($template){
		$pattern = sprintf('/%s\s*(.+)\s*%s/', $this->escapedDelimiter[0], $this->escapedDelimiter[1]);
		return preg_replace($pattern, '<?php echo htmlspecialchars($1); ?>', $template);
	}
	protected function compileControl($template){
		$pattern = sprintf('/(\v?)\s*%s\s*(.+)\s*%s/', $this->controlDelimiter[0], $this->controlDelimiter[1]);
		return preg_replace($pattern, '$1<?php $2 ?>', $template);
	}
}

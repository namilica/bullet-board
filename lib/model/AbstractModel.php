<?php namespace model;

abstract class AbstractModel{
	protected $fields = [];
	protected $data = [];
	protected $filters = [];
	protected $validators = [];
	protected $validator_class = 'Zend_Filter_Input';
	protected $validator = [];
	protected $messages;
	public function __construct(array $data = []){
		if (!empty($data)){
			foreach ($data as $name => $value){
				$this->$name = $value;
			}
		}
		return $this;
	}
	public function __set($name, $value){
		if (in_array($name, $this->fields)){
			$this->data[$name] = $value;
		} else {
			throw new ModelException("$name not found in class");
		}
	}
	public function __get($name){
		if (in_array($name, $this->fields)){
			return $this->data[$name];
		} else {
			throw new ModelException("$name not found in class");
		}
	}
	public function setValidatorClass($class){
		$this->validator_class = $class;
	}
	public function isValid(){
		$validator = $this->getValidator();
		$validator->setData($this->data);
		$isValid = $validator->isValid();
		if (!$isValid){
			$this->messages = $validator->getErrors();
		}
		return $isValid;
	}
	public function getValidator(){
		if (!$this->validator){
			$this->validator = new $this->validator_class($this->filters, $this->validators);
		}
		return $this->validator;
	}
	public function getMessages(){
		return $this->messages;
	}
}

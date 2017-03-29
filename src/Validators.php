<?php
namespace FormGuide\PHPFormValidator;

class Validators
{
	private $field_name;
	private $errors;

	public function __construct($field_name)
	{
		$this->field_name = $field_name;
		$this->errors = array();
	}

	public static function create($field_name)
	{
		return new Validators($field_name);
	}

	public function required($post, $details=array())
	{
		if(empty($post[$this->field_name]))
		{
			$this->addError("{$this->field_name} is Required.");
			return false;
		}

		$value = trim($post[$this->field_name]);

		if(empty($value))
		{
			$this->addError("{$this->field_name} is Required.");
			return false;
		}
		return true;
	}

	public function email($post)
	{
		if(empty($post[$this->field_name]))
		{
			return true;
		}

		if(!filter_var($post[$this->field_name] , FILTER_VALIDATE_EMAIL))
		{
			$this->addError("{$this->field_name} must be a valid email address");
			return false;
		}
		return true;
	}

	public function maxlen($post, $details)
	{
		if(empty($post[$this->field_name]))
		{
			return true;
		}
		
		$maxlen = intval($details['value']);

		if(strlen($post[$this->field_name]) > $maxlen)
		{
			$this->addError("{$this->field_name} exceeded maximum length allowed ($maxlen).");
			return false;
		}
		return true;
	}

	public function addError($error)
	{
		$this->errors[] = $error;
	}

	public function hasErrors()
	{
		return empty($this->errors)?false:true;	
	}

	public function getErrorCount()
	{
		return count($this->errors);
	}
	public function getError()
	{
		if(empty($this->errors))
		{
			return null;
		}
		else
		{
			return $this->errors[0];
		}
	}
}
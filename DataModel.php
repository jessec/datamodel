<?php
define('MODEL_DIR', dirname(__FILE__).'/../models/');
set_include_path(get_include_path().PATH_SEPARATOR.MODEL_DIR);
spl_autoload_extensions('.model.php');
spl_autoload_register();

class Controler
{
	var $model;
	var $returnArray;

	function __construct ()
	{

	}

	function setReturnArray ($key, $value)
	{
		$this->returnArray[$key] = $value;
	}

	function getReturnArray ()
	{
		return $this->returnArray;
	}

	function setDataModel ($datamodel)
	{
		$this->model = $datamodel;
	}

	function getDataModel ()
	{
		return $this->model;
	}

	function processDataModel ()
	{

		foreach ($this->model as $arr)
		{
			$inspector = new PreProcessor;
			$values = serialize($arr); 
			$returnArray = call_user_func_array(array($inspector, "inspect"), $values);
			if ($returnArray)
			{
				$this->setReturnArray($arr[0], $returnArray);
			}
		}

	}

}

class PreProcessor
{
	// Inspect data before processing
	function inspect ($arg)
	{
		$argArray = unserialize($arg);
		// this will give the abbility to inspect and act on the data passed.
		// to implement roles, users, validation, etc..
		$returnArray = call_user_func_array(array('ProcessClass', 'process'), $arg);
		return $returnArray;
	}

}

class ProcessClass
{

	static function process ($arg)
	{
		$argArray = unserialize($arg);
		// this will take the 1e as the class 2e as a function of that class and
		//then pass everything as an argument to that function.
		$returnArray = call_user_func_array(array($argArray[2], "$argArray[3]"), $arg);

		$postArray = call_user_func_array(array('PostProcessor', 'process'), $returnArray);
		return $postArray;
	}

}


class PostProcessor
{
	static function process ($arg)
	{
		// post process array
		return $returnArray;
	}

}


class dataObject
{
	var $object;
	function setValue ($value)
	{
		$this->object = $value;
	}
	function getValue ()
	{
		return $this->object;
	}
}

?>

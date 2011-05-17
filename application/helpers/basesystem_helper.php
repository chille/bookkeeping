<?php

class BaseSystem
{
	private static $instance;
	private $css = array();
	private $js = array();

	// A private constructor; prevents direct creation of object
	private function __construct()
	{
	}

	// The singleton method
	public static function singleton()
	{
		if (!isset(self::$instance))
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}

	// Prevent users to clone the instance
	public function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public function AddCss($file)
	{
		$this->css[] = $file;
	}

	public function AddJs($file)
	{
		$this->js[] = $file;
	}

	public function GetCss()
	{
		return $this->css;
	}

	public function GetJs()
	{
		return $this->js;
	}
}

<?php

class Message
{
	public function Set($str)
	{
		$_SESSION['messages'][] = $str;
	}

	public function Error($str)
	{
		$_SESSION['errors'][] = $str;
	}

	public function GetMessages()
	{
		$tmp = $_SESSION['messages'];
		unset($_SESSION['messages']);
		return '<p>'.implode('</p><p>', $tmp).'</p>';
	}

	public function GetErrors()
	{
		$tmp = $_SESSION['errors'];
		unset($_SESSION['errors']);
		return '<p>'.implode('</p><p>', $tmp).'</p>';
	}

	public function IsMessages()
	{
		return !empty($_SESSION['messages']);
	}

	public function IsErrors()
	{
		return !empty($_SESSION['errors']);
	}
}

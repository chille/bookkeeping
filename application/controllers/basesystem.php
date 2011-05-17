<?php

class BaseSystem extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function Header()
	{
		$this->CI =& get_instance();

		include('/var/www/CodeIgniter_2.0.2/application/views/header.php');

		echo $this->CI->output->get_output();

		include('/var/www/CodeIgniter_2.0.2/application/views/footer.php');
	}

	function Footer()
	{
		$this->load->view('header');
	}
}

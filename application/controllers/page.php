<?php

class Page extends CI_Controller
{
	function MainFrame()
	{
		$this->CI =& get_instance();
		include(APPPATH . 'views/header.php');
		echo $this->CI->output->get_output();
		include(APPPATH . 'views/footer.php');
	}
}

<?php

class Page extends CI_Controller
{
	function MainFrame()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('message');
		include(APPPATH . 'views/header.php');
		echo $this->CI->output->get_output();
		include(APPPATH . 'views/footer.php');
	}
}

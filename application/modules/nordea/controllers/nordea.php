<?php

/**
 * Nordea Controller
 *
 * Todo:
 *   Stöd för flera olika konton
 *   Spara synkroniseringsdatum
 *   Filtrera kontoutdrag på månad/år
 */
class Nordea extends CI_Controller
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		session_start();
		parent::__construct();

		$this->load->helper('url');
		$this->load->helper('basesystem');
		$this->load->helper('message');
		$this->load->helper('format');
		$this->load->model('NordeaModel');

		include('nordeahandler.php'); // TODO: Flytta?
	}

	/**
	 * Handles challange/response authentication used to login and receives account history
	 */
	function Import()
	{
		$nordea = new NordeaHandler();

		if(!isset($_POST['response']))
		{
			$data = array('challange' => $nordea->GetChallange(), 'orgnr' => '');
			$this->load->view('nordea/import', $data);
		}
		else
		{
			try
			{
				// Försök logga in
				$nordea->Login($_POST['orgnr'], $_POST['challange'], $_POST['response']);

				// Inloggning lyckades - Hämta kontoutdrag
				$tmp = $nordea->GetData('110801', date('ymd')); // TODO: Hårdkodat värde
				foreach($tmp as $x)
				{
					$this->NordeaModel->Insert($x);
				}

				// Logga ut
				$nordea->Logout();
			}
			catch(Exception $e)
			{
				// Inloggning misslyckades
				$data['challange'] = $nordea->GetChallange();
				$data['orgnr'] = $_POST['orgnr'];
				Message::Error($e->getMessage());
				$this->load->view('nordea/import', $data);
				return;
			}

			// Notifiera användaren och gå till kontoutdraget
			Message::Set('Uppdateringen lyckades.');
			redirect('nordea');
		}
	}

	/**
	 * Förstasidan, visar kontoutdraget
	 */
	function index()
	{
		$data['balance'] = $this->NordeaModel->GetBalance();

		$data['posts'] = array();
		$tmp = $this->NordeaModel->GetAccountHistory();
		foreach($tmp as $post)
		{
			$post->sum = Format::Currency($post->sum);
			$data['posts'][] = $post;
		}

		$this->load->view('nordea/index', $data);
	}
}

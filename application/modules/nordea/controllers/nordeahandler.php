<?php

/**
 * TODO:
 *   Skriv om felmeddelanden
 *   Går LOGINERR1 + LOGINERR2 att kombinera till en regexp?
 */

define('NORDEA_URL_LOGIN',        "https://eplusgiro.plusgirot.se/elloggco.html?cert_typ=&FUNC=loginRequest");
define('NORDEA_URL_LOGIN2',       "https://eplusgiro.plusgirot.se/elia/loggain?FUNC=loginSubmitCompany&ALT1=contract_frame.html");
define('NORDEA_URL_DATA',         "https://eplusgiro.plusgirot.se/elia/account/booked/bgya027/btranu27");
define('NORDEA_URL_LOGOUT',       "https://eplusgiro.plusgirot.se/elia/loggaut?KLIENT_ID=ePF");
define('NORDEA_REGEXP',           '/var SLUMPTAL      = new Array\("(.*)"\)/');
define('NORDEA_REGEXP_LOGINERR1', '/var FEL_RUBRIK = new Array\("(.*)"\)/');
define('NORDEA_REGEXP_LOGINERR2', '/var FEL_TEXT   = new Array\("(.*)"\)/');
define('NORDEA_REGEXP_DATA',      "/
  BET_TYP         \[RadIndx2\]='(?<transaction_type>.*)';
(.*)BDAT            \[RadIndx2\]='(?<date>.*)';
(.*)FRANTILL_KTO    \[RadIndx2\]='(?<account>.*)';
(.*)FRANTILL_NAMN   \[RadIndx2\]='(?<description>.*)';
(.*)BEL             \[RadIndx2\]='(?<sum>.*)';
(.*)MOTT_MED        \[RadIndx2\]='(?<ocr>.*)';
(.*)UNIK_NKL        \[RadIndx2\]='(?<id>.*)';
(.*)OTYP            \[RadIndx2\]='(?<type>.*)';/msU");


/**
 * A class used to login to Nordea Internetbanken Företag (Bussiness account in Swedish bank Nordea)
 *
 * Requirements: cURL (php5-curl)
 */
class NordeaHandler
{
	var $ch = NULL;
	var $slumptal = NULL;

	/**
	 * Create a new cURL resource
	 */
	function __construct()
	{
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_HEADER, 0);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, '/tmp/nordea_cookie.txt');
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, '/tmp/nordea_cookie.txt');
	}

	/**
	 * Close cURL resource and free up system resources
	 */
	function __descruct()
	{
		curl_close($this->ch);
	}

	/**
	 * Get the login page and parse out and return the random code used in the challange authentiaction
	 */
	function GetChallange()
	{
		// Set URL
		curl_setopt($this->ch, CURLOPT_URL, NORDEA_URL_LOGIN);

		// grab URL and pass it to the browser
		$result = curl_exec($this->ch);
		if($result === FALSE)
		{
			throw new Exception('Hämtning av login-sida misslyckades');
		}

		// Parse out the random code
		if(!preg_match(NORDEA_REGEXP, $result, $m))
		{
			throw new Exception('Fel vid tolkning av slumptal');
		}

		$this->slumptal = $m[1];

		return $this->slumptal;
	}

	/**
	 * Use the challange authentication code to perform a login
	 *
	 * TODO: Filtrera input-parametrar
	 */
	function Login($orgnr, $challange, $response)
	{
		$this->slumptal = $challange;

		if($this->slumptal == NULL)
		{
			throw new Exception('Slumptal saknas. GetChallange() måste anropas innan Login()');
		}

		// Form data
		$fields = array(
			'PERS_NR_ORG_NR' => $orgnr,
			'SLUMPTAL'       => $this->slumptal,
			'CERT'           => $response,
			'LOGIN_VERSION'  => '8.3',
			'OS_TYPE'        => 'Linux',
			'BROWSER_TYPE'   => 'Opera 9.80 (X11; Linux i686; U; en)',
			'FUNC'           => 'loginSubmitCompany',
			'FROMLOGON'      => 'Submit Logon Request',
			'CERT_TYP'       => 'EKOD',
			'STEP'           => '1',
			'RETRY'          => '',
			'LADDA'          => '',
		);

		// Create a string with data to post with cURL
		foreach($fields as $key => $value)
		{
			$field_data[] = $key.'='.urlencode($value);
		}
		$fields_string = implode('&', $field_data);

		// Set URL and form data
		curl_setopt($this->ch, CURLOPT_URL, NORDEA_URL_LOGIN2);
		curl_setopt($this->ch, CURLOPT_POST, count($fields));
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields_string);

		// Send request to server
		$result = curl_exec($this->ch);
		if($result === FALSE)
		{
			throw new Exception('Fel vid anslutning till loginsida');
		}

		// Check to see if there was any errors during login
		if(preg_match(NORDEA_REGEXP_LOGINERR1, $result, $m))
		{
			$title = $m[1];
			preg_match(NORDEA_REGEXP_LOGINERR2, $result, $m);
			$text = $m[1];

			// Change encoding from ISO-8859-1 to UTF-8
			$title = utf8_encode($title);
			$text  = utf8_encode($text);
			throw new Exception($title." - ".$text);
		}

		return true;
	}

	/**
	 * Logout
	 */
	function Logout()
	{
		// Set URL
		curl_setopt($this->ch, CURLOPT_URL, NORDEA_URL_LOGOUT);
		$result = curl_exec($this->ch);
	}

	/**
	 *
	 */
	function GetData($startdate, $enddate)
	{
		// Set URL
		curl_setopt($this->ch, CURLOPT_URL, NORDEA_URL_DATA);

		$tmp = "FUNC=search&STARTDAT_6=$startdate&SLUTDAT_6=$enddate&SOK_DEBKRED=A";
		curl_setopt($this->ch, CURLOPT_POST, 4);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $tmp);

		// Get data from server
		$result = curl_exec($this->ch);
		if($result === FALSE)
		{
			throw new Exception('Fel vid anslutning till datasida');

		}

		// Convert encoding from ISO-8859-1 to UTF-8
		$result = utf8_encode($result);

		// TODO: Ta bort fulhack
		$result = str_replace("\r", '', $result);

		// Parse data from server
		if(!preg_match_all(NORDEA_REGEXP_DATA, $result, $m))
		{
			// Check to see if there was any errors during login
			if(preg_match(NORDEA_REGEXP_LOGINERR1, $result, $m))
			{
				$title = $m[1];
				preg_match(NORDEA_REGEXP_LOGINERR2, $result, $m);
				$text = $m[1];

				// Change encoding from ISO-8859-1 to UTF-8
				$title = utf8_encode($title);
				$text  = utf8_encode($text);
				throw new Exception('Fel vid tolkning av data: '.$title." - ".$text);
			}
		}

		// Loop thru data and put into an array
		$data = array();
		for($i = 0; $i < count($m[1]); $i++)
		{
			$data[] = array(
				'id' => $m['id'][$i],
				'transaction_type' => $m['transaction_type'][$i],
				'date' => $m['date'][$i],
				'account' => $m['account'][$i],
				'description' => $m['description'][$i],
				'sum' => $this->_ParseCurrency($m['sum'][$i]),
				'ocr' => $m['ocr'][$i],
				'type' => $m['type'][$i]
			);
		}

		return $data;
	}

	/**
	 * Parse the readable format from Nordea into a float
	 */
	function _ParseCurrency($val)
	{
		$val = str_replace('.', '', $val);
		$val = str_replace(',', '.', $val);
		return (float)$val;
	}
}

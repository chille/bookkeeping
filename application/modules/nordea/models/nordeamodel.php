<?php

/**
 * Model for synchronization with Nordea bank account.
 */
class NordeaModel extends CI_Model
{
	/**
	 * Constructor - load a few helpers and libraries
	 */
	function __constructl()
	{
		parent::__construct();
	}

	/**
	 * Return all rows in account history
	 */
	function GetAccountHistory($id = NULL)
	{
		$this->db->join('nordea_to_verification', 'nordea_to_verification.nordea_id = nordea.nordea_id', 'left');
		return $this->db->order_by('date', 'desc')->get('nordea')->result();
	}

	/**
	 * Inserts a post into database (With duplicate prevention based on Nordea's unique identifier)
	 */
	function Insert($data)
	{
		$columns = array();
		foreach($data as $key => $value)
		{
			$columns[] = $key . '=' . $this->db->escape($value);
		}
		$this->db->query('REPLACE INTO nordea SET ' . implode(',', $columns));
	}

	/**
	 * Returns the sum of all rows in database
	 */
	function GetBalance()
	{
		return $this->db->select_sum('sum')->get('nordea')->row()->sum;
	}
}

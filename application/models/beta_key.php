<?php

/**
 * Beta Keys
 */
class Beta_key extends CI_Model {
	
	/**
	 * Validate Key
	 */
	public function validate($key = null) {
		
		// Check for invalid key
		if (!$key || strlen($key) < 5)
			return false;
		
		// Query provided key for match
		$this->db->where('beta_key', (string) $key);
		$this->db->where('status', 1);
		$query = $this->db->get('beta_keys');
		
		// Return true if valid
		if (count($query->result()) > 0)
			return true;
		else
			return false;
	}
	
	/**
	 * Mark Key As Used
	 */
	public function markKeyAsUsed($key) {
		$update_data = array(
			'status' => 0
		);
		$this->db->where('beta_key', (string) $key);
		$this->db->update('beta_keys', $update_data);
		return;
	}
	
}
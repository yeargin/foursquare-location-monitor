<?php

/**
 * Beta Keys
 */
class Beta_key extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('email');
		$this->load->helper('string');
	}
	
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
	 *
	 * @param string $key 
	 */
	public function markKeyAsUsed($key) {
		$update_data = array(
			'status' => 0
		);
		$this->db->where('beta_key', (string) $key);
		$this->db->update('beta_keys', $update_data);
		return;
	}
	
	/* *** Admin Methods *** */
	
		/**
	 * Admin Get All Beta Keys
	 *
	 * @param string $count 
	 */
	public function adminGetAllBetaKeys($count = 50) {
		$this->db->order_by('insert_ts', 'DESC');
		$this->db->limit($count);
		$query = $this->db->get('beta_keys');
		
		return $query->result();
		
	}

	/**
	 * Admin Create Beta Key From Post
	 */
	public function adminCreateBetaKeyFromPost() {
		
		$insert_beta_key_data = array(
			'name' => $this->input->post('name'),
			'beta_key' => random_string('alnum', 16),
			'email' => $this->input->post('email'),
			'status' => 1
		);
		
		$insert = $this->db->insert('beta_keys', $insert_beta_key_data);

		// IF created, return row
		if ($insert):
			$this->db->where('id', $this->db->insert_id());
			$query = $this->db->get('beta_keys');
			return $query->row();
		endif;

		return false;
	}
	
	/**
	 * Admin Revoke Beta Key
	 */
	public function adminRevokeBetaKey($beta_key) {
		
		$this->db->where('beta_key', $beta_key);
		$delete = $this->db->delete('beta_keys', $insert_beta_key_data);

		// If deleted, return true
		if ($delete)
			return true;

		return false;
	
	}
	
}
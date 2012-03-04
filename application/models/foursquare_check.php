<?php
/**
* Foursquare Check
*/
class Foursquare_check extends CI_Model {
	
	public $id;
	public $user_id;
	public $check_title;
	public $venue_id;
	public $active;
	public $insert_ts;
	public $update_ts;
	public $last_check_ts;

	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Get Checks by User ID
	 *
	 * @param string $user_id 
	 */
	public function getChecksByUserId($user_id) {
		$this->db->select(array('id', 'check_title', 'venue_id', 'active', 'insert_ts', 'update_ts', 'last_daily_check_ts', 'last_live_check_ts'));
		$this->db->where('user_id', $user_id);
		$this->db->order_by('check_title', 'ASC');
		$query = $this->db->get('foursquare_checks');
		
		return $query->result();
	}
	
	/**
	 * Get Check by Check ID
	 *
	 * @param string $user_id 
	 */
	public function getCheckById($check_id) {

		$user = unserialize($this->session->userdata('user'));

		$this->db->select(array('id', 'check_title', 'venue_id', 'active', 'insert_ts', 'update_ts', 'last_daily_check_ts', 'last_live_check_ts'));
		$this->db->where('id', $check_id);
		$this->db->where('user_id', $user->id);
		$this->db->limit(1);
		$query = $this->db->get('foursquare_checks');
		
		return $query->row();
	}
	
	/**
	 * Get Check By Venue ID
	 *
	 * @param string $venue_id 
	 * @param string $user_id 
	 */
	public function getCheckByVenueId($venue_id) {
		
		$user = unserialize($this->session->userdata('user'));
		
		$this->db->select(array('id', 'check_title', 'venue_id', 'active', 'insert_ts', 'update_ts', 'last_daily_check_ts', 'last_live_check_ts'));
		$this->db->where('venue_id', $venue_id);
		$this->db->where('user_id', $user->id);
		$this->db->limit(1);
		$query = $this->db->get('foursquare_checks');
		
		return $query->row();
	}
	
	/**
	 * Add New Check
	 *
	 * @param array $record 
	 */
	public function addNewCheck($record = array()) {
		$this->db->insert('foursquare_checks', $record);
	}
	
	/**
	 * Deactivate a Check
	 *
	 * @param int $check_id 
	 */
	public function deactivate($check_id) {
		$this->db->where('id', $check_id);
		$this->db->update('foursquare_checks', array('active' => '0'));
	}
	
	/**
	 * Activate a Check
	 *
	 * @param int $check_id 
	 */
	public function activate($check_id) {
		$this->db->where('id', $check_id);
		$this->db->update('foursquare_checks', array('active' => '1'));
	}

}
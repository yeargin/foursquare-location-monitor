<?php
/**
* Foursquare Check Log Live
*/
class Foursquare_check_log_live extends CI_Model {
	
	public $id;
	public $check_id;
	public $herenow_total;
	public $herenow_friends;
	public $herenow_other;
	public $insert_ts;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get Batch
	 *
	 * @param int $count 
	 * @param int $interval
	 */
	public function getBatch($count = 10, $interval = 300) {
		
		// Grab a quantity off the stack, stalest records first
		$this->db->select( array('id', 'user_id', 'check_title', 'venue_id') );
		$this->db->where('last_live_check_ts <', date('c', time() - $interval));
		$this->db->where('active', '1');
		$this->db->order_by( 'last_live_check_ts', 'ASC' );
		$query = $this->db->get('foursquare_checks', $count);
		// Format resulting data
		if ($query->num_rows):
			$result = $query->result();
			return $result;
		else:
			return array();
		endif;

	}

	/**
	 * Get Check Data
	 *
	 * @param string $check_id 
	 * @param array $range 
	 */
	public function getCheckData($check_id = 0, $range = array()) {
		
		$this->db->where('check_id', $check_id);
		
		// Handle date range if set
		if (isset($range['start_date']) && isset($range['end_date'])):
			$this->db->where('insert_ts >=', $range['start_date']);
			$this->db->where('insert_ts <=', $range['end_date']);
		endif;
		
		$this->db->order_by('insert_ts', 'ASC');
		
		$query = $this->db->get('foursquare_check_log_live');
		return $query->result();
		
	}

	/**
	 * Add Log Entry
	 *
	 * @param array $data 
	 */
	public function addLogEntry( $data = array() ) {
			
			// Used for record and for updating insert_ts
			$now = date('c');
			
			$row['check_id'] = (int) $data['check_id'];
			$row['herenow_total'] = (int) $data['count'];
			
			// Find relevant data in group data
			foreach ($data['groups'] as $group):
				// Friends
				if ($group->type == 'friends')
					$row['herenow_friends'] = $group->count;
				// Others
				if ($group->type == 'others')
					$row['herenow_other'] = $group->count;
			endforeach;

			$row['insert_ts'] = $now;

			// Add log entry
			$this->db->insert('foursquare_check_log_live', $row);

			// Update check timestamp
			$this->db->where('id', $data['check_id']);
			$this->db->update('foursquare_checks', array('last_live_check_ts' => $now));

	}

}
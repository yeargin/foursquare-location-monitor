<?php
/**
* Foursquare Check Log
*/
class Foursquare_check_log extends CI_Model {
	
	public $id;
	public $check_id;
	public $log_date;
	public $total_checkins;
	public $unique_visitors;
	public $tips_left;
	public $photo_count;
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
	public function getBatch($count = 10, $interval = 86400) {
		
		// Grab a quantity off the stack, stalest records first
		$this->db->select( array('id', 'user_id', 'check_title', 'venue_id') );
		$this->db->where('last_daily_check_ts <', date('c', time() - $interval));
		$this->db->where('active', '1');
		$this->db->order_by('last_daily_check_ts', 'ASC');
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
		
		$this->db->order_by('log_date', 'ASC');
	
		$query = $this->db->get('foursquare_check_log');

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
			
			$row['log_date'] = $now;
			$row['check_id'] = (int) $data['check_id'];
			$row['total_checkins'] = (int) $data['checkinsCount'];
			$row['unique_visitors'] = (int) $data['usersCount'];
			$row['tips_left'] = (int) $data['tipCount'];
			$row['photo_count'] = (int) $data['photoCount'];
			$row['insert_ts'] = $now;

			// Add log entry
			$stat = $this->db->insert('foursquare_check_log', $row);

			// Update check timestamp
			$this->db->where('id', $data['check_id']);
			$this->db->update('foursquare_checks', array('last_daily_check_ts' => $now));

	}
	
	/* *** Calculation methods *** */
	
	public function getAllCheckDataDelta($user_id, $date_range = array()) {		
		$this->db->where('foursquare_check_log.insert_ts >=', date('Y-m-d', strtotime($date_range['start_date'])));
		$this->db->where('foursquare_check_log.insert_ts <=', date('Y-m-d', strtotime($date_range['end_date'])));
		$this->db->where('user_id', $user_id);
		$this->db->where('active', '1');
		$this->db->join('foursquare_checks', 'check_id = foursquare_checks.id');
		$this->db->order_by('check_id', 'ASC');
		$this->db->order_by('log_date', 'DESC');
		$this->db->group_by('check_id, log_date');
		$result = $this->db->get('foursquare_check_log');
		$array = $result->result_array();
		
		// Build data array
		$return = array();
		foreach ($array as $k => $v):
		
			// Baseline numbers
			$v['total_checkins_delta'] = 0;
			$v['unique_visitors_delta'] = 0;
			$v['tips_left_delta'] = 0;
			$v['photo_count_delta'] = 0;
			
			// Process math if pre-existing row
			if (isset($array[$k-1]['total_checkins']) && $array[$k-1]['check_id'] == $v['check_id'])
				$return[$v['check_id']]['total_checkins'][$array[$k-1]['log_date']] = $array[$k-1]['total_checkins'] - $v['total_checkins'];

			if (isset($array[$k-1]['unique_visitors']) && $array[$k-1]['check_id'] == $v['check_id'])
				$return[$v['check_id']]['unique_visitors'][$array[$k-1]['log_date']] = $array[$k-1]['unique_visitors'] - $v['unique_visitors'];
			
			if (isset($array[$k-1]['tips_left']) && $array[$k-1]['check_id'] == $v['check_id'])
				$return[$v['check_id']]['tips_left'][$array[$k-1]['log_date']] = $array[$k-1]['tips_left'] - $v['tips_left'];
				
			if (isset($array[$k-1]['photo_count']) && $array[$k-1]['check_id'] == $v['check_id'])
				$return[$v['check_id']]['photo_count'][$array[$k-1]['log_date']] = $array[$k-1]['photo_count'] - $v['photo_count'];

			$return[$v['check_id']]['check_id'] = $v['check_id'];
			$return[$v['check_id']]['check_title'] = $v['check_title'];
			$return[$v['check_id']]['venue_id'] = $v['venue_id'];
			
		endforeach;
		
		// Sort array by check title (date ascending)
		usort($return, array($this, 'sortByCheckTitle'));
		
		return $return;
		
	}
	
	
	/**
	 * Delta Array
	 *
	 * @param array $array 
	 * @param string $key
	 * @return array $results
	 */
	public function deltaArray( $array = array(), $key = null ) {
		
		// Set up an empty variables
		$result = array();
		
		// Loop through 
		foreach ($array as $k => $row):
		
			if (!is_null($key)):
				$k = isset($row->$key) ? $row->$key : $row[$key];
			endif;
			
			// If an array, introspect each
			if (is_array($row) || is_object($row)):
				if (is_array($row)):
					// Array
					foreach($row as $k2 => $item):

						// Skip non-numeric properties
						if (!is_numeric($item))
							continue;
					
						// Determine whether to do calcualte delta
						if (isset($prev_value[$k2])):
							$delta = $item - $prev_value[$k2];
							if ($delta < 0): $delta = 0; endif;
							$result[$k][$k2] = $delta;
						else:
							$prev_value[$k2] = $item;
							continue;
						endif;
					endforeach;
				else:
					// Object
					foreach($row as $k2 => $value):

						// Skip non-numeric properties
						if (!is_numeric($value))
							continue;

						// Determine whether to do calcualte delta
						if (isset($prev_value[$k2])):
							$delta = $value - $prev_value[$k2];
							if ($delta < 0): $delta = 0; endif;
							$result[$k]->$k2 = $delta;
							$prev_value[$k2] = $value;
						else:
							$prev_value[$k2] = $value;
							continue;
						endif;
					endforeach;
				endif;

			// Not an array or object, simply calculate on value
			else:
			
				// Skip non-numeric properties
				if (!is_numeric($row))
					continue;
			
				// Determine whether to do calcualte delta
				if (isset($prev_value)):
					$delta = $row - $prev_value;
					if ($delta < 0): $delta = 0; endif;
					$result[$k] = $delta;
					$prev_value = $row;
				else:
					$prev_value = $row;
					continue; // no previous value to calc
				endif;
				
			endif;
		
		endforeach;
		
		return $result;

	}
	
	/* *** Private Methods *** */
	
	/**
	 * Sort By Check Title
	 *
	 * @param array $a 
	 * @param array $b 
	 */
	private function sortByCheckTitle($a, $b) {
		
		$al = strtolower($a['check_title']);
		$bl = strtolower($b['check_title']);

		if ($al == $bl)
			return 0;

		return ($al > $bl) ? +1 : -1;

	}
	
	/**
	 * Sort By Check Title, Date Descending
	 *
	 * @param array $a 
	 * @param array $b 
	 */
	private function sortByCheckTitleDateDecending($a, $b) {
		
		$al = strtolower($a['check_title']);
		$bl = strtolower($b['check_title']);

		if ($al == $bl)
			return 0;

		return ($al > $bl) ? +1 : -1;

	}

}
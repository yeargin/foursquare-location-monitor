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
		if (isset($range['start_ts']) && isset($range['end_ts'])):
			$this->db->where('insert_ts >=', $range['start_ts']);
			$this->db->where('insert_ts <=', $range['end_ts']);
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
	
	public function getAllCheckDataDelta($date_since = null, $date_sort_dir = 'DESC') {
		if ($date_since == '' || is_null($date_since))
			$date_since = date('Y-m-d', strtotime('-5 days'));
		
		$query = sprintf('SELECT fc.check_title, fc.venue_id, fcl1.check_id, fcl2.log_date, (fcl2.total_checkins-ifnull(fcl1.total_checkins, 0)) AS total_checkins_delta, (fcl2.unique_visitors-ifnull(fcl1.unique_visitors, 0)) AS unique_visitors_delta, (fcl2.tips_left-ifnull(fcl1.tips_left, 0)) AS tips_left_delta, (fcl2.photo_count-ifnull(fcl1.photo_count, 0)) AS photo_count_delta FROM foursquare_checks fc INNER JOIN foursquare_check_log fcl1 ON fc.id = fcl1.check_id LEFT OUTER JOIN foursquare_check_log fcl2 ON fcl1.check_id = fcl2.check_id AND DAY(fcl1.log_date)-MONTH(fcl2.log_date)=1 WHERE fcl2.log_date >= "%s" ORDER BY fcl1.check_id ASC, fcl2.log_date %s', date('Y-m-d', strtotime($date_since)), $date_sort_dir);
		$result = $this->db->query($query);
		
		$array = $result->result_array();
		
		// Build data array
		$return = array();
		foreach ($array as $k => $v):
				if (is_null($v['log_date'])) continue;
				$return[$v['check_id']]['check_id'] = $v['check_id'];
				$return[$v['check_id']]['check_title'] = $v['check_title'];
				$return[$v['check_id']]['venue_id'] = $v['venue_id'];
				$return[$v['check_id']]['total_checkins'][$v['log_date']] = $v['total_checkins_delta'];
				$return[$v['check_id']]['unique_visitors'][$v['log_date']] = $v['unique_visitors_delta'];
				$return[$v['check_id']]['tips_left'][$v['log_date']] = $v['tips_left_delta'];
				$return[$v['check_id']]['photo_count'][$v['log_date']] = $v['photo_count_delta'];
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
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
	public function getChecksByUserId($user_id, $tags = array()) {
		$this->db->select(array('id', 'check_title', 'venue_id', 'active', 'insert_ts', 'update_ts', 'last_daily_check_ts', 'last_live_check_ts'));
		$this->db->where('user_id', $user_id);
		
		// Filter results by tag(s)
		if (is_array($tags) && count($tags) > 0):
			$this->db->join('foursquare_check_tags', 'foursquare_check_tags.check_id = foursquare_checks.id', 'left');
			$this->db->where('tag IN', $tags);
		elseif (is_string($tags) && trim($tags) != ''):
			$this->db->join('foursquare_check_tags', 'foursquare_check_tags.check_id = foursquare_checks.id', 'left');
			$this->db->where('tag', $tags);
		endif;

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

		// Ensure that Check information is only viewed by authenticated user
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
		$record['active'] = '0'; // deactivated by default
		$this->db->insert('foursquare_checks', $record);
		
		// Activate check (if possible)
		return $this->activate($this->db->insert_id());
	}
	
	/**
	 * Add New Check
	 *
	 * @param array $record 
	 */
	public function updateCheck($record = array()) {
		$this->db->where('id', $record['id']);
		$this->db->update('foursquare_checks', $record);
	}
	
	/**
	 * Deactivate a Check
	 *
	 * @param int $check_id 
	 */
	public function deactivate($check_id) {
		$this->db->where('id', $check_id);
		$this->db->update('foursquare_checks', array('active' => '0'));
		
		return true;
	}
	
	/**
	 * Activate a Check
	 *
	 * @param int $check_id 
	 */
	public function activate($check_id) {
		// Check if user has remaining checks	
		if (!$this->hasRemainingChecks())
			return false;	
		
		$this->db->where('id', $check_id);
		$this->db->update('foursquare_checks', array('active' => '1'));
		return true;
	}
	
	public function deleteCheck($check_id) {
		
		if (!$check_id > 0)
			return false;
			
		// Cascade delete related records
		$this->db->delete('foursquare_checks', array('id' => $check_id));
		$this->db->delete('foursquare_check_log', array('check_id' => $check_id));
		$this->db->delete('foursquare_check_log_live', array('check_id' => $check_id));
		$this->db->delete('foursquare_check_tags', array('check_id' => $check_id));
		
		return true;
	}
	
	/**
	 * Add Tag
	 *
	 * @param int $check_id 
	 * @param string $tag 
	 */
	public function addTag($check_id, $tag) {
		
		// Cannot add non-string or blank tag
		if (!is_string($tag) || trim($tag) == '')
			return false;
			
		$stat = $this->db->query('REPLACE INTO foursquare_check_tags (check_id, tag) VALUES (?, ?)', array($check_id, $tag));
		
		return array('check_id' => $check_id, 'tag' => $tag, 'result' => $stat);
	}
	
	/**
	 * Add Tag Bulk
	 *
	 * @param string $check_id 
	 * @param mixed $tags 
	 */
	public function addTagBulk($check_id, $tags = '') {

		// Split comma separated list
		if(is_string($tags)):
			$tags = preg_split("/[\s]*[,][\s]*/", $tags);
		endif;
		
		// Add tags
		if (is_array($tags) && count($tags) > 0):
			foreach ($tags as $tag):
				$stat[] = $this->addTag($check_id, $tag);
			endforeach;
			return $stat;
		else:
			return false;
		endif;
	}
	
	/**
	 * Remove Tag
	 *
	 * @param int $check_id 
	 * @param string $tag 
	 */
	public function removeTag($check_id, $tag) {
		$this->db->where('check_id', $check_id);
		$this->db->where('tag', trim(__($tag)));
		$stat = $this->db->delete('foursquare_check_tags');

		return array('check_id' => $check_id, 'tag' => $tag, 'result' => $stat);
	}
	
	/**
	 * Get Tags By Check ID
	 *
	 * @param int $check_id 
	 */
	public function getTagsByCheckId($check_id) {
		$user = unserialize($this->session->userdata('user'));
		$this->db->select('tag');
		$this->db->where('check_id', $check_id);
		$this->db->order_by('tag', 'ASC');
		$query = $this->db->get('foursquare_check_tags');
		
		return $query->result();
	}
	
	/**
	 * Get Tags
	 *
	 * @param int $check_id 
	 */
	public function getTags() {
		$user = unserialize($this->session->userdata('user'));
		$this->db->select('foursquare_check_tags.check_id, tag');
		$this->db->join('foursquare_checks', 'foursquare_check_tags.check_id = foursquare_checks.id');
		$this->db->join('users', 'users.id = foursquare_checks.user_id');
		$this->db->where('foursquare_checks.user_id', $user->id);
		$this->db->order_by('tag', 'ASC');
		$query = $this->db->get('foursquare_check_tags');
		
		$result = $query->result();
		
		// Build indexed list of tags
		$output = array();
		if (is_array($result)):
			foreach ($result as $row):
				$output[$row->check_id][] = $row->tag;
			endforeach;
			return $output;
		else:
			return array();
		endif;
	}
	
	/**
	 * Get Tag List
	 */
	public function getTagList() {
		$user = unserialize($this->session->userdata('user'));
		$this->db->select('tag');
		$this->db->join('foursquare_checks', 'foursquare_check_tags.check_id = foursquare_checks.id');
		$this->db->join('users', 'users.id = foursquare_checks.user_id');
		$this->db->where('foursquare_checks.user_id', $user->id);
		$this->db->group_by('tag');
		$this->db->order_by('tag', 'ASC');
		$query = $this->db->get('foursquare_check_tags');
		
		$result = $query->result();
		
		// Build indexed list of tags
		$output = array();
		if (is_array($result)):
			foreach ($result as $row):
				$output[$row->tag] = $row->tag;
			endforeach;
			return $output;
		else:
			return array();
		endif;
	}
	
	public function remainingChecksCount() {
		// Get count of active checks
		$user = unserialize($this->session->userdata('user'));
		$this->db->select('count(*) AS active_checks');
		$this->db->where('active', '1');
		$this->db->where('user_id', $user->id);
		$query = $this->db->get('foursquare_checks');
		$checks = $query->row();
		
		// Get package limit
		$this->db->where('id', $user->package_id);
		$query = $this->db->get('packages');
		$package = $query->row();
		
		return array('package' => $package, 'checks' => $checks);
	}
	
	public function hasRemainingChecks() {

		$data = $this->remainingChecksCount();
		
		// Simple math to see if there are remaining checks
		if ($data['checks']->active_checks < $data['package']->check_limit)
			return true;
		else
			return false;
	}
	

	/**
	 * Admin: Get All Checks
	 *
	 * @param int $count 
	 */
	public function adminGetAllChecks($limit = 25, $offset = 0) {
		$this->db->select(array('foursquare_checks.id AS check_id', 'check_title', 'venue_id', 'last_live_check_ts', 'last_daily_check_ts', 'users.id AS user_id', 'username'));
		$this->db->join('users', 'users.id = foursquare_checks.user_id');
		$this->db->order_by('last_live_check_ts', 'DESC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get('foursquare_checks');
		
		return $query->result();
	}

}
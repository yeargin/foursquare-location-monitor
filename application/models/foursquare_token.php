<?php
/**
* Foursquare Tokens
*/
class Foursquare_token extends CI_Model {
	
	public $id;
	public $user_id;
	public $token;
	public $insert_ts;

	public function __construct() {
		parent::__construct();
	}

	function getTokenByUserId($user_id) {
		$query = $this->db->get_where('foursquare_tokens', array('user_id' => $user_id), 1);
		$result = $query->result();
		
		if (!$result)
			return false;
			
		if (is_object($result[0])) {
			$token = $result[0]->token;
			return unserialize($token); // returns object
		} else {
			return false;
		}
	}
	
	function getTokenInfo($user_id) {
		$record = $this->db->get_where('foursquare_tokens', array('user_id' => $user_id), 1);
		return $record;
	}

	function saveUserToken($user_id, $token) {
		$data = array(
			'user_id' => (int) $user_id,
			'token' => serialize($token),
			'insert_ts' => date('Y-m-d H:i:s')
		);
		$record = $this->db->insert('foursquare_tokens', $data);
		return $record;
	}

	function deleteUserToken($user_id) {
		$this->db->where('user_id', $user_id);
		$this->db->delete('foursquare_tokens');
	}
	
}

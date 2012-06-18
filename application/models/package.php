<?php

/**
 * Packages
 */
class Package extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}

	public function getPackages() {
		$this->db->order_by('check_limit', 'ASC');
		$query = $this->db->get('packages');
		
		return $query->result();	
	}
	
	public function getPackageById($package_id = 0) {
		$this->db->where('id', $package_id);
		$query = $this->db->get('packages');
		
		return $query->row();
	}
	
}
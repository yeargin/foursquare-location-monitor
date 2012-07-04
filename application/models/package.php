<?php

/**
 * Packages
 */
class Package extends CI_Model {
	
	public $id;
	public $name;
	public $description;
	public $check_limit;
	
	public function __construct() {
		parent::__construct();
	}

	public function getPackages() {
		$this->db->order_by('check_limit', 'ASC');
		$query = $this->db->get('packages');
		
		return $query->result();
	}

	public function getPackagesWithUserCount() {
		$query = $this->db->query('SELECT packages.*, count(users.id) AS user_count FROM packages LEFT JOIN users ON users.package_id = packages.id GROUP BY packages.id');
		return $query->result();
	}

	public function getPackageById($package_id = 0) {
		$this->db->where('id', $package_id);
		$query = $this->db->get('packages');
		return $query->row();
	}
	
	public function updatePackageFromPost() {
		$record_data = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'check_limit' => $this->input->post('check_limit')
		);
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('packages', $record_data);
		return;
	}
	
	public function addPackageFromPost() {
		$record_data = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'check_limit' => $this->input->post('check_limit')
		);
		$this->db->insert('packages', $record_data);
		return;
	}
	
	public function deletePackage($package_id = 0, $migrate_to = 0) {
		
		if ($package_id == $migrate_to || !$this->getPackageById($migrate_to))
			show_error('Could not delete package without a valid one to supercede it.');
		
		// Migrate users to new package
		$this->db->set('package_id', $migrate_to);
		$this->db->where('package_id', $package_id);
		$this->db->update('users');
		
		// Delete old package
		$this->db->where('id', $package_id);
		$this->db->delete('packages');
		return;
	}
	
}
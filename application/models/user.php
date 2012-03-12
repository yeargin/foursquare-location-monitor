<?php

class User extends CI_Model {

	public function login() {
		$this->db->select(array('id', 'username', 'display_name', 'first_name', 'last_name', 'email', 'level'));
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('password', md5($this->input->post('password') . $this->config->item('encryption_key')));
		$this->db->where('status', 1);
		$query = $this->db->get('users');
		
		// If valid, set 'user' session variable. If not, clear it.
		if($query->num_rows == 1):
			$this->session->set_userdata('user', serialize(array_shift($query->result())));
			return true;
		else:
			$this->session->unset_userdata('user');
			return false;
		endif;
	}
	
	public function logout() {
		$this->session->unset_userdata('user');
		return true;
	}
	
	public function isLoggedIn() {
		$user = unserialize($this->session->userdata('user'));
		if (!$user)
			return false;
		else
			return true;
	}
	
	public function getUserById($id) {
		$query = $this->db->get_where('users', array('id' => $id), 1);
		return array_shift($query->result());
	}
	
	public function createNewUserFromPost() {
		$new_member_insert_data = array(
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password') . $this->config->item('encryption_key')),
			'display_name' => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email' => $this->input->post('email_address')
		);
		
		$insert = $this->db->insert('users', $new_member_insert_data);
		return $insert;
	}

	/* ***** Site Administrator Methods ***** */
	
	public function isAdmin() {
		$user = unserialize($this->session->userdata('user'));
		if ($user->level != 'admin')
			return false;
		else
			return true;
	}
	
	public function adminGetAllUsers($status = 1) {
		if (!$this->isAdmin())
			show_error('Access denied.');
		
		$this->db->select(array('users.id', 'username', 'display_name', 'first_name', 'last_name', 'email', 'level', 'package_id AS package', 'count(*) AS check_count', 'users.insert_ts'));
		$this->db->join('foursquare_checks', 'foursquare_checks.user_id = users.id');
		$this->db->where('status', $status);
		$this->db->group_by('users.id');
		$query = $this->db->get('users');
		
		return $query->result();
	}


}
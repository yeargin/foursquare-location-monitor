<?php

class User extends CI_Model {

	public function login() {
		$this->db->select(array('id', 'username', 'display_name', 'first_name', 'last_name', 'email'));
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('password', md5($this->input->post('password') . $this->config->item('encryption_key')));
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
		$user = $this->session->userdata('user');
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
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_controller extends CI_Controller {
	
	public $layout = 'default';
	
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		// Require login
		if (!$this->user->isLoggedIn())
			redirect('login');

		// Require Admin
		if (!$this->user->isAdmin())
			show_error('Access denied.', 401);

		// Helpers
		$this->load->library('form_validation');

		// Models
		$this->load->model('foursquare_check');
		$this->load->model('beta_key');

	}

	/**
	 * Index
	 */
	public function index() {

		// Get list of users
		$data['active_accounts'] = $this->user->adminGetAllUsers(true, 10);
		$data['inactive_accounts'] = $this->user->adminGetAllUsers(false, 10);
		
		// Get most recent checks
		$data['last_checks'] = $this->foursquare_check->adminGetAllChecks(10);

		// Get beta keys
		$data['beta_keys'] = $this->beta_key->adminGetAllBetaKeys(25);

		$data['page_title'] = 'Administrator Dashboard';
		$this->load->view('admin/dashboard', $data);
		
		return;
	}

	/**
	 * Users
	 */
	public function users() {

		$limit = 10;
		$offset = (int) $this->input->get('page') * 10;

		$data['active_accounts'] = $this->user->adminGetAllUsers(true, $limit, $offset);
		$data['inactive_accounts'] = $this->user->adminGetAllUsers(false, $limit, $offset);

		return;
	}
	
	/**
	 * User
	 */
	public function user() {
		$user_id = $this->uri->segment(3);
		
		// If invalid user ID, show error
		if (!is_numeric($user_id))
			show_error('Invalid user ID.');
		
		// Package list
		$packages = $this->user->packageList();
		foreach ($packages as $row):
			$package_list[$row->id] = sprintf('%s (%s)', $row->name, number_format($row->check_limit));
		endforeach;
		$data['packages'] = $package_list;
		
		$data['user'] = $this->user->getUserById($user_id);
		$data['checks'] = $this->foursquare_check->getChecksByUserId($user_id);
		$data['page_title'] = sprintf('User: %s', $data['user']->username);
		$this->load->view('admin/user', $data);	
	}
	
	function user_change_package() {
		$user_id = $this->input->post('user_id');
		$package_id = $this->input->post('package_id');
		$this->user->adminchangeUserPackage($user_id, $package_id);
		$this->session->set_flashdata('Package updated!');
		redirect('admin/user/'.$user_id);
	}
	
	/**
	 * Deactivate User
	 */
	public function deactivate_user() {
		$user_id = $this->uri->segment(3);
		
		// Prevent deactivating current user
		$current_user = unserialize($this->session->userdata('user'));
		if ($current_user->id == $user_id)
			show_error('Deactivating your own account would be a mistake.', 403);
		
		$this->user->adminUpdateUserStatus($user_id, 0);
		$this->session->set_flashdata('message', 'User deactivated!');
		redirect('admin');
	}
	
	/**
	 * Activate User
	 */
	public function activate_user() {
		$user_id = $this->uri->segment(3);
		$this->user->adminUpdateUserStatus($user_id, 1);
		$this->session->set_flashdata('message', 'User activated!');
		redirect('admin');
	}

	/**
	 * Assume User
	 */
	public function assume_user() {
		$user_id = $this->uri->segment(3);
		$this->user->adminAssumeUser($user_id);
		$this->session->set_flashdata('message', 'You have been logged out of your account and in as this user.');
		redirect('/');
	}

	public function beta_keys() {
		return;
	}

	/**
	 * Beta Key New
	 */
	public function beta_key_new() {

		$this->form_validation->set_error_delimiters('<div class="alert alert-error">', '<a class="close" href="#">&times;</a></div>');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		
			// If submission valid, run 
			if ($this->form_validation->run() != FALSE):
				$key = $this->beta_key->adminCreateBetaKeyFromPost();
				$this->sendBetaKeyEmail($key);
				$this->session->set_flashdata('message', 'Beta key created! User has been sent their login information.');
				redirect('admin');
			endif;
		
		$data['name'] = $this->input->post('name');
		$data['email'] = $this->input->post('email');

		$data['page_title'] = 'Create Beta Key';
		$this->load->view('admin/form_beta_key', $data);
	}
	
	
	/**
	 * Send Beta Key Email
	 *
	 * @param object $key 
	 */
	private function sendBetaKeyEmail($key) {
		
		if (!is_object($key))
			show_error('Beta key was not saved.', 500);
		
		$data['key'] = $key;
		$data['application_name'] = $this->config->item('application_name');
		
		// Create message
		$this->email->from($this->config->item('application_email'), $this->config->item('application_name'));
		$this->email->to($key->email);
		$this->email->subject('Beta invitation for ' . $this->config->item('application_name') . '!');
		$this->email->message($this->load->view('emails/new_beta_key_message', $data, true));

		return $this->email->send();
		
	}
	

}
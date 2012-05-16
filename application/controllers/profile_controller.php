<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_controller extends CI_Controller {
	
	public $layout = 'default';
	
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
	
		// Require user access
		if (!$this->user->isLoggedIn())
			redirect('/login/?redirect=/profile');
	
		$this->load->model('user');
		$this->load->library('form_validation');
	}

	/**
	 * Index
	 */
	public function index() {
		
		// Setup controller
		$data = $this->setup();

		$this->form_validation->set_error_delimiters('<div class="alert alert-error">', '<a class="close" href="#">&times;</a></div>');
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		// If submission valid, run 
		if ($this->form_validation->run() != FALSE):
			$user = unserialize($this->session->userdata('user'));
			$this->user->updateUserFromPost($user->id);
			$this->session->set_flashdata('message', 'Profile updated!');
			redirect('profile');
		endif;

		$data['page_title'] = 'Profile';
		$data['sidebar_content'] = $this->load->view('account/_sidebar', $data, true);
		$this->load->view('account/profile', $data);
	}


	/**
	 * Change Password
	 */
	public function change_password() {
		
		// Setup controller
		$data = $this->setup();

		$this->form_validation->set_error_delimiters('<div class="alert alert-error">', '<a class="close" href="#">&times;</a></div>');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]');
		$this->form_validation->set_rules('password_confirm', 'Password (confirm)', 'required|matches[password]');

		if ($this->input->post()):
			if ($this->form_validation->run() != FALSE):
				$user = unserialize($this->session->userdata('user'));
				$this->user->updatePasswordFromPost($user->id);
				$this->session->set_flashdata('message', 'Password updated!');
				redirect('profile');
			endif;
		endif;

		$data['page_title'] = 'Change Password';
		$this->load->view('account/change_password', $data);
	}

	/**
	 * Setup
	 */
	private function setup() {
		$user = unserialize($this->session->userdata('user'));
		$profile = $this->user->getUserById($user->id);
		$data['profile'] = $profile;
		
		return $data;
	}

}
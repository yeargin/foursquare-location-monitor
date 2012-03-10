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
		
	}

	/**
	 * Index
	 */
	public function index() {
		
		// Setup controller
		$data = $this->setup();

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
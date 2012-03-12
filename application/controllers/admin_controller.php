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

		// Models
		$this->load->model('foursquare_check');

	}

	public function index() {

		// Get list of users
		$data['active_accounts'] = $this->user->adminGetAllUsers(1);
		$data['inactive_accounts'] = $this->user->adminGetAllUsers(0);
		
		// Get most recent checks
		$data['last_checks'] = $this->foursquare_check->adminGetAllChecks(25);

		$data['page_title'] = 'Administrator Dashboard';
		$this->load->view('admin/dashboard', $data);
		
		return;
	}

}
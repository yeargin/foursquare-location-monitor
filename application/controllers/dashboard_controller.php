<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_controller extends CI_Controller {

	public $layout = 'default';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		// Require login
		if (!$this->user->isLoggedIn())
			redirect('login');
		
		// Load models
		$this->load->model('foursquare_token');
		$this->load->model('foursquare_check');
		$this->load->model('foursquare_check_log');

	}

	/**
	 * Index
	 */
	public function index()
	{

		// Account information
		$user = unserialize($this->session->userdata('user'));

		// See if Foursquare token is present
		$user_token = $this->foursquare_token->getTokenByUserId($user->id);
		$data['has_foursquare'] = ($user_token) ? true : false;

		// Get list of Foursquare Checks
		$checks = $this->foursquare_check->getChecksByUserId($user->id);
		$data['checks'] = $checks;

		$data['page_title'] = 'Dashboard';
		$data['sidebar_content'] = $this->load->view('checks/_sidebar', $data, true);

		$data['dashboard_modules'] = $this->foursquare_check_log->getAllCheckDataDelta($user->id);

		$this->load->view('dashboard/index', $data);
		
	}
}
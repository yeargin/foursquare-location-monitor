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

		$data['page_title'] = 'Dashboard';
		$this->load->view('dashboard/index', $data);
	}
}
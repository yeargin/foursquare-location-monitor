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

		// If set in GET parameters, set and write to cookie
		if ($this->input->get('start_date') && $this->input->get('end_date')):
			$this->date_range = array(
				'start_date' => date('c', strtotime($this->input->get('start_date')) ),
				'end_date' => date('c', strtotime($this->input->get('end_date')) )
			);
			$this->input->set_cookie('locmon_start_date', $this->input->get('start_date'), 3600, null, '/');
			$this->input->set_cookie('locmon_end_date', $this->input->get('end_date'), 3600, null, '/');
		// Read from cookie
		elseif ($this->input->cookie('locmon_start_date') && $this->input->cookie('locmon_end_date')):
			$this->date_range = array(
				'start_date' => date('c', strtotime($this->input->cookie('locmon_start_date')) ),
				'end_date' => date('c', strtotime($this->input->cookie('locmon_end_date')) )
			);
		// Default to 7 days back
		else:
			$this->date_range = array(
				'start_date' => date('c', time() - (3600*24*7) ),
				'end_date' => date('c', time())
			);
		endif;


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

		$data['date_range'] = $this->date_range;

		$data['page_title'] = 'Dashboard';
		
		if ($data['has_foursquare']):
			$data['sidebar_content'] = $this->load->view('checks/_sidebar', $data, true);
			$data['sidebar_content'] .= $this->load->view('checks/_date_range_form', $data, true);
		endif;

		$data['dashboard_modules'] = $this->foursquare_check_log->getAllCheckDataDelta($user->id, $this->date_range);

		$this->load->view('dashboard/index', $data);
		
	}
}
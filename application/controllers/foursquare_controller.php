<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Foursquare_controller extends CI_Controller {
	
	public $layout = 'default';
	
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		
		// Require user access, but not for CLI work
		if (!$this->session->userdata('user') && !$this->input->is_cli_request())
			redirect('/login/?redirect=/foursquare');

		$this->load->helper('time');

		$this->apiKey = $this->config->item('foursquare_consumer_key');
		$this->clientId = $this->config->item('foursquare_consumer_key');
		$this->clientSecret = $this->config->item('foursquare_consumer_secret');
		$this->callbackUrl = $this->config->item('foursquare_callback_url');
		
		$this->load->library('IgniteFoursquare', array('clientId' => $this->clientId, 'clientSecret' => $this->clientSecret, 'redirectUrl' => $this->callbackUrl));
		
		// Tokens model
		$this->load->model('foursquare_token');
	}
	
	/**
	 * Index
	 */
	public function index() {

		// Redirect to Dashboard
		redirect('dashboard');
		
	}

	/**
	 * Venue
	 */
	public function venue() {
		
		// Setup for various actions
		$data = (array) $this->setup();
		
		// Grab venue from URL string
		$venue_id = $this->uri->segment(3);
		if (!$venue_id)
			show_error('Could not find venue.', 404);
		
		// Basic venue data
		$venue = json_decode($this->ignitefoursquare->GetPrivate(sprintf('/venues/%s', $venue_id)));
		$data['venue'] = $venue->response->venue;

		// Tips
		$tips = json_decode($this->ignitefoursquare->GetPrivate(sprintf('/venues/%s/tips', $venue_id)));
		$data['tips'] = $tips->response->tips;
		
		// Photos
		$photos = json_decode($this->ignitefoursquare->GetPrivate(sprintf('/venues/%s/photos', $venue_id)));
		$data['photos'] = $photos->response->photos;
		
		// Nearby
		$position = number_format($venue->response->venue->location->lat,6,'.',',') . ',' . number_format($venue->response->venue->location->lng,6,'.',',');
		$nearby = json_decode($this->ignitefoursquare->GetPrivate('/venues/search', array('ll' => $position, 'limit' => 10)));
		$data['nearby'] = $nearby->response->venues;
		
		// Stats
		$stats = json_decode($this->ignitefoursquare->GetPrivate(sprintf('/venues/%s/stats', $venue_id)));
		$data['stats'] = $stats->response;
		
		// Check Data
		$this->load->model('foursquare_check');
		$check = $this->foursquare_check->getCheckByVenueId($venue_id);

		if (isset($check->id) && $check->id > 0):
		
			$data['check'] = $check;
			
			// Load Live Check Data (30 days)
			$date_range = array(
				'start_ts' => date('c', time() - (3600*24*30) ),
				'end_ts' => date('c', time())
			);
			$this->load->model('foursquare_check_log_live');
			$live_data = $this->foursquare_check_log_live->getCheckData($check->id, $date_range);
			$data['live_data'] = $live_data;

			// Load Daily Check Data (7 days)
			$date_range = array(
				'start_ts' => date('c', time() - (3600*24*7) ),
				'end_ts' => date('c', time())
			);
			$this->load->model('foursquare_check_log');
			$daily_data = $this->foursquare_check_log->getCheckData($check->id, $date_range);
			$data['daily_data'] = $daily_data;
		
			// Calculate date difference
			$data['daily_data_delta'] = $this->foursquare_check_log->deltaArray($daily_data, 'log_date');
		
		endif;

		$data['page_title'] = sprintf('Foursquare - Venue: %s', isset($data['venue']->name) ? ($data['venue']->name) : 'Venue');
		$data['head_content'] = $this->load->view('foursquare/_head', $data, true);
		$data['head_content'] .= $this->load->view('checks/_chart_js', $data, true);
		$data['sidebar_content'] = $this->load->view('foursquare/_sidebar', $data, true);
		$this->load->view('foursquare/venue', $data);
		
	}

	/**
	 * Profile
	 */
	public function profile() {
		
		// Setup for various actions
		$data = (array) $this->setup();
		
		// Grab venue from URL string
		$profile_id = $this->uri->segment(3);
		if (!$profile_id)
			show_error('Could not find user.', 404);
		
		$profile = json_decode($this->ignitefoursquare->GetPrivate(sprintf('/users/%s', $profile_id)));
		$data['member'] = $profile->response->user;

		$data['page_title'] = sprintf('Foursquare - Profile: %s %s', isset($data['member']->firstName) ? $data['member']->firstName : '', isset($data['member']->lastName) ? $data['member']->lastName : '' );
		$data['sidebar_content'] = $this->load->view('foursquare/_sidebar', $data, true);
		$this->load->view('foursquare/profile', $data);
		
	}

	/**
	 * Search
	 */
	public function search() {
		// Setup for various actions
		$data = (array) $this->setup();
		
		
		
		// Get search results
		if ($this->input->get('near') != '' && $this->input->get('q')):
				
				$query = $this->input->get('q');
				$near = $this->input->get('near');
				
				// Process query results
				$position = $this->_geocode($near);
				
				if ($position == false)
					show_error('Search failed to locate a suitable address');
				
				$ll = join(',', array($position->location->lat, $position->location->lng));
				
				$search_results = json_decode($this->ignitefoursquare->GetPrivate('/venues/search', array('ll' => $ll, 'query' => $query, 'limit' => 10)));
				$data['search_results'] = $search_results->response->venues;
		
		else:
			$data['search_results'] = null;
		endif;
		
		$this->load->view('foursquare/search', $data);
		
	}
	
	/**
	 * Setup
	 */
	private function setup() {
		$user = unserialize($this->session->userdata('user'));
		$user_token = $this->foursquare_token->getTokenByUserId($user->id);

		if (is_array($user_token)) {
			$token = $user_token;
			$this->ignitefoursquare->SetAccessToken($token[0]);
			$this->ignitefoursquare->setAuthenticated(true);
			$data['foursquareAuthenticated'] = $this->ignitefoursquare->isAuthenticated();
			
		} else {
			redirect('foursquare/authenticate');
		}
		
		// Logged in user info
		$userInfo = json_decode($this->ignitefoursquare->GetPrivate('/users/self'));
		$data['userInfo'] = $userInfo->response->user;

		return $data;
		
	}


	/* *** OAuth Related *** */

	/**
	 * Authenticate
	 */
	public function authenticate() {
		// Authenticate if not already
		$authorizeUrl = $this->ignitefoursquare->AuthenticationLink($this->ignitefoursquare->redirectUrl);
		$data['authorizationUrl'] = $authorizeUrl;
		
		$data['page_title'] = 'Foursquare - Authenticate';
		$this->load->view('foursquare/authenticate', $data);
	}
	
	/**
	 * Callback
	 */
	public function callback() {
		if ($this->input->get('code') != '') {
			$user = unserialize($this->session->userdata('user'));
			$token = $this->ignitefoursquare->GetToken($this->input->get('code'), $this->ignitefoursquare->redirectUrl);
			$this->foursquare_token->saveUserToken($user->id, array($token));
			$this->ignitefoursquare->SetAccessToken($token->access_token);
			$this->ignitefoursquare->setAuthenticated(true);
		} else {
			redirect('foursquare/authenticate');
		}
		redirect('foursquare');
	}
	
	/**
	 * Reset
	 */
	public function reset() {
		$user = unserialize($this->session->userdata('user'));
		$this->foursquare_token->deleteUserToken($user->id);
		redirect('foursquare');
	}
	
	/* *** Private Methods *** */



	private function _geocode($address = null) {

		// Set up request to Google
		$headers = array();
		//$headers[] = "Authorization: Bearer " . $this->oauthToken;

		// Process cURL results
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, sprintf('http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false', urlencode($address)) );
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		
		// Check Response
		$json = json_decode($output);
		if ($json->status != 'OK')
			return false;

		// Return only the relevent component
		return $json->results[0]->geometry;
		
	}

}
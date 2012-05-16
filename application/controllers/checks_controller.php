<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checks_controller extends CI_Controller {
	
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
		$this->load->helper('tag');

		$this->apiKey = $this->config->item('foursquare_consumer_key');
		$this->clientId = $this->config->item('foursquare_consumer_key');
		$this->clientSecret = $this->config->item('foursquare_consumer_secret');
		$this->callbackUrl = $this->config->item('foursquare_callback_url');
		
		$this->load->library('IgniteFoursquare', array('clientId' => $this->clientId, 'clientSecret' => $this->clientSecret, 'redirectUrl' => $this->callbackUrl));
		
		// Load models
		$this->load->model('foursquare_token');
		$this->load->model('foursquare_check');

	}
	
	/**
	 * Index
	 */
	public function index() {
		
		// The 'checks' action is the default.
		$this->checks();
		
	}

	
	/**
	 * Checks
	 */
	public function checks() {

		// Setup for various actions
		$data = $this->setup();

		// Grab user object from session
		$user = unserialize($this->session->userdata('user'));

		// Check counts
		$counts = $this->foursquare_check->remainingChecksCount();
		$data['counts'] = $counts;

		// Load list of checks
		$checks = $this->foursquare_check->getChecksByUserId($user->id, $this->input->get('tag'));
		$data['checks'] = $checks;

		// Load Tag List
		$tags = $this->foursquare_check->getTags();
		$data['tags'] = $tags;
		
		$tag_list = $this->foursquare_check->getTagList();
		$data['tag_list'] = $tag_list;
		
		$data['page_title'] = 'Venue Checks';
		$data['head_content'] = $this->load->view('checks/_head', $data, true);
		$this->load->view('checks/checks', $data);	
		$this->load->view('checks/_tag_modal', $data);	
		$this->load->view('checks/_tag_js', $data);	

	}
	
	/**
	 * Check
	 */
	public function check() {
		// Setup for various actions
		$data = $this->setup();

		// Grab user object from session
		$user = unserialize($this->session->userdata('user'));
		
		// Grab check_id from URL
		$check_id = $this->uri->segment(3);
		
		// Load list of checks
		$checks = $this->foursquare_check->getChecksByUserId($user->id);
		$data['checks'] = $checks;
		
		// 7 days back
		$date_range = array(
			'start_ts' => date('c', time() - (3600*24*7) ),
			'end_ts' => date('c', time())
		);
		
		// Get data for this check
		$data['check'] = $this->foursquare_check->getCheckById($check_id);

		// Load Live Check Data
		$this->load->model('foursquare_check_log_live');
		$live_data = $this->foursquare_check_log_live->getCheckData($check_id, $date_range);
		$data['live_data'] = $live_data;

		// Load Daily Check Data
		$this->load->model('foursquare_check_log');
		$daily_data = $this->foursquare_check_log->getCheckData($check_id, $date_range);
		$data['daily_data'] = $daily_data;
		
		// Calculate date difference
		$data['daily_data_delta'] = $this->foursquare_check_log->deltaArray($daily_data, 'log_date');
		
		$data['page_title'] = 'Check Log: ' . __($data['check']->check_title);
		
		$this->load->view('checks/check', $data);	
	}
	
	/**
	 * Check Add
	 */
	public function check_add() {
		// Setup for various actions
		$data = $this->setup();

		// Grab user object from session
		$user = unserialize($this->session->userdata('user'));
		
		// Grab check_id from URL
		$venue_id = $this->uri->segment(3);
		
		// See if check is already added
		$check = $this->foursquare_check->getCheckByVenueId($venue_id, $user->id);
		if (isset($check->id))
			show_error('This venue is already setup for monitoring.');
			
		// Basic venue data
		$venue = json_decode($this->ignitefoursquare->GetPrivate(sprintf('/venues/%s', $venue_id)));
		if (!isset($venue->response->venue->name))
			show_error('Could not locate venue', 404);
		$data['check']->check_title = $venue->response->venue->name;
		$data['check']->active = '1';
		
		// Process posted check name
		if ($this->input->post('check_title') != ''):
			$record['user_id'] = $user->id;
			$record['venue_id'] = $venue_id;
			$record['check_title'] = $this->input->post('check_title');
			$record['insert_ts'] = date('c');
			$result = $this->foursquare_check->addNewCheck($record);
			redirect(sprintf('foursquare/venue/%s', $venue_id));
		endif;
		
		// Confirm Add
		$this->load->view('checks/check_form', $data);
	}
	
	/**
	 * Check Edit
	 */
	public function check_edit() {
		// Setup for various actions
		$data = $this->setup();

		// Grab check_id from URL
		$check_id = $this->uri->segment(3);
		
		// See if check is already added
		$check = $this->foursquare_check->getCheckById($check_id);
		$data['check'] = $check;
		
		if (!isset($check->id))
			show_error('Check could not be found.', 404);
		
		// Process posted check name
		if ($this->input->post('check_title') != ''):
			$record['id'] = $check_id;
			$record['check_title'] = $this->input->post('check_title');
			$result = $this->foursquare_check->updateCheck($record);
			redirect(sprintf('foursquare/venue/%s', $check->venue_id));
		endif;
		
		// Show Edit Form
		$this->load->view('checks/check_form', $data);
	
	}
	
	public function check_delete() {
		// Setup for various actions
		$data = $this->setup();

		// Grab check_id from URL
		$check_id = $this->uri->segment(3);
		
		// See if check is already added
		$check = $this->foursquare_check->getCheckById($check_id);
		$data['check'] = $check;
		
		if (!isset($check->id))
			show_error('Check could not be found.', 404);
		
		// Process check delete
		if ($this->input->post('confirm')):
			$result = $this->foursquare_check->deleteCheck($check_id);
			redirect(sprintf('foursquare/venue/%s', $check->venue_id));
		endif;
			
		// Show Edit Form
		$this->load->view('checks/check_delete', $data);
		
	}
	
	public function check_activate() {
		$check_id = $this->uri->segment(3);
		$check = $this->foursquare_check->getCheckById($check_id);
		if (!isset($check->id))
			show_error('Check could not be found.', 404);
				
		$result = $this->foursquare_check->activate($check_id);
		
		if ($result == true)
			$this->session->set_flashdata('message', 'Check activated!');
		else
			$this->session->set_flashdata('message', 'Unable to activate check.');
			
		redirect('checks');
	}
	
	public function check_deactivate() {
		$check_id = $this->uri->segment(3);
		$check = $this->foursquare_check->getCheckById($check_id);
		if (!isset($check->id))
			show_error('Check could not be found.', 404);
				
		$result = $this->foursquare_check->deactivate($check_id);
		
		if ($result == true)
			$this->session->set_flashdata('message', 'Check deactivated!');
		else
			$this->session->set_flashdata('message', 'Unable to deactivate check.');
			
		redirect('checks');
	}
	
	/* *** AJAX data sources *** */

	public function ajax_tags() {
		
		// Setup controller
		$this->layout = null;
		$this->setup();
		
		$check_id = $this->input->get('check_id');
		$tags = $this->input->get('tags');
		$action = $this->input->get('action');
		
		// Retrieve check record. If fails, exit
		$check = $this->foursquare_check->getCheckById($check_id);
		if (!$check):
			header('Content-type: application/json');
			print json_encode(array('error' => 'Could not load check data.'));
			exit;
		endif;
		
		// Switch based on action flag
		switch ($action):
		
			// Add Tag(s)
			case 'add_tag':
			case 'add_tags':
			
				// Ensure a check ID is set
				if (!$check_id || !$tags):
					$result = array('error' => 'Requires both a check ID and tag(s).'); break;
				endif;
				
				$result = $this->foursquare_check->addTagBulk($check_id, $tags);
					
				break;
				
			// Remove Tag
			case 'remove_tag':
			
				// Ensure a check ID and tag list are set
				if (!$check_id || !$tags):
					$result = array('error' => 'Requires both a check ID and tag(s).'); break;
				endif;
				
				$result = $this->foursquare_check->removeTag($check_id, $tags);
			
				break;
			
			// Get list of tags
			case 'list_tags':
			
				// Ensure a check ID is set
				if (!$check_id):
					$result = array('error' => 'Requires a check ID.'); break;
				endif;
			
				$result = $this->foursquare_check->getTagsByCheckId($check_id);
				
				break;
			
			// Error
			default:
				$result = array('error' => 'Did not recognize action or was not provided.');
		endswitch;
		
		header('Content-type: application/json');
		print json_encode($result);
		
	}


	/**
	 * AJAX Live Check Data
	 */
	public function ajax_live_check_data() {
		
		// Setup controller
		$this->layout = null;
		$this->setup();
		
		// Load model
		$this->load->model('foursquare_check_log_live');
		
		$check_id = $this->uri->segment(3);
		
		if (!is_numeric($check_id))
			die(json_encode(array('error' => 'Invalid check_id provided')));
		
		$live_data = $this->foursquare_check_log_live->getCheckData($check_id);
		
		foreach ($live_data as $k => $row):
			$return[] = array( ($row->insert_ts), (int) $row->herenow_friends, (int) $row->herenow_other, (int) $row->herenow_total );
		endforeach;

		header('Content-type: application/json');
		echo json_encode($return);
		
	}

	public function ajax_check_status() {
		
		$this->layout = null;
		
		$check_id = $this->uri->segment(3);
		$check = $this->foursquare_check->getCheckById($check_id);
		if (!isset($check->id))
			show_error('Check could not be found.', 404);
		$status = $this->input->get('status');

		// Switch
		if ($status == '1')
			$result = $this->foursquare_check->activate($check_id);
		elseif ($status == '0')
			$result = $this->foursquare_check->deactivate($check_id);
		
		if ($result == true)
			$out = 'success';
		else
			$out = 'failed';
			
		header('Content-type: application/json');
		echo json_encode($out);
		
	}

	

	/* *** Command line tools *** */
	
	/**
	 * Check Venues
	 */
	public function check_venues($type = 'live') {
		
		// Deny access from non-CLI interfaces
		if (!$this->input->is_cli_request())
			show_error('Access denied.', 403);
		
		// Strip layout
		$this->layout = null;
		$this->console_log('Starting Foursquare venue check process ...');
		
		// Switch based on type of check being executed
		switch ($type):
		
			/**
			 * This check polls the venue on a daily basis to build a picture of
			 * day-over-day changes in stats.
			 */
			case 'daily':
				$this->console_log('Switching mode to "daily" ...');
				$this->load->model('foursquare_check_log');
				
				$this->console_log('Loading a batch of checks to run ...');
				$batch = $this->foursquare_check_log->getBatch(10);
				
				// Stop if batch is empty
				if (!is_array($batch) || count($batch) == 0)
					$this->console_log('Ending foursquare check process (no items to run).', true);
				
				// Beginning of process
				$this->console_log('Iterating through batch ...');
				foreach($batch as $item):

					// Load token that will be used with transaction, avoid multiple lookups
					$this->console_log('Obtaining token for check #' . $item->id . ' ...');
					if (!isset($tokens[$item->user_id])):
						$this->console_log('Looking up token to use for this check ...');
						$foursquare_token = $this->foursquare_token->getTokenByUserId($item->user_id);
						$tokens[$item->user_id] = $foursquare_token;
					else:
						$this->console_log('Re-using previously loaded token ...');
						$foursquare_token = $tokens[$item->user_id];
					endif;
					
					// If token is not present, don't bother trying to run through it.
					if (!$foursquare_token):
						$this->console_log('Could not find a token for this user! Deactivating check ...');
						$this->foursquare_check->deactivate($item->id);
						continue;
					endif;
					
					// Set the token for requests, make the request
					$this->console_log(sprintf('Requesting statistics for "%s" (%s)', $item->check_title, $item->venue_id ));
					$this->ignitefoursquare->SetAccessToken($foursquare_token[0]);
					$venue = json_decode($this->ignitefoursquare->GetPrivate(sprintf('/venues/%s', $item->venue_id)));
					if (isset($venue->meta->code) && $venue->meta->code == 403):
						$this->console_log('API rate limit was hit. Sleeping for 60 seconds, then re-attempting last request.');
						sleep(60);
						$venue = json_decode($this->ignitefoursquare->GetPrivate(sprintf('/venues/%s', $item->venue_id)));
					endif;
					$log_data = isset($venue->response->venue->stats) ? $venue->response->venue->stats : false;
					
					// If statistical information present, log it.
					if (is_object($log_data)):
						
						// Cast as an array
						$log_data = (array) $log_data;
						$log_data['check_id'] = $item->id;
						
						// This was moved out of the stats array, so read from ->photos->count instead
						$log_data['photoCount'] = isset($venue->response->venue->photos->count) ? $venue->response->venue->photos->count : 0;
					
						// Add log entry
						$this->console_log('Adding log entry ...');
						$this->foursquare_check_log->addLogEntry($log_data);
					else:
						$this->console_log('This check did not return any statistics. Could be an API error. Skipping.');
						continue;
					endif;
					
				endforeach;
				
				break;
				
			/**
			 * This check is meant to be run more frequently at regular intervals
			 * and will keep up with how many people are 'Here Now'.
			 */
			case 'live':
				$this->console_log('Switching mode to "live" ...');
				$this->load->model('foursquare_check_log_live');
				
				$this->console_log('Loading a batch of checks to run ...');
				$batch = $this->foursquare_check_log_live->getBatch(100);
				
				// Stop if batch is empty
				if (!is_array($batch) || count($batch) == 0)
					$this->console_log('Ending foursquare check process (no items to run).', true);
				
				// Beginning of process
				$this->console_log('Iterating through batch ...');
				foreach($batch as $item):

					// Load token that will be used with transaction, avoid multiple lookups
					$this->console_log('Obtaining token for check #' . $item->id . ' ...');
					if (!isset($tokens[$item->user_id])):
						$this->console_log('Looking up token to use for this check ...');
						$foursquare_token = $this->foursquare_token->getTokenByUserId($item->user_id);
						$tokens[$item->user_id] = $foursquare_token;
					else:
						$this->console_log('Re-using previously loaded token ...');
						$foursquare_token = $tokens[$item->user_id];
					endif;
					
					// If token is not present, don't bother trying to run through it.
					if (!$foursquare_token):
						$this->console_log('Could not find a token for this user! Deactivating check ...');
						$this->foursquare_check->deactivate($item->id);
						continue;
					endif;
					
					// Set the token for requests, make the request
					$this->console_log(sprintf('Requesting statistics for "%s" (%s)', $item->check_title, $item->venue_id ));
					$this->ignitefoursquare->SetAccessToken($foursquare_token[0]);
					$venue = json_decode($this->ignitefoursquare->GetPrivate(sprintf('/venues/%s', $item->venue_id)));
					if (isset($venue->meta->code) && $venue->meta->code == 403):
						$this->console_log('API rate limit was hit. Sleeping for 60 seconds, then re-attempting last request.');
						sleep(60);
						$venue = json_decode($this->ignitefoursquare->GetPrivate(sprintf('/venues/%s', $item->venue_id)));
					endif;
					$log_data = isset($venue->response->venue->hereNow) ? $venue->response->venue->hereNow : false;
					
					// If statistical information present, log it.
					if (is_object($log_data)):
						
						// Cast as an array
						$log_data = (array) $log_data;
						$log_data['check_id'] = $item->id;
					
						// Add log entry
						$this->console_log('Adding log entry ...');
						$this->foursquare_check_log_live->addLogEntry($log_data);
					else:
						$this->console_log('This check did not return any statistics. Could be an API error. Skipping.');
						continue;
					endif;

				endforeach;
				
				break;
			
			/**
			 * Invalid argument passed to script.
			 */
			default:
				$this->console_log('Invalid argument. Expected "daily" or "live".', true);
				break;

		endswitch;

		$this->console_log('Ending Foursquare veneue check process (complete).');

		return;
		
	}

	/*** Private methods ***/
	
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
		$data['userInfo'] = (isset($userInfo->response->user)) ? $userInfo->response->user : false;

		return $data;
		
	}
	
	/**
	 * Console Log Message
	 *
	 * @param string $message 
	 * @param boolean $die 
	 */
	private function console_log($message = null, $die = false) {
		printf('[%s] %s' . PHP_EOL, date('Y-m-d h:i:s'), $message);
		
		// If die flag set, stop processing
		if ($die)
			exit;
		
	}


}
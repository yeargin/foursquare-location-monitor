<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Packages_controller extends CI_Controller {
	
	public $layout = 'default';
	
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		
		$this->load->model('user');
		$this->load->model('package');
		$this->load->model('foursquare_check');
		$this->load->library('form_validation');
		$this->load->library('email');
	}

	public function index() {
		$user = unserialize($this->session->userdata('user'));
		$profile = $this->user->getUserById($user->id);
		$data['profile'] = $profile;
		
		$data['packages'] = $this->package->getPackages();

		$data['usage'] = $this->foursquare_check->remainingChecksCount();

		$data['page_title'] = 'Packages';
		
		$this->load->view('account/packages', $data);
	}

	public function change() {
		$user = unserialize($this->session->userdata('user'));
		$profile = $this->user->getUserById($user->id);

		// Load desired package details
		$package_id = $this->input->get('package_id');
		$package = $this->package->getPackageById($package_id);
		if (!$package)
			show_error('Could not find desired package.', 404);
		$data['package'] = $package;

		// Check if change would cause an overage
		$usage = $this->foursquare_check->remainingChecksCount();
		$data['usage'] = $usage;
		$overage = ($usage['checks']->active_checks > $package->check_limit) ? $usage['checks']->active_checks - $package->check_limit : false;
		$data['overage'] = $overage;

		// Prevent a change to same package
		if ($usage['package']->id == $package->id)
			show_error('You are already using this package.');

		// Determine type of change
		$change_type = ($usage['package']->check_limit < $package->check_limit) ? 'Upgrade' : 'Downgrade';

		if ($this->input->post('confirm_change') == 'true'):
			$this->user->changePackage($user->id, $package->id);
			$this->session->set_flashdata('message', sprintf('%s to %s package complete!', $change_type, $package->name));
			redirect('profile');
		endif;

		$data['page_title'] = 'Confirm Package ' . $change_type;
		$this->load->view('account/package_change', $data);
	}
	
}
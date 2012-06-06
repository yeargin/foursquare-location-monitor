<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install_controller extends CI_Controller {

	public $layout = 'default';

	public function __construct() {
		parent::__construct();
		
		// This controller cannot be used after installation complete
		$query = $this->db->query('SHOW TABLES LIKE \'migrations\' ');
		if ($query->row())
			redirect('admin/upgrade_system');
		
		// Load migration library
		$this->load->library('migration');
		
	}

	public function index() {

		if ( ! $this->migration->current()):
			show_error($this->migration->error_string());
		endif;

		// Show view
		$data['application_name'] = $this->config->item('application_name');
		$this->load->view('admin/install_next_steps', $data);

	}

}
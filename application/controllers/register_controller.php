<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register_controller extends CI_Controller {
	
	public $layout = 'default';
	
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		
		$this->load->model('user');
		$this->load->model('beta_key');
		$this->load->library('form_validation');
		$this->load->library('email');
	}
	
	/**
	 * Index
	 */
	public function index() {
		$this->new_user();
	}
	
	public function email_test() {
		$this->send_welcome_email(1);
	}
	
	/**
	 * New User
	 */
	public function new_user() {
		
		// Find the provided beta key if given
		if ($this->input->get('beta_key'))
			$beta_key = $this->input->get('beta_key');
		elseif ($this->input->post('beta_key'))
			$beta_key = $this->input->post('beta_key');
		else
			$beta_key = null;
			
		// If beta key is required, validate it before proceeding
		if ($this->config->item('require_beta_key') == true && !$this->beta_key->validate($beta_key)):
			$this->session->set_flashdata('message', 'The beta key provided was invalid.');
			redirect('register/beta');
		endif;
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-error">', '<a class="close" href="#">&times;</a></div>');
		$this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		// If submission valid, run 
		if ($this->form_validation->run() != FALSE)
			$this->new_user_post();
		
		$profile = $this->user->instance();
		$profile->username = $this->input->post('username');
		$profile->first_name = $this->input->post('first_name');
		$profile->last_name = $this->input->post('last_name');
		$profile->email = $this->input->post('email');
		
		$data['profile'] = $profile;
		$data['beta_key'] = __($beta_key);
		
		$data['page_title'] = 'Register for an account';
		$this->load->view('register/form', $data);
	}
	
	/**
	 * Beta
	 */
	public function beta() {

		// If out of beta, disable this method
		if ($this->config->item('require_beta_key') != true):
			$this->session->set_flashdata('message', 'General public registrations are now open. Beta key is not required.');
			redirect('register');
		endif;
		
		$data['beta_key'] = $this->input->get('beta_key');
		$data['page_title'] = 'Enter Beta Key';
		
		$this->load->view('register/beta', $data);

	}
	
	/* *** Private Methods *** */
	
	/**
	 * New User Post
	 */
	private function new_user_post() {
		$user = $this->user->createNewUserFromPost();
		if ((is_object($user)) && $user->id > 0):
			$this->send_welcome_email($user);
			$this->beta_key->markKeyAsUsed($this->input->post('beta_key'));
			$this->session->set_flashdata('message', 'New user created! Check your e-mail for login information.');
			redirect('/');
		endif;
	}
	
	/**
	 * Send Welcome E-mail
	 */
	private function send_welcome_email($user) {
		$data['user'] = $user;
		$data['application_name'] = $this->config->item('application_name');
		
		// Create message
		$this->email->from($this->config->item('application_email'), $this->config->item('application_name'));
		$this->email->to($user->email);
		$this->email->subject('Welcome to ' . $this->config->item('application_name') . '!');
		$this->email->message($this->load->view('emails/new_user_message', $data, true));

		return $this->email->send();
	}
	
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Login
*/
class Login_controller extends CI_Controller
{
	public $layout = 'default';
	
	public function index() {
		$data['redirect'] = ($this->input->get('redirect')) ? $this->input->get('redirect') : '';
		$data['beta_only'] = $this->config->item('require_beta_key');
		$data['page_title'] = 'Sign In';
		$this->load->view('login/form', $data);
	}

	public function post() {
		$status = $this->user->login();
		if ($status):
			if ($this->input->post('redirect') != ''):
				redirect($this->input->post('redirect'));
			else:
				redirect('/');
			endif;
		else:
			$this->session->set_flashdata('message', 'Login failed!');
			redirect('/login/?login_failed=1');
		endif;
	}

	public function logout() {
		$this->user->logout();
		$this->session->set_flashdata('message', 'Logged out!');
		redirect('/');
	}
	
	public function forgot_password() {
		
		if ($this->input->post('username')):
			$username = $this->input->post('username');
			if ($this->user->userExists($username)):
				$this->send_password_reset_email($username);
				$this->session->set_flashdata('message', 'Check your e-mail for reset instructions.');
			else:
				$this->session->set_flashdata('message', 'An error ocurred.');
			endif;
				
			redirect('/login/forgot_password');
				
		endif;
		
		
		$data = array();
		$this->load->view('login/forgot_password_form', $data);
	}
	
	public function login_reset() {
		$key = $this->uri->segment(3);
		$this->user->usePasswordResetKey($key);
		
		if ($user->isLoggedIn()):
			redirect('profile/change_password');
		else:
			redirect();
		endif;
		
	}
	
	public function resume_admin() {
		$this->user->adminResumeAdmin();
		redirect('admin');
	}
	
	/* Private Methods */
	
	/**
	 * Send Password Reset E-mail
	 */
	private function send_password_reset_email($username = '') {
		$data['user'] = $this->user->getUserByUsername($username);
		$data['application_name'] = $this->config->item('application_name');
		$data['reset_key'] = $this->user->getPasswordResetKey($username);
		
		// Create message
		$this->email->from($this->config->item('application_email'), $this->config->item('application_name'));
		$this->email->to($user->email);
		$this->email->subject('Your login for ' . $this->config->item('application_name') . '!');
		$this->email->message($this->load->view('emails/reset_password_message', $data, true));

		return $this->email->send();
	}
	
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Login
*/
class Login_controller extends CI_Controller
{
	public $layout = 'default';
	
	/**
	 * Login
	 */
	public function index() {
		// If already logged in, ignore
		if ($this->user->isLoggedIn())
			redirect('dashboard');

		$data['redirect'] = ($this->input->get('redirect')) ? $this->input->get('redirect') : '';
		$data['beta_only'] = $this->config->item('require_beta_key');
		$data['page_title'] = 'Sign In';
		
		$data['sidebar_content'] = $this->load->view('login/_sidebar', $data, true);
		$this->load->view('login/form', $data);
	}

	/**
	 * Post Login
	 */
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

	/**
	 * Logout
	 */
	public function logout() {
		$this->user->logout();
		$this->session->set_flashdata('message', 'Logged out!');
		redirect('/');
	}
	
	/**
	 * Forgot Password
	 */
	public function forgot_password() {
		// If already logged in, ignore
		if ($this->user->isLoggedIn())
			redirect('dashboard');
		
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
		
		$data['page_title'] = 'Forgot Password';
		
		$this->load->view('login/forgot_password_form', $data);
	}
	
	/**
	 * Login Reset
	 */
	public function login_reset() {
		// If already logged in, ignore
		if ($this->user->isLoggedIn())
			redirect('dashboard');
		
		// Extract and process activation data
		$activation_data = explode(':', $this->input->get('reset'));
		$username = $activation_data[0];
		$key = $activation_data[1];
		$this->user->usePasswordResetKey($username, $key);
		
		// Check user object again
		if ($this->user->isLoggedIn()):
			$this->session->set_flashdata('message', 'Please update your password.');
			redirect('profile/change_password');
		else:
			$this->session->set_flashdata('message', 'An error ocurred.');
			redirect('login/forgot_password');
		endif;
		
	}
	
	/**
	 * Resume Admin Role
	 */
	public function resume_admin() {
		$this->user->adminResumeAdmin();
		redirect('admin');
	}
	
	/* Private Methods */
	
	/**
	 * Send Password Reset E-mail
	 */
	private function send_password_reset_email($username = '') {
		$this->load->library('email');
		
		$reset_data = $this->user->setPasswordResetKey($username);
		if (!$reset_data)
			show_error('Could not reset password for user.');

		$data['application_name'] = $this->config->item('application_name');
		$data['activation_key'] = $reset_data['activation_key'];
		$data['user'] = $reset_data['user'];
		
		// Create message
		$this->email->from($this->config->item('application_email'), $this->config->item('application_name'));
		$this->email->to($data['user']->email);
		$this->email->subject('Your login for ' . $this->config->item('application_name') . '!');
		$this->email->message($this->load->view('emails/reset_password_message', $data, true));

		return $this->email->send();
	}
	
}

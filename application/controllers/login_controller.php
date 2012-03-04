<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Login
*/
class Login_controller extends CI_Controller
{
	public $layout = 'default';
	
	public function index() {
		$data['redirect'] = ($this->input->get('redirect')) ? $this->input->get('redirect') : '';
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
	
}

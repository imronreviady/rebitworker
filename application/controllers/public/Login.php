<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if ($this->auth->is_logged_in())
			redirect('dashboard');
	}

	public function index()
	{
		echo "adasad";
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/public/Login.php */
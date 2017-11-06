<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	protected $vars = [];

	public function __construct() {
		parent::__construct();
		$timezone = NULL !== $this->session->userdata('timezone') ? $this->session->userdata('timezone') : 'Asia/Jakarta';
		date_default_timezone_set($timezone);
		if (is_dir(FCPATH.'install')) {
			redirect(base_url().'install','refresh');
		}
	}

}

require_once(APPPATH.'/core/Public_Controller.php');
require_once(APPPATH.'/core/Admin_Controller.php');

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
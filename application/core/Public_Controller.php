<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Public_Controller extends MY_Controller {

	protected $vars = [];

	public function __construct()
	{
		parent::__construct();
			
		$this->load->helper(['text', 'blog_helper']);

		if ($this->session->userdata('site_maintenance') == 'true' && 
			$this->session->userdata('site_maintenance_end_date') >= date('Y-m-d') && 
			$this->uri->segment(1) !== 'login') {
			redirect('under-construction');
		}

		if ($this->session->userdata('site_cache') == 'true' && intval($this->session->userdata('site_cache_time')) > 0) {
			$this->output->cache($this->session->userdata('site_cache_time'));
		}

		$this->load->model('m_menus');
		$this->vars['menus'] = $this->m_menus->get_parent_menu();
	}

}

/* End of file Public_Controller.php */
/* Location: ./application/core/Public_Controller.php */
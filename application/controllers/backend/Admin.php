<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
	}

	public function index() {
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'login', 'refresh');
        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'admin/dashboard', 'refresh');
	}

	public function dashboard() {
		if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name'] = 'dashboard';
        $page_data['page_title'] = get_phrase('admin_dashboard');
        $this->load->view('backend/index', $page_data);
	}

	public function authors($task = '', $author_id = '') {
		if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        if ($task == 'create') {
        	$this->m_admin->save_author_info();
        	$this->session->set_flashdata('message', get_phrase('author_info_saved_successfuly'));
        	redirect(base_url() . 'admin/authors');
        }

        if ($task == 'update') {
        	$this->m_admin->update_author_info($author_id);
        	$this->session->set_flashdata('message', get_phrase('author_info_updated_successfuly'));
        	redirect(base_url() . 'admin/authors');
        }

        if ($task == 'delete ') {
        	$this->m_admin->delete_author_info($author_id);
        	redirect(base_urld() . 'admin/authors');
        }

        $data['author_info'] = $this->m_admin->select_author_info();
        $data['page_name'] = 'manage_author';
        $data['page_title'] = get_phrase('authors');
        $this->load->view('backend/index', $data);
	}

}

/* End of file Admin.php */
/* Location: ./application/controllers/backend/Admin.php */
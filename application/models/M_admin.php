<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_admin extends CI_Model {

	public function select_author_info()
	{
		$json = $this->db->get('author')->result_array();
		return json_encode($json);
	}

}

/* End of file M_admin.php */
/* Location: ./application/models/M_admin.php */
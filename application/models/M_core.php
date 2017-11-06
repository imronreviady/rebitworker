<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_core extends CI_Model {

	public function get_image_url($type = '', $id = '')
	{
		if (file_exists('uploads/' . $type . '_image/' . $id . '.jpg'))
            $image_url = base_url() . 'uploads/' . $type . '_image/' . $id . '.jpg';
        else
            $image_url = base_url() . 'uploads/avatar.jpg';
        return $image_url;
	}

}

/* End of file M_core.php */
/* Location: ./application/models/M_core.php */
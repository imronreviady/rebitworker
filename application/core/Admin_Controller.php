<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Controller extends MY_Controller {

	protected $pk;

	protected $table;

	public function __construct()
	{
		parent::__construct();
		
		$this->auth->restrict();

		if (!in_array($this->uri->segment(1), $this->session->userdata('user_privileges'))) {
			redirect(base_url());
		}

		// $this->output->enable_profiler();
	}

	public function delete() {
		$response = [];
		$response['action'] = 'delete';
		$response['type'] = 'warning';
		$response['message'] = 'not_selected';
		$ids = explode(',', $this->input->post($this->pk));
		if (count($ids) > 0) {
			if($this->model->delete($ids, $this->table)) {
				$response = [
					'type' => 'success',
					'message' => 'deleted',
					'id' => $ids
				];
			} else {
				$response = [
					'type' => 'error',
					'message' => 'not_deleted'
				];
			}
		}

		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function restore() {
		$response = [];
		$response['action'] = 'restore';
		$response['type'] = 'warning';
		$response['message'] = 'not_selected';
		$ids = explode(',', $this->input->post($this->pk));
		if (count($ids) > 0) {
			if($this->model->restore($ids, $this->table)) {
				$response = [
		        	'action' => 'restore',
					'type' => 'success',
					'message' => 'restored',					
					'id' => $ids
				];
			} else {
				$response = [
					'action' => 'restore',
					'type' => 'error',
					'message' => 'not_deleted'
				];
			}
		}

		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT))
			->_display();
		exit;
	}

	public function email_check($str, $id) {
		$query = $this->model->is_email_exist($str, $id);
		if ($query['is_exist'] === TRUE) {
			$this->form_validation->set_message('email_check', 'Email sudah digunakan oleh ' . $query['used_by'] . '. Silahkan gunakan email lain');
			return FALSE;
		}
		return TRUE;
	}

	protected function post_image_upload_handler($id) {
		$response = [];
		$config['upload_path'] = './media_library/images/';
		$config['allowed_types'] = 'jpg|png|jpeg|gif';
		$config['max_size'] = 0;
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('post_image')) {
			$response['type'] = 'error';
			$response['message'] = $this->upload->display_errors();
		} else {
			$file = $this->upload->data();
			// chmood new file
			@chmod(FCPATH.'media_library/images/'.$file['file_name'], 0777);
			// resize new image
			$this->post_image_resize_handler(FCPATH.'media_library/images', $file['file_name']);
			$response['type'] = 'success';
			$response['file_name'] = $file['file_name'];
			if ($id > 0) {
				$query = $this->model->RowObject($this->table, $this->pk, $id);
				// chmood old file
				@chmod(FCPATH.'media_library/posts/thumbnail/'.$query->post_image, 0777);
				@chmod(FCPATH.'media_library/posts/medium/'.$query->post_image, 0777);
				@chmod(FCPATH.'media_library/posts/large/'.$query->post_image, 0777);
				// unlink old file
				@unlink(FCPATH.'media_library/posts/thumbnail/'.$query->post_image);
				@unlink(FCPATH.'media_library/posts/medium/'.$query->post_image);
				@unlink(FCPATH.'media_library/posts/large/'.$query->post_image);
			}
		}
		return $response;
	}

	private function post_image_resize_handler($source, $file_name) {
		$this->load->library('image_lib');
		// Thumbnail Image
		$thumbnail['image_library'] = 'gd2';
		$thumbnail['source_image'] = $source .'/'. $file_name;
		$thumbnail['new_image'] = './media_library/posts/thumbnail/'. $file_name;
		$thumbnail['maintain_ratio'] = false;
		$thumbnail['width'] = (int) $this->session->userdata('post_image_thumbnail_width');
		$thumbnail['height'] = (int) $this->session->userdata('post_image_thumbnail_height');
		$this->image_lib->initialize($thumbnail);
		$this->image_lib->resize();
		$this->image_lib->clear();
		// Medium Image
		$medium['image_library'] = 'gd2';
		$medium['source_image'] = $source .'/'. $file_name;
		$medium['new_image'] = './media_library/posts/medium/'. $file_name;
		$medium['maintain_ratio'] = false;
		$medium['width'] = (int) $this->session->userdata('post_image_medium_width');
		$medium['height'] = (int) $this->session->userdata('post_image_medium_height');
		$this->image_lib->initialize($medium);
		$this->image_lib->resize();
		$this->image_lib->clear();
		// Large Image
		$large['image_library'] = 'gd2';
		$large['source_image'] = $source .'/'. $file_name;
		$large['new_image'] = './media_library/posts/large/'. $file_name;
		$large['maintain_ratio'] = false;
		$large['width'] = (int) $this->session->userdata('post_image_large_width');
		$large['height'] = (int) $this->session->userdata('post_image_large_height');
		$this->image_lib->initialize($large);
		$this->image_lib->resize();
		$this->image_lib->clear();
		// Remove Original File
		@unlink($source .'/'. $file_name);
	}

	public function tinymce_upload_handler() {
		$config['upload_path'] = './media_library/posts/';
		$config['allowed_types'] = 'jpg|png|jpeg';
		$config['max_size'] = 0;
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('file')) {
			$this->output->set_header('HTTP/1.0 500 Server Error');
			exit;
		} else {
			$file = $this->upload->data();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode(['location' => base_url().'media_library/posts/'.$file['file_name']]))
				->_display();
			exit;
		}
	}

}

/* End of file Admin_Controller.php */
/* Location: ./application/core/Admin_Controller.php */
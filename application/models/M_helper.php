<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_helper extends CI_Model {

	public static $pk = 'id';

	public function __construct() {
		parent::__construct();
	}

	public function insert($table, array $fill_data) {
		$this->db->trans_start();
		$this->db->insert($table, $fill_data);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function update($id, $table, array $fill_data) {
		$this->db->trans_start();
		$this->db->where(self::$pk, $id)->update($table, $fill_data);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function delete_permanently($key, $value, $table) {
		$this->db->trans_start();
		$this->db->where_in($key, $value)->delete($table);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function delete(array $ids, $table) {
		$this->db->trans_start();
		$this->db->where_in(self::$pk, $ids)
			->update($table, [
				'is_deleted' => 'true',
				'deleted_by' => $this->session->userdata('id'),
				'deleted_at' => date('Y-m-d H:i:s')
			]
		);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function truncate($table) {
		$this->db->trans_start();
		$this->db->truncate($table);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function restore(array $ids, $table) {
		$this->db->trans_start();
		$this->db->where_in(self::$pk, $ids)
			->update($table, [
				'is_deleted' => 'false',
				'restored_by' => $this->session->userdata('id'),
				'restored_at' => date('Y-m-d H:i:s')
			]
		);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function isValExist($key, $value, $table) {
		$count = $this->db
			->where($key, $value)
			->count_all_results($table);
		return $count > 0;
	}

	public function RowObject($table, $key, $value) {
		return $this->db
			->where($key, $value)
			->get($table)
			->row();
	}

	public function ResultsObject($table) {
		return $this->db->get($table)->result();
	}

	public function RowArray($table, $key, $value) {
		return $this->db
			->where($key, $value)
			->get($table)
			->row_array();
	}

	public function ResultsArray($table) {
		return $this->db->get($table)->result_array();
	}

	public function clear_expired_session() {
		$this->db->query("DELETE FROM `_sessions` WHERE DATE_FORMAT(FROM_UNIXTIME(timestamp), '%Y-%m-%d') < CURRENT_DATE");
	}

	public function is_email_exist($email, $id) {
		// Var Initialize
		$response['is_exist'] = false;
		$response['used_by'] = '';

		// Check From freelancer
		$freelancer = $this->db
			->where('email', $email)
			->where('id !=', $id)
			->count_all_results('freelancers');
		if ($freelancer > 0) {
			$response['is_exist'] = true;
			$response['used_by'] = 'Freelancer';
			return $response;
		}

		// Check From client
		$client = $this->db
			->where('email', $email)
			->where('id !=', $id)
			->count_all_results('clients');
		if ($client > 0) {
			$response['is_exist'] = true;
			$response['used_by'] = 'Client';
			return $response;
		}

		// Check from users freelancers
		$user_freelancer = $this->db
			->where('user_type', 'freelancer')
			->where('user_email', $email)
			->where('profile_id !=', $id)
			->count_all_results('users');
		if ($user_freelancer > 0) {
			$response['is_exist'] = true;
			$response['used_by'] = 'Freelancer';
			return $response;
		}

		// Check from users clients
		$user_client = $this->db
			->where('user_type', 'client')
			->where('user_email', $email)
			->where('profile_id !=', $id)
			->count_all_results('users');
		if ($user_employee > 0) {
			$response['is_exist'] = true;
			$response['used_by'] = 'Client';
			return $response;
		}

		// Check from users administrator or super users
		$user = $this->db
			->where('user_email', $email)
			->where('id !=', $id)
			->where_in('user_type', ['administrator', 'super_user'])
			->count_all_results('users');
		if ($user > 0) {
			$response['is_exist'] = true;
			$response['used_by'] = 'Administrator';
			return $response;
		}
		return $response;
	}

}

/* End of file M_helper.php */
/* Location: ./application/models/M_helper.php */
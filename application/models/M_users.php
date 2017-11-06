<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_users extends CI_Model {

	public static $pk = 'id';

	public static $table = 'users';

	public function __construct()
	{
		parent::__construct();
	}

	public function logged_in($user_name) 
	{
		return $this->db
			->select('id
				, user_name
				, user_password
				, user_full_name
				, user_email
				, user_url
				, user_registered
				, user_group_id
				, user_type
				, profile_id
				, forgot_password_key
				, is_active
				, is_logged_in
				, last_logged_in
				, ip_address
			')
         ->where('user_name', $user_name)
         ->where('is_active', 'true')
         // ->where('is_logged_in', 'false')
         ->limit(1)
         ->get(self::$table);
	}

}

/* End of file M_users.php */
/* Location: ./application/models/M_users.php */
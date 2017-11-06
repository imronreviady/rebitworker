<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{

	public $CI;
	
	public function __construct()
	{
		$this->CI = &get_instance();
        $this->CI->load->model(['m_users', 'm_user_privileges']);
	}

	public function logged_in($user_name, $user_password, $ip_address) 
	{
        $login_attempts = $this->check_login_attempts($ip_address);
        if ($login_attempts) {
            $query = $this->CI->m_users->logged_in($user_name, $user_password);
            if ($query->num_rows() === 1) {
                $data = $query->row();
                if (password_verify($user_password, $data->user_password)) {
                    $session_data = [];
                    $session_data['id'] = $data->id;
                    $session_data['user_name'] = $data->user_name;
                    $session_data['user_full_name'] = $data->user_full_name;
                    $session_data['user_email'] = $data->user_email;
                    $session_data['user_url'] = $data->user_url;
                    $session_data['user_registered'] = $data->user_registered;
                    $session_data['user_group_id'] = $data->user_group_id;
                    $session_data['user_type'] = $data->user_type;
                    $session_data['profile_id'] = $data->profile_id;
                    $session_data['is_logged_in'] = true;
                    $session_data['user_privileges'] = $this->CI->m_user_privileges->module_by_user_group_id($data->user_group_id, $data->user_type);
                    $this->CI->session->set_userdata($session_data);
                    $this->last_logged_in($data->id);
                    return true;
                }
                return false;
            }
            $this->increase_login_attempts($ip_address);
            return false;
        }
        return false;
    }
}
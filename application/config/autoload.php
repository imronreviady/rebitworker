<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages'] = array();

$autoload['libraries'] = array('database', 'session', 'pagination', 'xmlrpc', 'form_validation', 'email', 'upload');

$autoload['drivers'] = array();

$autoload['helper'] = array('url', 'file', 'form', 'security', 'string', 'inflector', 'directory', 'download', 'multi_language_helper');

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array('m_core' => 'core');

/* End of file autoload.php */
/* Location: ./application/config/autoload.php */
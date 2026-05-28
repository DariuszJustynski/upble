<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class UserModel extends MY_Model
{
	function __construct()
	{
		$this->table_name = "users";
	}
}

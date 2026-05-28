<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Reports extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->table_name = 'report';
	}
	
	public function del($id)
	{
		if(is_array($id))
		{
			$this->db->where_in('id',$id);
		}
		else
		{
			$this->db->where('id',$id);
		}
		if($this->db->delete($this->table_name))
		{
			return true;
		}
		return false;
	}
	
}
?>
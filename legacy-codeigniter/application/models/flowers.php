<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Flowers extends MY_model
{
	protected $table_name='flower';
	
	public function hasSent($sender,$objectid,$idtype='review')
	{
		$this->db->select('objectid');
		$this->db->where('sender',$sender);
		$this->db->where('objectid',$objectid);
		$this->db->where('idtype',$idtype);
		$query = $this->db->get($this->table_name);
		if($row=$query->row())
		{
			return true;
		}
		return false;
	}
	
	public function del($objectid,$idtype='review')
	{
		$this->db->where('objectid',$objectid);
		$this->db->where('idtype',$idtype);
		$this->db->delete($this->table_name);
	}
	
}

?>
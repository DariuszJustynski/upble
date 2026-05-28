<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
Class Messages extends MY_Model
{
	protected $table_name="message";
	function __construct()
	{
		parent::__construct();
	}
	
	public function fetchList($limit=10,$offset=0,$conditions = '',$order_by = 'id',$order = 'desc')
	{
		$messages=array();
		if($conditions['type']=='inbox')
		{
			$this->db->select("id,username,title,unread,created_at");
			$this->db->where('inbox',1);
			$this->db->where('touid',$conditions['uid']);
		}
		else
		{
			$this->db->select("id,tousername as username,title,created_at");
			$this->db->where('sentbox',1);
			$this->db->where('uid',$conditions['uid']);
		}
		$this->db->order_by($order_by,$order);
		$query=$this->db->get($this->table_name,$limit,$offset);
		foreach($query->result() as $row)
		{
			$messages[]=$row;
		}
		
		return $messages;
	
	}
	
	public function getCount($conditions = '')
	{
		$count=0;
		$this->db->select('count(id) as num');
		if($conditions['type']=='inbox')
		{
			$this->db->where('inbox',1);
			$this->db->where('touid',$conditions['uid']);
		}
		else 
		{
			$this->db->where('sentbox',1);
			$this->db->where('uid',$conditions['uid']);			
		}
		
		$query=$this->db->get($this->table_name);
		if($row=$query->row())
		{
			$count=$row->num;
		}
		return $count;
	
	}
	

}
?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class MY_Model extends CI_Model
{
	protected $table_name="";
	protected $table_key = 'id';
	function __construct()
	{
		parent::__construct();
		
	}
	
	public function add($data,$returnid = true)
	{
		if($this->db->insert($this->table_name,$data))
		{
			return $returnid ? $this->db->insert_id(): true;
		}
		else 
		return false;
	}
	
	public function get($cons)
	{
		$item = false;
		if(is_array($cons))
		{
			foreach($cons as $k => $v)
			{
				$this->db->where($k,$v);
			}
		}
		else
			$this->db->where($this->table_key, $cons);
		
		$query=$this->db->get($this->table_name);
		if($row=$query->row())
		{
			
			$item = $row;
		}
		
		return $item;
		
	}
	public function update($data,$id)
	{
		$this->db->where($this->table_key,$id);
		if($this->db->update($this->table_name,$data))
		{
			return true;
		}
		return false;
		
	}
	
	public function delete($id)
	{
		$this->db->where($this->table_key,$id);
		if($this->db->delete($this->table_name))
			return true;
		return false;
	}
	
	public function getCount($conditions='')
	{
		$num = 0;
		if(is_string($conditions))
		{
			$sql = 'select count(*) as num from '.$this->table_name;
			if($conditions != '')
			{
				$sql .= ' where '.$conditions;
			}
			$query = $this->db->query($sql);
			
		}
		else
		{
			$this->db->select('count(*) as num');
			foreach($conditions as $k => $v)
			{
				$this->db->where($k,$v);
			}
			
			$query = $this->db->get($this->table_name);
		}
		if($row = $query->row())
		{
			$num = $row->num;
		}
		return $num;
		
	}
	
	public function fetchList($limit=10,$offset = 0,$conditions='',$order_by = 'id',$order = 'desc')
	{
		
		$list = array();
		if(is_string($conditions))
		{
			$sql = 'select * from '.$this->table_name;
			if($conditions != '')
			{
				$sql .= ' where '.$conditions;
			}
			$sql .= ' order by '.$order_by.' '.$order.' limit '.$offset.','.$limit;
			$query = $this->db->query($sql);
			
		}
		else
		{
			foreach($conditions as $k => $v)
			{
				$this->db->where($k,$v);
			}
			$this->db->order_by($order_by,$order);
			$query = $this->db->get($this->table_name,$limit,$offset);
		}
	
		foreach($query->result() as $row)
		{
			$list[$row->{$this->table_key}] = $row;
		}
		
		return $list;
			
		
	}
	
	public function getPageData($base_url,$uri_segment=4,$conditions='',$per_page = 0,$order_by = 'id',$order = 'desc')
	{
		$data =array();
		$data['count'] = $this->getCount($conditions);
		$page = $this->uri->segment($uri_segment,0);
		$this->load->library('pagination');
		$config['base_url'] =site_url($base_url);
		$config['uri_segment'] = $uri_segment;
		$config['per_page'] = $per_page == 0 ? 20 : $per_page;
		$config['total_rows']= $data['count'];
		$config['full_tag_open'] = '<p class="pageBar">';
		$config['full_tag_close'] = '</p>';
		$config['num_links'] =5;
		$this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links();
		$data['list']=$this->fetchList($config['per_page'],$page,$conditions,$order_by,$order);
		
		
		
		return $data;		
	}
	
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class Catsandcities extends MY_Model
{
	protected $table_name="category";
	
	public function delete($id)
	{
		$this->db->where('id',$id);
		$this->db->or_where('parent_id',$id);
		$this->db->delete($this->table_name);
		
	}
	public function hasChild($id)
	{
		
		$this->db->select('count(id) as num');
		$this->db->where('parent_id',$id);
		$query =  $this->db->get($this->table_name);
		if($row = $query->row())
		{
			return $row->num;
		}
		return false;
		
	}
	public function hasBiz($id)
	{
		$this->db->select('count(id) as num');
		if($this->table_name == 'category')
		{
			$this->db->where('catid_1',$id);
			$this->db->or_where('catid_2',$id);
		}
		else if($this->table_name == 'city')
		{
			$this->db->where('city_id',$id);
			$this->db->or_where('district_id',$id);
		}
		$query = $this->db->get('biz');
		if($row = $query->row())
		{
			return $row->num;
		}
		return false;
	}
	
	public function get_all()
	{
		
	
		$data=array();
		$this->db->order_by('parent_id','asc');
		$this->db->order_by('`order`','asc');
		$query=$this->db->get($this->table_name);
		foreach($query->result() as $row)
		{
			$data[$row->id]=$row;
		}
		
		return $data;
		
	}
	
	public function get_top()
	{
		$top_items=array();
		if($items=$this->get_all())
		{
			
			foreach($items as $i)
			{
				
				if($i->parent_id==='0')
				{
					$top_items[$i->id]= $i;
				}
			}
		}
		return $top_items;
		
	}
	
	public function get_children($parent_id)
	{
		$children=array();
		if($items=$this->get_all())
		{
			foreach($items as $i)
			{
				if($i->parent_id==$parent_id)
				{
					$children[]=$i;
				}
			}
		}
		return $children;
	}
	
	public function get($v,$k="id")
	{
		$item=false;
		if($items=$this->get_all())
		{
			foreach($items as $i)
			{
				if($i->{$k}==$v)
				{
					$item=$i;
					break;
				}
			}
		}
		return $item;
	
	}
	public function set_table_name($table_name)
	{
		$this->table_name=$table_name;
	}

}
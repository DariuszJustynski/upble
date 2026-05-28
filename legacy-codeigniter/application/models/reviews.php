<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class Reviews extends MY_model
{
	protected  $table_name='review';
	
	public function get_first_review($bizid)
	{
		$review = false;
		if($list = $this->fetchList(1,0,array('bizid'=>$bizid),'id','asc'))
		{
			$review = array_pop($list);
		}
		return $review;
	}
	
	public function get_last_review($bizid)
	{
		$review = false;
		if($list = $this->fetchList(1,0,array('bizid'=>$bizid),'id','desc'))
		{
			$review = array_pop($list);
		}
		
		return $review;
	}
	
	public function get_rating_stats($bizid)
	{
		$bizid = (int)$bizid;
		$q = "select rating,
				count(rating) as num 
				from {$this->table_name}
				where bizid = {$bizid} 
				group by rating";
		
		$query = $this->db->query($q);
		$stats = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0);
		foreach($query->result() as $row)
		{
			$stats[$row->rating] = $row->num;
		}
		
		return $stats;
	}
	
	// delete all reviews belonging to a business
	public function del_list($bizid)
	{
	
		$this->db->where('bizid',$bizid);
		if($this->db->delete($this->table_name))
		{
			return true;
		}
		return false;
	}
	
	//get reviews of a specifed city to display on homepage
	public function get_city_reviews($city_id,$limit,$offset)
	{
		$list = array();
		$this->db->select('review.*');
		$this->db->from('review');
		$this->db->join('biz','biz.id = review.bizid');
		$this->db->where('biz.city_id',$city_id);
		$this->db->order_by('review.created_at','desc');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		foreach($query->result() as $row)
		{
			$list[] = $row;
		}
		return $list;
	}
}
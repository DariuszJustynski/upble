<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class Bizs extends MY_model
{
	protected  $table_name="biz";
	
	public function fetchList($limit,$offset,$cons)
	{
		$list = array();
		$where =" 1=1";
		foreach($cons as $key => $val)
		{
			$val = addslashes($val);
			$where .= " and biz.{$key} = '{$val}'";
		}
		
		$q = "select
				biz.* ,
				count( distinct photo.id) as photo_count,
				IF(count( distinct photo.id) > 0, sum(rating)/count( distinct photo.id), sum(rating)) as rating_sum,
				count(distinct review.id) as review_num
		from biz
		left join review on biz.id = review.bizid
		left join photo on biz.id = photo.bizid
		where
			$where
		group by biz.id
		order by rating_sum desc
		limit
			$limit
		offset
			$offset
		";
		
		$query = $this->db->query($q);
		
		foreach($query->result() as $biz)
		{
			
			if($biz->review_num > 0)
			{
				$biz->rating = (($biz->rating_sum/($biz->review_num*5))*100).'%';
			}
			else
			{
				$biz->rating = '0%';
			
			}
			$biz = biz_cat_info($biz);
			$biz->first_photo = $this->get_cover_photo($biz->id);
			$list[] = $biz;
		}
		
		return $list;
		
	}
	
	private function get_cover_photo($bizid)
	{
		$photo = null;	
		$q = "select * from photo where bizid = {$bizid} order by flower desc limit 1";
		$query = $this->db->query($q);
		if($row = $query->row())
		{
			$photo = $row;
		}
		
		return $photo;
	}
	
	///search
	
	//
	public function search_result_count($search)
	{
		$count=0;
		$search = addslashes($search);
		$city_id = $this->tank_auth->get_user_city()->id;
		$sql="select count(id) as count from ".$this->table_name." where match(name) against ('$search')>0 and city_id ='$city_id'";
		$query=$this->db->query($sql);
		if($row=$query->row())
		{
			$count=$row->count;
		}
		return $count;
	}
	
	public function search($search,$limit=10,$offset=0)
	{
		$list = array();
		$search = addslashes($search);
		$city_id = $this->tank_auth->get_user_city()->id;
		$sql="select biz.*,
				count( distinct photo.id) as photo_count,
				IF(count( distinct photo.id) > 0, sum(rating)/count( distinct photo.id), sum(rating)) as rating_sum,
				count(distinct review.id) as review_num
		from ".$this->table_name." 
		left join review on biz.id = review.bizid
		left join photo on biz.id = photo.bizid
		where match(biz.name) against ('$search')>0 and biz.city_id ='$city_id' 
		group by biz.id
		limit $offset,$limit";
		$query = $this->db->query($sql);
		
		foreach($query->result() as $biz)
		{
			if($biz->review_num > 0)
			{
				$biz->rating = (($biz->rating_sum/($biz->review_num*5))*100).'%';
			}
			else
			{
				$biz->rating = '0%';
					
			}
			$biz = biz_cat_info($biz);
			$biz->first_photo = $this->get_cover_photo($biz->id);
			$list[] = $biz;
		}
		return $list;
	
	}

}
?>
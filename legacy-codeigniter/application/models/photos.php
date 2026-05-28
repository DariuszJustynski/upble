<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class Photos extends MY_model
{
	protected $table_name="photo";
	private $biz_photos_stats = array();
	private $user_photo_stat_holder = array();
	
	public function get($id,$bizid=0,$uid=0)
	{
		
		if(!$bizid)
			return parent::get($id);
		
		$photo=array();
		$uid==0 ? $biz_photos_stats = $this->get_biz_photo_stats($bizid) : $biz_photos_stats = $this->get_user_photo_stats($uid,$bizid);
		$biz_photos = $biz_photos_stats['photos'];
		if(isset($biz_photos[$id]))
		{
			$photo = $biz_photos[$id];
		}
		
		return $photo;
		
	}
	
	
	public function get_pre($id,$bizid,$uid=0)
	{
		$photo=$this->get($id,$bizid,$uid);
		if(!$photo)
		{
			return false;
		}
		$order=$photo->order;
		if($order>1)
		{
			$photo_stats= $uid==0 ? $this->get_biz_photo_stats($bizid) : $this->get_user_photo_stats($uid,$bizid);
			$relation=$photo_stats['relation'];
			$id=$relation[$order-1];
			return $photo_stats['photos'][$id];
		}
		else return false;
	}
	
	public function get_next($id,$bizid,$uid=0)
	{
		$photo=$this->get($id,$bizid,$uid);
		if(!$photo)
		{
			return false;
		}
		$order=$photo->order+1;
		$photo_stats=$uid==0 ? $this->get_biz_photo_stats($bizid) : $this->get_user_photo_stats($uid,$bizid);
		$relation=$photo_stats['relation'];
		if(isset($relation[$order]))
		{
			$id=$relation[$order];
			return $photo_stats['photos'][$id];
		}
		else return false;
		
		
	}
	public function get_first($bizid,$uid=0)
	{
		$photo=false;
		$photo_stats=$uid==0 ? $this->get_biz_photo_stats($bizid) : $this->get_user_photo_stats($uid,$bizid);
		$photos=$photo_stats['photos'];
		if(!empty($photos))
		{
		  $photo = array_shift($photos);
		}
		return $photo;
	}

	
	public function get_page($page_num,$bizid,$uid=0)
	{
		$list=array();
		$photo_stats=$uid==0 ? $this->get_biz_photo_stats($bizid) : $this->get_user_photo_stats($uid,$bizid);
		
		$photos=$photo_stats['photos'];
		foreach($photos as $p)
		{
			if($p->page==$page_num)
			{
				$list[]=$p;
			}
			if($p->page>$page_num)
				break;
		}
		return $list;
		
	}
	
	public function getCount($bizid,$uid=0)
	{
		$biz_photos_stats = $uid==0 ? $this->get_biz_photo_stats($bizid) : $this->get_user_photo_stats($uid,$bizid);
		$biz_photos = $biz_photos_stats['photos'];
		
		return sizeof($biz_photos);
	}
	
	private function get_biz_photo_stats($bizid)
	{
		$biz_photos = false;
		
		if(!isset($this->biz_photos_stats[$bizid]))
		{
			$photos=array();
			$relation=array();
			$user_photo_info=array();
			$this->db->where('bizid',$bizid);
			$this->db->order_by('flower','desc');
			$this->db->order_by('created_at','asc');
			$query = $this->db->get($this->table_name);
			$i=0;
			foreach($query->result() as $row )
			{
				$i++;
				$row->order = $i;
				
				$relation[$i] = $row->id;
				$photos[$row->id] = $row;
				if(!isset($user_photo_info[$row->uid]))
				{
					$user_photo_info[$row->uid]['first_photo'] = $row->id;
					$user_photo_info[$row->uid]['count'] = 0;
				}
				$user_photo_info[$row->uid]['count'] += 1;
			}
			
			if($i>0)
			{
				$page_count = $this->config->item('photo_page_count');
				foreach($photos as &$p)
				{
					$p->page=ceil($p->order/$page_count);
				}
			}
			$biz_photos['photos'] = $photos;
			$biz_photos['relation'] = $relation;
			$biz_photos['user_photo_info']=$user_photo_info;
			$this->biz_photos_stats[$bizid] = $biz_photos;
			
		}	
		
		
		return $this->biz_photos_stats[$bizid];
	
	}
	
	/////////////////////////user photo ////////////////////////////
	public function get_user_photo_count($uid,$bizid)
	{
		$biz_photos_stats = $this->get_biz_photo_stats($bizid);
		$user_photo_info = $biz_photos_stats['user_photo_info'];
		
		if(isset($user_photo_info[$uid]))
		{
			
			return $user_photo_info[$uid]['count'];
		}
		else return 0;
	}
	
	public function get_user_first_photo($uid,$bizid)
	{
		$biz_photos_stats = $this->get_biz_photo_stats($bizid);
		$user_photo_info = $biz_photos_stats['user_photo_info'];
		
		if(isset($user_photo_info[$uid]))
		{
			return $user_photo_info[$uid]['first_photo'];
		}
		else return false;
	
	}
	
	public function get_user_photo_stats($uid,$bizid)
	{
		
		if(!isset($this->user_photo_stat_holder[$bizid][$uid]))
		{
			$biz_photo_stats = $this->get_biz_photo_stats($bizid);
			$photos=array();
			$relation=array();
			$biz_photos = $biz_photo_stats['photos'];
			$i=0;
			foreach($biz_photos as $p)
			{
				if($p->uid == $uid)
				{
					$i++;
					$p->order = $i;
					$p->page = 0;
					$relation[$i] = $p->id;
					$photos[$p->id] = $p;
				}
				
			}
	
			
			$user_photo_stats['photos'] = $photos;
			$user_photo_stats['relation'] = $relation;
			$this->user_photo_stat_holder[$bizid][$uid] = $user_photo_stats;
		}
		return $this->user_photo_stat_holder[$bizid][$uid];
		
	
	}
	

}
?>
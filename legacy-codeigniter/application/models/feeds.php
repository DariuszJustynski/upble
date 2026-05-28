<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Feeds extends MY_Model
{
	protected $table_name = "feeds";
	function __construct()
	{
		parent::__construct();
	}
	
	public function delete($idtype,$objectid)
	{
		$this->db->where('idtype',$idtype);
		$this->db->where('objectid',$objectid);
		$this->db->delete($this->table_name);
	}
	public function fetchList($limit=15,$offset = 0,$conditions='',$order_by = 'created_at',$order = 'desc')
	{  
		$uid = $conditions['uid'];
		$data = array();
		if($uid != $this->tank_auth->get_user_id())
		{
			$data = parent::fetchList($limit,$offset,$conditions,$order_by,$order);
		}
		else
		{
			$q = "select feeds.* 
					from 
						feeds 
					left join
						friends on (feeds.uid = friends.fid) 
					where 
						friends.uid = $uid 
					union all
						select feeds.* 
						from 
							feeds 
						where
							feeds.uid = $uid 
			 		order by $order_by $order
					limit $offset,$limit";
			
			$query = $this->db->query($q);
			foreach ($query->result() as $row)
			{
				
				$data[] = $row;
			}
			
			
		}
		$feeds = array();
		$data = array_values($data);
		for($i = 0; $i < sizeof($data); $i++)
		{
			
			$feed = $data[$i];
			if(!is_array($feed->feed_data))
				$feed->feed_data = unserialize($feed->feed_data);
			$next = $i < sizeof($data) - 1? $data[$i+1] : false;
			
			//merge feeds
			if($feed->feed_type != 'photo')
			{
				while($next && $feed->uid == $next->uid && $feed->feed_type == $next->feed_type && $feed->idtype == $next->idtype && $feed->objectid == $next->objectid)
				{
					$i++;
					$next = $i < sizeof($data)-1 ? $data[$i+1] : false;
				}				
			}

			else 
			{
				$feed_data = array();
				$feed_data[] = $feed->feed_data;
				if($next && !is_array($next->feed_data))
					$next->feed_data = unserialize($next->feed_data);
				while($next && $feed->uid == $next->uid && $feed->feed_type == $next->feed_type
						&& $feed->feed_data['bizid'] == $next->feed_data['bizid'] && $feed->created_at - $next->created_at < 15*60)
				{
					$feed_data[] = $next->feed_data;
					$i = $i+1;
					$next = $i < sizeof($data)-1 ? $data[$i+1] : false;
					if($next)
						$next->feed_data = unserialize($next->feed_data);
				}
				$feed->feed_data = array_reverse($feed_data);
			}
			$feed = $this->_mkfeed($feed);
			if($feed->content)
				$feeds[]=$feed;
		}
		return $feeds;
	
	}
	public function getCount($conditions)
	{
		
		$uid = $conditions['uid'];
		if($uid != $this->tank_auth->get_user_id())
		{
			return parent::getCount($conditions);
		}
		$q = "select sum(total) as num from
			(select count(feeds.id) as total
				from
					feeds
				left join
					friends on (feeds.uid = friends.fid)
				where
					friends.uid = $uid
			union 
			 select count(feeds.id) as total
				from
					feeds
				where
					feeds.uid = $uid) t";
		$query = $this->db->query($q);
		if($row = $query->row())
			return $row->num;
		return 0;
		
	}
	
	private function _mkfeed($feed)
	{
		$content ="";
		$feed_data = $feed->feed_data;
		$this->load->config('feeds',TRUE);
		switch($feed->feed_type)
		{
			case 'friend' : 
				$template = $this->config->item('friend','feeds');
				$viewer_id = $this->tank_auth->get_user_id();
				$show_username = ($viewer_id == $feed->objectid) ? "you" : $feed_data['fusername'];
				$replace = "<a href='".site_url('member/'.$feed_data['fusername'])."'>".$show_username."</a>";
				$content = str_replace("{user}",$replace,$template);
				break;
			case 'review' :				
				$template = $this->config->item('review','feeds');				
				$replace = "<a href='".site_url('/biz/'.$feed_data['bizid'].'#review_'.$feed->objectid."'>".$feed_data['title'].'</a>');
				$content = str_replace("{biz}",$replace,$template);				
				break;
			case 'photo':
				$template = $this->config->item('photo','feeds');
				$replace = array();
				$replace[] = sizeof($feed->feed_data);
				$feed_data = $feed->feed_data[0];
				$replace[] = "<a href='".site_url('/biz/'.$feed_data['bizid'])."'>".$feed_data['title'].'</a>';
				$images = "";
				foreach($feed->feed_data as $feed_data)
				{
					$images .= '<a href="'.site_url('photo/'.$feed_data['bizid'].'/'.$feed_data['photo_id']).'"><img src="'.site_url($feed_data['thumb']).'"/></a>';
				}
				$feed->images = $images;
				$content = str_replace(array('{num}','{biz}','{photo_links}'), $replace, $template);
				
				break;
		}
		$feed->content = $content;
		return $feed;
				
		
	
	}
	
	
}
?>
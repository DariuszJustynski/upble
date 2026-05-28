<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Posts extends CI_model
{
	private $table_name="post";
	private $cache_folder = "post_stats";
	private $_stats = false;
	private $_has_fetched_stats = false;
	
	function __construct()
	{
		parent::__construct();
		$this->cached=$this->config->item('cached');
		if($this->cached)
		{
			$this->load->library('cache_util');
		}
			
	}
	
	public function add($data)
	{
		if($this->db->insert($this->table_name,$data))
		{
			$this->delete_cache($data['tid']);
			$pid = $this->db->insert_id();
			//update topic 
			if(!$data['is_topic'])
			{
				$sql = "update topic set reply_num = reply_num+1, last_replied_at = $data[created_at] where id = $data[tid]";
				$this->db->query($sql);
			}
			
			return $pid;
		}
		else 
			return false;
	}
	public function get($tid,$pid)
	{
		$post = false;
		$posts =  $this->get_posts($tid);
		if(isset($posts[$pid]))
		{
			$post = $posts[$pid];
		}
		return $post;
		
		
	}
	public function delete($post)
	{
		$this->db->where('id',$post->id);
		if($this->db->delete($this->table_name))
		{
			$this->delete_cache($post->tid);
			if($post->is_topic)
			{
				$this->db->query("delete from post where tid=".$post->tid);
			}
			else
			{
				$this->db->query("update topic set reply_num = reply_num-1 where id=".$post->tid);
			}
			return true;
		}
		return false;
	}
	public function fetchList($tid,$limit=20,$offset=0)
	{
		$list = array();
		$posts = $this->get_posts($tid);
		$i=1;
		foreach($posts as $post)
		{
			if($i>$offset && $i<=$limit+$offset)
			{
				$list[] = $post;
			}
			if($i>$limit+$offset)
			{
				break;
			}
			$i++;
		}
		return $list;
		
	
	}
	public function get_topic($tid)
	{
		
		$stats = $this->get_topic_stats($tid);
		return $stats['topic'];
	}
	
	
	public function getCount($tid)
	{
		return sizeof($this->get_posts($tid));
	}
	
	
	private function get_posts($tid)
	{
		$stats = $this->get_topic_stats($tid);
		return $stats['posts'];
	}
	private function get_topic_stats($tid)
	{
		if($this->_has_fetched_stats && is_array($this->_stats) && isset($this->_stats['topic']))
		{
			$cached_topic = $this->_stats['topic'];
			if($cached_topic->id == $tid)
				return  $this->_stats;
			else unset($this->_stats);
			
		}
		
		$stats = false;
		if($this->cached)
		{
			$stats = $this->cache_util->get($this->cache_folder,'topic',$tid);
		}
		if($stats === false)
		{
			$stats = array('topic'=>array(),'posts'=>array());
			
			//get topic
			$this->load->model('topics');
			$topic = $this->topics->get($tid);
			$stats['topic'] = $topic;
			if($topic)
			{
				// get posts
				$posts = array();
				$this->db->where('tid',$tid);
				$this->db->order_by('created_at','asc');
				$query = $this->db->get($this->table_name);
				foreach($query->result() as $row)
				{
					$posts[$row->id] = $row;
					
				}
				$stats['posts'] = $posts;
				$this->cache_util->save($this->cache_folder,'topic',$tid,$stats);
			}
			
			
			
		}
		$this->_stats = $stats;
		$this->_has_fetched_stats = true;
		return $this->_stats;
		
		
	}
	private function delete_cache($tid)
	{
		if($this->cached)
		{
		  @$this->cache_util->delete($this->cache_folder,'topic',$tid);
		} 
	}
}

?>
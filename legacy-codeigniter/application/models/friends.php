<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Friends extends MY_Model
{
   protected $table_name='friends';
   function __construct()
   {
		parent::__construct();

   }
  
   public function follow($uid,$username,$fid,$fusername)
   {
		
		$data=array(
		'uid'=>$uid,
		'username'=>$username,
		'fid'=>$fid,
		'fusername'=>$fusername,
		);
		
		if($this->add($data))
			return true;
		return false;
		
   }
   public function unfollow($uid,$fid)
   {
		$this->db->where('uid',$uid);
		$this->db->where('fid',$fid);
		$this->db->delete($this->table_name);
		if ($this->db->affected_rows() > 0)
		{
			return true;
		}
		
		return false;
   }
   public function isFollowed($uid,$fid)
   {
		if($this->get(array('uid'=>$uid,'fid'=>$fid)))
			return true;
		return false;
   }
  
}
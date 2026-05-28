<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class flower extends CI_controller
{
	private $type=array('review','photo');
	function __construct()
	{
		parent::__construct();
	}
	public function send()
   {
		if(!$this->tank_auth->is_logged_in())
		{
			exit('<!--_LOGIN_REQUIRED-->');
			
		}
		$bizid = $this->uri->segment(3,0);
		$id = $this->uri->segment(4,0);
		$idtype = $this->uri->segment(5,'review');
		if(!$id || !$bizid || !in_array($idtype,$this->type))
		{
			exit("<!--_ERROR-->Invalid Access!<!--_ERROR-->");
		}
		$idtype == 'review' ? $this->load->model('reviews', 'objects') : $this->load->model('photos', 'objects');
		if(!$object=$this->objects->get($id))
		{
			exit("<!--_ERROR-->Invalid Access!<!--_ERROR-->");
		}
		$uid = $this->tank_auth->get_user_id();
		$this->load->model('flowers');
		if($this->flowers->hasSent($uid,$object->id,$idtype))
		{
			exit("<!--_ERROR-->You have sent a flower for this $idtype already!<!--_ERROR-->");
		}
		if($uid == $object->uid)
		{
			exit("<!--_ERROR-->You can't send flower to yourself!<!--_ERROR-->");
		}
		
		$data = array( 
						'sender' => $uid, 
						'objectid'=> $object->id,
						'idtype' => $idtype,
						'receiver' => $object->uid,
						'created_at'=>date('Y-m-d H:i:s')
					);
		
		if($this->flowers->add($data,false))
		{
			$this->objects->update(array('flower'=>$object->flower+1),$object->id);
			
			$flowers=$object->flower+1;
			exit("{$flowers}");
		}
		else exit("<!--_ERROR-->Unkown Error occurred, You may try later.<!--_ERROR-->");
   }
   
   public function user()
   {
		$uid = $this->uri->segment(3,0);
		$this->load->model('flowers');
		echo $this->flowers->getCount(array('receiver'=>$uid));
   }
}
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class Review extends CI_controller
{
	
	
	function __construct()
   {
		parent::__construct();
   }
   
   public function add()
   {
		if(!$this->tank_auth->is_logged_in())
		{
			die('<!--_LOGIN_REQUIRED-->Login is required!<!--_LOGIN_REQUIRED-->');
		}
		$this->load->helper('form');
		
		$review=$this->get_form_data();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters(' ',' ');
		$this->form_validation->set_rules('bizid', 'Business', 'trim|required|intval|callback_is_exist');
		$this->form_validation->set_rules('rating', 'Rating', 'trim|required|intval|greater_than[0]|less_than[6]');
		$this->form_validation->set_rules('content', 'Review', 'trim|required|max_length[2000]|min_length[40]');
		if($this->form_validation->run())
		{
			//review data
			$review=$this->get_form_data();
			$review['uid']=$this->tank_auth->get_user_id();
			$review['username']=$this->tank_auth->get_username();
			$review['created_at']=time();
	
			$this->load->model('bizs');
			$this->load->model('reviews');
			$biz = $this->bizs->get($review['bizid']);
			
			if($id = $this->reviews->add($review))
			{
				
				
				//publish feeds
				//add feeds here
				$this->load->model('feeds');
				
				$feedarr = array(
				'uid'=>$review['uid'],
				'username'=>$review['username'],
				'idtype' => 'review',
				'objectid' => $id,
				'feed_type' => 'review',
				'feed_data' => serialize( array('bizid'=>$biz->id,'title'=>$biz->name)),
				'created_at' => $review['created_at']
				
				);
				
				$this->feeds->add($feedarr);
			}
			
			die('<!--_REDIRECT-->'.str_replace('/', '\/',site_url('/biz/'.$biz->id.'#review_'.$id)).'<!--_REDIRECT-->');
			
			
		}
		
		die('<!--_ERROR-->'.$this->form_validation->error_string().'<!--_ERROR-->');
		
   }
   public function edit()
   {
		$bizid = $this->uri->segment(3,0);
		$id = $this->uri->segment(4,0);
		if (!$id || !$bizid)
		{
			show_404();
		}
		if(!$this->tank_auth->is_logged_in())
		{
			redirect('/ucp/login/');
		}
		$uid = $this->tank_auth->get_user_id();
		$this->load->model('reviews');
		$this->load->model('bizs');
		
		//check the auth to edit
		if (!($review = $this->reviews->get($id))|| $review->uid != $uid)
		{
			show_404();
		}
		
		$biz = $this->bizs->get($bizid);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('rating', 'Rating', 'trim|required|intval|greater_than[0]|less_than[6]');
		$this->form_validation->set_rules('content', 'content', 'trim|required|xss_clean|max_length[2000]|min_length[100]');
		if($this->form_validation->run())
		{
			$new_review=array(
				'rating' => $this->input->post('rating'),
				'content' => $this->input->post('content'),
				'updated_at'=> time(),
			);
			$this->reviews->update($new_review,$review->id);
			redirect('/biz/'.$biz->id.'#review_'.$review->id);
		}
		
		$form_data = $this->get_form_data();
		if($form_data)
		{
			$review = $form_data;
		}
		else
		{
			$review = (array)$review;
		}
		$data['biz'] = $biz;
		$data['review'] = $review;
		
		$data['heading'] = 'Edit Review';
		$this->load->view('/review/add',$data);
		
   }
   //recieve flower
   
   
   public function del()
   {
	
		if(!$this->tank_auth->is_admin())
		{
			show_404();
		}
		$bizid = $this->input->post('bizid');
		$id = $this->input->post('id');
		$this->load->model('reviews');
		$this->load->model('bizs');
		if(!$review = $this->reviews->get($id))
		{
			show_404();
		}
		$uid=$review->uid;
		if($this->reviews->delete($id))
		{
			//delete flower record
			$this->load->model('flowers');
			$this->flowers->del($id);
			
			// delete feed generate by this review
			$this->load->model('feeds');
			$this->feeds->delete('review',$review->id);
		}
		
		redirect('/biz/'.$bizid);
   }
   public function is_exist($bizid)
   {
		$this->load->model('bizs');
		if($biz = $this->bizs->get($bizid))
		{
			// has ever reviewed this business?
			$this->load->model('reviews');
			$uid = $this->tank_auth->get_user_id();
			if(!($review=$this->reviews->get(array('uid'=>$uid,'bizid'=>$bizid))))
			{
				return true;
			}
			else
			{
				$this->form_validation->set_message('is_exist', " You have reviewed this bussiness before!");
			}
				
		}
		else
		{
			$this->form_validation->set_message('is_exist', " Invalid Operate!");
		}
		return false;
   }
   
   private function get_form_data()
   {
		if($this->uri->segment(2)=='edit'&&empty($_POST))
		{
			return false;
		}
		
		$review=array(
			'id' => $this->input->post('id'),
			'bizid' => $this->input->post('bizid'),
			'rating' => $this->input->post('rating'),
			'content' => $this->input->post('content')
		);
		return $review;
		
   }
   
   // this is only for admin to manage reviews that are flagged by users
   public function show()
   {
		$id = $this->uri->segment(3,0);
		if(!$id)
		{
			show_404();
		}
		$this->load->model('reviews');
		if(!($review = $this->reviews->get($id)))
		{
			die("The review doesn't exist");
		}
		$data['review'] = $review;
		
		//function getBizById located in common_helper.php in application helper folder
		$data['biz'] = getBizById($review->bizid);
		
		$this->load->view('review/show',$data);
   }
}
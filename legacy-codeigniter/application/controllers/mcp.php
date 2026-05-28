<?php if (!defined('BASEPATH')) exit('Access denied!');
class Mcp extends CI_Controller
{
	
	function profile($username='')
	{

		//get profile data
		if((!$username && !($username=$this->tank_auth->get_username())) || !($user=$this->users->get_user_by_username($username)))
		{
			show_404();
		}
		
		//get follower list
		$this->load->model('friends');
		$cons = array('uid' => $user->id);
		$data['friendData'] = $this->friends->getPageData('/mcp/network/'.$username.'/following/',5,$cons);
		$data['friendData']['type']="following";
		$data['friendData']['user']=$user;
		
		$this->load->model('feeds');
		$data['feedData'] = $this->feeds->getPageData('mcp/feedData/'.$username.'/',4,array('uid'=>$user->id),10);
		
		$data['bioData']= $this->bioData($user);
		
		//send the final view
		$data['user'] = $user;
		$data['username']=$username;
		$data['heading']=$username.'\'s profile';
		$this->load->view('user/profile',$data);
		
		
	}
	
	
	/**
	* profile setting
	*/
	function setting()
	{
		
		if(!$this->tank_auth->is_logged_in())
		{
			redirect('/ucp/login/');
		}
		//
		$this->load->helper('url');
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		
		$uid=$this->tank_auth->get_user_id();
		if(!empty($_POST))
		{
			$this->form_validation->set_rules('website', 'Website', 'trim|xss_clean|max_length[255]|prep_url');
			$this->form_validation->set_rules('about_me', 'About Me', 'trim|xss_clean|max_length[300]');
			$this->form_validation->set_rules('city', 'City', 'trim|xss_clean|max_length[20]');
			$valid = $this->form_validation->run();
			$data['website'] = $this->input->post('website');
			$data['about_me'] = $this->input->post('about_me');
			$data['city'] =  $this->input->post('city');
			
			if($valid)
			{
				$this->users->update_profile($uid,$data);
				$this->uploadThumb();
				$this->session->set_flashdata('success','Profile updated');
				redirect(site_url('mcp/setting'));
			}
			
			
		}
		else $data=(array)($this->users->get_profile($uid));
		

		$data['heading']='Profile setting';
		$this->load->view('user/setting_form',$data);
		//avatar handling end
		
		
	
	}
	
	private function uploadThumb()
	{
		//handle avatar uploading
		$uid=$this->tank_auth->get_user_id();
		
		if(isset($_FILES['picture']) && $_FILES['picture']['name'])
		{
			
			$longid=str_pad($uid,9,'0',STR_PAD_LEFT);
			$avatar_folder=FCPATH.'upload/avatar/'.substr($longid,0,3).'/'.substr($longid,3,2).'/'.substr($longid,5,2).'/';
			$name_a=explode('.', $_FILES['picture']['name']);
			$ext=$name_a[1];
			$file_name=substr($longid,7,2).'_avatar_picture'.'.'.$ext;
			if(!file_exists($avatar_folder))
			{
				mkdirs($avatar_folder);
			}
			$config['upload_path']= $avatar_folder;
			$config['allowed_types'] = 'gif|jpg|png';
			$config['file_name'] = $file_name;
			$config['overwrite']	= true;
			$config['max_size']	= '1024';
			$config['max_width'] = '1024';
			$config['max_height'] = '768';
			$this->load->library('upload', $config);
			
			if(!$this->upload->do_upload('picture'))
			{
				$this->session->set_flashdata('error',$this->upload->display_errors());
			}
			else
			{
				$upload_data=$this->upload->data();
				$this->load->library('image_lib');
				$config=array();
				//big avatar
				$config['source_image']=$avatar_folder.$file_name;
				$config['new_image'] = $avatar_folder.substr($longid,7,2).'_avatar_big'.'.'.$ext;
				$config['overwrite']= true;
				$config['maintain_ratio'] =true;
				$config['width'] = 100;
				$config['height'] 	= 100;
				$this->image_lib->initialize($config);
				$this->image_lib->resize();
				//middle avatar
				$config['new_image'] = $avatar_folder.substr($longid,7,2).'_avatar_mid'.'.'.$ext;
				$config['maintain_ratio'] =false;
				$config['width'] = 50;
				$config['height'] 	= 50;
				$this->image_lib->initialize($config);
				$this->image_lib->resize();
				//small avatar
				$config['new_image'] = $avatar_folder.substr($longid,7,2).'_avatar_small'.'.'.$ext;
				$config['width'] = 25;
				$config['height'] 	= 25;
				$this->image_lib->initialize($config);
				$this->image_lib->resize();
				unlink($config['source_image']);
		
			}
		}
			
	}
	function follow()
	{
		if(!$this->tank_auth->is_logged_in())
		{
			die("<!--_LOGIN_REQUIRED-->");
	
		}
		$username = $this->input->post('username');
		
		if(!$username || !($fuser=$this->users->get_user_by_username($username))||$username==$this->tank_auth->get_username())
		{
		   die("<!--_ERROR-->Wrong request!<!--_ERROR-->");
		}
		
		$this->load->model('friends');
		$user_id=$this->tank_auth->get_user_id();
		$username=$this->tank_auth->get_username();
		
		if(!$this->friends->isFollowed($user_id,$fuser->id))
		{
			if($this->friends->follow($user_id,$username,$fuser->id,$fuser->username))
			{
				
				//add feeds here
				$this->load->model('feeds');
				
				$feedarr = array(
				'uid'=>$user_id,
				'username'=>$username,
				'idtype' => 'user',
				'objectid' => $fuser->id,
				'feed_type' => 'friend',
				'feed_data' => serialize( array('fusername'=>$fuser->username)),
				'created_at' => time()
				
				);
				
				$this->feeds->add($feedarr);
				echo "1";
			}
			else echo "<!--_ERROR-->Oop! Error happened, Please try again!<!--_ERROR-->";
			
		}
		else
		{
			echo "<!--_ERROR-->You have been already following this user!<!--_ERROR-->";
		}
		
	
	}
	function unfollow()
	{
		if(!$user_id=$this->tank_auth->get_user_id())
		{
			die("<!--_LOGIN_REQUIRED-->");
		}
		
		$fusername = $this->input->post('username');
		
		$this->load->model('friends');
		$this->load->model('users');
		if(!$fusername ||!($fuser = $this->users->get_user_by_username($fusername)) ||!($this->friends->isFollowed($user_id,$fuser->id)))
		{
			die("<!--_ERROR-->Wrong request!<!--_ERROR-->");
		}
		if($this->friends->unfollow($user_id,$fuser->id))
		echo '1';
		else echo "<!--_ERROR-->Oop! Error happened, Please try again!<!--_ERROR-->";
	
	}
	
	// /mcp/feedData/biker/20
	function feedData()
	{    
		
		$username=$this->uri->segment(3, '');
		if(!$user=$this->users->get_user_by_username($username))
		{
			die("<!--_ERROR-->Wrong request!<!--_ERROR-->");
		}
		
		$this->load->model('feeds');
		
		//get data
		$pageData = $this->feeds->getPageData('mcp/feedData/'.$username.'/',4,array('uid'=>$user->id),10);
		$data['list'] = $pageData['list'];
		$data['pagination_links'] = $pageData['pagination_links'];
		$this->load->view("user/feed_container",$data);
	}
	// /mcp/network/biker/friend/20
	function network()
	{

		$type="following";
		
		$username = $this->uri->segment(3, '');
		$type = $this->uri->segment(4, 'following');
		$page = $this->uri->segment(5,0);
		
		if(!$user=$this->users->get_user_by_username($username))
		{
			die("<!--_ERROR-->Wrong request!<!--_ERROR-->");
		}
		
		$this->load->model('friends');
		if($type == "following")
		{
			$cons = array('uid' => $user->id);
		}
		else 
			$cons = array('fid' => $user->id);
		
		$pageData = $this->friends->getPageData('/mcp/network/'.$username.'/'.$type.'/',5,$cons);
		$data['pagination_links'] = $pageData['pagination_links'];
		$data['list'] = $pageData['list'];
		$data['count'] = $pageData['count'];
		//get data
		$data['type']=$type;
		$data['user']=$user;
		$this->load->view('user/network_container',$data);
	}
	
	//获取用户所有评论
	
	function review()
	{
		$username = $this->uri->segment(2,'');
		if(!$username || !($user = $this->users->get_user_by_username($username)))
		{
			show_404();
		}
		
		//get reviews
		$this->load->model('reviews');
		$pageData = $this->reviews->getPageData('/mcp/'.$username.'/reviews/',4,array('uid' => $user->id));
		$data['pagination_links'] = $pageData['pagination_links'];
		$data['reviews'] = $pageData['list'];
		
		// get user information
		$data['user'] = $user;
		$data['bioData']= $this->bioData($user);
		$data['heading'] = $username."'s reviews";
		$this->load->view('user/user_review',$data);
		
	}
	
	private function bioData($user)
	{
		$data['is_self'] = false;
		$data['is_friend'] = false;
		if($user->id == $this->tank_auth->get_user_id())
			$data['is_self']=true;
	
		$data['user'] = $user;
		$profile = $this->users->get_profile($user->username,'username');
	
		if(!$data['is_self'] && $this->tank_auth->is_logged_in())
		{
			$this->load->model('friends');
			if($this->friends->isFollowed($this->tank_auth->get_user_id(),$user->id))
			{
				$data['is_friend'] = true;
			}
		}
		$data['profile'] = $profile;
		return $data;
	}
	
}

?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class Photo extends CI_controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('bizs');
		//$this->output->enable_profiler(TRUE);
	}
	
	public function upload()
	{
		if(!$this->tank_auth->is_logged_in())
		{
			redirect('/ucp/login/');
		}
		
		$bizid=$this->uri->segment(3,0);
		
		if(!$bizid||!($biz=$this->bizs->get($bizid)))
		{
			show_404();
		}
		
		
		$this->load->library('form_validation');
		$data['biz']=$biz;
		$data['heading'] = 'Upload Photos';
		$this->load->view('photo/add',$data);
		
	}
	public function uploadFile()
	{
		//check login
		if(!$this->tank_auth->is_logged_in())
		{
			$data['type'] = 0;
			$data['msg'] = 'Please login to continue';
			exit(json_encode($data));
		}
		
		if (!empty($_FILES)) {
			
			$bizid = (int)$_POST['bizid'];
			if(!$biz=$this->bizs->get($bizid))
			{
				$data['type'] = 0;
				$data['msg'] = 'Invalid request!';
				exit(json_encode($data));
			}
			
			$data=array();
			$tempFile = $_FILES['Filedata']['tmp_name'];
			if(!is_uploaded_file($_FILES['Filedata']['tmp_name']))
			{
				$data['type'] = 0;
				$data['msg'] = 'Invalid Operate, or the picture you uploaded is too big';
				exit(json_encode($data));
			}
			$file_name=$_FILES['Filedata']['name'];
			if(!$this->is_img($tempFile))
			{
				$data['type'] = 0;
				$data['msg'] = 'Fail to upload '.$file_name.'. '.'Invalid File Type!';
				exit(json_encode($data));
			}
			$info=getimagesize($tempFile);
			$ext=strtolower($this->get_extension($file_name));
			$size=round($_FILES['Filedata']['size']/1024,2);
			if(!in_array($ext,array('.jpg')) || !in_array($info['mime'],array('image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg')))
			{
				$data['type'] = 0;
				$data['msg'] = 'Fail to upload '.$file_name.'. '.'Only JPG format is allowed';
				exit(json_encode($data));
			
			}
			if($size>1024)
			{
				$data['type'] = 0;
				$data['msg'] = 'Fail to upload '.$file_name.'. '.'The img size must be under 1MB';
				exit(json_encode($data));
				
			}
			if($info[0]>1000||$info[1]>768)
			{
				$data['type'] = 0;
				$data['msg'] = 'Fail to upload '.$file_name.'. '.'The img dimensions must be under 1000x768';
				exit(json_encode($data));
			}
			if($info[0]<250||$info[1]<200)
			{
				$data['type'] = 0;
				$data['msg'] = 'Fail to upload '.$file_name.'. '.'The img dimensions must be bigger than 250x200';
				exit(json_encode($data));
			}
			
			$this->proccess($tempFile,$biz);
		}
	}
	private function proccess($org_file,$biz)
	{
		
		
		$id=uniqid();
		$folder = $this->getDir($id);
		$dir=FCPATH.'upload/biz_photos/'.$folder.'/';
		if(!is_dir($dir))
		{
			mkdirs($dir);
		}
		
		//handle img
		$des_file=$dir.$id.'.jpg';
		$thumb_file=$dir.$id.'.thumb.jpg';
		$info=getimagesize($org_file);
		if($info[0] <= 600)
		{
			copy($org_file,$dir.$id.'.jpg');
		}
		else
		{
			$this->load->library('image_lib');
			$config['source_image']=$org_file;
			$config['new_image'] = $des_file;
			$config['overwrite']= true;
			$config['maintain_ratio'] =true;
			$config['width'] = 600;
			$config['height'] = floor(($info[1]/$info[0])*600);
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
		}
		
		// make thumb img
		$this->makeThumb($org_file, $thumb_file, $info[0], $info[1], 100, 100);
		
		
		$this->load->model('photos');
		$uid=$this->tank_auth->get_user_id();
		$arr = array(
			'id' => $id,
			'bizid' => $biz->id,
			'uid' => $uid,
			'username' => $this->tank_auth->get_username(),
			'folder' => $folder,
			'created_at' => time()
		);
		if($this->photos->add($arr,false))
		{
			$thumb = 'upload/biz_photos/'.$folder.'/'.$id.'.thumb.jpg';
			$this->load->model('feeds');
			$feedarr = array(
					'uid'=> $this->tank_auth->get_user_id(),
					'username'=>$this->tank_auth->get_username(),
					'idtype' => 'photo',
					'objectid' => $id,
					'feed_type' => 'photo',
					'feed_data' => serialize(array('bizid'=>$biz->id,'title'=>$biz->name, 'photo_id'=>$id,'thumb'=>$thumb)),
					'created_at' => time()
			);
			$this->feeds->add($feedarr);
			
			$data['thumb']= $thumb;
			$data['id']=$id;
			$data['biz'] = $biz;
			$view = $this->load->view('photo/proccess.php',$data,true);
			$data['type'] = 1;
			$data['view'] = $view;
			exit(json_encode($data));
			
		}
		else
		{
			unlink($des_file);
			unlink($thumb_file);
			$data['type'] = 0;
			$data['msg'] = 'Error occur, Please try again later!';
			exit(json_encode($data));			
			
		}
	
	}
	public function edit()
	{
		if(!$this->tank_auth->is_logged_in())
		{
			redirect('/ucp/login/');
		}
		$bizid=intval($this->uri->segment(3,0));
		$this->load->model('bizs');
		if(!$bizid ||!($biz=$this->bizs->get($bizid)))
		{
			show_404();
		}
		$captions=$this->input->post('caption',true);
		
		if(!is_array($captions)||empty($captions))
		{
			redirect('/biz/'.$bizid);
		}
		$first=array_shift(array_keys($captions));
		$this->load->model('photos');
		$uid = $this->tank_auth->get_user_id();
		$this->load->helper('text');
		foreach ($captions as $id => $val)
		{
			if(!($photo = $this->photos->get($id,$bizid)))
			{
				$this->session->set_flashdata('msg',array('type'=>'error','content'=>'The photo has been removed'));
				redirect('/biz/'.$bizid);
			}
			if($photo->uid != $uid)
			{
				$this->session->set_flashdata('msg',array('type'=>'error','content'=>'Invalid Access!'));
				redirect('/biz/'.$bizid);
			}
			$val = trim($val);
			if(!empty($val))
			{
				$val=character_limiter($val,60);
				$this->photos->update(array('caption'=>$val),$photo->id);
			}
			
			
		}
		
		redirect('/photo/'.$bizid.'/'.$first);
		
	
	}
	
	public function show()
	{
		$bizid=$this->uri->segment(2,0);
		$id=$this->uri->segment(3,0);
		//echo $id;
		if(!$bizid || !$id)
		{
			show_404();
		}
		$uid = 0;
		$data['single_user_mode'] = false;
		$this->load->model('photos');
		if($this->uri->total_segments() == 5 && $this->uri->segment(4) == 'from')
		{
			$username = $this->uri->segment(5,'');
			
			if($username && ($user = $this->users->get_user_by_username($username)))
			{
				$uid = $user->id;
				$data['single_user_mode'] = true;
				$data['first_photo'] = $this->photos->get_first($bizid,$uid);
			}
			
		}
		
		$this->load->model('bizs');
		
		if(!($biz=$this->bizs->get($bizid)) || !($photo=$this->photos->get($id,$bizid,$uid)))
		{
			show_404();
			
			
		}
		
		//
		$data['biz'] = $biz;
		$data['photo']=$photo;
		//print_r( $photo);
		$data['photo_list'] = $this->photos->get_page($photo->page,$bizid,$uid);
		$data['pre_photo']=$this->photos->get_pre($id,$bizid,$uid);
		$data['next_photo']=$this->photos->get_next($id,$bizid,$uid);
		
		//paginating
		$page=$photo->page;
		$this->load->library('pagination');
		$config['base_url'] =site_url('/photo/'.$bizid.'/page/');
		$config['uri_segment'] = 4;
		$config['cur_page'] = ($photo->page-1)*$this->config->item('photo_page_count');
		$config['total_rows']=$data['count']=$this->photos->getCount($bizid,$uid);
		$config['per_page'] =$uid == 0 ? $this->config->item('photo_page_count') : $config['total_rows'];
		$config['full_tag_open'] = "<p>";
		$config['full_tag_close'] = '</p>';
		$config['num_links'] =5;
		$this->pagination->initialize($config);
		$data['pagination_links'] = $this->pagination->create_links();
		$data['heading'] = $photo->caption?$photo->caption:'Photos for '.$biz->name;
		$this->load->view('photo/show',$data);
		
	}
	
	public function page()
	{
		$bizid=$this->uri->segment(2,0);
		$this->load->model('bizs');
		if(!$bizid || !($this->bizs->get($bizid)))
		{
			show_404();
		}
		$start=$this->uri->segment(4,0)+1;
		$page=ceil(($start+1)/$this->config->item('photo_page_count'));
		$this->load->model('photos');
		$list=$this->photos->get_page($page,$bizid);
		if(empty($list))
		{
			show_404();
		}
		$first=array_shift($list);
		redirect('/photo/'.$bizid.'/'.$first->id);
	}
	
	public function del()
	{
		if(!$this->tank_auth->is_logged_in())
		{
			exit('<!--_LOGIN_REQUIRED-->');
		}
		$isAdmin = $this->tank_auth->is_admin();
		
		//this delete action must be done by POST when it's taken by admin
		if($isAdmin)
		{
			$bizid  = $this->input->post('bizid');
			$id = $this->input->post('id');
		}
		else 
		{
			$bizid=$this->uri->segment(3,0);
			$id=$this->uri->segment(4,0);			
		}

		if(!$bizid || !$id)
		{
			exit("<!--_ERROR-->Invalid request!<!--_ERROR-->");
		}
		$this->load->model('photos');
		if(!($photo=$this->photos->get($id)))
		{
			exit("<!--_ERROR-->This image has been removed!<!--_ERROR-->");
		}
		
		$uid=$this->tank_auth->get_user_id();
		if($photo->uid!=$uid && !$isAdmin)
		{
			exit("<!--_ERROR-->Invalid operate!<!--_ERROR-->");
		}
		if(time()-$photo->created_at>60*10 && !$isAdmin)
		{
			exit("<!--_ERROR-->You can only delete the photos in 10 minutes after uploading!<!--_ERROR-->");
		}
		
		if($this->photos->delete($id))
		{
			$this->load->model('feeds');
			$this->feeds->delete('photo',$photo->id);
			
			$doc_root=FCPATH.'upload/biz_photos/'.$photo->folder.'/';
			@unlink($doc_root.$photo->id.'.jpg');
			@unlink($doc_root.$photo->id.'.thumb.jpg');
			echo '1';
		}
	}
	
	
	//code for checking whether the file is real img and no harm ,taken from CI upload libarary
	function is_img($file)
	{
		if(@getimagesize($file) !== FALSE)
		{
			if (($file = @fopen($file, 'rb')) === FALSE) // "b" to force binary
				{
					return FALSE; // Couldn't open the file, return FALSE
				}

				$opening_bytes = fread($file, 256);
				fclose($file);

				// These are known to throw IE into mime-type detection chaos
				// <a, <body, <head, <html, <img, <plaintext, <pre, <script, <table, <title
				// title is basically just in SVG, but we filter it anyhow

				if ( ! preg_match('/<(a|body|head|html|img|plaintext|pre|script|table|title)[\s>]/i', $opening_bytes))
				{
					return TRUE; // its an image, no "triggers" detected in the first 256 bytes, we're good
				}
		}
		return FALSE;
	}
	function get_extension($filename) 
	{
		   $x = explode('.', $filename);
		   return '.'.end($x);
	}

	function getDir($id)
	{
		//get hashcode
		$str = (string)$id;
		$len = strLen($str);
		$sum = 0;
		for ($i = 0; $i < $len; $i++) {
			$sum = (int)(31 * $sum + ord($str[$i]));
		}
		
		$firstFolder = sprintf("%02x", $sum & 255);
		$secondFolder = sprintf("%02x",($sum >> 8) & 255);
		return $firstFolder.'/'.$secondFolder;
	}
	
	private function makeThumb($src,$dist,$_w,$_h,$w,$h)
	{
		
		$thumb_w = 0;
		$thumb_h = 0;
	
		$gd = @imagecreatefromstring(file_get_contents($src));
		
		$_w = imagesx($gd);
		$_h = imagesy($gd);
		if($w > $_w) $w = $_w;
		if($h > $_h) $h = $_h;

		if (($w / $_w) < ($h / $_h)) {
			$thumb_w = $w;
			$thumb_h = floor($_h * ($w / $_w));
		} else {
			$thumb_w = floor($_w * ($h / $_h));
			$thumb_h = $h;
		}

		$gdt = imagecreatetruecolor($w, $h);
		$gdtBg = imagecolorallocate($gdt, 255, 255, 255);
		imagefill($gdt, 0, 0, $gdtBg);
		imagecopyresampled($gdt, $gd, ($w - $thumb_w) / 2, ($h - $thumb_h) / 2, 0, 0, $thumb_w, $thumb_h, $_w, $_h);
		
		imagejpeg($gdt,$dist);
	}
	
	
}


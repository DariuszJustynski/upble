<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class Biz extends CI_controller
{
   function __construct()
   {
		parent::__construct();
   }
   
   public function add()
   {
		
		if(!$this->tank_auth->is_logged_in())
		{
			redirect('/ucp/login/');
		}
		$this->load->helper('form');
		$biz=$this->get_form_data();
		$with_review=1;
		if(!empty($_POST)&&!isset($_POST['with_review']))
		{
			$with_review=0;
		}
		
		
		
		
		//validating
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('','');
		$this->form_validation->set_rules('city_id', 'City', 'trim|required|intval|max_length[20]|callback_city_check');
		$this->form_validation->set_rules('district_id', 'District', 'trim|intval|max_length[20]|callback_city_check');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('addrs1', 'Address', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('addrs2', 'Address2', 'trim|max_length[100]');
		$this->form_validation->set_rules('catid_1', 'Category', 'trim|required|intval|callback_cat_check');
		$this->form_validation->set_rules('catid_2', 'Category', 'trim|intval|callback_cat_check');
		$this->form_validation->set_rules('tel', 'Telephone', 'trim|xss_clean');
		$this->form_validation->set_rules('website', 'Website', 'trim|callback_website_check');
		$this->form_validation->set_rules('about', 'About', 'trim|max_length[2000]');
		if($with_review==1)
		{
			$this->form_validation->set_rules('rating', 'Rating', 'trim|required|intval|greater_than[0]|less_than[6]');
			$this->form_validation->set_rules('review', 'Review', 'trim|required|max_length[2000]|min_length[40]');
		}
		if($this->form_validation->run())
		{
			//get validated data
			$biz=$this->get_form_data();
			if($with_review==1)
			{
				$review=array(
					'uid'=>$this->tank_auth->get_user_id(),
					'username'=>$this->tank_auth->get_username(),
					'rating'=>$biz['rating'],
					'content'=>$biz['review'],
					
				);
				
				
			}
			unset($biz['rating']);
			unset($biz['review']);

			$biz['published']=1;
			$biz['created_at']=time();
			
			$biz['uid']=$this->tank_auth->get_user_id();
			$biz['username']=$this->tank_auth->get_username();
			//save business
			$this->load->model('bizs');
			$bizid = $this->bizs->add($biz);
			
			if( $bizid && $with_review )
			{
				$review['bizid'] = $bizid;
				$review['created_at']=time();
				$this->load->model('reviews');
				$this->reviews->add($review);
			}
			
			redirect('/biz/'.$bizid);
		
		}
		
		//get cities
		$this->load->model('catsAndCities','cc');
		$this->cc->set_table_name('city');
		$data['cities'] = $this->cc->get_top();
		
		if(!$biz['city_id'])
		{
			
			//$city=$data['cities'][0];
			$biz['city_id'] = 0;
			if($this->tank_auth->get_user_city())
				$biz['city_id']=$this->tank_auth->get_user_city()->id;
			
		}
		$data['districts']=$this->cc->get_children($biz['city_id']);
		
		//get categories
		$this->cc->set_table_name('category');
		$data['categories']=$this->cc->get_top();
		
		//get sub categories
		if(!empty($biz['catid_1']))
		{
			$data['subcats']=$this->cc->get_children($biz['catid_1']);
		}
		
		//$data['districts']=$this->cc->get_children();
		$data['biz']=$biz;
		$data['heading']="Add A Business";
		$data['with_review']=$with_review;
		$this->load->view('biz/add',$data);
   
   }
   
   public function show()
   {
		$id=$this->uri->segment(2);
		if(!$id || !is_numeric($id))
			show_404();
		$this->load->model("bizs");
		if(!($biz = $this->bizs->get($id)) || !$biz->published)
		{
			show_404();
		}
		$this->load->model('reviews');
		$biz->rating_stats = $this->reviews->get_rating_stats($biz->id);
		//get biz category info
		$biz = biz_cat_info($biz);
		
		// get biz rating stats
		$biz = biz_rate_stats($biz);
		
		// get photo stats
		$biz = biz_photo_stats($biz);
		
		$data['biz']=$biz;
		
		$pageData = $this->reviews->getPageData('/biz/'.$id.'/',3,array('bizid'=>$id));
		$data['pagination_links'] = $pageData['pagination_links'];
		$data['reviews'] = $pageData['list'];
		$biz->review_num = $pageData['count'];
		
		$data['first_review'] = $this->reviews->get_first_review($biz->id);
		
		
		if($this->tank_auth->is_logged_in())
		{   
			$uid=$this->tank_auth->get_user_id();
			if($my_review=$this->reviews->get(array('uid'=>$uid,'bizid'=>$biz->id)))
			{
				if(isset($data['reviews'][$my_review->id]))
				{
					unset($data['reviews'][$my_review->id]);
				}
				array_unshift($data['reviews'],$my_review);
				$data['my_review']=$my_review;
			}
		}
		
		
		$this->load->helper('form');
		$data['biz_map']=1;
		$data['heading']="{$biz->city->name} {$biz->name} ratings and reviews";
		$this->load->view('biz/show',$data);
   }
   
   public function edit($id)
   {
		
		if(!$this->tank_auth->is_admin() || !$id)
		{
			show_404();
		}
		$this->load->model('bizs');
		if(!($biz=$this->get_form_data()) && !($biz=$this->bizs->get($id)))
		{
			show_404();
		}
		if(!is_array($biz))
		{
			$biz=(array)$biz;
		}
			
		//validating
		$this->load->library('form_validation');
		$this->form_validation->set_rules('city_id', 'City', 'trim|required|intval|max_length[20]|callback_city_check');
		$this->form_validation->set_rules('district_id', 'District', 'trim|intval|max_length[20]|callback_city_check');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('addrs1', 'Address', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('addrs2', 'Address2', 'trim|xss_clean|max_length[100]');
		$this->form_validation->set_rules('catid_1', 'Category', 'trim|required|intval|callback_cat_check');
		$this->form_validation->set_rules('catid_2', 'Category', 'trim|intval|callback_cat_check');
		$this->form_validation->set_rules('tel', 'Telephone', 'trim|xss_clean');
		$this->form_validation->set_rules('website', 'Website', 'trim|callback_website_check');
		$this->form_validation->set_rules('about', 'About', 'trim|max_length[2000]');
		
		if($this->form_validation->run())
		{
			$biz=$this->get_form_data();
			
			$this->bizs->update($biz,$id);
			redirect('/biz/'.$id);
		}
		
		//get cities
		$this->load->model('catsAndCities','cc');
		$this->cc->set_table_name('city');
		$data['cities']=$this->cc->get_top();
		$data['districts']=$this->cc->get_children($biz['city_id']);
		
		//get categories
		$this->cc->set_table_name('category');
		$data['categories']=$this->cc->get_top();
		$data['subcats']=$this->cc->get_children($biz['catid_1']);
		
		$data['biz']=$biz;
		$data['heading']="Edit Business";
		$this->load->view('biz/add',$data);
		
		
   }
   public function del()
   {
		$this->load->model('bizs');
		$id = $this->input->post('id');
		if(!$this->tank_auth->is_admin() || !($biz=$this->bizs->get(intval($id))))
		{
			show_404();
		}
		$this->load->model('reviews');
		$this->load->model('photos');
		$this->bizs->delete($id);
		$this->reviews->del_list($id);
		
		//get all the photos of this business and delete them
		$photos = $this->photos->fetchList(1000,0,array('bizid'=>$id));
		foreach($photos as $photo)
		{
			$doc_root=FCPATH.'upload/biz_photos/'.$photo->folder.'/';
			@unlink($doc_root.$photo->id.'.jpg');
			@unlink($doc_root.$photo->id.'.thumb.jpg');			
		}
		redirect('/');
   }
   function search($keyword='')
	{
		
		if($this->input->post('q'))
		{
		  $q=urlencode($this->input->post('q'));
		 
		  redirect('/biz/search/'.$q);
		}
		
		$terms=urldecode(trim($keyword));
		$data['count']=0;
		$data['terms']=$terms;
		$data['bizs']=array();
		if($terms)
		{
			$this->load->model('bizs');
			$terms=mysql_real_escape_string($terms);
			//pagination
			$data['count']=$this->bizs->search_result_count($terms);
			$page=$this->uri->segment(4, 0);
			$this->load->library('pagination');
			$config['base_url'] =site_url('/biz/search/'.$keyword.'/');
			$config['uri_segment'] = 4;
			$config['per_page'] = 10;
			$config['total_rows']=$data['count'];
			$config['full_tag_open'] = "<p>";
			$config['full_tag_close'] = '</p>';
			$config['num_links'] =5;
			$this->pagination->initialize($config);
			$data['pagination_links'] = $this->pagination->create_links();
			//
			$data['bizs']=$this->bizs->search($terms,$config['per_page'],$page);
			
		}
		else{
			 redirect('/');
		}
		$data['heading']=$data['count']." Business in {$this->tank_auth->get_user_city()->name} matches '$terms'";
		$this->load->view('biz/search',$data);
	}
	
	//
	public function location()
	{
		if(!$this->tank_auth->is_logged_in())
		{
			die("<!--_LOGIN_REQUIRED-->");
		}
		
		$bizid = $this->input->post('id');
		$this->load->model("bizs");
		$biz = $this->bizs->get(intval($bizid));
		
		if(!$biz || ($biz->uid != $this->tank_auth->get_user_id() && !$this->tank_auth->is_admin()))
		{
			die("Invalid operation");
		}
		$lat = $this->input->post("lat",true);
		$lng = $this->input->post("lng",true);
		
		if($lat != $biz->location_x || $lng != $biz->location_y)
		{
			
			if($this->bizs->update(array('location_x'=>$lat,'location_y'=>$lng),$biz->id))
			echo 1;
		}
		
	}
 
   //get subcategories and districts
   public function get_children()
   {
		$parent_id=$this->uri->segment(3,0);
		$table=$this->uri->segment(4,'city');
		$this->load->model('catsAndCities','cc');
		$this->cc->set_table_name($table);
		$children=$this->cc->get_children($parent_id);
		$options='';
		if(!empty($children))
		{
			foreach($children as $c)
			{
				$options.='<option value="'.$c->id.'">'.$c->name.'</option>';
			}
		}
		if(!empty($options))$options='<option value=""></option>'.$options;
		if($table=='category'&&!empty($options))
		{
			$options="<select name='catid_2'>$options</select>";
		}
		echo $options;
   }
   
   public function city_check($city_id)
   {
		if(empty($city_id))return true;
		$this->load->model('catsAndCities','cc');
		$this->cc->set_table_name('city');
		if(!$this->cc->get($city_id))
		{
			$this->form_validation->set_message('city_check', "Invalid value");
			return false;
		}
		
		
		return true;
   }
   
   function c()
   {
	   	$this->load->model('catsAndCities','cc');
	   	$cons = array();
	   	$heading = '';
	   	
	   	//get city
	   	$city_slug = $this->uri->segment(3,'');
	   	$local = get_catorcity_by_field($city_slug,'city','slug');
	   	if(!$local)
	   	{
	   		show_404();
	   	}
	   	
	   	if($local->parent_id==0)
	   	{
	   	
	   		$data['city'] = $local;
	   		$data['districts'] = $this->cc->get_children($local->id);
	   	
	   		$cons['city_id'] = $local->id;
	   		$heading = $local->name;
	   	}
	   	else
	   	{
	   	
	   		$data['city'] = $this->cc->get($local->parent_id);
	   		$data['district'] = $local;
	   		$data['districts'] = $this->cc->get_children($data['city']->id);
	   	
	   		$cons['district_id'] = $local->id;
	   		$heading = $data['city']->name.' '.$local->name;
	   	}
	   	
	   	// get category
	   	$this->cc->set_table_name('category');
	   	$cat_slug = $this->uri->segment(4,'');
	   	$cat = $this->cc->get($cat_slug,'slug');
	   	if(!$cat)
	   	{
	   		show_404();
	   	}
	   	
	   	if($cat->parent_id == 0)
	   	{
	   	
	   		$data['top_cat'] = $cat;
	   		$data['sub_cats'] = $this->cc->get_children($cat->id);
	   		$cons['catid_1'] = $cat->id;
	   		$heading = $heading.' '.$cat->name;
	   	}
	   	else
	   	{
	   		$data['top_cat'] = $this->cc->get($cat->parent_id);
	   		$data['sub_cats'] = $this->cc->get_children($data['top_cat']->id);
	   		$data['sub_cat'] = $cat;
	   		$cons['catid_2'] = $cat->id;
	   	
	   		$heading = $heading.' '.$cat->name.' '.$this->cc->get($cat->parent_id)->name;
	   	}
	   	
	   	//get biz
	   	$this->load->model('bizs');
	   	$pageData = $this->bizs->getPageData('biz/c/'.$local->slug.'/'.$cat->slug.'/',5,$cons);
	   	$data['pagination_links'] = $pageData['pagination_links'];
	   	$data['bizs'] = $pageData['list'];
	   	
	   	$data['cat'] = $cat;
	   	$data['local'] = $local;
	   	$data['heading'] =$heading.' reviews';
	   	$this->load->view('biz/category',$data);
   }
   
   public function cat_check($cat_id)
   {
		if(empty($cat_id))return true;
		$this->load->model('catsAndCities','cc');
		$this->cc->set_table_name('category');
		if(!$this->cc->get($cat_id))
		{
			$this->form_validation->set_message('cat_check', "Invalid value");
			return false;
		}
		
		
		return true;
		
   }
  
   private function get_form_data()
   {
		if($this->uri->segment(2)=='edit'&&empty($_POST))
		{
			return false;
		}
		
		$biz=array(
		'city_id'=>$this->input->post("city_id"),
		'district_id'=>$this->input->post("district_id"),
		'name'=>$this->input->post("name"),
		'addrs1'=>$this->input->post("addrs1"),
		'addrs2'=>$this->input->post("addrs2"),
		'catid_1'=>$this->input->post("catid_1"),
		'catid_2'=>$this->input->post("catid_2"),
		'tel'=>$this->input->post("tel"),
		'website'=>$this->input->post("website"),
		'about'=>$this->input->post("about"),
		'location_x' => $this->input->post('location_x'),
		'location_y' => $this->input->post('location_y')	
		);
		if($this->uri->segment(2)=='add')
		{
			$biz['rating']=$this->input->post('rating');
			$biz['review']=$this->input->post('review');
		}
		if($this->uri->segment(2)=='edit')
		{

			$biz['published']=$this->input->post('published');
		}
		return $biz;
   
   }
  
   //adding test data 
   public function auto_add()
   {
		if(!$this->tank_auth->is_admin())
		{
			show_404();
		}
		$query = $this->db->query('select parent_id, id from city where parent_id !=0');
		$cities = array();
		foreach($query->result() as $row)
		{
			$cities[] = $row;
		}
		$query = $this->db->query('select parent_id, id from category where parent_id !=0');
		$cats = array();
		foreach($query->result() as $row)
		{
			$cats[] = $row;
		}
		$this->load->model('bizs');
		$num = 1;
		foreach( $cities as $c)
		{
			foreach($cats as $cat)
			{
				for($i = 0;$i < 5; $i++)
				{
					$biz = array(
						'city_id' => $c->parent_id,
						'district_id' => $c->id,
						'catid_1' => $cat->parent_id,
						'catid_2' => $cat->id,
						'name' => 'Golden Road Vioce '.$num,
						'published' =>1,
						'addrs1' => '168 Tianhe North Road',
						'website' => 'http://www.clonedinchina.com',
						'tel' => '020-87698976',
						'location_x' => '23.1410815',
						'location_y' => '113.3227619',
						'created_at' => time()
					
					);
					$this->bizs->add($biz);
					
					$num++;
				}
			}
		}
		
		echo $num.' biz added';
   
   }
   
}
?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class Local extends CI_controller
{
	
	
	function __construct()
   {
		parent::__construct();
   }
	public function index()
	{
		$this->city();
	}
	
	public function city()
	{
		$heading = '';
		$slug = $this->uri->segment(1,'');
		
		$city = null;
		if($slug)
		{
			$this->load->model('catsAndCities','cc');
			$this->cc->set_table_name('city');
			$city = $this->cc->get($slug,'slug');
			if(!$city || $city->parent_id != 0)
			{
				show_404();
			}
			else 
			{
				$this->tank_auth->set_user_city($city);
			}
		}
		else 
		{
			$city = $this->tank_auth->get_user_city();
		}
		
		// no city? send the master to do some job
		if(!$city)
		{
			$this->load->view('local/index');
			return;
		}
		
		$data['city'] = $city;
		$heading = $city->name.' ';
		
		$this->cc->set_table_name('category');
		$cats = $this->cc->get_top();
		
		
		// get bizs
		//*$this->load->model('bizs');
		foreach($cats as &$cat) 
		{
			$heading .= $cat->name.', ';
		}
		$data['cats'] = $cats;
		
		//get reviews
		$this->load->model('reviews');
		$data['reviews'] = $this->reviews->get_city_reviews($city->id,5,0);
		
		
		$this->load->helper("text");
		
		$data['heading'] = trim($heading,', ').' reviews';
		$this->load->view('local/index',$data);
		
	}
}
?>
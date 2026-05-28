<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Node extends CI_Controller
{
	private $tables = array('city','category','news_cats');
	private $alias = array('city'=>'City or Neighborhood','category'=>'Business Category');
	private $table = '';
	public function __construct()
	{
		parent::__construct();
		if(!$this->tank_auth->is_admin())
		{
			show_404();
		}
		$this->load->model('CatsAndCities','cc');
		$this->table=$this->uri->segment(4,'');
		if(!in_array($this->table,$this->tables))
		{
			die('table '.$this->table.' does not exist!');
		}
		
		$this->cc->set_table_name($this->table);
		
		
	}
	
	// admin/node/nodeList/city
	public function nodelist()
	{
		
		$nodes = $this->cc->get_all();
		$topNodes = array();
		$childNodes =  array();
		foreach($nodes as $node)
		{
			if($node->parent_id == 0)
			{
				$topNodes[] = $node;
			}
			else
			{
				$childNodes[$node->parent_id][]=$node;
			}
		}
		$data['topNodes'] = $topNodes;
		$data['childNodes'] = $childNodes;
		$data['table'] = $this->table;
		$data['alias'] = $this->alias;
		$this->load->view('admin/node/list',$data);
		
	}
	// admin/node/order/city set the order of nodes
	public function order()
	{
		
		$order = $this->input->post('order',true);
		$i = 1;
		$last = sizeof($order);
		foreach( $order as $k=>$v)
		{
			if($i<$last) $clear_cache = false;
			else $clear_cache = true;
			$this->cc->update(array('order'=>$v),$k,$clear_cache);
			$i++;
			
		}
	
		redirect('/admin/node/nodelist/'.$this->table);
	}
	// admin/node/add/city
	public function add()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','Name','required|max_length[20]|xss_clean');
		$this->form_validation->set_rules('slug','Slug','max_length[20]|xss_clean');
		$this->form_validation->set_rules('order','Order','intval');
		$this->form_validation->set_rules('parent_id','Parent Node','required|intval');
		$node = $this->get_form_data();
		if($this->form_validation->run())
		{
			$node = $this->get_form_data();
			if(!$node['slug'])
			{
				$node['slug'] = $this->slug($node['name']);
			}
			if(!$node['order'])
			{
				$node['order'] = 0;
			}
			$this->cc->add($node);
			redirect('admin/node/nodelist/'.$this->table);
		}
		$topNodes = $this->cc->get_top();
		$data['topNodes'] = $topNodes;
		$data['node'] = $node;
		$data['table'] = $this->table;
		$data['alias'] = $this->alias;
		$this->load->view('admin/node/add',$data);
	}
	
	public function edit()
	{
		$id = $this->uri->segment(5,'');
		if(!$id)
		{
			show_404();
		}
		$node = $this->get_form_data();
		if(!$node && !($node = $this->cc->get($id)))
		{
			show_404();
		}
		if(!is_array($node))$node=(array)$node;
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','Name','required|max_length[20]|xss_clean');
		$this->form_validation->set_rules('slug','Slug','max_length[20]|xss_clean');
		$this->form_validation->set_rules('order','Order','intval');
		$this->form_validation->set_rules('parent_id','Parent Node','required|intval');
		if($this->form_validation->run())
		{
			$node = $this->get_form_data();
			if(!$node['slug'])
			{
				$node['slug'] = slug($node['name']);
			}
			if(!$node['order'])
			{
				$node['order'] = 0;
			}
			$this->cc->update($node,$id);
			redirect('admin/node/nodelist/'.$this->table);
		}
		$topNodes = $this->cc->get_top();
		$data['topNodes'] = $topNodes;
		$data['node'] = $node;
		$data['table'] = $this->table;
		$data['alias'] = $this->alias;
		$this->load->view('admin/node/add',$data);
	}
	public function del()
	{
		$id = $this->uri->segment(5,'');
		if(!$id || !($node = $this->cc->get($id)))
		{
			show_404();
		}
		if($this->cc->hasChild($id))
		{
			$this->session->set_flashdata('error',$this->table.' '.$node->name.' has child node, can not be deleted');
		}
		else if($this->cc->hasBiz($id))
		{
			$this->session->set_flashdata('error',$this->table.' '.$node->name.' has business, can not be deleted');
		}
		else
		{
			$this->cc->delete($id);
			$this->session->set_flashdata('success',$this->table.' '.$node->name.' deleted');
		}
		redirect('/admin/node/nodelist/'.$this->table);	
		
		
	}
	private function slug($slug)
	{
		$slug = strtolower($slug);
		$slug = preg_replace('/\W+/','_',$slug);
		return $slug;
	}
	private function get_form_data()
	{
		if($this->uri->segment(3)=='edit'&&empty($_POST))
		{
			return false;
		}
		return array(
			'name' => $this->input->post('name'),
			'slug' => $this->input->post('slug'),
			'order' => $this->input->post('order'),
			'parent_id' => $this->input->post('parent_id'),
		
		);
	}
}

?>
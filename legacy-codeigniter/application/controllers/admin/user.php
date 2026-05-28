<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class User extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('usermodel');
	}
	
	function index()
	{
		$this->listing();
	}
	
	
	function listing()
	{
		$data = $this->usermodel->getPageData('admin/user/listing',4);
		$data['heading'] = 'User Management';
		$this->load->view('admin/user/list',$data);
	}
	
	public function edit($id)
	{
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('role','Role','required');
		$this->form_validation->set_rules('activated','Activated','required|intval');
		$this->form_validation->set_rules('banned','Banned','required|intval');
		$this->form_validation->set_rules('ban_reason','Banned Reason','max_length[500]');
		
		if($this->form_validation->run())
		{
			$user['role'] = $this->input->post('role',true);
			$user['activated'] = $this->input->post('activated');
			$user['banned'] = $this->input->post('banned');
			$user['ban_reason'] = $this->input->post('ban_reason');
			$this->usermodel->update($user,$id);
			$this->session->set_flashdata('success','User '.$this->input->post('username').' updated!');
			redirect('/admin/user');
		}
		
		
		$data['user'] =(array)$this->usermodel->get($id);
		$this->load->view('admin/user/add',$data);
		
	}
	
}
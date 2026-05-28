<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if(!$this->tank_auth->is_admin())
		{
			redirect(site_url('ucp/login'));
		}
		$this->load->model('reports');
	}
	
	public function index()
	{
		$this->listing();
	}
	
	public function listing()
	{
		$data = $this->reports->getPageData('admin/report/listing',4);
		$this->load->view('admin/report/list',$data);
		
	}
	
	public function del()
	{
		$id = $this->input->post('id',true);
		print_r($id);
		
		if(!$id || empty($id))
		{
			$this->session->set_flashdata('error','Invalid Access');
			redirect('admin/report/listing');
		}
		array_walk($id, function(&$val) { $val = (int)$val; });
		if($this->reports->del($id))
		{
			$this->session->set_flashdata('success','Successfully deleted the selected reports!');
		}
		else
		{
			$this->session->set_flashdata('error','unknown error!');
		}
		redirect('admin/report/listing');
	}
	
}


?>
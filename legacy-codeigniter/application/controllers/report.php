<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Report extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function add()
	{
		if(!$this->tank_auth->is_logged_in())
		{
			die("You need to login to do this");
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('url','url','trim|required|max_length[225]');
		$this->form_validation->set_rules('comment','Comment','trim|required|max_length[225]');
		if($this->form_validation->run())
		{
			$data = array(
				'uid' => $this->tank_auth->get_user_id(),
				'username' => $this->tank_auth->get_username(),
				'url' => $this->input->post('url'),	
				'comment' => $this->input->post('comment'),
				'created_at' => time()
			);
			
			$this->load->model('reports');
			if($this->reports->add($data))
			{
				echo '1';
			}
			else echo 'Unkown error happened, please try again';
		}
		else 
			echo $this->form_validation->error_string();
		
	}	
}
?>
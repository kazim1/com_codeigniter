<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Super_AdminController extends CI_Controller {
    
    public $view_path = null;
    
    public function __construct() {
        
        parent::__construct();
    
        $this->load->model('auth');
        
        
        try {
            $this->auth->is_admin_logged_in();
        }
        catch(Exception $e) {
            redirect(admin_site_url('login/auth'));
        }
        $this->lang->load('label', DEFAULT_LANG);
        $this->load->library('form_validation');
        $this->load_view_path();
    }
    
    public function load_view_path() {
        
        $controller_name      = strtolower($this->router->fetch_class());
        $action_name          = strtolower($this->router->fetch_method());
        
        $this->view_path = ADMIN_VIEW_PATH.$controller_name.'/'.$action_name;
            
        
    }
    
    protected function set_model($model) {
    
        $this->load->model($model);    
    
    }
    
    public function uploadify() {
    
        $config['upload_path']          = 'public/uploads/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['encrypt_name']         =  true; 
        
        
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('Filedata'))
		{
			$error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
            
            $data['cx_path']   =  $config['upload_path']; 
            
            echo json_encode($data);
		}   
    
    }
    
}
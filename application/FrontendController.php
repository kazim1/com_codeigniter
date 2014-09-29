<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Super_FrontendController extends CI_Controller {
    
    public $view_path = null;
    
    public function __construct() {
        
        parent::__construct();
    
        $this->lang->load('label', DEFAULT_LANG);
        $this->load_view_path();
    }
    
    public function load_view_path() {
        
        $controller_name      = strtolower($this->router->fetch_class());
        $action_name          = strtolower($this->router->fetch_method());
        
        $this->view_path      = $controller_name.'/'.$action_name;
            
        
    }
    
    protected function set_model($model) {
    
        $this->load->model($model);    
    
    }
    
    protected function load_captcha_img() {
        
        $this->load->helper('captcha');
        $captcha_val = rand(1000,9999);
        $this->session->set_userdata(array('captcha_val'=>$captcha_val));
        
        $args = array(
            'word'	=> $captcha_val,
            'img_path'	=> './public/captcha/',
            'img_url'	=> base_url('public/captcha').'/',
            'img_width'	=> '80',
            'img_height' => '36',
            'expiration' => 7200
            );

        $cap = create_captcha($args);
        return $cap['image'];
        
    }
    
    
    public function match_captcha($str) {
        
        if($str != $this->session->userdata('captcha_val')) {
            $this->form_validation->set_message('match_captcha', 'Invalid %s');
            return false;
        }
        return true;
        
    }
}
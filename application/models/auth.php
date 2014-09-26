<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Model {

    
    public function __construct() {
        
        parent::__construct();
    
    }
    
    
    public function login($authdata) {
        if(count($authdata) && count( array_filter($authdata) )) {
            
            $this->lang->load('error', DEFAULT_LANG);
            
            $this->load->library('encrypt');
            
            $username = $authdata['username'];
            $password = $authdata['password'];
            
            $admindata = $this->get_admin_info($username);
            
            if($admindata) {
                
                
                $dcrypted_password = $this->encrypt->decode($admindata->password);
                
                if($password == $dcrypted_password) {
                
                    $update_data['last_loggedin_ip']    = $this->input->ip_address();
                    $update_data['last_login_time']     = date('Y-m-d H:i:s');

                    $where['username']                  = $username;

                    if($this->update_admin($update_data,$where) > 0) {
                        
                        $this->session->set_userdata ('is_admin_logged_in',true);
                        $this->session->set_userdata ('admindata',$admindata);
                        
                        return;
                        
                    }                    
                    
                }
                
                
            }
            
            throw new Exception(lang('ADMIN_LOG_IN_FAILED_ERR'));
            
        
        }
        else {
            throw new Exception(lang('ADMIN_LOG_IN_INVALID_ERR'));
        }
    
    }
    
    
    private function get_admin_info($username) {
        
        return $this
            ->db
            ->select('username,password,last_loggedin_ip,last_login_time')
            ->where('username',$username)
            ->get(ADMIN_TABLE)
            ->row();
        
    }
    
    public function update_admin($data,$where) {
        $this
            ->db
            ->where($where)
            ->update(ADMIN_TABLE,$data);
        return $this->db->affected_rows();
    
    }
    
    public function is_admin_logged_in() {
        
        $admindata = $this->session->userdata('admindata');
        if($this->session->userdata('is_admin_logged_in') && $admindata) return true;
        throw new Exception(lang('ADMIN_LOG_IN_INVALID_ERR'));
            
    
    }
    
    public function logout_admin() {
        
        $this->session->unset_userdata('is_admin_logged_in');
        
        $this->session->unset_userdata('admindata');
        
    }

}
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    

function get_option($key = false) {
    
    $ci =& get_instance();
    
    if($key) {
        $row = $ci->db->select('value')->where(array('key'=> $key))->get('options')->row();
        if($row) {
            return $row->value;
        }
    }
    return '';
    
}

function set_option($key = "", $value = "") {
    
    $ci =& get_instance();
    
    $num_rows = $ci->db->select('value')->where(array('key'=> $key))->get('options')->num_rows();
    
    if($num_rows) {
        $ci->db->update('options',array('value'=>$value),array( 'key' => $key ));
    }
    else {
        $ci->db->insert('options',array('key'=>$key,'value'=>$value));
    }
    
    return $ci->db->affected_rows();
    
}



function trim_text($Text) {
	$Text = str_replace(chr(13), " ", $Text);
	$Text = str_replace(chr(10), " ", $Text);
	return $Text;
}


function get_currency_format($price = 0) {
    return '<div class="price">'.number_format($price).' <span class="c-code">'.get_option('currency_code').'</span></div>';
}
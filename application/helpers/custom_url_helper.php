<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function admin_site_url($arg) {
    if(preg_match('/^\s/',$arg)) {
        $arg = preg_replace('/^\s/','',$arg);
    }
    
    return site_url(ADMIN_SITE_FOLDER.$arg);

}

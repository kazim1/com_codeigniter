<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entity extends CI_Model {


    protected $row = null;
	
    protected $table = null;
    
    protected $attributes;
    
    protected $primary_key;
    
    protected $where_param = null;
    
    protected $where_in_param = null;
    
    protected $offset = 0;
    
    protected $limit = 10;
    
    protected $list = array();
    
    protected $select = null;
    
    protected $page_num = 1;
    
    public $total_rows;
    
     protected $url;
    
    function __construct() {
        
        if($this->table != null) {
            $attributes = $this->db->list_fields($this->table);
            $this->attributes = array_fill_keys($attributes,'');
        
            $fields = $this->db->field_data($this->table);

            foreach ($fields as $field) {
               if($field->primary_key) {
                   $this->primary_key = $field->name; 
                   break;
               }
            }
        }
        
    }
    
	function __call($name,$arg) {
        
		if(preg_match('/^get_/',$name)) {
			$field = preg_replace('/^get_/','',$name);
            return $this->attributes[$field];
		}
        else if(preg_match('/^set_/',$name)) {
            $field = preg_replace('/^set_/','',$name);
            $this->attributes[$field] = $arg[0];
            return $this;
        }
		else {
			throw new Exception('Method Not found');
		}
	
    }
	
    public function set_attribute($attributes) {
        
        if( !count(array_diff_key($attributes,$this->attributes)) ) {
            $this->attributes = $attributes;
            return $this;
        }
        else {
            throw new Exception('Invalid Data');
        }
        
    }
    
    public function get_primary_key() {
        
        return $this->primary_key;
    
    }
    
    public function select_field($select) {
    
        $this->select = $select;
        
        return $this;
    }
    
     public function set_table($table) {
        
        $this->table = $table;
        return $this;
    
    }
 
    public function set_limit($limit,$offset = 0) {
        
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    
    }
    
   public function set_page_num($page_num) {
    
       $this->page_num = $page_num;
        
       return $this;
   }
   
    public function set_where_in_param($where_in_param) {
        
        $this->where_in_param = $where_in_param;
        
        return $this;
        
    }
    
    public function set_where_param($where_param) {
        
        $this->where_param = $where_param;
        
        return $this;
        
    }
    
	public function load_row() {
		
        if($this->where_param != null) {
            
            $this->db->where($this->where_param);
            
        }
        
        if($this->select != null) {
            
            $this->db->select($this->select);
            
        }
        
		$this->row = $this->db->get($this->table)->row();
		$this->attributes = (array) $this->row ;
        return $this;
		
	}
    
   
    
    public function load_list() {
    
        if($this->where_param != null) {
            
            $this->db->where($this->where_param);
            
        }
        
        $this->total_rows = $this->db->count_all($this->table);
        
        
        if($this->where_param != null) {
            
            $this->db->where($this->where_param);
            
        }
        
        
        if($this->select != null) {
            
            $this->db->select($this->select);
            
        }
        
        if($this->limit) {
            
            $offset = $this->limit * ($this->page_num - 1); 
            
            $this->db->limit($this->limit,$offset);
        
        }
        
        $q = $this->db->get($this->table);
        
		$this->list = $q->result();
        
        return $this->list;
            
    }
    
    public function get_data() {
    
        return $this->row;
        
    }
    
    public function get_list() {
        
        return $this->list;
    
    }
   
    public function save() {
        
        if($this->where_param != null) {
            $this->db
                ->where($this->where_param)
                ->update($this->table,$this->attributes);    
        }
        else if( $this->attributes[$this->primary_key] ) {
            $id = $this->attributes[$this->primary_key];
            $this->db
                ->where($this->primary_key,$id)
                ->update($this->table,$this->attributes);    
        }
        else {
            $this->db
                ->insert($this->table,$this->attributes);
            $this->db->insert_id(); 
            $this->attributes[$this->primary_key] = $this->db->insert_id();
        }
        
        return $this;
    
    }
    
    public function flush() {
    
        $this->row = null;

        $this->where_param = null;

        $this->offset = 0;

        $this->limit = null;
        
        $this->page_num = 1;

        $this->list = array();

        $this->select = null;
        
        return $this;
        
    }
    
    public function get_pagination($uri_segment = 4) {
        
        if( $this->total_rows > $this->limit ) {
            
            $this->load->library('pagination');

            $config['base_url']             = $this->url;
            $config['total_rows']           = $this->total_rows;
            $config['per_page']             = $this->limit; 
            //$config['page_query_string']    = TRUE;
            $config['use_page_numbers']     = TRUE;
            //$config['query_string_segment'] = 'page';
            $config['uri_segment']            = $uri_segment;
            $this->pagination->initialize($config); 

            return $this->pagination->create_links();
        
        }
    
    }
    
    public function set_url($url) {
        $this->url = $url;
        return $this;
    }
    
    public function delete() {
        
        if($this->where_param != null) {
            
            $this->db->where($this->where_param);
            
        }
        
        if($this->where_in_param != null) {
            
            $this->db->where_in($this->where_in_param);
            
        }
        
        $this->db->delete($this->table);
        
    }
    
    
    public function save_batch_data($data,$update_param = false) {
        
        if($update_param) {
            $this->db->update_batch($this->table, $data, $update_param); 
        }
        else {
            $this->db->insert_batch($this->table, $data); 
        }
        
        return $this->db->affected_rows();
        
    }

}
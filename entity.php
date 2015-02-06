<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entity extends CI_Model {


    protected $row = null;
	
    protected $table = null;
    
    public $data;
    
    protected $primary_key;
    
    protected $where = null;
    
    protected $where_in_param = null;
    
    protected $offset = 0;
    
    protected $limit = 10;
    
    protected $list = array();
    
    protected $select = null;
    
    protected $page_num = 1;
    
    public $total_rows;
    
    protected $url;
    
    protected $orderby = null;
    
    protected $order = 'desc';
    
    protected $relation;
    
    protected $from;
    
    protected $escape = null;
    
    protected $table_alias = false;
    
    public $affected_rows = 0;
    
    function __construct() {
        
        if($this->table != null) {
            $data = $this->db->list_fields($this->table);
            $this->data = array_fill_keys($data,'');
        
            $fields = $this->db->field_data($this->table);

            foreach ($fields as $field) {
               if($field->primary_key) {
                   $this->primary_key = $field->name; 
                   break;
               }
            }
        }
        
    }
    
	public function __call($name,$arg) {
        
		if(preg_match('/^get_/',$name)) {
			$field = preg_replace('/^get_/','',$name);
            if(isset($this->data[$field])) return $this->data[$field];
            else return false;
            
		}
        else if(preg_match('/^set_/',$name)) {
            $field = preg_replace('/^set_/','',$name);
            $this->data[$field] = $arg[0];
            return $this;
        }
		else {
			throw new Exception('Method Not found');
		}
	
    }
	
	/*
	 * Method Setdata 
	 * @param data (array) required
	 * Set or override data
	 * @return current instance
	 */
	
    public function setData($data) {
        
        if( !count( array_diff_key($data,$this->data) ) ) {
        	
            $this->data = $data;
			
            return $this;
        
		}
        else {
            	
            $diff_data = json_encode(array_diff_key($data,$this->data));
            
            throw new Exception('Invalid Data: '.$diff_data);
        
		}
        
    }
    
	/*
	 * Method AddData 
	 * @param data (array) required
	 * append data to main data
	 * @return current instance
	 */
	
	
    public function addData($data) {
        
        if( !count(array_diff_key($data,$this->data)) ) {
            $this->data = array_merge($this->data,$data,$data);
            return $this;
        }
        else {
            $diff_data = json_encode(array_diff_key($data,$this->data));
            echo $diff_data;
            throw new Exception('Invalid Data: '.$diff_data);
        }
        
    }
    /*
	 * Method OrderBy 
	 * @param orderby (string) optional
	 * @param order (string) optional
	 * Set Order by Clause 
	 * @return current instance
	 */
	
    public function orderBy($orderby = null, $order = 'desc') {
        
        if($orderby == null && in_array($orderby,$this->data)) {
            
            $this->orderby = $this->getPrimaryKey();
            
        }
        else {
            $this->orderby = $orderby;
        }
        $this->order = $order;
        
        return $this;
    
    }
    
	/*
	 * GetPrimaryKey
	 * Get Current Table Primary Key if exists
	 * @return current table primary key column name
	 */
	
    public function getPrimaryKey() {
        
        return $this->primary_key;
    
    }
    
    /*
	 * Select
	 * @param select (string) optional
	 * @param escape (bool) optional 
	 * @return current instance
	 */
	
    
    public function select($select = '*',$escape = null) {
    
        $this->select = $select;
        $this->escape = $escape;
        
        return $this;
    }
    
	/*
	 * Table
	 * @param table (string) required 
	 * @return current instance
	 */
	
    public function table($table) {
        
        $this->table = $table;
        return $this;
    
    }
 	
	/*
	 * Limit
	 * @param limit (int) optional 
	 * @param offset (int) optional 
	 * @return current instance
	 */
 
    public function limit($limit = 10, $offset = 0) {
        
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    
    }
    
	/*
	 * Page
	 * @param page (int) required
	 * @return current instance
	 */
	
    public function page($page_num) {
    
       $this->page_num = $page_num;
        
       return $this;
   }
   
   /*
	 * Where In
	 * @param where_in_param (mixed) required
	 * @return current instance
	 */
   
    public function where_in($where_in_param) {
        
        $this->where_in_param = $where_in_param;
        
        return $this;
        
    }
    
	/*
	 * Where
	 * @param where (mixed) required
	 * @return current instance
	 */
	
    public function where($where) {
        
        $this->where = $where;
        
        return $this;
        
    }
    
	public function fetchRow() {
		
        if($this->where != null) {
            
            $this->db->where($this->where);
            
        }
        
        if($this->select != null) {
            
            $this->db->select($this->select);
            
        }
        
        if($this->relation && count($this->relation) > 0) {
            
            foreach($this->relation as $rel) {
                
                $rel['cond'] = isset($rel['cond']) ? $rel['cond'] : null;
                
                $rel['type'] = isset($rel['type']) ? $rel['type'] : null;
                
                $this->db->join($rel['join_table'], $rel['cond'], $rel['type']);    
                
            }
            
        }
        
        if($this->from) {
            
            $this->db->from($this->from);
            
            $q = $this->db->get();
        
        } else {
            
            $q = $this->db->get($this->table);
        
        }
        
		$this->row = $q->row();
		$this->data = (array) $this->row ;
        return $this;
		
	}
        
    public function fetchList() {
    
        if($this->where != null) {
            
            $this->db->where($this->where);
            
        }
        
        $this->total_rows = $this->db->count_all_results($this->table);
        
        
        if($this->where != null) {
            
            $this->db->where($this->where);
            
        }
        
        
        if($this->select != null) {
            
            $this->db->select($this->select, $this->escape);
            
        }
        
        if($this->limit) {
            
            $offset = $this->limit * ($this->page_num - 1); 
            
            $this->db->limit($this->limit,$offset);
        
        }
        
        if($this->orderby) {
            
            $this->db->order_by($this->orderby, $this->order);
        
        }
        
        
        if($this->relation && count($this->relation) > 0) {
            
            foreach($this->relation as $rel) {
                
                $rel['cond'] = isset($rel['cond']) ? $rel['cond'] : null;
                
                $rel['type'] = isset($rel['type']) ? $rel['type'] : null;
                
                $this->db->join($rel['join_table'], $rel['cond'], $rel['type']);    
                
            }
            
        }
        
        if($this->from) {
            
            $this->db->from($this->from);
            
            $q = $this->db->get();
        
        } else {
            
            $q = $this->db->get($this->table);
        
        }
        
        
		$this->list = $q->result();
        
        return $this->list;
            
    }
    
    public function getData() {
    
        return $this->row;
        
    }
    
    public function getList() {
        
        return $this->list;
    
    }
   
    public function save() {
        
        if($this->where != null) {
            $this->db
                ->where($this->where)
                ->update($this->table,$this->data);   
        }
        else if( isset($this->data[$this->primary_key]) && $this->data[$this->primary_key] ) {
            $id = $this->data[$this->primary_key];
            $this->db
                ->where($this->primary_key,$id)
                ->update($this->table,$this->data);
            
        }
        else {
            $this->db
                ->insert($this->table,$this->data);
            $this->data[$this->primary_key] = $this->db->insert_id();
        }
        
        $this->affected_rows = $this->db->affected_rows();
        
        return $this;
    
    }
    
    public function flush() {
    
        $this->row = null;

        $this->relation = null;
        
        $this->select = null;
        
        $this->where = null;

        $this->offset = 0;

        $this->limit = null;
        
        $this->from = null;
        
        $this->orderby = null;
        
        $this->order = null;
        
        $this->page_num = 1;

        $this->list = array();

        $this->select = null;
        
        if($this->table != null) {
            $data = $this->db->list_fields($this->table);
            $this->data = array_fill_keys($data,'');
        
            $fields = $this->db->field_data($this->table);

            foreach ($fields as $field) {
               if($field->primary_key) {
                   $this->primary_key = $field->name; 
                   break;
               }
            }
        }
        
        return $this;
        
    }
    
    public function getPagination($uri_segment = 4) {
        
        if( $this->total_rows > $this->limit ) {
            
            $this->load->library('pagination');

            $config['base_url']             = $this->url;
            $config['total_rows']           = $this->total_rows;
            $config['per_page']             = $this->limit; 
            //$config['page_query_string']    = TRUE;
            $config['use_page_numbers']     = TRUE;
            //$config['query_string_segment'] = 'page';
            $config['uri_segment']            = $uri_segment;
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';   
            $config['full_tag_open'] = '<ul>';
            $config['full_tag_close'] = '</ul>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $this->pagination->initialize($config); 

            return $this->pagination->create_links();
        
        }
    
    }
    
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }
    
    public function delete() {
        
        if($this->where != null) {
            
            $this->db->where($this->where);
            
        }
        
        if($this->where_in_param != null) {
            
            $this->db->where_in($this->where_in_param);
            
        }
        
        $this->db->delete($this->table);
        
    }
    
    public function saveBatch($data,$update_param = false) {
        
        if($update_param) {
            $this->db->update_batch($this->table, $data, $update_param); 
        }
        else {
            $this->db->insert_batch($this->table, $data); 
        }
        
        return $this->db->affected_rows();
        
    }

    public function relation($relation) {
        $this->relation = $relation;
        
        return $this;
        
    }
    
    public function from($from) {
        
        $this->from = $from;
        
        return $this;
        
    }
    
    protected function tableAlias($table_alias) {
        
        $this->table_alias = $table_alias;
        
    }
}
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tables = [
        	'orders' => 'orders',
            'party'         => 'party'
        ];
    }

     public function insert($data) {
        $this->db->insert($this->tables['orders'], $data);
        $id= $this->db->insert_id();
        if(!empty($id))
        {
            return $id;
        }else{
            return false;
        }
    }

    public function get_order_data()
    {
    	 $this->db->select('id,order_number');
        $this->db->order_by($this->tables['orders'].'.id', 'DESC');
        $this->db->limit(1);
    	$query = $this->db->get($this->tables['orders']);
    	if ($query->num_rows() > 0) {
            $res= $query->result();
            $order_number=date('dmY').$res[0]->id+1;
        }else{
          $no=1;
          $order_number=date('dmY').$no;
        }
       return $order_number;
    }

     public function update_party($data, $id) {
        return $this->db->update($this->tables['party'], $data, ['id' => $id]);
    }

    public function get_party_data($pid='')
    {
         $this->db->select('id,pr_start_date,pr_end_date');
         if(!empty($pid))
         {
          $this->db->where([$this->tables['party'].'.id' => $pid]);
         }
         $this->db->order_by($this->tables['party'].'.id', 'DESC');
         $query = $this->db->get($this->tables['party']);
        if ($query->num_rows() > 0) {
            $res= $query->result();
            if(!empty($pid))
             {
                if($res[0]->pr_start_date==date('Y-m-d'))
                {
                    //$this->update_party(array('papular_status'=>1),$pid);
                    return true;
                }else{
                    return false;
                }
             }else{  
                return $res;
             }
        }else{
            return false;
        }
      
    }
}
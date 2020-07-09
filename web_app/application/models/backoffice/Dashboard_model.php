<?php
class Dashboard_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		        
    }
    //get name count
    public function getRestaurantCount()
    {
    	return $this->db->get('restaurant')->num_rows();
    }
    //get restaurant
    public function restaurant(){
        $this->db->select('entity_id, name,phone_number,email');
        $this->db->order_by('entity_id','desc');
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('created_by',$this->session->userdata('UserID')); 
        }
        $this->db->limit(5, 0);
        return $this->db->get('restaurant')->result(); 
    }	
    //get total user account
    public function gettotalAccount()
    {
    	return $this->db->get('users')->num_rows();
    }	
    //get order count
    public function getOrderCount()
    {
    	return $this->db->get('order_master')->num_rows();
    }
    public function getLastOrders(){
        $this->db->select('o.total_rate as rate,o.order_date,o.order_status as ostatus,o.status,o.entity_id as entity_id,u.first_name as fname,u.last_name as lname');
        $this->db->join('users as u','o.user_id = u.entity_id','left');
        $this->db->join('restaurant','o.restaurant_id = restaurant.entity_id');
        $this->db->order_by('o.entity_id','desc');
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('restaurant.created_by',$this->session->userdata('UserID'));
        }
        $this->db->limit(5, 0);
        return $this->db->get('order_master as o')->result(); 
    }			
}
?>
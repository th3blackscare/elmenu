<?php
class Order_model extends CI_Model {
	function __construct()
	{
		parent::__construct();		        
	}	
	// method for getting all
	public function getGridList($sortFieldName = '', $sortOrder = 'DESC', $displayStart = 0, $displayLength = 10,$order_status)
	{
		if($this->input->post('page_title') != ''){
			$this->db->where("CONCAT(u.first_name,' ',u.last_name) like '%".$this->input->post('page_title')."%'");
		}
		if($this->input->post('Status') != ''){
			$this->db->like('o.status', $this->input->post('Status'));
		}
		if($this->input->post('order_total') != ''){
			$this->db->like('o.total_rate', $this->input->post('order_total'));
		}
		if($this->input->post('order_status') != ''){
			$this->db->like('o.order_status', $this->input->post('order_status'));
		}
		$this->db->select('o.total_rate as rate,o.order_status as ostatus,o.status,o.entity_id as entity_id,u.first_name as fname,u.last_name as lname,u.entity_id as user_id,order_status.order_status as orderStatus');
		$this->db->join('users as u','o.user_id = u.entity_id','left');
		//$this->db->join('restaurant','o.restaurant_id = restaurant.entity_id','left');
		$this->db->join('order_status','o.entity_id = order_status.order_id','left');
		if($this->session->userdata('UserType') == 'Admin'){
			$this->db->where('restaurant.created_by',$this->session->userdata('UserID'));
		}
		if($order_status){
			$this->db->where('o.order_status',$order_status);
		}
		$this->db->group_by('o.entity_id');
		$result['total'] = $this->db->count_all_results('order_master as o');

		if($sortFieldName != '')
			$this->db->order_by($sortFieldName, $sortOrder);
		if($this->input->post('page_title') != ''){
			$this->db->where("CONCAT(u.first_name,' ',u.last_name) like '%".$this->input->post('page_title')."%'");
		}
		if($this->input->post('Status') != ''){
			$this->db->like('o.status', $this->input->post('Status'));
		}
		if($this->input->post('order_total') != ''){
			$this->db->like('o.total_rate', $this->input->post('order_total'));
		}
		if($this->input->post('order_status') != ''){
			$this->db->like('o.order_status', $this->input->post('order_status'));
		}
		if($displayLength>1)
			$this->db->limit($displayLength,$displayStart);  
		$this->db->select('o.total_rate as rate,o.order_status as ostatus,o.status,o.entity_id as entity_id,u.first_name as fname,u.last_name as lname,u.entity_id as user_id,order_status.order_status as orderStatus');
		$this->db->join('users as u','o.user_id = u.entity_id','left');   
		//$this->db->join('restaurant','o.restaurant_id = restaurant.entity_id','left'); 
		$this->db->join('order_status','o.entity_id = order_status.order_id','left');
		if($order_status){
			$this->db->where('o.order_status',$order_status);
		}  
		if($this->session->userdata('UserType') == 'Admin'){
			$this->db->where('restaurant.created_by',$this->session->userdata('UserID'));
		}
		$this->db->group_by('o.entity_id');
		$result['data'] = $this->db->get('order_master as o')->result();     
		return $result;
	}		
	// method for adding 
	public function addData($tblName,$Data)
	{   
		$this->db->insert($tblName,$Data);            
		return $this->db->insert_id();
	} 
	// method for adding 
	public function addBatch($tblName,$Data)
	{   
		$this->db->insert_batch($tblName,$Data);            
		return $this->db->insert_id();
	}
	// method to get details by id
	public function getEditDetail($entity_id)
	{
		$this->db->select('order.*,res.name, address.address,address.landmark,address.city,address.zipcode,u.first_name,u.last_name,uaddress.address as uaddress,uaddress.landmark as ulandmark,uaddress.city as ucity,uaddress.zipcode as uzipcode');
		$this->db->join('restaurant as res','order.restaurant_id = res.entity_id','left');
		$this->db->join('restaurant_address as address','res.entity_id = address.resto_entity_id','left');
		$this->db->join('users as u','order.user_id = u.entity_id','left');
		$this->db->join('user_address as uaddress','u.entity_id = uaddress.user_entity_id','left');
		return  $this->db->get_where('order_master as order',array('order.entity_id'=>$entity_id))->first_row();
	}
	// update data common function
	public function updateData($Data,$tblName,$fieldName,$ID)
	{        
		if($tblName == 'order_item'){
			$this->db->where('order_id',$ID);
			$this->db->delete($tblName);
			//insert
			$this->db->insert_batch($tblName,$Data);
			return $this->db->insert_id();
		}else{
			$this->db->where($fieldName,$ID);
			$this->db->update($tblName,$Data);            
			return $this->db->affected_rows();
		}
	}
	 // updating the changed
	public function UpdatedStatus($tblname,$entity_id,$status){
		if($status==0){
			$userData = array('status' => 1);
		} else {
			$userData = array('status' => 0);
		}        
		$this->db->where('entity_id',$entity_id);
		$this->db->update($tblname,$userData);
		return $this->db->affected_rows();
	}
	// delete
	public function ajaxDelete($tblname,$entity_id)
	{
		$this->db->delete($tblname,array('entity_id'=>$entity_id));  
	}
	//get list
	public function getListData($tblname){
		if($tblname == 'users'){
			$this->db->select('first_name,last_name,entity_id');
			$this->db->where('status',1);
			$this->db->where('user_type !=','MasterAdmin');
			if($this->session->userdata('UserType') == 'Admin'){
				$this->db->where('created_by',$this->session->userdata('UserID'));  
			}        
			return $this->db->get($tblname)->result();
		}else{
			$this->db->select('name,entity_id,amount_type,amount');
			$this->db->where('status',1);
			if($this->session->userdata('UserType') == 'Admin'){
				$this->db->where('created_by',$this->session->userdata('UserID'));  
			} 
			return $this->db->get($tblname)->result();
		}
	}
	//get items
	public function getItem($entity_id){
		$this->db->select('entity_id,name,price');
		$this->db->where('restaurant_id',$entity_id);
		$this->db->where('status',1);
		if($this->session->userdata('UserType') == 'Admin'){
			$this->db->where('created_by',$this->session->userdata('UserID'));  
		} 
		return $this->db->get('restaurant_menu_item')->result();
	}
	//get address
	public function getAddress($entity_id){
		$this->db->where('user_entity_id',$entity_id);
		return $this->db->get('user_address')->result();
	}
	//get menu item
	public function getMenuItem($entity_id)
	{
		$this->db->select('order_item.*,restaurant_menu_item.name');
		$this->db->join('restaurant_menu_item','order_item.item_id = restaurant_menu_item.entity_id','left');
		$this->db->where('order_id',$entity_id);
		return $this->db->get('order_item')->result();
	}
	//get invoice data
	public function getInvoiceMenuItem($entity_id){
		$this->db->where('order_id',$entity_id);
		return $this->db->get('order_detail')->first_row();
	}
	//get user data
	public function getUserDate($entity_id){
		$this->db->select('device_id');
		$this->db->where('entity_id',$entity_id);
		return $this->db->get('users')->first_row();
	}
	//delete multiple order
	public function deleteMultiOrder($order_id){
		$this->db->where_in('entity_id',$order_id);
		$this->db->delete('order_master');
		return $this->db->affected_rows();
	}
}
?>
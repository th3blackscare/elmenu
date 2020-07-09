<?php
class Users_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		        
    }	
    // method for getting all users
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if($this->input->post('page_title') != ''){
            $this->db->like('first_name', $this->input->post('page_title'));
        }
        if($this->input->post('phone') != ''){
            $this->db->like('mobile_number', $this->input->post('phone'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        $this->db->where('user_type !=','MasterAdmin');
        $this->db->where('status !=',2);   
        $result['total'] = $this->db->count_all_results('users');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('page_title') != ''){
            $this->db->like('first_name', $this->input->post('page_title'));
        }
        if($this->input->post('phone') != ''){
            $this->db->like('mobile_number', $this->input->post('phone'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart); 
        $this->db->where('user_type !=','MasterAdmin');  
        $this->db->where('status !=',2);     
        $result['data'] = $this->db->get('users')->result();        
        return $result;
    }		
    // method for adding users
    public function addData($tblName,$Data)
    {   
        $this->db->insert($tblName,$Data);            
        return $this->db->insert_id();
    } 
    // method to get user details by id
    public function getEditDetail($tblname,$entity_id)
    {
        return $this->db->get_where($tblname,array('entity_id'=>$entity_id))->first_row();
    }
    // delete user
    public function deleteUser($tblname,$entity_id)
    {
        if($entity_id  != ''){
            $userData = array('status' => 2);
        } 
        $this->db->where('entity_id',$entity_id);
        $this->db->update('users',$userData);
        return $this->db->affected_rows();
  		//$this->db->delete($tblname,array('entity_id'=>$entity_id));  
    }
    // update data common function
    public function updateData($Data,$tblName,$fieldName,$ID)
    {        
        $this->db->where($fieldName,$ID);
        $this->db->update($tblName,$Data);            
        return $this->db->affected_rows();
    }
    // updating the changed status
	public function UpdatedStatus($entity_id,$status){
        if($status==0){
            $userData = array('status' => 1);
        } else {
            $userData = array('status' => 0);
        }        
        $this->db->where('entity_id',$entity_id);
        $this->db->update('users',$userData);
        return $this->db->affected_rows();
    }
    //get users
    public function getUsers(){
        $this->db->select('first_name,last_name,entity_id');
        $this->db->where('user_type !=','MasterAdmin');       
        return $this->db->get('users')->result();
    }
    //address grid
    public function getAddressGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if($this->input->post('page_title') != ''){
            $this->db->like('u.first_name', $this->input->post('page_title'));
        }
        if($this->input->post('page_title') != ''){
            $this->db->like('u.last_name', $this->input->post('page_title'));
        }
        if($this->input->post('address') != ''){
            $this->db->like('address.address', $this->input->post('address'));
        }
        $this->db->select('address.entity_id,address.address,u.first_name,u.last_name');
        $this->db->join('users as u','address.user_entity_id = u.entity_id','left');
        $this->db->where('u.user_type !=','MasterAdmin');
        $result['total'] = $this->db->count_all_results('user_address as address');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('page_title') != ''){
            $this->db->like('u.first_name', $this->input->post('page_title'));
        }
        if($this->input->post('page_title') != ''){
            $this->db->like('u.last_name', $this->input->post('page_title'));
        }
        if($this->input->post('address') != ''){
            $this->db->like('address.address', $this->input->post('address'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);
        $this->db->select('address.entity_id,address.address,u.first_name,u.last_name');
        $this->db->join('users as u','address.user_entity_id = u.entity_id','left'); 
        $this->db->where('u.user_type !=','MasterAdmin');       
        $result['data'] = $this->db->get('user_address as address')->result();        
        return $result;
    }   
    public function checkExist($mobile_number,$entity_id){
        $this->db->where('mobile_number',$mobile_number);
        $this->db->where('entity_id !=',$entity_id);
        return $this->db->get('users')->num_rows();
    }
    public function checkEmailExist($email,$entity_id){
        $this->db->where('email',$email);
        $this->db->where('entity_id !=',$entity_id);
        return $this->db->get('users')->num_rows();
    }
}
?>
<?php
class Inv extends CI_Controller { 
    public $module_name = 'Inv';
    public $controller_name = 'inv';
    public $prefix = '_inv';
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/order_model');
    }
    

    //create invoice
    public function getInvoice(){
        $entity_id = ($this->input->post('entity_id'))?$this->input->post('entity_id'):'';
        $data['order_records'] = $this->order_model->getEditDetail($entity_id);
        $data['menu_item'] = $this->order_model->getInvoiceMenuItem($entity_id);
        $html = $this->load->view('backoffice/order_invoice',$data,true);
        if (!@is_dir('uploads/invoice')) {
          @mkdir('./uploads/invoice', 0777, TRUE);
        } 
        $filepath = 'uploads/invoice/'.$entity_id.'.pdf';
        $this->load->library('M_pdf'); 
        $mpdf=new mPDF('','Letter'); 
        $mpdf->SetHTMLHeader('');
        $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://restaura.evdpl.com/~restaura/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
        $mpdf->AddPage('', // L - landscape, P - portrait 
            '', '', '', '',
            0, // margin_left
            0, // margin right
            10, // margin top
            23, // margin bottom
            0, // margin header
            0 //margin footer
        );
        $mpdf->WriteHTML($html);
        $mpdf->output($filepath,'F');
        echo $filepath;
          
    }
    //add status
    public function updateOrderStatus(){
        $entity_id = ($this->input->post('entity_id'))?$this->input->post('entity_id'):''; 
        $user_id = ($this->input->post('user_id'))?$this->input->post('user_id'):'';
        if($entity_id){
            $this->db->set('order_status',$this->input->post('order_status'))->where('entity_id',$entity_id)->update('order_master');
            $addData = array(
                'order_id'=>$entity_id,
                'order_status'=>$this->input->post('order_status'),
                'time'=>date('Y-m-d H:i:s')
            );
            $order_id = $this->order_model->addData('order_status',$addData);
            $userdata = $this->order_model->getUserDate($user_id);
            $message = "حالة طلبك الأن : ".$this->input->post('order_status')."";
            $device_id = $userdata->device_id;
            $this->sendFCMRegistration($device_id,$message);
        }
    }
    // Send notification
    function sendFCMRegistration($registrationIds,$message) {   
        if($registrationIds){        
            #prep the bundle
            $fields = array();            
           
            $fields['to'] = $registrationIds; // only one user to send push notification
            $fields['notification'] = array ('body'  => $message,'sound'=>'default');
            $fields['data'] = array ('screenType'=>'order');
           
            $headers = array (
                'Authorization: key=' . FCM_KEY,
                'Content-Type: application/json'
            );
            #Send Reponse To FireBase Server    
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch);
            curl_close($ch);            
        } 
    }
}
?>
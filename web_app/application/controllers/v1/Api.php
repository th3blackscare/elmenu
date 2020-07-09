<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(-1);
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('v1/api_model');                
        $this->load->library('form_validation');
    }
    // Registration API
    public function registration_post()
    {
        if($this->post('FirstName') !="" && $this->post('PhoneNumber') != "" && $this->post('Password') !="")
        {
            $checkRecord = $this->api_model->getRecord('users', 'mobile_number',$this->post('PhoneNumber'));
            if(empty($checkRecord))
            {
                $addUser = array(
                    'mobile_number'=>trim($this->post('PhoneNumber')),
                    'first_name'=>trim($this->post('FirstName')),
					'country'=>trim($this->post('country')),
                    'last_name'=>'',
					'active'=> '1',
					'image'=>'profile/def.jpg',
                    'password'=>md5(SALT.$this->post('Password')),
                    'user_type'=>'User',
                    'status'=>1                
                );
                $UserID = $this->api_model->addRecord('users', $addUser);
                $login = $this->api_model->getRegisterRecord('users',$UserID);
                if($UserID)
                {
                    $this->response(['User' => $login,'active'=>true,'status'=>1,'message' => $this->lang->line('registration_success')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    $data = array('device_id'=>$this->post('firebase_token'));
                    $this->api_model->updateUser('users',$data,'entity_id',$UserID);
                    
                }
                else
                {
                    $this->response([
                        'status' => 0,
                        'message' => $this->lang->line('registration_fail')
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                }                        
            }
            else
            {
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('user_exist')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code            
            }
        }
        else
        {
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('regi_validation')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code                        
        }
    }
    // Login API
    public function login_post()
    {
        $login = $this->api_model->getLogin($this->post('PhoneNumber'), $this->post('Password'));
        if(!empty($login)){
            if($login->active == 1){
                $data = array('active'=>1,'device_id'=>$this->post('firebase_token'));
                if($login->status==1)
                {
                    // update device 
                    $image = ($login->image)?image_url.$login->image:'';               
                    $this->api_model->updateUser('users',$data,'entity_id',$login->entity_id);
                    $last_name = ($login->last_name)?$login->last_name:'';
                    $login_detail = array('FirstName'=>$login->first_name,'LastName'=>$last_name,'image'=>$image,'PhoneNumber'=>$login->mobile_number,'UserID'=>$login->entity_id,'notification'=>$login->notification);
                    $this->response(['login' => $login_detail,'status'=>1,'active'=>true,'message' =>$this->lang->line('login_success') ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    //$this->sendFCMRegistration($this->post('firebase_token'));
                } else if ($login->status==0){
                    $adminEmail = $this->api_model->getSystemOptoin('Admin_Email_Address');
                    $this->response(['status' => 2,'message' => $this->lang->line('login_deactive'),'email'=>$adminEmail->OptionValue], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                } 
            }else{
                $this->response([
                    'status' => 0,
                    'active' => false,
                    'message' => $this->lang->line('otp_inactive')
                ], REST_Controller::HTTP_OK);
            }
        }        
        else
        {
            $emailexist = $this->api_model->getRecord('users','mobile_number',$this->post('PhoneNumber'));
            if($emailexist){
                $this->response([
                    'status' => 0,
                    'message' =>$this->lang->line('pass_validation')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            } else {
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        }        
    }

	public function AdminLogin_post()
	{
		$login = $this->api_model->getAdminLogin($this->post('user'), $this->post('Password'));
		if(!empty($login)){
			if($login->active == 1){
				//$data = array('active'=>1,'device_id'=>$this->post('firebase_token'));
				if($login->status==1)
				{
					// update device
					//$image = ($login->image)?image_url.$login->image:'';
					//$this->api_model->updateUser('users',$data,'entity_id',$login->entity_id);
					//$last_name = ($login->last_name)?$login->last_name:'';
					$login_detail = array('Name'=>$login->Name,'user_name'=>$login->user_name,'UserID'=>$login->entity_id);
					$this->response(['login' => $login_detail,'status'=>1,'active'=>true,'message' =>$this->lang->line('login_success') ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
					$this->sendFCMRegistration($this->post('firebase_token'));
				} else if ($login->status==0){
					$adminEmail = $this->api_model->getSystemOptoin('Student_Email_Address');
					$this->response(['status' => 2,'message' => $this->lang->line('login_deactive'),'email'=>$adminEmail->OptionValue], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				}
			}else{
				$this->response([
					'status' => 0,
					'active' => false,
					'message' => $this->lang->line('otp_inactive')
				], REST_Controller::HTTP_OK);
			}
		}
		else
		{
			$emailexist = $this->api_model->getRecord('users','user_name',$this->post('user_name'));
			if($emailexist){
				$this->response([
					'status' => 0,
					'message' =>$this->lang->line('pass_validation')
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			} else {
				$this->response([
					'status' => 0,
					'message' => $this->lang->line('not_found')
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
	}


	//verify OTP
    public function verifyOTP_post()
    {
        $login = $this->api_model->getLogin($this->post('PhoneNumber'), $this->post('Password'));
        if(!empty($login)){
            if($this->post('active') == 1){
                $data = array('active'=>1);
                $this->api_model->updateUser('users',$data,'entity_id',$login->entity_id);
                 $image = ($login->image)?image_url.$login->image:'';  
                 $last_name = ($login->last_name)?$login->last_name:'';
                 $login_detail = array('FirstName'=>$login->first_name,'LastName'=>$last_name,'image'=> $image ,'PhoneNumber'=>$login->mobile_number,'UserID'=>$login->entity_id,'notification'=>$login->notification);
                $this->response(['login' => $login_detail,'active'=>true,'status'=>1,'message' => 'success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else{
                $this->response([
                    'status' => 0,
                    'active' => false,
                    'message' => $this->lang->line('otp_inactive')
                ], REST_Controller::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //get homepage
    public function getHome_post(){
        //for event
        if($this->post('isEvent') == 1){
            $latitude = ($this->post('latitude'))?$this->post('latitude'):'';
            $longitude = ($this->post('longitude'))?$this->post('longitude'):'';
            $searchItem = ($this->post('itemSearch'))?$this->post('itemSearch'):'';
            $restaurant = $this->api_model->getEventRestaurant($latitude,$longitude,$searchItem);
            if(!empty($restaurant)){
               $this->response([
                    'restaurant'=>$restaurant,
                    'status' => 1,
                    'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code 
            }else{
                $this->response([
                    'status' => 1,
                    'message' => $this->lang->line('not_found')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }  
        }else{ // for home page
            if($this->post('latitude') !="" && $this->post('longitude') != "")
            {
                $food = $this->post('food');
                $rating = $this->post('rating');
                $distance = $this->post('distance');
                $searchItem = ($this->post('itemSearch'))?$this->post('itemSearch'):'';
                $restaurant = $this->api_model->getHomeRestaurant($this->post('latitude'),$this->post('longitude'),$searchItem,$food,$rating,$distance);
                $slider = $this->api_model->getbanner();
                $category = $this->api_model->getcategory();
                $this->response([
                    'restaurant'=>$restaurant,
                    'slider'=>$slider,
                    'category'=>$category,
                    'status' => 1,
                    'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else{
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code                        
            }
        }
    }
    public function  getStudents_get(){
    	$students = $this->api_model->getStudents();
    	$this->response([
    		'Student'=>$students,
			'status'=> 1,
			'message' => $this->lang->line('record_found')]);
	}

	public function  getMenu_get(){
		$menu = $this->api_model->getMenu();
		$this->response([
			'menu'=>$menu,
			'status'=> 1,
			'message' => $this->lang->line('record_found')]);
	}

	public function  getMenuCategory_get(){
		$category = $this->api_model->getMenuCategory();
		$this->response([
			'category'=>$category,
			'status'=> 1,
			'message' => $this->lang->line('record_found')]);
	}
	public function  getOffers_get(){
    	$offers = $this->api_model->getOffers();
		$this->response([
			'offer'=>$offers,
			'status'=> 1,
			'message' => $this->lang->line('record_found')]);
	}
    // Forgot Password
    public function forgotpassword_post()
    {
        $checkRecord = $this->api_model->getRecordMultipleWhere('users', array('mobile_number'=>$this->post('PhoneNumber'),'status'=>1));
        if(!empty($checkRecord))
        {
            $activecode = substr(md5(uniqid(mt_rand(), true)) , 0, 8);
            $password = random_string('alnum', 8);
            $data = array('active_code'=>$activecode,'password'=>md5(SALT.$password));
            $this->api_model->updateUser('users',$data,'mobile_number',$this->post('PhoneNumber'));
            $this->response(['status' => 1,'password'=>$password,'message' => $this->lang->line('success_password_change')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('user_not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code            
        }
    }
    // Get CMS Pages
    public function getCMSPage_post()
    {    
        $entity_id  = $this->post('cms_id');  
        $cmsData = $this->api_model->getCMSRecord('cms',$entity_id); 
        if ($cmsData)
        {
            $this->response([
                'cmsData'=>$cmsData,
                'status' => 1,
                'message' => $this->lang->line('found')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //add review
    public function addReview_post(){
        if($this->post('rating') != '' && $this->post('review') != ''){
            $add_data = array(
                'rating'=>trim($this->post('rating')),
                'review'=>trim($this->post('review')),
                'restaurant_id'=>$this->post('restaurant_id'),
                'user_id'=>$this->post('user_id'),
                'status'=>1,
                'created_date'=>date('Y-m-d H:i:s')                
            );
            $this->api_model->addRecord('review', $add_data);
            $this->response(['status'=>1,'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }else{
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('validation')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //get restaurant
    public function getRestaurantDetail_post(){
        if($this->post('restaurant_id')){
            $details = $this->api_model->getRestaurantDetail($this->post('restaurant_id'));
            $popular_item = $this->api_model->getPopularItem($this->post('restaurant_id'));
            $menu_item = $this->api_model->getMenuItem($this->post('restaurant_id'),$this->post('food'),$this->post('price'));
            $review = $this->api_model->getRestaurantReview($this->post('restaurant_id'));
            $package = $this->api_model->getPackage($this->post('restaurant_id'));
            $this->response([
                'restaurant'=>$details,
                'popular_item'=>$popular_item,
                'menu_item'=>$menu_item,
                'review'=>$review,
                'package'=>$package,
                'status'=>1,
                'message' => $this->lang->line('found')], 
            REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }else{
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    public function editProfile_post(){
        $token = $this->post('token');
        $user_id =$this->post('user_id');
        $tokenusr = $this->api_model->checkToken($token, $user_id);
        if($tokenusr){
            $add_data =array(
                'first_name'=>$this->post('first_name'),
                'last_name'=>'',
                'notification'=>$this->post('notification'),
            );
            if (!empty($_FILES['image']['name']))
            {
                  $this->load->library('upload');
                  $config['upload_path'] = './uploads/profile';
                  $config['allowed_types'] = 'jpg|png|jpeg';
                  $config['encrypt_name'] = TRUE; 
                  // create directory if not exists
                  if (!@is_dir('uploads/profile')) {
                    @mkdir('./uploads/profile', 0777, TRUE);
                  }
                  $this->upload->initialize($config);                  
                  if ($this->upload->do_upload('image'))
                  {  
                    $img = $this->upload->data();
                    $add_data['image'] = "profile/".$img['file_name']; 
                  }
                  else
                  {
                    $data['Error'] = $this->upload->display_errors(); 
                    $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                  }  
            } 
            $this->api_model->updateUser('users',$add_data,'entity_id',$this->post('user_id'));
            $token = $this->api_model->checkToken($token, $user_id);
            $image = ($token->image)?image_url.$token->image:''; 
            $last_name = ($token->last_name)?$token->last_name:'';
            $login_detail = array('FirstName'=>$token->first_name,'LastName'=>$last_name,'image'=> $image ,'PhoneNumber'=>$token->mobile_number,'UserID'=>$token->entity_id,'notification'=>$token->notification);
            $this->response(['profile'=>$login_detail,'status'=>1,'message' => $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) 
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
        }
    }
    //package avalability
    public function bookingAvailable_post(){
        if($this->post('booking_date') != '' && $this->post('people') != ''){
            $time = date('Y-m-d H:i:s',strtotime($this->post('booking_date')));
            $date = date('Y-m-d H:i:s');
            if(date('Y-m-d',strtotime($this->post('booking_date'))) == date('Y-m-d') && date($time) < date($date)){
                $this->response(['status'=>0,'message' => 'Time should be greater than current time'], REST_Controller::HTTP_OK); // OK      
            }else{
                $check = $this->api_model->getBookingAvailability($this->post('booking_date'),$this->post('people'),$this->post('restaurant_id'));
                if($check){
                   $this->response(['status'=>1,'message' => $this->lang->line('booking_available')], REST_Controller::HTTP_OK); // OK  
                }else{
                   $this->response(['status'=>0,'message' => $this->lang->line('booking_not_available')], REST_Controller::HTTP_OK); // OK  
                }  
            }
        }else{
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('not_found'),
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
        }
    }
    //book event
    public function bookEvent_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);    
        if($tokenres){
            /*$time = date('H:i:s',strtotime($this->post('booking_date')));
            $date = date('H:i:s');
            if($time > $date){*/
                if($this->post('booking_date') != '' && $this->post('people') != ''){
                    $add_data = array(                   
                        'name'=>$this->post('name'),
                        'no_of_people'=>$this->post('people'),
                        'booking_date'=>date('Y-m-d H:i:s',strtotime($this->post('booking_date'))),
                        'restaurant_id'=>$this->post('restaurant_id'),
                        'user_id'=>$this->post('user_id'),
                        'package_id'=>$this->post('package_id'),
                        'status'=>1,
                        'created_by' => $this->post('user_id'),
                        'event_status'=>'pending'
                    ); 
                    $event_id = $this->api_model->addRecord('event',$add_data); 
                    $users = array(
                        'first_name'=>$tokenres->first_name,
                        'last_name'=>($tokenres->last_name)?$tokenres->last_name:''
                    );
                    $taxdetail = $this->api_model->getRestaurantTax('restaurant',$this->post('restaurant_id'),$flag="order");
                    $package = $this->api_model->getRecord('restaurant_package','entity_id',$this->post('package_id'));
                    $package_detail = '';
                    if(!empty($package)){
                        $package_detail = array(
                            'package_price'=>$package->price,
                            'package_name'=>$package->name,
                            'package_detail'=>$package->detail
                        );
                    }
                    $serialize_array = array(
                        'restaurant_detail'=>(!empty($taxdetail))?serialize($taxdetail):'',
                        'user_detail'=>(!empty($users))?serialize($users):'',
                        'package_detail'=>(!empty($package_detail))?serialize($package_detail):'',
                        'event_id'=>$event_id
                    );
                    $this->api_model->addRecord('event_detail',$serialize_array); 
                    $this->response(['status'=>1,'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK  
                }else{
                    $this->response(['status'=>0,'message' => $this->lang->line('not_found')], REST_Controller::HTTP_OK); // OK  
                }
            /*}else{
                $this->response(['status'=>0,'message' => 'Time should be greater than current time'], REST_Controller::HTTP_OK); // OK     
            }*/
        }
        else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code   
        }    
    }
    //get booking
    public function getBooking_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);    
        if($tokenres){
            $data = $this->api_model->getBooking($user_id);
            $this->response(['upcoming_booking'=>$data['upcoming'],'past_booking'=>$data['past'],'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code    
        } 
    }
    //delete address
    public function deleteAddress_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);    
        if($tokenres){
            $this->api_model->deleteRecord('user_address','entity_id',$this->post('address_id')); 
            $this->response(['status'=>1,'message' => $this->lang->line('record_deleted')], REST_Controller::HTTP_OK); // OK
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //get recipe
    public function getReceipe_post(){
        $searchItem = ($this->post('itemSearch'))?$this->post('itemSearch'):'';
        $food = $this->post('food');
        $timing = $this->post('timing');
        $popular_item = $this->api_model->getRecipe($searchItem,$food,$timing);
        if($popular_item){
            $this->response(['items'=>$popular_item,'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK  
        }else{
             $this->response(['status'=>0,'message' => $this->lang->line('not_found')], REST_Controller::HTTP_OK); // OK  
        }
    }
    //delete booking
    public function deleteBooking_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);    
        if($tokenres){
            $this->api_model->deleteRecord('event','entity_id',$this->post('event_id'));
            $this->response(['status'=>1,'message' => $this->lang->line('record_deleted')], REST_Controller::HTTP_OK); // OK
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    public function addtoCart_post(){
        /*$token = $this->post('token');*/
        $user_id = $this->post('user_id');
        /*$tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){ */
                $cart_id = $this->post('cart_id');
                $items = $this->post('items');
                $itemDetail = json_decode($items, true);
                $item = array();
                $subtotal = 0;
                $discount = 0;
                $total = 0;
                $taxdetail = $this->api_model->getRestaurantTax('restaurant',$this->post('restaurant_id'),$flag='');
                if(!empty($itemDetail)){
                    foreach ($itemDetail['items'] as $key => $value) {
                        $data = $this->api_model->checkExist($value['menu_id']);
                        if(!empty($data)){
                            $image = ($data->image)?image_url.$data->image:''; 
                            $item[] = array('name'=>$data->name,'image'=>$image,'menu_id'=>$value['menu_id'],'quantity'=>$value['quantity'],'price'=>$data->price,'is_veg'=>$data->is_veg);
                            $subtotal = $subtotal + ($value['quantity'] * $data->price);
                        }
                    }
                }
                $messsage =  $this->lang->line('record_found');
                $status = 1;
                $subtotalCal = $subtotal;
                $coupon_id = $coupon_amount = $coupon_type = $name  = $isApply = $coupon_discount = '';
                if($this->post('coupon')){
                    $check = $this->api_model->checkCoupon($this->post('coupon'));
                    if(!empty($check)){
                        if(strtotime($check->end_date) > strtotime(date('Y-m-d H:i:s'))){
                            if($check->max_amount < $subtotal){ 
                                if($check->amount_type == 'Percentage'){
                                    $total = $subtotalCal - (($subtotalCal * $check->amount)/100);
                                    $discount = $total - $subtotalCal;
                                }else if($check->amount_type == 'Amount'){
                                    $total = $subtotalCal - $check->amount;
                                    $discount = $total - $subtotalCal;
                                }
                                $subtotalCal = $total;  
                                $coupon_id = $check->entity_id;  
                                $coupon_type = $check->amount_type;
                                $coupon_amount = $check->amount;  
                                $coupon_discount = abs($discount);
                                $name = $check->name;     
                            }else{
                                $messsage = $this->lang->line('not_applied');
                                $status = 2;
                            }
                        }else{
                            $messsage = $this->lang->line('coupon_expire');
                            $status = 2;
                        }
                    }else{
                        $messsage = $this->lang->line('coupon_not_found');
                        $status = 2;
                    }
                }
                //get subtotal
                if($taxdetail->amount_type == 'Percentage'){
                    $total = $subtotalCal + (($subtotalCal * $taxdetail->amount) / 100);
                }else{
                    $total = $subtotalCal + $taxdetail->amount; 
                }
                $type = ($taxdetail->amount_type == 'Percentage')?'%':'';
                $discount = ($discount)?array('label'=>'Discount','value'=>(number_format((float)abs($discount), 2, '.', ''))):'';
                if($discount){
                    $priceArray = array(
                        array('label'=>'Sub Total','value'=>number_format((float)$subtotal, 2, '.', '')),
                        $discount,
                        array('label'=>'Service Fee','value'=>$taxdetail->amount.$type),
                        array('label'=>'Total','value'=>number_format((float)$total, 2, '.', ''))
                    );
                    $isApply = true;
                }else{
                    $priceArray = array(
                        array('label'=>'Sub Total','value'=>number_format((float)$subtotal, 2, '.', '')),
                        array('label'=>'Service Fee','value'=>$taxdetail->amount.$type),
                        array('label'=>'Total','value'=>number_format((float)$total, 2, '.', ''))
                    ); 
                }
                $add_data = array(
                    'user_id'=>$user_id,
                    'items'=> serialize($item),
                    'restaurant_id'=>$this->post('restaurant_id')
                );
                if($cart_id == ''){
                    $cart_id = $this->api_model->addRecord('cart_detail',$add_data);
                }else{
                    $this->api_model->updateUser('cart_detail',$add_data,'cart_id',$cart_id);
                }
                $this->response([
                'total'=>number_format((float)$total, 2, '.', ''),
                'cart_id'=>$cart_id,
                'items'=>$item,
                'price'=>$priceArray,
                'coupon_id'=>$coupon_id,
                'coupon_amount'=>$coupon_amount,
                'coupon_type'=>$coupon_type,
                'coupon_name'=>$name,
                'coupon_discount'=>$coupon_discount,
                'subtotal'=>number_format((float)$subtotal, 2, '.', ''),
                'is_apply'=>$isApply,
                'status'=>$status,
                'message' =>$messsage], REST_Controller::HTTP_OK); // OK  
       /* }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }*/
    }
    //add address
    public function addAddress_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            $address_id = $this->post('address_id');
            $add_data = array(
                'address'=>$this->post('address'),
                'landmark'=>$this->post('landmark'),
                'latitude'=>$this->post('latitude'),
                'longitude'=>$this->post('longitude'),
                'zipcode'=>$this->post('zipcode'),
                'city'=>$this->post('city'),
				'date'=>$this->post('date'),
				'country'=>$this->post('country'),
                'user_entity_id'=>$this->post('user_id')
            );
            if($address_id){
                $this->api_model->updateUser('user_address',$add_data,'entity_id',$address_id);
            }else{
                $address_id = $this->api_model->addRecord('user_address',$add_data);
            }
            $this->response(['address_id'=>$address_id,'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK  
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //get address
    public function getAddress_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            $address = $this->api_model->getAddress('user_address','user_entity_id',$user_id);
            $this->response(['address'=>$address,'status'=>1,'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK  
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code       
        }
    }
    //change address
    public function changePassword_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            if(md5(SALT.$this->post('old_password')) == $tokenres->password){
                if($this->post('confirm_password') == $this->post('password')){
                    $this->db->set('password',md5(SALT.$this->post('password')));
                    $this->db->where('entity_id',$user_id);
                    $this->db->update('users');
                    $this->response(['status'=>1,'message' => $this->lang->line('success_password_change')], REST_Controller::HTTP_OK); // OK  
                }else{
                    $this->response(['status'=>0,'message' => $this->lang->line('confirm_password')], REST_Controller::HTTP_OK); // OK  
                }
            }else{
                $this->response(['status'=>0,'message' => $this->lang->line('old_password')], REST_Controller::HTTP_OK); // OK  
            }
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //add order
    public function addOrder_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            $taxdetail = $this->api_model->getRestaurantTax('restaurant',$this->post('restaurant_id'),$flag="order");
            $total = 0;
            $subtotal = $this->post('subtotal');
            $add_data = array(              
                'user_id'=>$this->post('user_id'),
                'restaurant_id' =>$this->post('restaurant_id'),
                'address_id' =>$this->post('address_id'),
                'coupon_id' =>$this->post('coupon_id'),
                'order_status' =>'placed',
                'order_date' =>date('Y-m-d',strtotime($this->post('order_date'))),
                'subtotal'=>$subtotal,
                'tax_rate'=>$taxdetail->amount,
                'tax_type'=>$taxdetail->amount_type,
                'coupon_type'=>$this->post('coupon_type'),
                'coupon_amount'=>$this->post('coupon_amount'),
                'total_rate' =>$this->post('total'),
                'status'=>1,
                'coupon_discount'=>$this->post('coupon_discount'),
                'coupon_name'=>$this->post('coupon_name')
            ); 
            $order_id = $this->api_model->addRecord('order_master',$add_data);  
            //add items
            $items = $this->post('items');
            $itemDetail = json_decode($items,true);
            $add_item = array();
            if(!empty($itemDetail)){
                foreach ($itemDetail['items'] as $key => $value) {
                    $add_item[] = array(
                        "item_name"=>$value['name'],
                        "item_id"=>$value['menu_id'],
                        "qty_no"=>$value['quantity'],
                        "rate"=>$value['price'],
                        "order_id"=>$order_id
                    );
                }
            }
            $address = $this->api_model->getAddress('user_address','entity_id',$this->post('address_id'));
            $user_detail = array(
                'first_name'=>$tokenres->first_name,
                'last_name'=>($tokenres->last_name)?$tokenres->last_name:'',
                'address'=>$address[0]->address,
                'landmark'=>$address[0]->landmark,
                'zipcode'=>$address[0]->zipcode,
                'city'=>$address[0]->city
            );
            $order_detail = array(
                'order_id'=>$order_id,
                'user_detail' => serialize($user_detail),
                'item_detail' => serialize($add_item),
                'restaurant_detail' => serialize($taxdetail),
            );
            $this->api_model->addRecordBatch('order_item',$add_item);
            $this->api_model->addRecord('order_detail',$order_detail);
            $this->response(['restaurant_detail'=>$taxdetail,'order_status'=>'placed','order_date'=>date('Y-m-d',strtotime($this->post('order_date'))),'status'=>1,'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK */
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }  
    }
    //order detail
    public function orderDetail_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            $result['in_process'] = $this->api_model->getOrderDetail('process',$user_id);  
            $result['past'] = $this->api_model->getOrderDetail('past',$user_id); 
            $this->response(['in_process'=>$result['in_process'],'past'=>$result['past'],'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK */
        }else{
            $this->response([
                'status' => -1,
                'message' => 'fuck'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }   
    }
    
        public function ResOrderDetail_post(){
        $user_id = $this->post('user_id');
            $result['in_process'] = $this->api_model->getOrderDetail('process',$user_id);  
            $result['past'] = $this->api_model->getOrderDetail('past',$user_id); 
            $this->response([$result['in_process'],'past'=>$result['past']], REST_Controller::HTTP_OK); // OK */
    }
    
    //check promocode
    /*public function checkPromocode_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        $price = json_decode($this->post('price'));
        if($tokenres){
            $subtotal = $this->post('subtotal');
            $check = $this->api_model->checkCoupon($this->post('coupon'));
            if(!empty($check)){
                if(strtotime($check->end_date) < strtotime(date('Y-m-d H:i:s'))){
                    $total = 0;
                    $discount = 0;
                    $subtotal = 0;  
                    $service_tax = 0;                    
                    foreach ($price as $key => $value) {                        
                        if($value->label=='Sub Total'){
                           $subtotal =  $value->value;
                        }
                        if($value->label=='Service Fee'){
                           $service_tax =  str_replace('%', '', $value->value);
                        }
                    }
                    if($check->max_amount < $subtotal){                
                        if($check->amount_type == 'Percentage'){
                            $total = $subtotal - (($subtotal * $check->amount)/100);
                            $discount = $total - $subtotal;
                        }else if($check->amount_type == 'Amount'){
                            $total = $subtotal - $check->amount;
                            $discount = $total - $subtotal;
                        }
                        //get tax
                        if($this->post('tax_type') == 'Percentage'){
                            $total = $total + (($total * $service_tax) / 100);
                        }else if($this->post('tax_type') == 'Amount'){
                            $total = $total + $service_tax; 
                        }
                        $type = ($this->post('tax_type') == 'Percentage')?'%':'';
                        $priceArray = array(
                            array('label'=>'Sub Total','value'=>number_format((float)$subtotal, 2, '.', '')),
                            array('label'=>'Coupon Discount','value'=>number_format((float)abs($discount), 2, '.', '')),
                            array('label'=>'Service Fee','value'=>$service_tax.$type),
                            array('label'=>'Total','value'=>number_format((float)$total, 2, '.', ''))
                        );
                        $this->response([
                            'coupon_id'=>$check->entity_id,
                            'coupon_amount'=>$check->amount,
                            'coupon_type'=>$check->amount_type,
                            'price'=>$priceArray,
                            'total'=>number_format((float)$total, 2, '.', ''),
                            'status' => 1,
                            'message' =>$this->lang->line('record_found')
                        ],  REST_Controller::HTTP_OK); // NOT_FOUND (404
                    }else{
                        $this->response([
                        'status' => 0,
                        'message' =>$this->lang->line('not_found')
                        ],  REST_Controller::HTTP_OK); // NOT_FOUND (404
                    }
                }else{
                    $this->response([
                    'status' => 0,
                    'message' =>$this->lang->line('coupon_expire')
                    ],  REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
                }
            }else{
                $this->response([
                'status' => 0,
                'message' =>$this->lang->line('not_found')
                ],  REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code   
            }
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }   
    }*/
    //get promocode list
    public function couponList_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            $subtotal = $this->post('subtotal');
            $coupon = $this->api_model->getcouponList($subtotal,$this->post('restaurant_id'));
            if(!empty($coupon)){
                $this->response([
                    'coupon_list'=>$coupon,
                    'status' => 1,
                    'message' =>$this->lang->line('record_found')
                ],  REST_Controller::HTTP_OK);
            }else{
                $this->response([
                'status' => 0,
                'message' => 'There is no promocode applicable for this'
                 ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
            }
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }  
    }
    // Send notification
    function sendFCMRegistration($registrationIds) {        
        if($registrationIds){        
            $message = "Push notification";
            #prep the bundle
            $fields = array();            
           
            $fields['to'] = $registrationId; // only one user to send push notification
            $fields['notification'] = array ('body'  => $message,'sound'=>'default');
            $fields['data'] = array ('screenType'=>'general');
           
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
            $result = curl_exec($ch );
            curl_close( $ch );            
        } 
    }
    //get notification list
    function getNotification_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id); 
        if($tokenres){
            $notification = $this->api_model->getNotification($user_id,$this->post('count'),$this->post('page_no'));
            if(!empty($notification)){
                $this->response([
                    'notification'=>$notification['result'],
                    'status' => 1,
                    'notificaion_count'=>$notification['count'],
                    'message' =>$this->lang->line('record_found')
                ],  REST_Controller::HTTP_OK);
            }else{
                $this->response([
                'status' => 0,
                'message' => $this->lang->line('not_found')
                 ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
            } 
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }  
    }
  
}

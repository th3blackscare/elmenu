<?php
class Api_model extends CI_Model {
    function __construct()
    {
        parent::__construct();      
    }
    public function getRecord($table,$fieldName,$where)
    {
        $this->db->where($fieldName,$where);
        return $this->db->get($table)->first_row();
    } 
    //get record with multiple where
    public function getRecordMultipleWhere($table,$whereArray)
    {
        $this->db->where($whereArray);
        return $this->db->get($table)->first_row();
    }
	public function getMenu(){
    	$this->db->select('item_id,item_name,item_details,item_price,item_photo,menu_category.category_name as category,isPopular');
    	$this->db->join('menu_category','menu_category.category_id = menu_item.category_id','left');
    	$result = $this->db->get('menu_item')->result();
    	foreach ($result as $key => $value){
    		$value->item_id;
    		$value-> item_name;
			$value-> item_details;
			$value-> item_price;
			$value-> category;
			$value-> isPopular;
			$value-> item_photo = ($value-> item_photo)?image_url.$value->item_photo:'';
		}
    	return $result;
	}

	public function getMenuCategory(){
    	$this->db->select('category_id,category_name');
    	$result = $this->db->get('menu_category')->result();
    	foreach ($result as $key => $value){
    		$value->category_id;
    		$value->category_name;
		}
    	return $result;
	}

	public function getOffers(){
    	$this->db->select('entity_id,offer_title,offer_discription,offer_detalis,offer_price,offer_discount,is_discount,offer_photo');
    	$result = $this->db->get('offers')->result();
    	foreach ($result as $key=>$value){
    		$value->entity_id;
    		$value->offer_title;
			$value->offer_discription;
			$value->offer_detalis;
			$value->offer_price;
			$value->offer_discount;
			$value->is_discount;
			$value->offer_photo = ($value->offer_photo)?image_url.$value->offer_photo:image_url.'/restaurant/6c576d3217c931a471fd74b5c577e585.png';
		}
    	return $result;
	}

    //Admin Login
    public function getAdminLogin($user,$password){
        $enc_pass = md5(SALT.$password);
        $this->db->select('entity_id,user_name,Name,status,active');
        $this->db->where('user_name',$user);
        $this->db->where('password',$enc_pass);
        $this->db->where('user_type','MasterAdmin');
        return $this->db->get('users')->first_row();
    }
    // Update User
    public function updateUser($tableName,$data,$fieldName,$UserID)
    {
        $this->db->where($fieldName,$UserID);
        $this->db->update($tableName,$data);
    }
    // check token for every API Call
    public function checkToken($token, $userid)
    {
        return $this->db->get_where('users',array('mobile_number'=>$token,' entity_id'=>$userid))->first_row();
    }
    // Common Add Records
    public function addRecord($table,$data)
    {
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }
    // Common Add Records Batch
    public function addRecordBatch($table,$data)
    {
        return $this->db->insert_batch($table, $data);
    }
    public function deleteRecord($table,$fieldName,$where)
    {
        $this->db->where($fieldName,$where);
        return $this->db->delete($table);
    }
    public function checkEmailExist($emailID,$UserID)
    {
        $this->db->where('Email',$emailID);
        $this->db->where('UserID !=',$UserID);
        $this->db->where('deleteStatus',0);
        return $this->db->get('users')->num_rows();
    }
    // get config
    public function getSystemOptoin($OptionSlug)
    {        
        $this->db->select('OptionValue');                
        $this->db->where('OptionSlug',$OptionSlug);        
        return $this->db->get('system_option')->first_row();
    }
    //get record after registration
    public function getRegisterRecord($tblname,$UserID){
        $this->db->select('entity_id,first_name,mobile_number');
        $this->db->where('entity_id',$UserID);
        return $this->db->get($tblname)->first_row();
    }
    //check email for user edit
    public function getExistingEmail($table,$fieldName,$where,$UserID)
    {
        $this->db->where($fieldName,$where);
        $this->db->where('UserID !=',$UserID);
        return $this->db->get($table)->first_row();
    } 
    //get cms detail 
    public function getCMSRecord($tblname,$entity_id){
        $this->db->select('entity_id,name,description');
        $this->db->where('entity_id',$entity_id);
        $this->db->where('status',1);
        return $this->db->get($tblname)->result();
    }
    //check booking availability
    public function getBookingAvailability($date,$people,$restaurant_id){
        $date = date('Y-m-d H:i:s',strtotime($date));
       // $time = date('g:i A',strtotime($date));
        $datetime = date($date,strtotime('+1 hours'));
        $this->db->select('capacity,timings');
        $this->db->where('entity_id',$restaurant_id);
        $capacity =  $this->db->get('restaurant')->first_row();
        $timing = $capacity->timings;
        if($timing){
            $timing =  unserialize(html_entity_decode($timing));
            $newTimingArr = array();
            $day = date('l', strtotime($date));
            foreach($timing as $keys=>$values) {
                $day = date('l', strtotime($date));
                if($keys == strtolower($day)){
                    $newTimingArr[strtolower($day)]['open'] = (!empty($values['open']))?date('g:i A',strtotime($values['open'])):'';
                    $newTimingArr[strtolower($day)]['close'] = (!empty($values['close']))?date('g:i A',strtotime($values['close'])):'';
                    $newTimingArr[strtolower($day)]['off'] = (!empty($values['open']) && !empty($values['close']))?'open':'close';
                }
            }
        }
        $capacity->timings = $newTimingArr[strtolower($day)];
        //for booking
        $this->db->select('SUM(no_of_people) as people');
        $this->db->where('booking_date',$datetime);
        $this->db->where('restaurant_id',$restaurant_id);

        $event = $this->db->get('event')->first_row();
        //get event booking
        $peopleCount = $capacity->capacity - $event->people;
        if($peopleCount >= $people && (date('H:i',strtotime($capacity->timings['close'])) > date('H:i',strtotime($date))) && (date('H:i',strtotime($capacity->timings['open'])) < date('H:i',strtotime($date)))){
            return true;
        }else{
            return false;
        }
    }
    //get package
    public function getPackage($restaurant_id){
        $this->db->select('entity_id as package_id,name,price,detail,availability');
        $this->db->where('restaurant_id',$restaurant_id);
        return $this->db->get('restaurant_package')->result();
    }
    //get event
    public function getBooking($user_id){
        $currentDateTime = date('Y-m-d H:i:s');
        //upcoming
        $this->db->select('event.entity_id as event_id,event.booking_date,event.no_of_people,event_detail.package_detail,event_detail.restaurant_detail,AVG (review.rating) as rating');
        $this->db->join('event_detail','event.entity_id = event_detail.event_id','left');
        $this->db->join('review','event.restaurant_id = review.restaurant_id','left');
        $this->db->where('event.user_id',$user_id);
        $this->db->where('event.booking_date >',$currentDateTime);
        $this->db->group_by('event.entity_id');
        $this->db->order_by('event.entity_id','desc');
        $result = $this->db->get('event')->result();
        $upcoming = array();
        foreach ($result as $key => $value) {
            $package_detail = '';
            $restaurant_detail = '';
            if(!isset($value->event_id)){
                $upcoming[$value->event_id] = array();
            }
            if(isset($value->event_id)){
                $package_detail = unserialize($value->package_detail);
                $restaurant_detail = unserialize($value->restaurant_detail);
                $upcoming[$value->event_id]['entity_id'] =  $value->event_id;
                $upcoming[$value->event_id]['booking_date'] =  $value->booking_date;
                $upcoming[$value->event_id]['no_of_people'] =  $value->no_of_people;

                $upcoming[$value->event_id]['package_name'] =  (!empty($package_detail))?$package_detail['package_name']:'';
                $upcoming[$value->event_id]['package_detail'] = (!empty($package_detail))?$package_detail['package_detail']:'';
                $upcoming[$value->event_id]['package_price'] = (!empty($package_detail))?$package_detail['package_price']:'';

                $upcoming[$value->event_id]['name'] =  (!empty($restaurant_detail))?$restaurant_detail->name:'';
                $upcoming[$value->event_id]['image'] =  (!empty($restaurant_detail) && $restaurant_detail->image != '')?image_url.$restaurant_detail->image:'';
                $upcoming[$value->event_id]['address'] =  (!empty($restaurant_detail))?$restaurant_detail->address:'';
                $upcoming[$value->event_id]['landmark'] =  (!empty($restaurant_detail))?$restaurant_detail->landmark:'';
                $upcoming[$value->event_id]['city'] =  (!empty($restaurant_detail))?$restaurant_detail->city:'';
                $upcoming[$value->event_id]['zipcode'] =  (!empty($restaurant_detail))?$restaurant_detail->zipcode:'';
                $upcoming[$value->event_id]['rating'] =  $value->rating;
            }
        }
        $finalArray = array();
        foreach ($upcoming as $key => $val) {
           $finalArray[] = $val; 
        }
        $data['upcoming'] = $finalArray;
        //past
        /*$this->db->select('event.entity_id as entity_id,event.booking_date,event.no_of_people,event.package_id,package.name as package_name,package.detail as package_detail,res.name,res.entity_id as restuarant_id,res.image,address.address,address.landmark,address.city,address.zipcode,AVG (review.rating) as rating');
        $this->db->join('restaurant as res','event.restaurant_id = res.entity_id','left');
        $this->db->join('restaurant_address as address','res.entity_id = address.resto_entity_id','left');
        $this->db->join('review','res.entity_id = review.restaurant_id','left');
        $this->db->join('restaurant_package as package','event.package_id = package.entity_id','left');*/
        $this->db->select('event.entity_id as event_id,event.booking_date,event.no_of_people,event_detail.package_detail,event_detail.restaurant_detail,AVG (review.rating) as rating');
        $this->db->join('event_detail','event.entity_id = event_detail.event_id','left');
        $this->db->join('review','event.restaurant_id = review.restaurant_id','left');
        $this->db->where('event.user_id',$user_id);
        $this->db->where('event.booking_date <',$currentDateTime);
        $this->db->group_by('event.entity_id');
        $this->db->order_by('event.entity_id','desc');
        $resultPast = $this->db->get('event')->result();
        $past = array();
        foreach ($resultPast as $key => $value) {
            if(!isset($value->event_id)){
                $past[$value->event_id] = array();
            }
            if(isset($value->event_id)){
                $package_detail = unserialize($value->package_detail);
                $restaurant_detail = unserialize($value->restaurant_detail);
                $past[$value->event_id]['entity_id'] =  $value->event_id;
                $past[$value->event_id]['booking_date'] =  $value->booking_date;
                $past[$value->event_id]['no_of_people'] =  $value->no_of_people;

                $past[$value->event_id]['package_name'] =  (!empty($package_detail))?$package_detail['package_name']:'';
                $past[$value->event_id]['package_detail'] = (!empty($package_detail))?$package_detail['package_detail']:'';
                $past[$value->event_id]['package_price'] = (!empty($package_detail))?$package_detail['package_price']:'';

                $past[$value->event_id]['name'] =  (!empty($restaurant_detail))?$restaurant_detail->name:'';
                $past[$value->event_id]['image'] =  (!empty($restaurant_detail) && $restaurant_detail->image != '')?image_url.$restaurant_detail->image:'';
                $past[$value->event_id]['address'] =  (!empty($restaurant_detail))?$restaurant_detail->address:'';
                $past[$value->event_id]['landmark'] =  (!empty($restaurant_detail))?$restaurant_detail->landmark:'';
                $past[$value->event_id]['city'] =  (!empty($restaurant_detail))?$restaurant_detail->city:'';
                $past[$value->event_id]['zipcode'] =  (!empty($restaurant_detail))?$restaurant_detail->zipcode:'';
                $past[$value->event_id]['rating'] =  $value->rating;
            }
        }
        $final = array();
        foreach ($past as $key => $val) {
           $final[] = $val; 
        }
        $data['past'] = $final;
        return $data;
    } 
    //get receipe
    public function getRecipe($searchItem,$food,$timing)
    {
        $this->db->select('entity_id as item_id,name,image,receipe_detail,menu_detail,receipe_time,is_veg');
        if($searchItem){
            $this->db->where("name like '%".$searchItem."%'");
        }else if($food == '' && $timing == ''){
            $this->db->where("popular_item",1);
        }
        if($food != ''){
            $this->db->where('is_veg',$food);
        }
        if($timing){
            $this->db->where('receipe_time <=',$timing);
        }
        $result =  $this->db->get('restaurant_menu_item')->result();
        foreach ($result as $key => $value) {
           $value->image = ($value->image)?image_url.$value->image:'';
        }
        return $result;
    } 
    //check if item exist
    public function checkExist($item_id)
    {
        $this->db->select('price,image,name,is_veg');
        $this->db->where('entity_id',$item_id);
        return $this->db->get('restaurant_menu_item')->first_row();
    } 
    //get tax
    public function getRestaurantTax($tblname,$restaurant_id,$flag){
        if($flag == 'order'){
            $this->db->select('restaurant.name,restaurant.image,restaurant.phone_number,restaurant.email,restaurant.amount_type,restaurant.amount,restaurant_address.address,restaurant_address.landmark,restaurant_address.zipcode,restaurant_address.city');
            $this->db->join('restaurant_address','restaurant.entity_id = restaurant_address.resto_entity_id','left');
        }else{
            $this->db->select('restaurant.name,restaurant.image,restaurant_address.address,restaurant_address.landmark,restaurant_address.zipcode,restaurant_address.city,restaurant.amount_type,restaurant.amount');
            $this->db->join('restaurant_address','restaurant.entity_id = restaurant_address.resto_entity_id','left');
        }
        $this->db->where('restaurant.entity_id',$restaurant_id);
        return $this->db->get($tblname)->first_row();
    }
    //get address
    public function getAddress($tblname,$fieldName,$user_id){
        $this->db->select('entity_id as address_id,address,landmark,latitude,longitude,city,country,date');
        $this->db->where($fieldName,$user_id);
        return $this->db->get($tblname)->result();
    }
    //get order detail
    public function getOrderDetail($flag,$user_id){
        $this->db->select('order_master.*,order_detail.*,status.order_status as ostatus,status.time');
       // $this->db->join('order_item','order_master.entity_id = order_item.order_id','left');
       // $this->db->join('restaurant_menu_item','order_item.item_id = restaurant_menu_item.entity_id','left');
        $this->db->join('order_detail','order_master.entity_id = order_detail.order_id','left');
        $this->db->join('order_status as status','order_master.entity_id = status.order_id','left');
        if($flag == 'process'){
            $this->db->where('(order_master.order_status != "delivered" AND order_master.order_status != "cancel")');
        } 
        if($flag == 'past'){
            $this->db->where('(order_master.order_status = "delivered" OR order_master.order_status = "cancel")');
        }
        $this->db->where('order_master.user_id',$user_id);
        $this->db->order_by('order_master.entity_id','desc');
        $result =  $this->db->get('order_master')->result();
        $items = array();
        foreach ($result as $key => $value) {
            if(!isset($items[$value->order_id])){
                $items[$value->order_id] = array();
                $items[$value->order_id]['preparing'] = '';
                $items[$value->order_id]['onGoing'] = '';
                $items[$value->order_id]['delivered'] = '';
            }
            if(isset($items[$value->order_id])) 
            {
                $type = ($value->tax_type == 'Percentage')?'%':'';                
                $items[$value->order_id]['order_id'] = $value->order_id;
                $items[$value->order_id]['restaurant_id'] = $value->restaurant_id;
                if($value->coupon_name){
                    $discount = array('label'=>'Discount('.$value->coupon_name.')','value'=>$value->coupon_discount);
                }else{
                    $discount = '';
                }
                if($discount){
                $items[$value->order_id]['price'] = array(
                    array('label'=>'Sub Total','value'=>number_format((float)$value->subtotal, 2, '.', '')),
                    $discount,
                    array('label'=>'Service Fee','value'=>$value->tax_rate.$type),
                    array('label'=>'Total','value'=>number_format((float)$value->total_rate, 2, '.', '')));
                }else{
                    $items[$value->order_id]['price'] = array(
                    array('label'=>'Sub Total','value'=>number_format((float)$value->subtotal, 2, '.', '')),
                    array('label'=>'Service Fee','value'=>$value->tax_rate.$type),
                    array('label'=>'Total','value'=>number_format((float)$value->total_rate, 2, '.', '')));
                }
                $items[$value->order_id]['order_status'] = $value->order_status;
                $items[$value->order_id]['total'] = number_format((float)$value->total_rate, 2, '.', '');
                $items[$value->order_id]['placed'] = date('g:i a',strtotime($value->order_date));
                if($value->ostatus == 'preparing')
                {
                    $items[$value->order_id]['preparing'] = ($value->time!="")?date('g:i A',strtotime($value->time)):'';                    
                }
                if($value->ostatus == 'onGoing')
                {
                    $items[$value->order_id]['onGoing'] = ($value->time!="")?date('g:i A',strtotime($value->time)):'';                    
                }
                if($value->ostatus == 'delivered')
                {
                    $items[$value->order_id]['delivered'] = ($value->time!="")?date('g:i A',strtotime($value->time)):'';                    
                }
                $items[$value->order_id]['order_date'] = date('Y-m-d H:i:s',strtotime($value->order_date));
                $item_detail = unserialize($value->item_detail);
                $value1 = array();
                if(!empty($item_detail)){
                    $data1 = array();
                    foreach ($item_detail as $key => $valuee) {
                        $this->db->select('image,is_veg');
                        $this->db->where('entity_id',$valuee['item_id']);
                        $data = $this->db->get('restaurant_menu_item')->first_row();
                        //if(!empty($data)){
                            $data1['image'] = (!empty($data) && $data->image != '')?$data->image:'';
                            $data1['is_veg'] = (!empty($data) && $data->is_veg != '')?$data->is_veg:'';
                            $valueee['image'] = (!empty($data) && $data->image != '')?image_url.$data1['image']:'';
                            $valueee['is_veg'] = (!empty($data) && $data->is_veg != '')?$data1['is_veg']:'';
                       // }
                        $valueee['menu_id'] = $valuee['item_id'];
                        $valueee['name'] = $valuee['item_name'];
                        $valueee['quantity'] = $valuee['qty_no'];
                        $valueee['price'] = $valuee['rate'];
                        $valueee['itemTotal'] = number_format($valuee['qty_no'] * $valuee['rate'],2);
                        $value1[] =  $valueee;
                    } 
                }
                $items[$value->order_id]['items']  = $value1;
            }
        }
        $finalArray = array();
        foreach ($items as $nm => $va) {
            $finalArray[] = $va;
        }
        if($flag == 'process'){
            $res['in_process'] = $finalArray;
        }
        if($flag == 'past'){
            $res['past'] = $finalArray;
        }
        return $res;
    }
    //check coupon
    public function checkCoupon($coupon){
        $this->db->where('name',$coupon);
        return $this->db->get('coupon')->first_row();
    }
    //get coupon list
    public function getcouponList($subtotal,$restaurant_id){
        $this->db->select('name,entity_id as coupon_id,amount_type,amount,description');
        $this->db->where('max_amount <=',$subtotal);
        $this->db->where('(restaurant_id = 0 OR restaurant_id = '.$restaurant_id.')');
        $this->db->where('end_date >',date('Y-m-d H:i:s'));
        return $this->db->get('coupon')->result();
    }
    //get notification
    public function getNotification($user_id,$count,$page_no = 1){
        $page_no = ($page_no > 0)?$page_no-1:0;
        $this->db->select('notifications.notification_title,notifications.notification_description,notifications_users.notification_id');
        $this->db->join('notifications','notifications_users.notification_id =  notifications.entity_id','left');
        $this->db->limit($count,$page_no*$count);
        $this->db->where('notifications_users.user_id',$user_id);
        $data['result'] =  $this->db->get('notifications_users')->result();

        $this->db->select('notifications.notification_title,notifications.notification_description,notifications_users.notification_id');
        $this->db->join('notifications','notifications_users.notification_id =  notifications.entity_id','left');
        $this->db->where('notifications_users.user_id',$user_id);
        $data['count'] =  $this->db->count_all_results('notifications_users');
        return $data;
    }
}
?>

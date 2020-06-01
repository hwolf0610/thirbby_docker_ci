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
    //get home
    public function getHomeRestaurant($latitude,$longitude,$searchItem,$food,$rating,$distance){
        $this->db->select("res.entity_id as restuarant_id,res.name,res.timings,res.image,address.address,address.landmark,AVG (review.rating) as rating, (6371 * acos ( cos ( radians($latitude) ) * cos( radians(address.latitude ) ) * cos( radians( address.longitude ) - radians($longitude) ) + sin ( radians($latitude) ) * sin( radians( address.latitude )))) as distance");
        $this->db->join('restaurant_address as address','res.entity_id = address.resto_entity_id','left');
        $this->db->join('review','res.entity_id = review.restaurant_id','left');
        if($searchItem){
            $this->db->join('restaurant_menu_item as menu','res.entity_id = menu.restaurant_id','left');
            $this->db->join('category','menu.category_id = category.entity_id','left');
            $where = "(menu.name like '%".$searchItem."%' OR res.name like '%".$searchItem."%' OR category.name like '%".$searchItem."%')";
            $this->db->where($where);
        }
        if($food != ''){
            $this->db->where('res.is_veg',$food); 
            $this->db->or_where('res.is_veg',NULL);     
        }
        if($rating){
            $this->db->having('rating <=',$rating);
        }
        if($distance){
            $this->db->having('distance <=',$distance);
        }else{
            $this->db->having('distance <',NEAR_KM);
        }
        $this->db->group_by('res.entity_id');
        $result =  $this->db->get('restaurant as res')->result();
        foreach ($result as $key => $value) {
            $timing = $value->timings;
            if($timing){
               $timing =  unserialize(html_entity_decode($timing));
               $newTimingArr = array();
                $day = date("l");
                foreach($timing as $keys=>$values) {
                    $day = date("l");
                    if($keys == strtolower($day)){
                        $newTimingArr[strtolower($day)]['open'] = (!empty($values['open']))?date('g:i A',strtotime($values['open'])):'';
                        $newTimingArr[strtolower($day)]['close'] =(!empty($values['close']))?date('g:i A',strtotime($values['close'])):'';
                        $newTimingArr[strtolower($day)]['off'] = (!empty($values['open']) && !empty($values['close']))?'open':'close';
                        $newTimingArr[strtolower($day)]['closing'] = (!empty($values['close']))?($values['close'] <= date('H:m'))?'close':'open':'close';
                    }
                }
            }
            $value->timings = $newTimingArr[strtolower($day)];
            $value->image = ($value->image)?image_url.$value->image:'';
            $value->rating = ($value->rating)?number_format((float)$value->rating, 1, '.', ''):null;
        }
        return $result;
    }
    //get banner
    public function getbanner(){
        $this->db->select('image');
        $images =  $this->db->get('slider_image')->result();
        foreach ($images as $key => $value) {
            $value->image = ($value->image)?image_url.$value->image:'';
        }
        return $images;
    }
    //get home page category
    public function getcategory(){
        $this->db->select('category.entity_id as category_id, category.name,category.image');
        $this->db->order_by('category.entity_id','desc');
        $this->db->limit(4, 0);
        $result =  $this->db->get('category')->result(); 
        foreach ($result as $key => $value) {
            $value->image = ($value->image)?image_url.$value->image:'';
        }
        return $result;
    }
    //get restaurant
    public function getRestaurantDetail($restaurant_id){
        $this->db->select("res.entity_id as restuarant_id,res.name,res.timings,res.image,address.address,address.landmark,AVG(review.rating) as rating");
        $this->db->join('restaurant_address as address','res.entity_id = address.resto_entity_id','left');
        $this->db->join('review','res.entity_id = review.restaurant_id','left');
        $this->db->where('res.entity_id',$restaurant_id);
        $this->db->group_by('res.entity_id');
        $result =  $this->db->get('restaurant as res')->result();
        foreach ($result as $key => $value) {
            $timing = $value->timings;
            if($timing){
               $timing =  unserialize(html_entity_decode($timing));
               $newTimingArr = array();
                $day = date("l");
                foreach($timing as $keys=>$values) {
                    $day = date("l");
                    if($keys == strtolower($day)){
                        $newTimingArr[strtolower($day)]['open'] = (!empty($values['open']))?date('g:i A',strtotime($values['open'])):'';
                        $newTimingArr[strtolower($day)]['close'] = (!empty($values['close']))?date('g:i A',strtotime($values['close'])):'';
                        $newTimingArr[strtolower($day)]['off'] = (!empty($values['open']) && !empty($values['close']))?'open':'close';
                        $newTimingArr[strtolower($day)]['closing'] = (!empty($values['close']))?($values['close'] <= date('H:m'))?'close':'open':'close';
                    }
                }
            }
            $value->timings = $newTimingArr[strtolower($day)];
            $value->image = ($value->image)?image_url.$value->image:'';
            $value->rating = ($value->rating)?number_format((float)$value->rating, 1, '.', ''):null;
        }
        return $result;
    }
    //get populer item
    public function getPopularItem($restaurant_id){
        $this->db->select('image,name,price');
        $this->db->where('popular_item',1);
        $this->db->where('image !=','');
        if($restaurant_id){
            $this->db->where('restaurant_id',$restaurant_id);
        }
        $this->db->limit(10, 0);
        $result = $this->db->get('restaurant_menu_item')->result();
        foreach ($result as $key => $value) {
            $value->image = ($value->image)?image_url.$value->image:'';
        }
        return $result;
    }
    //get items
    public function getMenuItem($restaurant_id,$food,$price){
        $this->db->select('menu.entity_id as menu_id,menu.name,menu.price,menu.menu_detail,menu.image,menu.is_veg,menu.receipe_detail,availability,c.name as category,c.entity_id as category_id');
        $this->db->join('category as c','menu.category_id = c.entity_id','left');
        $this->db->where('menu.restaurant_id',$restaurant_id);
        if($price == 1){
            $this->db->order_by('menu.price','desc');
        }else{
            $this->db->order_by('menu.price','asc');
        }
        if($food != ''){
            $this->db->where('menu.is_veg',$food);
        }
        $result = $this->db->get('restaurant_menu_item as menu')->result();
        $menu = array();
        foreach ($result as $key => $value) {
            if (!isset($menu[$value->category_id])) 
            {
                $menu[$value->category_id] = array();
                $menu[$value->category_id]['category_id'] = $value->category_id;
                $menu[$value->category_id]['category_name'] = $value->category;  
            }
            $image = ($value->image)?image_url.$value->image:'';
            $menu[$value->category_id]['items'][]  = array('menu_id'=>$value->menu_id,'name' => $value->name,'price' => $value->price,'menu_detail' => $value->menu_detail,'image'=>$image,'receipe_detail'=>$value->receipe_detail,'availability'=>$value->availability,'is_veg'=>$value->is_veg);
        }
        $finalArray = array();
        foreach ($menu as $nm => $va) {
            $finalArray[] = $va;
        }
        return $finalArray;     
    }
    //get resutarant review
    public function getRestaurantReview($restaurant_id){
        $this->db->select("review.rating,review.review,users.first_name,users.last_name,users.image");
        $this->db->join('users','review.user_id = users.entity_id','left');
        $this->db->where('review.status',1);
        $this->db->where('review.restaurant_id',$restaurant_id);
        $result =  $this->db->get('review')->result();
        foreach ($result as $key => $value) {
            $value->last_name = ($value->last_name)?$value->last_name:'';
            $value->first_name = ($value->first_name)?$value->first_name:'';
            $value->image = ($value->image)?image_url.$value->image:'';
        }
        return $result;
    }
    //get event restuarant
    public function getEventRestaurant($latitude,$longitude,$searchItem){
        if($searchItem){
            $this->db->select("res.entity_id as restuarant_id,res.name,res.timings,res.image,address.address,address.landmark,address.city,address.zipcode,AVG (review.rating) as rating");
            $this->db->join('restaurant_address as address','res.entity_id = address.resto_entity_id','left');
            $this->db->join('review','res.entity_id = review.restaurant_id','left');
            $where = "(res.name like '%".$searchItem."%')";
            $this->db->where($where);
        }else{
            $this->db->select("res.entity_id as restuarant_id,res.name,res.timings,res.image,address.address,address.landmark,address.city,address.zipcode,AVG (review.rating) as rating, (6371 * acos ( cos ( radians($latitude) ) * cos( radians(address.latitude ) ) * cos( radians( address.longitude ) - radians($longitude) ) + sin ( radians($latitude) ) * sin( radians( address.latitude )))) as distance");
            $this->db->join('restaurant_address as address','res.entity_id = address.resto_entity_id','left');
            $this->db->join('review','res.entity_id = review.restaurant_id','left');
        }
        $this->db->group_by('res.entity_id');
        $result =  $this->db->get('restaurant as res')->result();
        foreach ($result as $key => $value) {
            $timing = $value->timings;
            if($timing){
               $timing =  unserialize(html_entity_decode($timing));
               $newTimingArr = array();
                $day = date("l");
                foreach($timing as $keys=>$values) {
                    $day = date("l");
                    if($keys == strtolower($day)){
                        $newTimingArr[strtolower($day)]['open'] = (!empty($values['open']))?date('g:i A',strtotime($values['open'])):'';
                        $newTimingArr[strtolower($day)]['close'] = (!empty($values['close']))?date('g:i A',strtotime($values['close'])):'';
                        $newTimingArr[strtolower($day)]['off'] = (!empty($values['open']) && !empty($values['close']))?'open':'close';
                        $newTimingArr[strtolower($day)]['closing'] = (!empty($values['close']))?($values['close'] <= date('H:m'))?'close':'open':'close';
                    }
                }
            }
            $value->timings = $newTimingArr[strtolower($day)];
            $value->image = ($value->image)?image_url.$value->image:'';
            $value->rating = ($value->rating)?number_format((float)$value->rating, 1, '.', ''):null;
        }
        return $result;
    }
    // Login
    public function getLogin($phone,$password)
    {        
        $enc_pass  = md5(SALT.$password);
        $this->db->select('users.entity_id,users.first_name,users.last_name,users.status,users.active,users.mobile_number,users.image,users.notification,phone_code');
        $this->db->where('mobile_number',$phone);
        $this->db->where('password',$enc_pass);
        $this->db->where('user_type','User');
        return $this->db->get('users')->first_row();
    }
    //get rating of user
    public function getRatings($userid){
        $this->db->select('AVG(review.rating) as rating');
        $this->db->where('order_user_id',$userid);
        $this->db->group_by('review.order_user_id');
        return $this->db->get('review')->first_row();
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
                    $newTimingArr[strtolower($day)]['closing'] = (!empty($values['close']))?($values['close'] <= date('H:m'))?'close':'open':'close';
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
            $this->db->select('restaurant.name,restaurant.image,restaurant.phone_number,restaurant.email,restaurant.amount_type,restaurant.amount,restaurant_address.address,restaurant_address.landmark,restaurant_address.zipcode,restaurant_address.city,restaurant_address.latitude,restaurant_address.longitude');
            $this->db->join('restaurant_address','restaurant.entity_id = restaurant_address.resto_entity_id','left');
        }else{
            $this->db->select('restaurant.name,restaurant.image,restaurant_address.address,restaurant_address.landmark,restaurant_address.zipcode,restaurant_address.city,restaurant.amount_type,restaurant.amount,restaurant_address.latitude,restaurant_address.longitude');
            $this->db->join('restaurant_address','restaurant.entity_id = restaurant_address.resto_entity_id','left');
        }
        $this->db->where('restaurant.entity_id',$restaurant_id);
        return $this->db->get($tblname)->first_row();
    }
    //get address
    public function getAddress($tblname,$fieldName,$user_id){
        $this->db->select('entity_id as address_id,address,landmark,latitude,longitude,city,zipcode');
        $this->db->where($fieldName,$user_id);
        return $this->db->get($tblname)->result();
    }
    //get order detail
    public function getOrderDetail($flag,$user_id){
        $this->db->select('order_master.*,order_detail.*,order_driver_map.driver_id,status.order_status as ostatus,status.time,users.first_name,users.last_name,users.mobile_number,users.phone_code,users.image,driver_traking_map.latitude,driver_traking_map.longitude,restaurant_address.latitude as resLat,restaurant_address.longitude as resLong,restaurant.timings');
        $this->db->join('order_detail','order_master.entity_id = order_detail.order_id','left');
        $this->db->join('order_status as status','order_master.entity_id = status.order_id','left');
        $this->db->join('order_driver_map','order_master.entity_id = order_driver_map.order_id AND order_driver_map.is_accept = 1','left');
        $this->db->join('users','order_driver_map.driver_id = users.entity_id AND order_driver_map.is_accept = 1','left');
        $this->db->join('driver_traking_map','order_driver_map.driver_id = driver_traking_map.driver_id','left');
        $this->db->join('restaurant_address','order_master.restaurant_id = restaurant_address.resto_entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
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
                $timing =  $value->timings;
                if($timing){
                   $timing =  unserialize(html_entity_decode($timing));
                   $newTimingArr = array();
                    $day = date("l");
                    foreach($timing as $keys=>$values) {
                        $day = date("l");
                        if($keys == strtolower($day)){
                            $newTimingArr[strtolower($day)]['open'] = (!empty($values['open']))?date('g:i A',strtotime($values['open'])):'';
                            $newTimingArr[strtolower($day)]['close'] = (!empty($values['close']))?date('g:i A',strtotime($values['close'])):'';
                            $newTimingArr[strtolower($day)]['off'] = (!empty($values['open']) && !empty($values['close']))?'open':'close';
                            $newTimingArr[strtolower($day)]['closing'] = (!empty($values['close']))?($values['close'] <= date('H:m'))?'close':'open':'close';
                        }
                    }
                    $items[$value->order_id]['timings'] = $newTimingArr[strtolower($day)];
                }
                $items[$value->order_id]['order_status'] = ucfirst($value->order_status);
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
                $user_detail = unserialize($value->user_detail);
                $items[$value->order_id]['user_latitude'] = (isset($user_detail['latitude']))?$user_detail['latitude']:'';
                $items[$value->order_id]['user_longitude'] = (isset($user_detail['longitude']))?$user_detail['longitude']:'';
                $items[$value->order_id]['resLat'] = $value->resLat;
                $items[$value->order_id]['resLong'] = $value->resLong;
                $items[$value->order_id]['items']  = $value1;
                if($value->first_name && $value->order_delivery == 'Delivery'){
                    $driver['first_name'] =  $value->first_name;
                    $driver['last_name'] =  $value->last_name;
                    $driver['mobile_number'] =  $value->phone_code.$value->mobile_number;
                    $driver['latitude'] =  $value->latitude;
                    $driver['longitude'] =  $value->longitude;
                    $driver['image'] = ($value->image)?image_url.$value->image:'';
                    $driver['driver_id'] = ($value->driver_id)?$value->driver_id:'';
                    $items[$value->order_id]['driver'] = $driver;
                }
                $items[$value->order_id]['delivery_flag'] = ($value->order_delivery == 'Delivery')?'delivery':'pickup';
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
    //check delivery is available
    public function checkOrderDelivery($latitude,$longitude,$user_id,$restaurant_id,$request,$order_id){
        
        $this->db->select('users.entity_id');
        $this->db->where('user_type','Driver');
        $driver = $this->db->get('users')->result_array();
        
        $this->db->select('driver_traking_map.latitude,driver_traking_map.longitude,driver_traking_map.driver_id,users.device_id');
        $this->db->join('users','driver_traking_map.driver_id = users.entity_id','left');
        $this->db->where('users.status',1);
        $this->db->where('driver_traking_map.created_date = (SELECT
            driver_traking_map.created_date
        FROM
            driver_traking_map
        WHERE
            driver_traking_map.driver_id = users.entity_id
        ORDER BY
            driver_traking_map.created_date desc
        LIMIT 1)');
        if(!empty($driver)){
            $this->db->where_in('driver_id',array_column($driver, 'entity_id'));
        }
        $detail = $this->db->get('driver_traking_map')->result();
        $flag = false;
        if(!empty($detail)){
            foreach ($detail as $key => $value) {
                $longitude = $value->longitude;
                $latitude = $value->latitude;
                $this->db->select("(6371 * acos ( cos ( radians($latitude) ) * cos( radians(address.latitude ) ) * cos( radians( address.longitude ) - radians($longitude) ) + sin ( radians($latitude) ) * sin( radians( address.latitude )))) as distance");
                $this->db->join('restaurant_address as address','restaurant.entity_id = address.resto_entity_id','left');
                $this->db->where('restaurant.entity_id',$restaurant_id);
                $this->db->having('distance <',NEAR_KM);
                $result = $this->db->get('restaurant')->result();
                if($request == 1){
                    if(!empty($result)){
                        if($value->device_id){ 
                            $flag = true;   
                            $array = array(
                                'order_id'=>$order_id,
                                'driver_id'=>$value->driver_id,
                                'date'=>date('Y-m-d H:i:s')
                            );
                            $id = $this->addRecord('order_driver_map',$array);
                            #prep the bundle
                            $fields = array();            
                            $message = 'You have new order, please accept.';
                            $fields['to'] = $value->device_id; // only one user to send push notification
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
                if($request == ''){
                    if(!empty($result)){
                        if($value->device_id){ 
                            $flag = true;
                        }
                    }
                }
            }
        }
        if($flag == false && $request == 1){
            return true;
        }
        if($flag == true && $request == ''){
            return true;
        }
    }
    //get driver location for traking
    public function getdriverTracking($order_id,$user_id){
        $this->db->select('order_driver_map.order_id,order_master.total_rate,order_master.order_status,driver_traking_map.latitude as driverLatitude,driver_traking_map.longitude as driverLongitude,restaurant_address.latitude as resLat,restaurant_address.longitude as resLong,user_address.latitude as userLat,user_address.longitude as userLong,user_address.address,user_address.landmark,user_address.zipcode,user_address.state,user_address.city,driver.first_name,driver.last_name,driver.image,driver.mobile_number,driver.phone_code');
        $this->db->join('order_driver_map','driver_traking_map.driver_id = order_driver_map.driver_id AND order_driver_map.is_accept = 1','left');
        $this->db->join('order_master','order_driver_map.order_id = order_master.entity_id AND order_driver_map.is_accept = 1','left');
        $this->db->join('restaurant_address','order_master.restaurant_id = restaurant_address.resto_entity_id','left');
        $this->db->join('user_address','order_master.address_id = user_address.entity_id','left');
        $this->db->join('users as driver','order_driver_map.driver_id = driver.entity_id','left');
        $this->db->where('order_master.entity_id',$order_id);
        $this->db->where('driver_traking_map.created_date = (SELECT
            driver_traking_map.created_date
        FROM
            driver_traking_map
        WHERE
            driver_traking_map.driver_id = order_driver_map.driver_id
        ORDER BY
            driver_traking_map.created_date desc
        LIMIT 1)');
        if(!empty($driver)){
            $this->db->where_in('driver_id',array_column($driver, 'entity_id'));
        }
        $detail = $this->db->get('driver_traking_map')->first_row();
        if(!empty($detail)){
            $detail->image = ($detail->image )?$detail->image :'';
            $detail->mobile_number = ($detail->mobile_number )?$detail->phone_code.$detail->mobile_number :'';
        }
        return $detail;
    }
}
?>
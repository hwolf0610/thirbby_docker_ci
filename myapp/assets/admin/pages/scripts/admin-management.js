// Add CMS Validation
jQuery("#form_add_category").validate({  
  rules: {    
    CategoryName: {
      required: true
    }
  }  
});
//Reset password
jQuery('#newPasswordform').validate({
  rules:{
    password: {
      required: true,
      //passwordcustom: true
    },
    confirm_pass: {
      required: true,
      equalTo: "#password"
    }
  }
});
//add user
jQuery('#form_add_us').validate({
  rules:{
    first_name:{
      required:true
    },
    last_name:{
      required:true
    },
    email:{
      required:true,
      emailcustom:true
    },
    mobile_number:{
      required:true,
      digits:true
    },
    phone_number:{
      digits:true
    },
    user_type:{
      required:true
    },
    password:{
      required:{
        depends: function(){
          if($('#entity_id').val() == ''){
              return true;
          }
        }
      },
      passwordcustome:true
    },
    confirm_password:{
      required:{
        depends: function(){
          if($('#entity_id').val() == ''){
              return true;
          }
        }
      },
      equalTo:'#password'
    }
  }
});
//add address
jQuery('#form_add_ad').validate({
  rules:{
    user_entity_id:{
      required:true
    },
    address:{
      required:true
    },
    landmark:{
      required:true
    },
    latitude:{
      required:true,
      number:true
    },
    longitude:{
      required:true,
      number:true
    },
    zipcode:{
      required:true,
      digits:true
    },
    country:{
      required:true,
    },
    state:{
      required:true,
    },
    city:{
      required:true,
    },
  }
});
//add restaurant
jQuery('#form_add_re').validate({
  rules:{
    name:{
      required:true
    },
    phone_number:{
      required:true
    },
    mobile_number:{
      required:true
    },
    email:{
      required:true
    },
    capacity:{
      required:true,
      number:true
    },
    /*no_of_table:{
      required:true,
      number:true
    },
    */
    address:{
      required:true,
    },
    landmark:{
      required:true,
    },
    accountNumber:
    {
      required:true,
      number:true,
    },
    routingNumber:
    {
      required:true,
      number:true,
    },

    latitude:{
      required:true,
    },
    longitude:{
      required:true,
    },
    state:{
      required:true,
    },
    country:{
      required:true,
    },
    city:{
      required:true,
    },
    zipcode:{
      required:true,
      digits:true
    },
    amount_type:{
      required:true,
    },
    coupon_amount:{
      required:true,
    },
    amount: {
      required: true,
      number:true,
      max: {
        param:100,
        depends: function(){
          if($("input[name=amount_type]:checked").val() == "Percentage" ){
            return true;
          }
        }
      },
      min: function(element){
          if($("input[name=amount_type]:checked").val() == "Percentage"){
              return 1;
          }else{
              return 0;
          }
      }
    },
    driver_commission:{
      required: true,
      number:true,
      max: 100,
      min:1
    },
    enable_hours:{
      required:true
    }
  }
});
//add category
jQuery('#form_add_cg').validate({
  rules:{
    category_name:{
      required:true
    }
  }
});
//add menu
jQuery('#form_add_menu').validate({
  ignore: [],
  rules:{
    name:{
      required:true
    },
    restaurant_id:{
      required:true
    },
    category_id:{
      required:true
    },
    price:{
      required:true,
      number:true,
      min:0
    },
    menu_detail:{
      required:true
    },
    receipe_detail:{
      required: function() 
      {
        CKEDITOR.instances.receipe_detail.updateElement();
      }
    },
    receipe_time:{
      required : true,
      digits :true
    },
    'availability[]':{
      required:true
    },
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("name") == "receipe_detail") 
    {
      error.insertAfter('#cke_receipe_detail');
      element.next().css('border', '1px solid red');
    } 
    else 
    {
      error.insertAfter(element);
    }
  }
});
//add package
jQuery('#form_add_pac').validate({
  ignore: [],
  rules:{
    name:{
      required:true
    },
    restaurant_id:{
      required:true
    },
    category_id:{
      required:true
    },
    price:{
      required:true,
      number:true,
      min:0
    },
    detail:{
      required: function() 
      {
        CKEDITOR.instances.detail.updateElement();
      }
    },
    'availability[]':{
      required:true
    },
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("name") == "detail") 
    {
      error.insertAfter('#cke_detail');
      element.next().css('border', '1px solid red');
    } 
    else 
    {
      error.insertAfter(element);
    }
  }
});
//add branch
jQuery('#form_add_br').validate({
  rules:{
    name:{
      required:true
    },
    branch_entity_id:{
      required:true
    },
    phone_number:{
      required:true
    },
    email:{
      required:true
    },
    capacity:{
      required:true,
      number:true
    },
    no_of_table:{
      required:true,
      number:true
    },
    address:{
      required:true,
    },
    landmark:{
      required:true,
    },
    latitude:{
      required:true,
    },
    longitude:{
      required:true,
    },
    state:{
      required:true,
    },
    country:{
      required:true,
    },
    city:{
      required:true,
    },
    zipcode:{
      required:true,
      digits:true
    },
    amount_type:{
      required:true,
    },
    coupon_amount:{
      required:true,
    },
    amount: {
      required: true,
      number:true,
      max: {
        param:100,
        depends: function(){
          if($("input[name=amount_type]:checked").val() == "Percentage" ){
            return true;
          }
        }
      },
      min: function(element){
          if($("input[name=amount_type]:checked").val() == "Percentage"){
              return 1;
          }else{
              return 0;
          }
      }
    },
    enable_hours:{
      required:true
    }
  }
});
//add coupon
jQuery('#form_add_cpn').validate({
  ignore:[],
  rules:{
    name:{
      required:true
    },
    description:{
      required: function() 
      {
        CKEDITOR.instances.description.updateElement();
      }
    },
    amount_type:{
      required:true
    },
    amount:{
      required: true,
      number:true,
      max: {
        param:100,
        depends: function(){
          if($("input[name=amount_type]:checked").val() == "Percentage" ){
            return true;
          }
        }
      },
      min: function(element){
          if($("input[name=amount_type]:checked").val() == "Percentage"){
              return 1;
          }else{
              return 0;
          }
      }
    },
    max_amount:{
      required:true,
      number:true,
      min:1,
    },
    start_date:{
      required:true
    },
    end_date:{
      required:true
    },
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("name") == "description") 
    {
      error.insertAfter('#cke_description');
      element.next().css('border', '1px solid red');
    } 
    else 
    {
      error.insertAfter(element);
    }
  }
});
//add category
jQuery('#form_add_order').validate({
  rules:{
    user_id:{
      required:true
    },
    restaurant_id:{
      required:true
    },
    address_id:{
      required:true
    },
    order_status:{
      required:true
    },
    order_date:{
      required:true
    },
    total_rate:{
      required:true
    }
  }
});
jQuery('#form_add_event').validate({
  rules:{
    name:{
      required:true
    },
    no_of_people:{
      required:true,
      digits:true
    },
    no_of_table:{
      required:true,
      digits:true
    },
    booking_date:{
      required:true
    },
    restaurant_id:{
      required:true
    },
    user_id:{
      required:true
    },
    end_date:{
      required:true
    }
  }
});
jQuery('#form_add_cms').validate({
  ignore:[],
  rules:{
    name:{
      required:true
    },
    description:{
      required: function() 
      {
        CKEDITOR.instances.description.updateElement();
      }
    }
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("name") == "description") 
    {
      error.insertAfter('#cke_description');
      element.next().css('border', '1px solid red');
    } 
    else 
    {
      error.insertAfter(element);
    }
  }
});
// Add Email Template Validation
jQuery("#form_add_email").validate({  
  ignore:[],
  rules: {    
    title: {
      required: true
    },
    subject: {
      required: true
    },
    message: {
      required: function() 
      {
        CKEDITOR.instances.message.updateElement();
      }
    },
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("name") == "message") 
    {
      error.insertAfter('#cke_message');
      element.next().css('border', '1px solid red');
    } 
    else 
    {
      error.insertAfter(element);
    }
  }
});
//add Amount
jQuery('#form_add_amount').validate({
  rules:{
    amount:{
      required:true,
      number:true,
      min:0,
    },
    event_status:{
      required:true
    },
    subtotal:{
      required:true
    }
  }
});
//add Amount
jQuery('#form_add_notification').validate({
  rules:{
    'user_id[]':{
      required:true
    },
    notification_title:{
      required:true
    },
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("id") == "user_id") 
    {
      error.insertAfter('.sumo_user_id');
    } 
    else 
    {
      error.insertAfter(element);
    }
  }
});
//add Amount
jQuery('#send_email').validate({
  rules:{
    'user_id[]':{
      required:true
    },
    template_id:{
      required:true
    },
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("id") == "user_id") 
    {
      error.insertAfter('.sumo_user_id');
    } 
    if (element.attr("id") == "template_id") 
    {
      error.insertAfter('.sumo_template_id');
    } 
  }
});
//system option
jQuery('#SystemOption').validate({
  rules:{
    'OptionValue[]':{
      required:true
    },
  }
});
// admin email exist check
function checkEmailExist(email,entity_id){
  $.ajax({
    type: "POST",
    url: BASEURL+"backoffice/users/checkEmailExist",
    data: 'email=' + email +'&entity_id='+entity_id,
    cache: false,
    success: function(html) {
      if(html > 0){
        $('#EmailExist').show();
        $('#EmailExist').html("User is already exist with this email id!");        
        $(':input[type="submit"]').prop("disabled",true);
      } else {
        $('#EmailExist').html("");
        $('#EmailExist').hide();        
        $(':input[type="submit"]').prop("disabled",false);
      }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {                 
      $('#EmailExist').show();
      $('#EmailExist').html(errorThrown);
    }
  });
}
$.validator.addMethod("emailcustom",function(value,element)
{
  return this.optional(element) || /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(value);
},"Please enter valid email address");

// custom password
$.validator.addMethod("passwordcustome",function(value,element)
{
  return this.optional(element) || /^(?=.*[0-9])(?=.*[!@#$%^&*)(])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*)(]{8,}$/.test(value);
},"Passwords must contain at least 8 characters, including uppercase, lowercase letters, symbols and numbers.");
// end here
 /^[+-]?\d+$/
// custom code for lesser than
jQuery.validator.addMethod('lesserThan', function(value, element, param) {  
  return ( parseInt(value) <= parseInt(jQuery(param).val()) );
}, 'Must be less than close time' );

// custom code for greater than
$.validator.addMethod("greaterThan", function(value, element, param) {
  return ( parseInt(value) >= parseInt(jQuery(param).val()) );    
}, "Must be greater than open time");

// custom code for greater than
$.validator.addMethod("greater", function(value, element, param) {
  return ( parseInt(value) > parseInt(jQuery(param).val()));    
}, "Must be greater than Amount");
// custom password
$.validator.addMethod("phoneNumber",function(value,element)
{
  return this.optional(element) || /^[+]?\d+$/.test(value);
},"Please enter valid phone number");
// end here
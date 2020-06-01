// Merchant Login Form
$("#login_form").validate({
  rules: {
    username: {
      required:true,
      emailcustome: true
    },
    password: {
      required:true
    }
  },
  messages: {
    username: {        
        emailcustome: "Please enter a valid email address"
      }
  }
});


$.validator.addMethod("passwordcustome",function(value,element)
{
  return this.optional(element) || /^(?=.*[0-9])(?=.*[!@#$%^&*)(])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*)(]{8,}$/.test(value);
},"Passwords minimum 8 characters");

$.validator.addMethod("emailcustome",function(value,element)
{
  return this.optional(element) || /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(value);
},"Please enter valid email address");


// forgot password validation
jQuery("#forget-password-form").validate({
  rules: {
    email_address: {
      required: true,
      emailcustome: true
    }
  }
});


// for newpassword
jQuery("#newPasswordform").validate({
  rules: {    
    password: {
      required: true,
      passwordcustome: true
    },
    confirm_pass: {
      required: true,              
      equalTo: "#password"
    }
  },
  messages: {    
    password: {
      passwordcustome: "Passwords must contain at least 8 characters, including uppercase, lowercase letters, symbols and numbers."
    },
    confirm_pass: {
      equalTo: "Please enter the same password as above"
    }
  }
});
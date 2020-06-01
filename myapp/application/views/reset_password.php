<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<meta charset="utf-8"/>
<title><?php echo $MetaTitle ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/admin/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/admin/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/admin/css/login.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/admin/css/components.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<link rel="icon" href="<?php echo base_url();?>assets/admin/img/favicon.png" type="image/x-icon" />
</head>
<body class="login">
<div class="logo">
    <img src="<?php echo base_url();?>assets/admin/img/logo.png" alt=""/>
</div>
<div class="menu-toggler sidebar-toggler">
</div>
<div class="content">
    <!-- BEGIN FORM -->
    <?php if(validation_errors()){?>
        <div class="alert alert-danger">
            <?php echo validation_errors();?>
        </div>
    <?php } ?>
    <?php if($this->session->flashdata('PasswordChange')){ ?>
    <div class="alert alert-success">
        <strong>Success!</strong> <?php echo $this->session->flashdata('PasswordChange');?>
    </div>
    <?php } ?>
    <?php if($this->session->flashdata('verifyerr')){ ?>
    <div class="alert alert-success">
        <strong>Success!</strong> <?php echo $this->session->flashdata('verifyerr');?>
    </div>
    <?php } ?>
    <form action="<?php echo base_url()?>user/reset" method="Post" id="newPasswordform" class="form-wrap">
    <h3>New Password</h3>
    <p>
        Create your new password.
    </p>
      <div class="form-group">
         <label>Password</label>
         <input type="hidden" value="<?php echo $verificationCode?>" name="verificationCode" id="verificationCode">
         <input class="form-control" type="password" placeholder="Password" id="password" name="password"/>
      </div>
      <div class="form-group">
         <label>Confirm Password</label>
         <input type="password" id="confirm_pass" name="confirm_pass" class="form-control" placeholder="Confirm Password">
      </div>
    <div class="action-wrp">
     <button class="btn btn-lg btn-signin" type="submit" name="submit" id="submit" value="Submit">Submit</button>
    </div>
   </form>
    <!-- END  FORM -->
</div>
<!-- END -->
<script src="<?php echo base_url();?>assets/admin/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>
</body>
</html>
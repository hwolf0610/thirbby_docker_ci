<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<meta charset="utf-8"/>
<title><?php echo $this->lang->line('site_title');?></title>
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
<link rel="shortct icon" href="<?php echo base_url();?>assets/admin/img/favicon.png"/>
</head>
<body class="login">
<div class="bg-login"></div>
<div class="overlay"></div>
<div class="menu-toggler sidebar-toggler">
</div>
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <div class="logo">
        <img src="<?php echo base_url();?>assets/admin/img/logo.png" alt=""/>
    </div>
    <?php if($this->session->flashdata('PasswordChange')){ ?>
    <div class="alert alert-success">
        <strong>Success!</strong> <?php echo $this->session->flashdata('PasswordChange');?>
    </div>
    <?php } ?>

    <?php if($this->session->flashdata('verified')){ ?>
    <div class="alert alert-success">
        <strong>Success!</strong> <?php echo $this->session->flashdata('verified');?>
    </div>
    <?php } ?>
    
    <?php if($this->session->flashdata('loginError')){?>
    <div class="alert alert-danger">
        <strong>Error!</strong> <?php echo $this->session->flashdata('loginError');?>
    </div>
    <?php } else if(isset($loginError) && $loginError !=""){?>
    <div class="alert alert-danger">
        <strong>Error!</strong> <?php echo $loginError;?>
    </div>
    <?php } ?>
    <?php if($this->session->flashdata('ErrorPreventMultiLogin')){?>
    <div class="alert alert-danger">
        <strong>Error!</strong> <?php echo $this->session->flashdata('ErrorPreventMultiLogin');?>
    </div>
    <?php } ?>
    <?php // get Cookies
    parse_str(get_cookie('adminAuth'), $adminCook); 
    ?>
    <form id="login_form" class="login-form" action="<?php echo base_url().ADMIN_URL; ?>/home/do_login" method="post">
        <h3 class="form-title">Login to your account</h3>
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span>Please Enter Email and Password.</span>
        </div>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Username</label>
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="username" id="username" value="<?php echo isset($adminCook['usr'])?$adminCook['usr']:'';?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" id="password" value="<?php echo isset($adminCook['hash'])?$adminCook['hash']:'';?>" />
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox" name="rememberMe" id="rememberMe" value="1" <?php echo ($adminCook)?"checked":""?>/> Remember me
            <input type="submit" class="btn danger-btn pull-right" name="submit" value="Login" style="background-color: #86CD82 !important;">
        </div>
        <div class="forget-password">
            <h4>Forgot your password ?</h4>
            <p>
                 Click <a href="<?php echo base_url().ADMIN_URL;?>/home/forgotpassword" id="forget-password" style="color: #86CD82 !important;">
                here </a>
                to reset your password.
            </p>
        </div>
    </form>
    <!-- END LOGIN FORM -->

<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
     <?php  echo 'COPYRIGHT ' ?>&copy; <?php echo date('Y').'. ALL RIGHTS RESERVED.';?>  <?php echo $this->lang->line('dev_site_name');?>
</div>
</div>
<!--[if lt IE 9]>
    <script src="<?php echo base_url();?>assets/admin/plugins/respond.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="<?php echo base_url();?>assets/admin/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-login-forgot-validation.js"></script>
</body>
</html>
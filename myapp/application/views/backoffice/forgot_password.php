<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?php echo $this->lang->line('site_title');?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/admin/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/admin/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php echo base_url();?>assets/admin/css/login.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="<?php echo base_url();?>assets/admin/css/components.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="<?php echo base_url();?>assets/admin/img/favicon.png"/>
</head>
<body class="login">
  <div class="bg-login"></div>
<div class="overlay"></div>
<!-- BEGIN LOGO -->
<!-- END LOGO -->
<!-- END sidebar TOGGLER BUTTON -->
<!-- BEGIN LOGIN -->
<div class="content">
  <div class="logo">
    <img src="<?php echo base_url();?>assets/admin/img/logo.png" alt=""/>
</div>

    <!-- BEGIN FORGOT PASSWORD FORM -->
    <?php if(validation_errors()){?>
        <div class="alert alert-danger">
            <?php echo $error; echo validation_errors();?>
        </div>
    <?php } ?>
    <?php if($this->session->flashdata('emailNotExist')){ ?>
      <div class="alert alert-danger">
          <?php echo $this->session->flashdata('emailNotExist');?>
      </div>
    <?php } ?>
    <?php if($this->session->flashdata('verifyerr')){ ?>
      <div class="alert alert-danger">
          <?php echo $this->session->flashdata('verifyerr');?>
      </div>
    <?php } ?>
    <form class="forget-password-form" id="forget-password-form" action="<?php echo base_url().ADMIN_URL; ?>/home/forgotpassword" method="post">
        <h3>Forgot Password ?</h3>
        <p>
             Enter your e-mail address below to reset your password.
        </p>
        <div class="form-group">
            <div class="input-icon">
                <i class="fa fa-envelope"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email_address" id="email_address" />
            </div>
        </div>        
        <div class="form-actions">
            <a id="back-btn" class="btn default" href="<?php echo base_url().ADMIN_URL; ?>/home" style="background-color: #FFB0AF !important;"><i class="m-icon-swapleft"></i> Back </a>
            <input type="submit" class="btn danger-btn pull-right" value="Submit" name="Submit" id="Submit" style="background-color: #86CD82 !important;">
        </div>
    </form>
    <!-- END FORGOT PASSWORD FORM -->

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
<script src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-login-forgot-validation.js"></script>

</body>
</html>
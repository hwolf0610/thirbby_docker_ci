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
<!-- BEGIN LOGO -->
<div class="logo">
    <a href="<?php echo base_url().ADMIN_URL?>">
      <img src="<?php echo base_url();?>assets/admin/img/logo.png" alt="<?php $this->lang->line('site_title') ?>"/>
    </a>
</div>
<!-- END LOGO -->
<div class="main">
  <div class="container">
    <!-- BEGIN SIDEBAR & CONTENT -->
    <div class="row">
      <!-- BEGIN CONTENT -->
        <div class="col-md-8 front-from">
          <div class="informatoin-content">
              <h2 class="title-type1 text-center">Password Assistance</h2>
              <strong>Check your e-mail.</strong>
              <hr class="red">
              <p>You will receive an email from us with instructions for resetting your password. If you don't receive this email, please check your junk mail folder.</p>
              <p><a href="<?php echo base_url().ADMIN_URL;?>">Return home </a></p>
          </div>  
      </div>
    </div>
  </div>
</div>
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
     <?php  echo 'COPYRIGHT ' ?>&copy; <?php echo date('Y').'. ALL RIGHTS RESERVED.';?>  <?php echo $this->lang->line('dev_site_name');?>
</div>
</body>
</html>
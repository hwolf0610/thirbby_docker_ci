<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">        
        <ul class="page-sidebar-menu" data-auto-scroll="false" data-auto-speed="200">            
            <li class="sidebar-toggler-wrapper">                
                <div class="sidebar-toggler">
                </div>                
            </li>
            <li>&nbsp;</li>
            <li class="start <?php echo ($this->uri->segment(2)=='dashboard')?"active":""; ?>">
                <a href="<?php echo base_url().ADMIN_URL;?>/dashboard">
                    <i class="fa fa-dashboard"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>
            <?php if($this->session->userdata('UserType') == 'MasterAdmin'){ ?>
                <li class="start <?php echo ($this->uri->segment(2)=='users' || $this->uri->segment(3)=='driver' || $this->uri->segment(3)=='commission')?"active":""; ?>">
                    <a href="<?php echo base_url().ADMIN_URL;?>/users/view">
                        <i class="fa fa-users"></i>
                        <span class="title">Users</span>
                        <span class="arrow <?php echo ($this->uri->segment(2)=='users' || $this->uri->segment(3) == 'driver' || $this->uri->segment(4)=='driver')?"open":""; ?>"></span>
                        <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="start <?php echo ($this->uri->segment(2)=='users' && $this->uri->segment(3) != 'driver' && $this->uri->segment(4)!='driver' && $this->uri->segment(3)!='commission' && $this->uri->segment(5)!='driver' && $this->uri->segment(3) != 'review')?"active":""; ?>">
                            <a href="<?php echo base_url().ADMIN_URL;?>/users/view">
                                <i class="fa fa-users"></i>
                                <span class="title">Manage Users</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(3)=='driver' || $this->uri->segment(4)=='driver' ||  $this->uri->segment(3)=='commission' && $this->uri->segment(5)=='driver' || $this->uri->segment(3) == 'review')?"active":""; ?>">
                            <a href="<?php echo base_url().ADMIN_URL;?>/users/driver">
                                <i class="fa fa-motorcycle" aria-hidden="true"></i>
                                <span class="title">Manage Drivers</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } ?>
            <li class="start <?php echo ($this->uri->segment(2)=='restaurant' || $this->uri->segment(2)=='branch')?"active":""; ?>">
                <a href="<?php echo base_url().ADMIN_URL;?>/restaurant/view">
                    <i class="fa fa-file-text"></i>
                    <span class="title">Restaurant</span>
                    <span class="arrow <?php echo ($this->uri->segment(2)=='restaurant' || $this->uri->segment(2)=='branch')?"open":""; ?>"></span>
                    <span class="selected"></span>
                </a> 
                <ul class="sub-menu">
                    <li class="start <?php echo ($this->uri->segment(2)=='restaurant' && $this->uri->segment(3) =='view')?"active":""; ?>">
                        <a href="<?php echo base_url().ADMIN_URL;?>/restaurant/view">
                            <i class="fa fa-cutlery"></i>
                            <span class="title">Manage Restaurant</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="start <?php echo ($this->uri->segment(3)=='view_menu' || $this->uri->segment(3) == 'add_menu')?"active":""; ?>">
                        <a href="<?php echo base_url().ADMIN_URL;?>/restaurant/view_menu">
                            <i class="fa fa-bars"></i>
                            <span class="title">Manage Restaurant Menu</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="start <?php echo ($this->uri->segment(3)=='view_package' || $this->uri->segment(3) == 'add_package')?"active":""; ?>">
                        <a href="<?php echo base_url().ADMIN_URL;?>/restaurant/view_package">
                            <i class="fa fa-gift"></i>
                            <span class="title">Manage Restaurant Package</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="start <?php echo ($this->uri->segment(2) == 'branch')?"active":""; ?>">
                        <a href="<?php echo base_url().ADMIN_URL;?>/branch/view">
                            <i class="fa fa-building-o"></i>
                            <span class="title">Manage Branch</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="start <?php echo ($this->uri->segment(2)=='category')?"active":""; ?>">
                <a href="<?php echo base_url().ADMIN_URL;?>/category/view">
                    <i class="fa fa-list-alt"></i>
                    <span class="title">Menu</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="start <?php echo ($this->uri->segment(2)=='order')?"active":""; ?>">
                <a href="<?php echo base_url().ADMIN_URL;?>/order/view">
                    <i class="fa fa-file-text"></i>
                    <span class="title">Orders</span>
                    <span class="arrow <?php echo ($this->uri->segment(2)=='order')?"open":""; ?>"></span>
                    <span class="selected"></span>
                </a> 
                <ul class="sub-menu">
                    <li class="start <?php echo ($this->uri->segment(2)=='order' && $this->uri->segment(3) != 'pending' && $this->uri->segment(3) != 'delivered' && $this->uri->segment(3) != 'on-going')?"active":""; ?>">
                        <a href="<?php echo base_url().ADMIN_URL;?>/order/view">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="title">All Orders</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="start <?php echo ($this->uri->segment(3) =='pending')?"active":""; ?>">
                        <a href="<?php echo base_url().ADMIN_URL;?>/order/pending">
                            <i class="fa fa-clock-o"></i>
                            <span class="title">Placed</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="start <?php echo ($this->uri->segment(3) == 'delivered')?"active":""; ?>">
                        <a href="<?php echo base_url().ADMIN_URL;?>/order/delivered">
                            <i class="fa fa-truck"></i>
                            <span class="title">Delivered</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="start <?php echo ($this->uri->segment(3) == 'on-going')?"active":""; ?>">
                        <a href="<?php echo base_url().ADMIN_URL;?>/order/on-going">
                            <i class="fa fa-motorcycle"></i>
                            <span class="title">On Going</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="start <?php echo ($this->uri->segment(3) == 'cancel')?"active":""; ?>">
                        <a href="<?php echo base_url().ADMIN_URL;?>/order/cancel">
                            <i class="fa fa-times"></i>
                            <span class="title">Cancel</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="start <?php echo ($this->uri->segment(2)=='event')?"active":""; ?>">
                <a href="<?php echo base_url().ADMIN_URL;?>/event/view">
                    <i class="fa fa-calendar"></i>
                    <span class="title">Event Bookings</span>
                    <span class="selected"></span>
                </a>
            </li>
            <?php if($this->session->userdata('UserType') == 'MasterAdmin'){ ?>
            <li class="start <?php echo ($this->uri->segment(2)=='coupon')?"active":""; ?>">
                <a href="<?php echo base_url().ADMIN_URL;?>/coupon/view">
                    <i class="fa fa-dollar"></i>
                    <span class="title">Coupons</span>
                    <span class="selected"></span>
                </a>
            </li>
            <?php } ?>
            <li class="start <?php echo ($this->uri->segment(2)=='review')?"active":""; ?>">
                <a href="<?php echo base_url().ADMIN_URL;?>/review/view">
                    <i class="fa fa-star"></i>
                    <span class="title">Reviews & Ratings</span>
                    <span class="selected"></span>
                </a>
            </li>
            <?php if($this->session->userdata('UserType') == 'MasterAdmin'){ ?>
                <li class="start <?php echo ($this->uri->segment(2)=='notification')?"active":""; ?>">
                    <a href="<?php echo base_url().ADMIN_URL;?>/notification/view">
                        <i class="fa fa-file-text"></i>
                        <span class="title">Notifications</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(2)=='slider-image')?"active":""; ?>">
                    <a href="<?php echo base_url().ADMIN_URL;?>/slider-image/view">
                        <i class="fa fa-image"></i>
                        <span class="title">Slider</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(2)=='cms')?"active":""; ?>">
                    <a href="<?php echo base_url().ADMIN_URL;?>/cms/view">
                        <i class="fa fa-file-text"></i>
                        <span class="title">CMS</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(2)=='system_option')?"active":""; ?>">
                    <a href="<?php echo base_url().ADMIN_URL;?>/system_option/view">
                        <i class="fa fa-file-text"></i>
                        <span class="title">System Options</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(2)=="email_template")?"active":""; ?>">
                    <a href="<?php echo base_url().ADMIN_URL;?>/email_template/view">
                        <i class="fa fa-envelope-o"></i>
                        <span class="title">E-mail Templates</span>
                        <span class="selected"></span>
                    </a>
                </li>
            <?php } ?>
        </ul>        
    </div>
</div>
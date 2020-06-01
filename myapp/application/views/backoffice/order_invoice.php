<style type="text/css">
body {
	font-family: Arial
}
.pdf_main {
	background: #fff;
	margin-left: 25px;
	margin-right: 25px;
}
.clearfix {
	clear: both;
}
ul, li {
	list-style: none;
	margin: 0px;
	padding: 0px;
}
.head-main {
	float: left;
	width: 100%;
	margin-bottom: 30px;
}
.pdf_main .logo {
	float: left;
	padding-top: 24px;
	width: 30%;
}
.pdf_main .logo:hover {
	opacity: 1;
}
.pdf_main .head-right {
	float: right;
	width: 330px;
}
.pdf_main .quote-title {
	float: right;
	text-align: right;
	width: 100%;
	padding-bottom: 15px;
}
.pdf_main .col-li {
	float: left;
	display: inline-block;
	text-align: center;
	padding: 0 10px;
	font-size: 12px;
	font-weight: 700;
	width:170px;
}
.pdf_main .col-li span {
	font-weight: 400;
}
.pdf_main .col-li .icon {
	display: block;
	padding-bottom: 5px;
}
.pdf_main .main-container {
	float: left;
	width: 100%;
}
.pdf_main .head-main h3 {
	text-align: right;
	margin-bottom: 20px;
	float: right;
}
.pdf_main .head-right li.last, .pdf_main .head-right li:last-child {
	padding-right: 0px;
}
.bill-ship-details {
	margin: 0 -4%;
	clear: both;
}
.pdf_main .colm {
	float: left;
	padding: 0 4%;
	width: 40%;
}
.pdf_main .footer {
	background-color: #0076c0;
	float: left;
	text-align: center;
	display: block;
	padding: 12px 0 0px;
	box-sizing: border-box;
	margin-top: 30px;
	width: 650px;
}
.foot-li {
	color: #fff;
	font-size: 12px;
	font-weight: bold;
}
.foot-li.last {
	border-right: none;
}
.pdf_main table {
	border: 2px #bebcbc solid;
	border-collapse: collapse;
}
.pdf_main table tbody td {
	border: none !important;
}
.pdf_main table th {
	border: none !important;
}
.pdf_main .pdf_table {
	margin-bottom: 50px;
	margin-bottom: 30pt;
}
.pdf_main .pdf_table p {
	color: #000000;
	font-size: 11px;
	font-weight: 400;
	margin-bottom: 10px;
}
.bill-ship-details .colm h3 {
	border-bottom: 2px solid #000000;
	font-size: 16px;
	padding-bottom: 7px;
	margin-bottom: 12px;
}
.bill-ship-details p {
	font-size: 13px;
	color: #000
}
.pdf_main .pdf_table table td[colspan="3"] {
	padding-top: 24px;
}
.pdf_main .pdf_table thead th, .pdf_main .pdf_table tfoot td.grand-total,.div-thead {
	color: #ffffff;
	font-size: 14px;
	background-color: #ffb300;
}
.div-thead-black{
  color: #ffffff;
  font-size: 14px;
  background-color: #000000;
}
.pdf_main .pdf_table {
	margin-bottom: 50px;
	margin-bottom: 30pt;
}
.pdf_main .pdf_table p {
	color: #000000;
	font-size: 11px;
	font-weight: 400;
	margin-bottom: 10px;
}
.signature h4.signature-heading {
	font-size: 15px;
	display: inline-block;
	margin: 0px;
}
.signature .signature-line {
	border-bottom: 1px solid black;
	display: inline-block;
	vertical-align: middle;
	width: 311px;
	margin-left: 8px;
.black-theme.pdf_main .pdf_table thead th {
  color: #ffffff;
  font-size: 16px;
  background-color: #000000;
  text-align:left;
}
.black-theme.pdf_main tfoot td.grand-total {
	color: #ffffff;
	background-color: #000000;
}
.black-theme.pdf_main .footer {
	background-color: #000000;
	border-bottom: 3px #000000 solid;
}
.black-theme.pdf_main .footer li {
	border-right: 0;
}
.black-theme.pdf_main table tbody td {
	border: none !important;
}
.black-theme.pdf_main table th {
	border: none !important;
}
.lenth-sec {

	margin-left: 5px;
}
.lenth-sec > label {
	font-weight: 400;
}
.lenth-sec {
	height: 31px;
	vertical-align: top;
}
tr, td, th {
	border: 1px solid #bebcbc;
}
/*.pdf_main {
	margin-left: 38px;
	margin-right: 38px
}*/
.table-style tr td, .table-style tr td{padding-top:4px; padding-bottom:4px;}
.table-style tr .border-line{padding-bottom:7px;}
.segment-main {
  width: 100%;
  border: 2px solid #bebcbc;
  font-size: 12px;
}


</style>
<div class="pdf_main">
    <div class="head-main">
	    <div class="logo"> <img src="http://eatance.evincedev.com/assets/admin/img/logo.png" alt="" width="240" height="122"/> </div>
	    <div class="head-right" style="float: right;">
	      <div class="quote-title"><img src="http://eatance.evincedev.com/assets/admin/img/quote-text-img.png" width="135" alt="" /></div>
	      <div class="col-li" style="width: 100px;"> <span class="icon"><img src="http://eatance.evincedev.com/assets/admin/img/note-icon.png" width="50" alt="" /></span>
	        <p>Order NO. <br>
	          <span><?php echo $order_records->entity_id; ?></span></p>
	      </div>
	      <div class="col-li"> <span class="icon"><img src="http://eatance.evincedev.com/assets/admin/img/calender.png" width="50"  alt="" /></span>
	        <p>DATE <br>
	          <span>
	          <?php $date = date("d-m-Y h:i A",strtotime($order_records->order_date)); echo $date; ?>
	          </span></p>
	      </div>
	    </div>
    </div>
	<div class="main-container">
		<div class="bill-ship-details">
	      <div class="colm" style="float:left">
	        <h3>Ship To</h3>
	        <?php $user_detail = unserialize($menu_item->user_detail);
	        if(!empty($user_detail)){ ?>
	        <p><?php echo $user_detail['first_name'].' '.$user_detail['last_name'].'<br>' .$user_detail['address'].'<br> '.$user_detail['landmark'].'<br>'.$user_detail['city'].' '.$user_detail['zipcode'] ?></p>
	       	<?php }else{ ?>
	       		<p>Order By Restaurant</p>
	       	<?php } ?>
	      </div>
	      <div class="colm last">
	        <h3>Restaurant</h3>
	        <?php $restaurant_detail = unserialize($menu_item->restaurant_detail);
	        if(!empty($restaurant_detail)){ ?>
	        <p><?php echo $restaurant_detail->name.'<br>' .$restaurant_detail->address.'<br> '.$restaurant_detail->landmark.'<br>'.$restaurant_detail->city.' '.$restaurant_detail->zipcode ?></p>
	  	    <?php }else{ ?>
	  	    <p><?php echo $order_records->name ?></p>
	  	    <?php	} ?>
	      </div>
	    </div>
	    <div class="clearfix" style="clear:both; height:10px"></div>
	</div>
	<div class="segment-main">
		<!-- Header -->
        <div class="div-thead">
          	<div>
          		<div style="text-align:left;width:5%;float:left;padding:5px 0 5px 10px;">#</div>
	            <div style="text-align:left;width:35%;float:left;padding:5px 0 5px 10px;">Item</div>
	            <div style="text-align:center;width:15%;float:left;padding:5px 0 5px 0">Price</div>
	            <div style="text-align:center;width:15%;float:left;padding:5px 0 5px 0">Qty</div>
	            <div style="text-align:center;width:20%;float:left;padding:5px 0 5px 0">Total</div>
            </div>
        </div>
        <!-- body -->
        <div>
        	<?php $item_detail = unserialize($menu_item->item_detail);
        	 if(!empty($item_detail)){ $Subtotal = 0; $i = 1;
        		foreach($item_detail as $key => $value){ ?>
	            <div style="border-bottom:0px;">
	            	<div style="text-align:left;width:5%;float:left;padding:5px 0 5px 10px"><?php echo $i ?></div>
		            <div style="text-align:left;width:35%;float:left;padding:5px 0 5px 10px"><?php echo $value['item_name'] ?></div>
		            <div style="text-align:center;width:15%;float:left;padding:5px 0 5px 0" class="center"><?php echo number_format($value['rate'],'2') ?></div>
		            <div style="text-align:center;width:15%;float:left;padding:5px 0 5px 0" class="center"><?php echo number_format($value['qty_no'],'2') ?></div>
		            <div style="text-align:center;width:20%;float:left;padding:5px 0 5px 0" class="center"><?php echo number_format($value['rate'] * $value['qty_no'],'2'); ?></div>
	           </div>
           <?php $Subtotal = $Subtotal + ($value['rate'] * $value['qty_no']);$i++; }  } ?>
        </div>
	</div>
	<!-- Footer part for Price -->
    <table border="3" cellpadding="10" cellspacing="0" width="100%" class="table-style">
          <tr>
            <td rowspan="4" style="width: 60%">&nbsp;</td>
            <td class="align-right" style="width: 15%"><strong>Subtotal</strong></td>
            <td class="align-left" style="width: 20%"><?php echo number_format($Subtotal,'2') ?></td>
          </tr>
          <tr>
            <td class="align-right"><strong>Discount</strong></td>
            <td class="align-left"><?php echo $order_records->coupon_amount ?><?php echo ($order_records->coupon_type == 'Percentage')?'%':'' ?></td>
          </tr>
          <tr>
            <td class="align-right"><strong>Sales Tax</strong></td>
            <td class="align-left"><?php echo $order_records->tax_rate ?><?php echo ($order_records->tax_type == 'Percentage')?'%':'' ?></td>
          </tr>
          <tr>
            <td class="align-right grand-total"><strong>TOTAL</strong></td>
            <td class="align-left grand-total"><?php echo $order_records->total_rate; ?></td>
          </tr>
    </table>
    <!-- Footer part for Price end -->
</div>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Email Template</h4>
</div>
<?php
// create array for email fields
$arrayName =array(
    'Site Name' =>array('#Site_Name#',''),
    'Company Name' =>array('#Company_Name#',''),
    'First  Name'=>array('#firstname#','#s_firstname#'),
    'Last Name'=>array('#lastname#','#s_lastname#'),
    'Email'=>array('#email#','#s_email#'),
    'UserType'=>array('#utype#','#s_utype#'),
    'Address'=>array('#address#',''),
    'Password'=>array('#password#',''),    
    'Phone'=>array('#phone#',''),
    'Country'=>array('#country#',''),    
    'State'=>array('#state#',''),
    'City'=>array('#city#',''),
    'Zip Code'=>array('#zipcode#',''),
    'Ip Address'=>array('#ip#',''),
    'Bank of Months'=>array('#BankofMonths#',''),
    'Expiry'=>array('#expiry#',''),
    'Login Link'=>array('#loginlink#',''),
    'Renew account Link'=>array('#renew_account_link#',''),
);

?>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                    <thead>
                            <tr>                            
                             <th>Mail Merge Field for Reciever</th>
                             <th>Mail Merge Field for Sender</th>
                             <th>Replaced Field</th>
                         </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($arrayName as $key => $value) { ?>                    
                    <tr>
                        <td><?php echo $value[0];?></td>
                        <td><?php echo $value[1];?></td>
                        <td><?php echo $key;?></td>
                    </tr>                 
                    <?php } ?>                        
                    </tbody>
                    </table>
 </div>
</div>
</div>
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn default" data-dismiss="modal">Close</button>    
</div>
<div class="page-footer">
    <div class="page-footer-inner">
         <?php  echo 'COPYRIGHT ' ?>&copy; <?php echo date('Y').'. ALL RIGHTS RESERVED.';?>  <?php echo $this->lang->line('dev_site_name');?>
    </div>
    <div class="page-footer-tools">
        <span class="go-top">
        <i class="fa fa-angle-up"></i>
        </span>
    </div>
</div>
<!-- END footer -->
</body>
<!-- END BODY -->
<script type="text/javascript">
$(document).ready(function(){
   var i = setInterval(function(){
      jQuery.ajax({
        type : "POST",
        dataType : "json",
        async: false,
        url : '<?php echo base_url().ADMIN_URL?>/dashboard/ajaxNotification',
        success: function(response) {
            var past_count = $('.notification span.count').html();
            if(response != null){
              if(response.order_count != '' && response.order_count != null){
                if(past_count < response.order_count){
                    var obj = document.createElement("audio");
                    obj.src = "<?php echo base_url() ?>assets/admin/img/notification_sound.wav"; 
                    obj.play(); 
                }
                var count = (response.order_count >= 100)?'99+':response.order_count;
                $('.notification span.count').html(count);
              }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {           
        }
      });
    },10000);  
});
function changeViewStatus(){
    jQuery.ajax({
        type : "POST",
        dataType : "html",
        url : '<?php echo base_url().ADMIN_URL?>/dashboard/changeViewStatus',
        success: function(response) {
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {           
        }
    });
}
</script>
</html>
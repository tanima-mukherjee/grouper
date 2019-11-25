<script>var WEBROOT = '<?php echo $this->webroot;?>';</script>
<script>
function get_friend_list(search_name){
	if(search_name!="" && search_name.length>1){
		$("#main_search_html").show("");
		$("#main_search_html").html("<img style='margin-left:30%;' src='<?php echo $this->webroot;?>images/ajax_loader-2.gif'>");
		//return false;
		$.ajax({
		  type: "POST",
		  url: "<?php echo $this->webroot?>chat/get_friend_list/",
		  data: { search_name: search_name},
		  success: function(msg)
		  {				
				$("#main_search_html").html(msg);
								 				
		  }
		});
	}else{
		$("#main_search_html").html("");	
	}
}
</script>

<main class="main-body">  
<div class="category-details-content">
    <div class="container">
      <div class="heading-title">
        <h2>Chat</h2>
        <div class="clearfix"></div>
      </div>  
     
      <div class="fullchat-section">
        <div class="col-sm-3 whitebg">
			
          <div class="chatsearch-part">
            <input type="text" placeholder="Search" onkeyup="get_friend_list(this.value)"/>
            <input type="button"/>
            <div id="main_search_html" class="search_panel"></div>
          </div>
          
          <div class="chat-listmem">
            <?php echo $this->element('chat_panel');?>
          </div>
        
          <div class="clearfix"></div>
        </div>
        <div class="col-sm-9">
          <div class="right-chatpart">
            <h3>To: <span>Sofia</span></h3>
            
           <div id="chat">
            <div class="upperchat-part" id="cstream">
            
             
             <?php if(!empty($ArChatHistory)){
						foreach($ArChatHistory as $ChatHistory){
				 ?>
              <div class="<?php if($user_id != $ChatHistory['Chat']['from_id']){?> chat-odd <?php }else{ ?> chat-even <?php } ?>">
                <div class="<?php if($user_id != $ChatHistory['Chat']['from_id']){?> chatper-img <?php }else{ ?> chatper-imgeven <?php } ?>">
                  
                  <?php if($user_id != $ChatHistory['Chat']['from_id']){
						if($ChatHistory['Sender']['image']!='')	
						{
					  ?>
                  <img src="<?php echo $this->webroot;?>user_images/thumb/<?php echo $ChatHistory['Sender']['image'];?>" alt="image">
				  <?php }else{ ?>
				<img src="<?php echo $this->webroot;?>images/noimage.jpg" alt="image">
				  <?php } ?>  
                  <?php }else if($user_id == $ChatHistory['Chat']['from_id']){
					  if($ChatHistory['Sender']['image']!='')	
						{
					  ?>
				  
				  <img src="<?php echo $this->webroot;?>user_images/thumb/<?php echo $ChatHistory['Sender']['image'];?>" alt="image">  
				   <?php }else{ ?>
				  <img src="<?php echo $this->webroot;?>images/noimage.jpg" alt="image">
				  <?php } ?>  
				  <?php } ?>
				  
                </div>
                <div class="<?php if($user_id != $ChatHistory['Chat']['from_id']){?> chattext-part <?php }else{ ?> chattext-partright <?php } ?>">
                  <p><?php echo $ChatHistory['Chat']['conversation'];?></p>
                </div>
              </div>
              <?php }
              } ?>
              
              
			
            </div>
            
           </div>
           
            <div class="textpost-part">
              <form method="post" id="msenger" action="">
					<textarea name="msg" id="msg-min"></textarea>
					<input type="hidden" name="mid" id="mid" value="<?php echo $user_id;?>">
					<input type="hidden" name="fid" id="fid" value="<?php echo $ArChatWith['Friendlist']['sender_id'];?>">
					<input type="hidden" name="to_name" id="to_name"  value="<?php echo $ArChatWith['Sender']['fname'].' '.$ArChatWith['Sender']['lname'];?>">
					<input type="hidden" name="from_name" id="from_name" value="<?php echo $ArChatWith['Receiver']['fname'].' '.$ArChatWith['Receiver']['lname'];?>">
					<input type="submit" value="Send" id="sb-mt" class="submit-btnarchitech">
				  </form>
			<div id="dataHelper" last-id=""></div>
            </div>
          </div>
        
        </div>
      
      </div>    
      
    </div>
  </div> 
  </main> 
  
 <script>

$(document).keyup(function(e){
 if(e.keyCode == 13){
 if($('#msenger textarea').val().trim() == ""){
 $('#msenger textarea').val('');
 }else{
 $('#msenger textarea').attr('readonly', 'readonly');
 $('#sb-mt').attr('disabled', 'disabled'); // Disable submit button
 sendMsg();
 } 
 }
}); 
 
$(document).ready(function() {
 $('#msg-min').focus();
 $('#msenger').submit(function(e){
 $('#msenger textarea').attr('readonly', 'readonly');
 $('#sb-mt').attr('disabled', 'disabled'); // Disable submit button
 sendMsg();
 e.preventDefault(); 
 });
});
 
 
// send msg to other 
function sendMsg(){
//alert(111);
var msg = $('#msenger').serialize();
//alert(msg);
 $.ajax({
 type: 'post',
 //url: 'chatM.php?rq=new',
 url: WEBROOT+'gossip/detail_chat_new',
 data: $('#msenger').serialize(),
 dataType: 'json',
 success: function(rsp){
 $('#msenger textarea').removeAttr('readonly');
 $('#sb-mt').removeAttr('disabled'); // Enable submit button
 if(parseInt(rsp.status) == 0){
 //alert(rsp.msg);
 }else if(parseInt(rsp.status) == 1){
 //alert('mmmmmm');
 $('#msenger textarea').val('');
 $('#msenger textarea').focus();
 //$design = '<div>'+rsp.msg+'<span class="time-'+rsp.lid+'"></span></div>';

 //alert(rsp.lid);

 if(rsp.profile_image!=null){
	var profile_image = 'user_images/'+rsp.profile_image;
 }else{
	var profile_image = 'images/noimage.jpg' ; 
 }

/*$design =  '<div class="chat-odd" id="focus_'+rsp.lid+'">'+
                '<div class="chatper-img">'+
                '<img src="'+WEBROOT+profile_image+'" alt="profileimg">'+
                '</div>'+
                '<div class="chattext-part">'+
                '<p>'+rsp.msg+'</p>'+
                '<h4 class="msg-time time-'+rsp.lid+'">'+rsp.time+'</h4>'+
                '</div>'+
                '</div>'+
                '<input type="hidden" id="last_id_'+rsp.lid+'" class="optinput"><br>';*/
 
 $design = '<div class="chat-even">'+
          '<div class="chatper-imgeven">'+
          '<img src="'+WEBROOT+profile_image+'" alt="profileimg">'+
          '</div>'+
          '<div class="chattext-partright">'+
          '<p>'+rsp.msg+'</p>'+
          '<h4 class="msg-time time-'+rsp.lid+'">'+rsp.time+'</h4>'+
          '</div>'+
          '</div>'+
          '<input type="hidden" id="last_id_'+rsp.lid+'" class="optinput"><br>';



//alert($design);
 $('#cstream').append($design);
 $('#last_id_'+rsp.lid).focus();
 $('#msg-min').focus();
 $('.time-'+rsp.lid).livestamp();
 $('#dataHelper').attr('last-id', rsp.lid);
 $('#chat').scrollTop($('#cstream').height());
 
 }
 }
 });
}


/*function handle404(xhr){
    alert('404 not found');
}

function handleError(xhr, status, exc) {
     // 0 for cross-domain requests in FF and security exception in IE 
    alert(xhr.status);
    switch (xhr.status) {
        case 404:
            handle404(xhr);
            break;
   }
}*/



function checkStatus(){
 $fid = jQuery('#fid').val();
 $mid = jQuery('#mid').val();
 /*$project_id = jQuery('#project_id').val();
 $type = jQuery('#type').val();
 $type_to = jQuery('#type_to').val();*/
 //alert($fid+','+$mid);
 $msg = 'msg';
 $last_id = $('#dataHelper').attr('last-id');
 //alert($last_id);
 //alert($fid);
 jQuery.ajax({
 type: 'post',
 //url: WEBROOT+'chatM.php?rq=msg',
 url: WEBROOT+'gossip/detail_chat_msg',
 //data: { fid: $fid, mid: $mid, project_id: $project_id, type: $type, type_to: $type_to, lid: $('#dataHelper').attr('last-id') },
 data: { fid: $fid, mid: $mid, lid: $('#dataHelper').attr('last-id') },
 dataType: 'json',
 cache: false,
 success: function(rsp){
 //alert(rsp.status);
 if(parseInt(rsp.status) == 0){
	 
 return false;
 }else if(parseInt(rsp.status) == 1){
//alert('success');
 getMsg();
 }

 }
 }); 
}
 
// Check for latest message
setInterval(function(){checkStatus();}, 200);
 
 
 
 
// get msgs sent by other 
function getMsg(){
 $fid = jQuery('#fid').val();
 $mid = jQuery('#mid').val();
 /*$project_id = jQuery('#project_id').val();
 $type = jQuery('#type').val();
 $type_to = jQuery('#type_to').val();*/
 
 $.ajax({
 type: 'post',
 //url: 'chatM.php?rq=NewMsg',
 url: WEBROOT+'gossip/detail_chat_newmsg',
 //data:  {fid: $fid, mid: $mid, project_id: $project_id, type: $type, type_to: $type_to},
 data:  {fid: $fid, mid: $mid},
 dataType: 'json',
 success: function(rsp){
 if(parseInt(rsp.status) == 0){
 //alert(rsp.msg);

 }else if(parseInt(rsp.status) == 1){
 //alert('html msg');
 if(rsp.profile_image!=null){
	var profile_image = 'user_images/'+rsp.profile_image;
 }else{
	var profile_image = 'images/noimage.jpg' ; 
 }
/*$design = '<div class="chat-even">'+
          '<div class="chatper-imgeven">'+
          '<img src="'+WEBROOT+profile_image+'" alt="profileimg">'+
          '</div>'+
          '<div class="chattext-partright">'+
          '<p>'+rsp.msg+'</p>'+
          '<h4 class="msg-time time-'+rsp.lid+'">'+rsp.time+'</h4>'+
          '</div>'+
          '</div>'+
          '<input type="hidden" id="last_id_'+rsp.lid+'" class="optinput"><br>';*/
 
 $design =  '<div class="chat-odd" id="focus_'+rsp.lid+'">'+
                '<div class="chatper-img">'+
                '<img src="'+WEBROOT+profile_image+'" alt="profileimg">'+
                '</div>'+
                '<div class="chattext-part">'+
                '<p>'+rsp.msg+'</p>'+
                '<h4 class="msg-time time-'+rsp.lid+'">'+rsp.time+'</h4>'+
                '</div>'+
                '</div>'+
                '<input type="hidden" id="last_id_'+rsp.lid+'" class="optinput"><br>';
 
 //alert($design);
 
 $('#cstream').append($design);
 $('#last_id_'+rsp.lid).focus();
 $('#msg-min').focus();
 $('#chat').scrollTop ($('#cstream').height());
 $('.time-'+rsp.lid).livestamp();
 $('#dataHelper').attr('last-id', rsp.lid); 
 }
 }
 });
}

</script>

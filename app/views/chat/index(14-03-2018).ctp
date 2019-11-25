<script>var WEBROOT = '<?php echo $this->webroot;?>';</script>
<script>
function get_friend_list(search_name){
	if(search_name!="" && search_name.length>1){
		$("#main_search_html").show("");
		$("#main_search_html").html("<img style='margin-left:30%;' src='<?php echo $this->webroot;?>images/loading.gif'>");
		//return false;
		$.ajax({
		  type: "POST",
		  url: "<?php echo $this->webroot?>chat/get_friend_list/",
		  data: { search_name: search_name},
		  success: function(msg)
		  {		
        $("#main_search_html").hide();
        $("#friend_chat_list").html(msg);
				//$("#main_search_html").html(msg);
				$('#cstream').animate({ scrollTop: $('#cstream')[0].scrollHeight}, 2000);
								 				
		  }
		});
	}else if(search_name == ''){
    $.ajax({
      type: "POST",
      url: "<?php echo $this->webroot?>chat/all_friends/",
      data: { search_name: search_name},
      success: function(msg)
      {   
        $("#main_search_html").hide();
        $("#friend_chat_list").html(msg);
        //$("#main_search_html").html(msg);
        $('#cstream').animate({ scrollTop: $('#cstream')[0].scrollHeight}, 2000);
                        
      }
    });
  }else{
		$("#main_search_html").html("");	
	}
}


</script>

<main class="main-body">  
<div class="category-details-content chat-content" id="chat_page">
    <div class="container chat-container">
      <div class="heading-title chat-heading">
        <h2>Chat</h2>
        <div class="clearfix"></div>
      </div>  
     
      <div class="fullchat-section">
        <div class="chat-left">
		  <div class="whitebg">
          <div class="chatsearch-part">
            <input type="text" placeholder="Search" onkeyup="get_friend_list(this.value)" id="search"/>
			<i class="fa fa-search"></i>
            <div id="main_search_html" class="search_panel"></div>
			<div class="clearfix"></div>
          </div>
          <div class="chat-listmem">
            <?php echo $this->element('chat_panel');?>
          </div>
        
          <div class="clearfix"></div>
		  </div>
        </div>
        <div class="col-sm-9 pull-right chatright">
          <div class="right-chatpart" id="chat_replace">
            <h3>To: <span><?php echo ucwords($ArSenderDetail['User']['fname'].' '.$ArSenderDetail['User']['lname']);?></span></h3>
            
           <div id="chat">
            <div class="upperchat-part" id="cstream">
            
            <?php //pr($ArChatHistory);?>
             
             <?php 
             $last_chat_count = 0;
             if(!empty($ArChatHistory)){
						foreach($ArChatHistory as $ChatHistory){
				 ?>
              <div id="msg-focus-<?php echo $ChatHistory['Chat']['id'];?>" class="<?php if($user_id != $ChatHistory['Chat']['from_id']){?> chat-odd <?php }else{ ?> chat-even <?php } ?>">
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
                  <h4 class="msg-time time-"><?php echo date('Y-m-d h:i A',strtotime($ChatHistory['Chat']['created']));?></h4>
                </div>
              </div>
              <input type="hidden" id="last_id_<?php echo $ChatHistory['Chat']['id'];?>" class="optinput"><br>
              <?php 
					$last_chat_count++;
				}
              } ?>
              
            <?php 
            if($last_chat_count > 0)
			{
			$last_chat_count = $last_chat_count-1;
			}
            ?>
            
			</div>
            
           </div>
           <div class="clearfix"></div>
            <div class="textpost-part">
              <form method="post" id="msenger" action="">
				 	<textarea name="msg" id="msg-min"></textarea>
					<input type="hidden" name="mid" id="mid" value="<?php echo $user_id;?>">
					<input type="hidden" name="fid" id="fid" value="<?php echo $ArSenderDetail['User']['id'];?>">
					<input type="hidden" name="to_name" id="to_name"  value="<?php echo ($ArSenderDetail['User']['fname'].' '.$ArSenderDetail['User']['lname']);?>">
					<input type="hidden" name="from_name" id="from_name" value="<?php echo ($ArUserDetail['User']['fname'].' '.$ArUserDetail['User']['lname']);?>">
					<input type="submit" value="Send" id="sb-mt" class="submit-btnarchitech">
				</form>
			<div id="dataHelper" last-id="<?php echo $ArChatHistory[$last_chat_count]['Chat']['id'];?>"></div>
			<div class="clearfix"></div>
            </div>
          
          </div>
       
        </div>
      
      </div>    
      
    </div>
  </div> 
  </main> 
 
  
  
<div id="all_script">
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
 
//$(document).ready(function() {

//alert(1);
$('#cstream').animate({ scrollTop: $('#cstream')[0].scrollHeight}, 2000);

 //$('#msg-min').focus();
 //$('#msg-focus-14').focus();
 //$('#test').focus();
 $('#msenger').submit(function(e){
 //alert(1);
 $('#msenger textarea').attr('readonly', 'readonly');
 $('#sb-mt').attr('disabled', 'disabled'); // Disable submit button
 sendMsg();
 e.preventDefault(); 
 });
//});
 
 
// send msg to other 
function sendMsg(){

var msg = $('#msenger').serialize();
 $.ajax({
 /*type: 'post',
 url: WEBROOT+'gossip/detail_chat_new',*/
 type: 'get',
 url: WEBROOT+'chat.php?rq=new',
 data: $('#msenger').serialize(),
 dataType: 'json',
 success: function(rsp){
 $('#msenger textarea').removeAttr('readonly');
 $('#sb-mt').removeAttr('disabled'); // Enable submit button
 
 //alert(rsp.status);
 if(parseInt(rsp.status) == 0){
 //alert(rsp.msg);
 }else if(parseInt(rsp.status) == 1){
 //alert('mmmmmm');
 $('#msenger textarea').val('');
 $('#msenger textarea').focus();
 
 if(rsp.profile_image!=null){
	var profile_image = 'user_images/'+rsp.profile_image;
 }else{
	var profile_image = 'images/noimage.jpg' ; 
 }

$design = '<div id="msg-focus-'+rsp.lid+'" class="chat-even">'+
          '<div class="chatper-imgeven">'+
          '<img src="'+WEBROOT+profile_image+'" alt="profileimg">'+
          '</div>'+
          '<div class="chattext-partright">'+
          '<p>'+rsp.msg+'</p>'+
          '<h4 class="msg-time time-'+rsp.lid+'">'+rsp.time+'</h4>'+
          '</div>'+
          '</div>'+
          '<input type="hidden" id="last_id_'+rsp.lid+'" class="optinput"><br>';


 $('#cstream').append($design);
 $('#last_id_'+rsp.lid).focus();
 $('#msg-focus-'+rsp.lid).focus();
 //$('#msg-min').focus();
 $('#cstream').animate({ scrollTop: $('#cstream')[0].scrollHeight}, 2000);
 $('.time-'+rsp.lid).livestamp();
 $('#dataHelper').attr('last-id', rsp.lid);
 $('#chat').scrollTop($('#cstream').height());
 
 }
 }
 });
}


function checkStatus(){
 $fid = jQuery('#fid').val();
 $mid = jQuery('#mid').val();
 
 $msg = 'msg';
 $last_id = $('#dataHelper').attr('last-id');
 //alert($last_id);
 //alert($fid);
 jQuery.ajax({
 type: 'get',
 //url: WEBROOT+'gossip/detail_chat_msg',
 url: WEBROOT+'chat.php?rq=msg',
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
setInterval(function(){checkStatus();}, 1000);
 
 
 
 
// get msgs sent by other 
function getMsg(){
 $fid = jQuery('#fid').val();
 $mid = jQuery('#mid').val();

 $.ajax({
 type: 'get',
 //url: WEBROOT+'gossip/detail_chat_newmsg',
 url: WEBROOT+'chat.php?rq=NewMsg',
 data:  {fid: $fid, mid: $mid},
 dataType: 'json',
 success: function(rsp){
	// alert(rsp.msg);
 if(parseInt(rsp.status) == 0){
 //alert(rsp.msg);

 }else if(parseInt(rsp.status) == 1){
 //alert('html msg');
 if(rsp.profile_image!=null){
	var profile_image = 'user_images/'+rsp.profile_image;
 }else{
	var profile_image = 'images/noimage.jpg' ; 
 }
 
 $design =  '<div class="chat-odd" id="msg-focus-'+rsp.lid+'">'+
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
 $('#msg-focus-'+rsp.lid).focus();
 //$('#msg-min').focus();
 $('#cstream').animate({ scrollTop: $('#cstream')[0].scrollHeight}, 2000);
 $('#chat').scrollTop ($('#cstream').height());
 $('.time-'+rsp.lid).livestamp();
 $('#dataHelper').attr('last-id', rsp.lid); 
 }
 }
 });
}


//alert($( 'body' ).height());


</script>


</div>

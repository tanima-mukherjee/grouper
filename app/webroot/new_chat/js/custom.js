var WEBROOT = '<?php echo $this->webroot;?>';

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
 
function sendMsg(){
//alert(111);
 $.ajax({
 type: 'post',
 //url: 'chatM.php?rq=new',
 url: WEBROOT+'chat/detail_chat_new',
 data: $('#msenger').serialize(),
 dataType: 'json',
 success: function(rsp){
 $('#msenger textarea').removeAttr('readonly');
 $('#sb-mt').removeAttr('disabled'); // Enable submit button
 if(parseInt(rsp.status) == 0){
 alert(rsp.msg);
 }else if(parseInt(rsp.status) == 1){
//alert('mmmmmm');
 $('#msenger textarea').val('');
 $('#msenger textarea').focus();
 //$design = '<div>'+rsp.msg+'<span class="time-'+rsp.lid+'"></span></div>';
 $design = '<div class="float-fix">'+
 '<div class="m-rply">'+
 '<div class="msg-bg">'+
 '<div class="msgA">'+
 rsp.msg+
 '<div class="">'+
 '<div class="msg-time time-'+rsp.lid+'"></div>'+
 '<div class="myrply-i"></div>'+
 '</div>'+
 '</div>'+
 '</div>'+
 '</div>'+
 '</div>';
 $('#cstream').append($design);
 
 $('.time-'+rsp.lid).livestamp();
 $('#dataHelper').attr('last-id', rsp.lid);
 $('#chat').scrollTop($('#cstream').height());
 }
 }
 });
}
function checkStatus(){
 $fid = '<?php echo $fid; ?>';
 $mid = '<?php echo $myid; ?>';
 $msg = 'msg';
 $last_id = $('#dataHelper').attr('last-id');
 
 alert($fid);
 $.ajax({
 type: 'post',
 //url: WEBROOT+'chatM.php?rq=msg',
 url: WEBROOT+'chat/detail_chat_msg',
 data: { fid: $fid, mid: $mid, lid: $('#dataHelper').attr('last-id')},
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
 
function getMsg(){
//	alert('get msg');
 $fid = '<?php echo $fid; ?>';
 $mid = '<?php echo $myid; ?>';
 //alert($fid);
 $.ajax({
 type: 'post',
 //url: 'chatM.php?rq=NewMsg',
 url: WEBROOT+'chat/detail_chat_newmsg',
 data:  {fid: $fid, mid: $mid},
 dataType: 'json',
 success: function(rsp){
 if(parseInt(rsp.status) == 0){
 //alert(rsp.msg);
 }else if(parseInt(rsp.status) == 1){
 $design = '<div class="float-fix">'+
 '<div class="f-rply">'+
 '<div class="msg-bg">'+
 '<div class="msgA">'+
 rsp.msg+
 '<div class="">'+
 '<div class="msg-time time-'+rsp.lid+'"></div>'+
 '<div class="myrply-f"></div>'+
 '</div>'+
 '</div>'+
 '</div>'+
 '</div>'+
 '</div>';
 $('#cstream').append($design);
 $('#chat').scrollTop ($('#cstream').height());
 $('.time-'+rsp.lid).livestamp();
 $('#dataHelper').attr('last-id', rsp.lid); 
 }
 }
 });
}

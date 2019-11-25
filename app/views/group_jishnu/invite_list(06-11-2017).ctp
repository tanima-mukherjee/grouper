<!DOCTYPE html>
<html lang="en">
  <head>
	 <meta charset="utf-8">
	 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grouper</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="<?php echo $this->webroot?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $this->webroot?>css/font-awesome.min.css" rel="stylesheet"> 
	
   <!-- custom css -->  
   <link href="<?php echo $this->webroot?>css/main.css" rel="stylesheet"> 
  
	<style>
		body{
			background-color:transparent;
		}
	</style>
</head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
var WEBROOT = '<?php echo $this->webroot;?>';
function lookup_friends(inputString, group_id){
	var str_selected_users=$('#str_selected_users').val();
	if(inputString.length < 3) {
		// Hide the suggestion box.
		$('#suggestions').hide();
	} else {
		$.ajax({
           type: "GET",
           url: WEBROOT+"group/get_suggestion_list_friends",
           data: {search_text:inputString, group_id:group_id, str_selected_users:str_selected_users},
           success: function(msg){
           if(msg!=0){ 
		   		$('#suggestions').show();
				$('#autoSuggestionsList').html(msg);	  
            }
			else{
				$('#autoSuggestionsList').html('<ul><li>No data found</li></ul>');	
		   	}
           }
      });
	}
}

var friends = new String();
function fill_friends(fname, lname, userid){
	$("#selected_friends ul").append('<li id="friend_'+userid+'"><span>'+fname+' '+lname+'</span><a href="javascript:void(0)" onClick="remove_friend('+userid+')"><i class="fa fa-remove"></i></a></li>');
	$('#suggestions').hide();
	
	var selected_users= jQuery('#str_selected_users').val();
	if(selected_users == ''){
		selected_users = userid;
	}
	else{
		selected_users = selected_users+','+userid;
	}
	jQuery('#str_selected_users').val(selected_users);
}

function remove_friend(userid){
	$('#friend_'+userid).remove();
	
	var current_users= jQuery('#str_selected_users').val();
	current_users='@'+current_users+'@';
	newval1 = ','+userid+',';
	newval2 = '@'+userid+',';
	newval3 = ','+userid+'@';
	newval4 = '@'+userid+'@';

	current_users = current_users.replace(newval2, ',');
	current_users = current_users.replace(newval1, ',');
	current_users = current_users.replace(newval3, ',');
	current_users = current_users.replace(newval4, '');
	//alert(current_users);
	current_users = current_users.replace(",,", ',');
	current_users = current_users.replace("@", '');
	current_users = current_users.replace("@", '');
	
	var len = current_users.length;
		
	if(current_users.charAt(0)==','){
		current_users = current_users.substr(1);
		jQuery('#str_selected_users').val(current_users);
	}
	else if(current_users.charAt(len-1)==','){
		current_users = current_users.substr(0,len-1);
		jQuery('#str_selected_users').val(current_users);
	}
	else{
		jQuery('#str_selected_users').val(current_users);
	}
}

function submit_friend_invitation(){
	var group_id= $('#group_id').val();
	var str_users= $('#str_selected_users').val();
	var mode= $('#mode').val();
	var sender_type= $('#sender_type').val();
	var group_type= $('#group_type').val();
	
	if(str_users!=''){
		$.ajax({
			   type: "GET",
			   url: WEBROOT+"group/submit_invitation",
			   data: {mode:mode, group_id:group_id, str_users:str_users, sender_type:sender_type, group_type:group_type},
			   success: function(msg){
			   if(msg!=0){ 
			   		$('#str_selected_users').val(''); 
					$("#selected_friends ul > li").remove();
					$('#success_msg').show();
					$('#success_msg').html('Invitation sent successfully !!!');	 
					$("#success_msg").delay(12000).fadeToggle(); 
				}
				else{
					$('#success_msg').html('');	
					$('#success_msg').hide();
				}
			   }
		  });
	}
	else{
		$('#success_msg').show();
		$('#success_msg').html('Please, choose your friend !!!');	 
		$("#success_msg").delay(12000).fadeToggle(); 
	}
}


function lookup_users(inputString, group_id){
	var str_selected_users=$('#str_selected_users2').val();
	if(inputString.length < 3) {
		// Hide the suggestion box.
		$('#suggestions_user').hide();
	} else {
		$.ajax({
           type: "GET",
           url: WEBROOT+"group/get_suggestion_list_users",
           data: {search_text:inputString, group_id:group_id, str_selected_users:str_selected_users},
           success: function(msg){
           if(msg!=0){ 
		   		$('#suggestions_user').show();
				$('#autoSuggestionsList_user').html(msg);	  
            }
			else{
				$('#autoSuggestionsList_user').html('<ul><li>No data found</li></ul>');	
		   	}
           }
      });
	}
}

var users = new String();
function fill_users(fname, lname, userid){
	$("#selected_users ul").append('<li id="user_'+userid+'"><span>'+fname+' '+lname+'</span><a href="javascript:void(0)" onClick="remove_user('+userid+')"><i class="fa fa-remove"></i></a></li>');
	$('#suggestions_user').hide();
	
	var selected_users= jQuery('#str_selected_users2').val();
	if(selected_users == ''){
		selected_users = userid;
	}
	else{
		selected_users = selected_users+','+userid;
	}
	jQuery('#str_selected_users2').val(selected_users);
}

function remove_user(userid){
	$('#user_'+userid).remove();
	
	var current_users= jQuery('#str_selected_users2').val();
	current_users='@'+current_users+'@';
	newval1 = ','+userid+',';
	newval2 = '@'+userid+',';
	newval3 = ','+userid+'@';
	newval4 = '@'+userid+'@';

	current_users = current_users.replace(newval2, ',');
	current_users = current_users.replace(newval1, ',');
	current_users = current_users.replace(newval3, ',');
	current_users = current_users.replace(newval4, '');
	//alert(current_users);
	current_users = current_users.replace(",,", ',');
	current_users = current_users.replace("@", '');
	current_users = current_users.replace("@", '');
	
	var len = current_users.length;
		
	if(current_users.charAt(0)==','){
		current_users = current_users.substr(1);
		jQuery('#str_selected_users2').val(current_users);
	}
	else if(current_users.charAt(len-1)==','){
		current_users = current_users.substr(0,len-1);
		jQuery('#str_selected_users2').val(current_users);
	}
	else{
		jQuery('#str_selected_users2').val(current_users);
	}
}

function submit_user_invitation(){
	var group_id= $('#group_id').val();
	var str_users= $('#str_selected_users2').val();
	var mode= $('#mode2').val();
	var sender_type= $('#sender_type').val();
	var group_type= $('#group_type').val();
	
	if(str_users!=''){
		$.ajax({
			   type: "GET",
			   url: WEBROOT+"group/submit_invitation",
			   data: {mode:mode, group_id:group_id, str_users:str_users, sender_type:sender_type, group_type:group_type},
			   success: function(msg){
			   if(msg!=0){
			   		$('#str_selected_users2').val(''); 
					$("#selected_users ul > li").remove();
					$('#success_msg').show();
					$('#success_msg').html('Invitation sent successfully !!!');	  
					$("#success_msg").delay(12000).fadeToggle(); 
				}
				else{
					$('#success_msg').html('');	
					$('#success_msg').hide();
				}
			   }
		  });
	}
	else{
		$('#success_msg').show();
		$('#success_msg').html('Please, choose site user !!!');	 
		$("#success_msg").delay(12000).fadeToggle(); 
	}
}



function lookup_emails(inputString, group_id){
	var str_selected_users=$('#str_selected_users3').val();
	var arr_selected_users = str_selected_users.split(',');
	//alert(arr_selected_users.indexOf(inputString));
	
	if(arr_selected_users.indexOf(inputString)== -1){
		 var r = new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?");
		if(inputString.match(r) == null){
			// Hide the suggestion box.
			$('#suggestions_email').hide();
		} 
		else{
			$('#suggestions_email').show();
			$('#autoSuggestionsList_email').html('<ul><li onclick="fill_emails(\''+inputString+'\')">'+inputString+'</li></ul>');
		}
	}
	else{
			$('#suggestions_email').hide();
	}
}

var emails = new String();
function fill_emails(email){
	var count_li= $('#selected_emails ul li').length;
	var new_count_li= count_li+1;
	
	$("#selected_emails ul").append('<li id="'+new_count_li+'"><span>'+email+'</span><a href="javascript:void(0)" onClick="remove_email(\''+new_count_li+'\', \''+email+'\')"><i class="fa fa-remove"></i></a></li>');
	$('#suggestions_email').hide();
	
	var selected_users= jQuery('#str_selected_users3').val();
	if(selected_users == ''){
		selected_users = email;
	}
	else{
		selected_users = selected_users+','+email;
	}
	jQuery('#str_selected_users3').val(selected_users);
}


function remove_email(count, email){

	$('#'+count).remove();
	
	var current_users= jQuery('#str_selected_users3').val();
	current_users='*'+current_users+'*';
	newval1 = ','+email+',';
	newval2 = '*'+email+',';
	newval3 = ','+email+'*';
	newval4 = '*'+email+'*';

	current_users = current_users.replace(newval2, ',');
	current_users = current_users.replace(newval1, ',');
	current_users = current_users.replace(newval3, ',');
	current_users = current_users.replace(newval4, '');
	//alert(current_users);
	current_users = current_users.replace(",,", ',');
	current_users = current_users.replace("*", '');
	current_users = current_users.replace("*", '');
	
	var len = current_users.length;
		
	if(current_users.charAt(0)==','){
		current_users = current_users.substr(1);
		jQuery('#str_selected_users3').val(current_users);
	}
	else if(current_users.charAt(len-1)==','){
		current_users = current_users.substr(0,len-1);
		jQuery('#str_selected_users3').val(current_users);
	}
	else{
		jQuery('#str_selected_users3').val(current_users);
	}
}

function submit_email_invitation(){
	var group_id= $('#group_id').val();
	var str_users= $('#str_selected_users3').val();
	var mode= $('#mode3').val();
	var sender_type= $('#sender_type').val();
	var group_type= $('#group_type').val();
	
	if(str_users!=''){
		$.ajax({
			   type: "GET",
			   url: WEBROOT+"group/submit_invitation",
			   data: {mode:mode, group_id:group_id, str_users:str_users, sender_type:sender_type, group_type:group_type},
			   success: function(msg){
			   if(msg==1){
			   		$('#str_selected_users3').val(''); 
					$("#selected_emails ul > li").remove();
					$('#success_msg').show();
					$('#success_msg').html('Invitation sent successfully !!!');	  
					$("#success_msg").delay(12000).fadeToggle(); 
				}
				else{
					$('#str_selected_users3').val(''); 
					$("#selected_emails ul > li").remove();
					$('#success_msg').show();
					$('#success_msg').html(msg);	  
					$("#success_msg").delay(12000).fadeToggle(); 
				}
			   }
		  });
	}
	else{
		$('#success_msg').show();
		$('#success_msg').html('Please, put atleast one email !!!');	 
		$("#success_msg").delay(12000).fadeToggle(); 
	}
}

function show_search_box(search_type){
	$("[id^='section']").hide();		
	$('#section'+search_type).show();
}
</script>

<style>

</style>
<body>
	<div class="user-dialog">
     <div class="modal-header">        
        <h4 class="modal-title">Search User</h4>
      </div>
	 <div id="success_msg" style="display:none; background:#ccc; padding:10px 12px; font-weight:bold; font-size:16px">Sourov</div>
	 
	  <div class="group-join">
	  <span class="group-type">			
			<input type="radio" name="search_type" value="1" id="search_type" checked="checked" onClick="show_search_box(this.value);">
			<label for="friend">Friend</label>
		</span>
		<span class="group-type">			
			<input type="radio" name="search_type" value="2" id="search_type" onClick="show_search_box(this.value);">
			<label for="public">Site User</label>
		</span>
		<span class="group-type">			
			<input type="radio" name="search_type" value="3" id="search_type" onClick="show_search_box(this.value);">
			<label for="private">Non Site User</label>
		</span>
	</div>
	
	
      <div class="col-sm-12 fields-margin" id="section1">
		<label class="profilelabel">Search Friend *</label>
		<form name="submit_friends" id="submit_friends" method="post" action="">
		<input type="hidden" name="mode" id="mode" value="invite_friends">
		<input type="hidden" name="str_selected_users" id="str_selected_users" value="">
		<input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id; ?>">
		<input type="hidden" name="sender_type" id="sender_type" value="GO">
		<input type="hidden" name="group_type" id="group_type" value="<?php echo $group_detail['Group']['group_type']; ?>">
		
		<div class="profilefield">
		  <input type="text" id="friend"  class="profileform-control" name="friend" onKeyUp="lookup_friends(this.value, <?php echo $group_id; ?>);" onBlur="fill();" autocomplete="off" />
		  
		  <div class="suggestionsBox" id="suggestions" style="display:none;" >
	 
			<div class="suggestionList" id="autoSuggestionsList">
				<a>&nbsp;</a>
			</div>
		</div>	
	</div>
	
		<div class="user-friendlist" id="selected_friends">
			<ul>
				
			</ul>
		</div>
		<div class="clearfix"></div>
		<div class="text-center"><input type="button" value="submit" class="popup_submit" onClick="submit_friend_invitation();"></div>
	    <div class="clearfix"></div>
		</form>
	  </div>
	  
	  <div class="col-sm-12 fields-margin" id="section2" style="display:none">
		<label class="profilelabel">Search User *</label>
		<div id="success_msg2" style="display:none"></div>
		<form name="submit_users" id="submit_users" method="post" action="">
		<input type="hidden" name="mode2" id="mode2" value="invite_users">
		<input type="hidden" name="str_selected_users2" id="str_selected_users2" value="">
		<input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id; ?>">
		<input type="hidden" name="sender_type" id="sender_type" value="GO">
		<input type="hidden" name="group_type" id="group_type" value="<?php echo $group_detail['Group']['group_type']; ?>">
		
		<div class="profilefield">
		  <input type="text" id="user"  class="profileform-control" name="user" onKeyUp="lookup_users(this.value, <?php echo $group_id; ?>);" autocomplete="off" />
		  
		  <div class="suggestionsBox" id="suggestions_user" style="display:none;" >
	 
			<div class="suggestionList" id="autoSuggestionsList_user">
				<a>&nbsp;</a>
			</div>
		</div>		 
	</div>
	
		<div class="user-friendlist" id="selected_users">
			<ul>
				
			</ul>
		</div>
		<div class="clearfix"></div>
		<div class="text-center"><input type="button" value="submit" class="popup_submit" onClick="submit_user_invitation();"></div>
	   <div class="clearfix"></div>
	   </form>
	  </div>
	  
	  <div class="col-sm-12 fields-margin" id="section3" style="display:none">
		<label class="profilelabel">Invite Via Email *</label>
	
		<form name="submit_emails" id="submit_emails" method="post" action="">
		<input type="hidden" name="mode3" id="mode3" value="invite_emails">
		<input type="hidden" name="str_selected_users3" id="str_selected_users3" value="">
		<input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id; ?>">
		<input type="hidden" name="sender_type" id="sender_type" value="GO">
		<input type="hidden" name="group_type" id="group_type" value="<?php echo $group_detail['Group']['group_type']; ?>">
		<div class="profilefield">
			  <input type="text" id="email"  class="profileform-control" name="user" onKeyUp="lookup_emails(this.value, <?php echo $group_id; ?>);" autocomplete="off" />
			  
			  <div class="suggestionsBox" id="suggestions_email" style="display:none;" >
		 
				<div class="suggestionList" id="autoSuggestionsList_email">
					<a>&nbsp;</a>
				</div>
			</div>		 
		</div>
		
		<div class="user-friendlist" id="selected_emails">
			<ul>
				
			</ul>
		</div>
		
		<div class="clearfix"></div>
		<div class="text-center"><input type="button" value="submit" class="popup_submit" onClick="submit_email_invitation();"></div>
	   <div class="clearfix"></div>
	   </form>
	  </div>

      
	</div>  
</body>
</html>


							
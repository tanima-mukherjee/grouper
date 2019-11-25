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
function lookup(inputString, group_id){
//alert(group_id);
	if(inputString.length <= 3) {
		// Hide the suggestion box.
		$('#suggestions').hide();
	} else {
		$.ajax({
           type: "GET",
           url: WEBROOT+"group/get_suggestion_list_friends",
           data: {search_text:inputString, group_id:group_id},
           success: function(msg){
           alert(msg);
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
</script>

<style>
.suggestionsBox {
		/*position: relative;*/
		left:10px;
		margin:0px 0px 0px 0px;
		width: 95%;
		background-color:#fff;
		border:#cccccc solid 1px;
		color: #999999;
		position:absolute;
		z-index:999;
		padding:5px;
	}
	
	.suggestionList {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionList li {
		
		margin: 0px 0px 3px 0px;
		padding: 3px;
		cursor: pointer;
		list-style:none;
	}
	
	.suggestionList li:hover {
		background-color: #659CD8;
		color:#F3F3F3;
	}
</style>
<body>
	<div class="user-dialog">
     <div class="modal-header">        
        <h4 class="modal-title">Search User</h4>
      </div>

      <div class="col-sm-12 fields-margin">
                    <label class="profilelabel">Search Friend *</label>
                                <div class="profilefield">
                                  <input type="text" id="friend"  class="profileform-control" name="friend_id" onKeyUp="lookup(this.value, <?php echo $group_id; ?>);" onBlur="fill();" autocomplete="off" />
								  
								  <div class="suggestionsBox" id="suggestions" style="display:none;" >
							 
									<div class="suggestionList" id="autoSuggestionsList">
										<a>&nbsp;</a>
									</div>
								</div>
                                 
                                   </div>
                                  <div class="clearfix"></div>
                                </div>

      
	</div>  
</body>
</html>


							
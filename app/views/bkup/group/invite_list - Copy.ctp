
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
   <!-- Autocomplete Start -->
<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="<?php echo $this->webroot?>css/token-input-facebook.css" type="text/css" />

<style>
div.token-input-dropdown-facebook{
  width:46%;
}
</style>
<script language="javascript" src="<?php echo $this->webroot?>js/jquery-ui.js"></script>


 <!-- Autocomplete End -->
	<style>
		body{
			background-color:transparent;
		}
	</style>
  </head>
<body>
	<div class="user-dialog">
     <div class="modal-header">        
        <h4 class="modal-title">Invite User List</h4>
      </div>

      <div class="col-sm-12 fields-margin">
                    <label class="profilelabel">Search Friend *</label>
                                <div class="profilefield">
                                  <input type="text" id="friend"  class="profileform-control" name="friend_id"/>
                                  <script>
                                   $(document).ready(function() {
                                   $("#friend").tokenInput("<?php echo $this->webroot.'group/invite_user_list'?>",
                                    {
                                      theme: "facebook",
                                      tokenLimit: 10,
                                      preventDuplicates: true
                                      
                                    });
                                  });
                                   </script>
                                   </div>
                                  <div class="clearfix"></div>
                                </div>

      
	</div>  
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

</body>
</html>


							
<div class="headerpanel">
  <a href="#" class="showmenu"></a>
  <div class="headerright">
    <!--dropdown-->
    
    <!-- <div class="dropdown userinfo">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Join Ogma HRM Community</a>
    </div>-->
    <div class="dropdown userinfo">
     
      <!--<ul class="dropdown-menu">
        <li class="nav-header">Notifications</li>                        
          <li><a href="#"><span class="icon-envelope"></span> New message from <strong>Jack</strong> <small class="muted"> - 19 hours ago</small></a></li>
          <li><a href="#"><span class="icon-envelope"></span> New message from <strong>Daniel</strong> <small class="muted"> - 2 days ago</small></a></li>
          <li><a href="#"><span class="icon-user"></span> <strong>Bruce</strong> is now following you <small class="muted"> - 2 days ago</small></a></li>
          <li class="viewmore"><a href="notifications.html">View More Notifications</a></li>
      </ul>-->
    </div>
    <div class="dropdown userinfo">
      <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
        <span >Hi, <?php echo $this->Session->read('adminData.Admin.username'); ?>!</span><b class="caret"></b>
      </a>
      <ul class="dropdown-menu">
        <!--<li><a href="#"><span class="icon-edit"></span>Edit Profile</a></li>-->
        <!--<li><a href="localisationdash.html"><span class="icon-wrench"></span>Localization</a></li>-->
        <!-- <li><a href="<?php echo $this->webroot;?>admins/change_password"><span class="icon-eye-open"></span>Change Password</a></li> -->
        <!-- <li class="divider"></li> -->
        <li><a href="<?php echo $this->webroot; ?>admins/logout"><span class="icon-off"></span>Sign Out</a></li>
      </ul>
    </div><!--dropdown-->
   <!--  <div class="dropdown userinfo" style="background-color:none;box-shadow:none;">
      <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#" style="box-shadow: none;background: none;">
        <span class="iconsweetss-logo1  iconsweets-white" style="height: 20px; width: 20px;"></span><b class="caret"></b>
      </a>
      <ul class="dropdown-menu">
        <li>
          <a href="#">
            <p>Last login from:</p>
            <p>204.194.143.30</p>
            <p>12/09/2014 - 09:24:39</p>
          </a>
        </li>
      </ul>
    </div> -->
  </div><!--headerright-->
</div><!--headerpanel-->

<div class="breadcrumbwidget">
  <!--skins-->
<!--   <ul class="skins">
    <li><a href="#"><img src="<?php echo $this->webroot; ?>admin/img/square-facebook-16.png"></a></li>
    <li><a href="#"><img src="<?php echo $this->webroot; ?>admin/img/square-twitter-16.png"></a></li>
    <li><a href="#"><img src="<?php echo $this->webroot; ?>admin/img/youtube_old.png"></a></li>
  </ul> -->
  <ul class="breadcrumb">
    <li><a href="<?php echo $this->webroot; ?>dashboard">Home</a> <span class="divider">/</span></li>
    <li class="active"><?php echo $pageTitle; ?></li>
  </ul>
</div><!--breadcrumbs-->
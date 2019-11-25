<!--START OF LEFT PANEL -->
<div class="leftpanel">

  <div class="logopanel" style="padding-bottom:7px;">
    <h1><a href="<?php echo $this->webroot; ?>dashboard"><img src="<?php echo $this->webroot; ?>admin/img/logo.png"></a></h1>
  </div><!--logopanel-->

  <div class="datewidget">Today is <?php echo date('l, M j, Y', time()); ?> </div>

  <!-- <div class="searchwidget">
    <form action="#" method="post">
      <div class="input-append">
        <input type="text" class="span2 search-query" placeholder="Search here...">
        <button type="submit" class="btn"><span class="icon-search"></span></button>
      </div>
    </form>
  </div> --><!--searchwidget-->



  <div class="leftmenu">        
    <ul class="nav nav-tabs nav-stacked">
      <li class="nav-header" style="text-shadow: none;color: white;background: linear-gradient(to bottom, #8A8A8A 0%,#8A8A8A 100%);border-top: 1px solid #bbb;">Menu</li>
      
      <li><a href="<?php echo $this->webroot; ?>dashboard"><span class="iconsweets-home"></span>Admin Dashboard</a></li>
      <?php /*
        <li><a href="#"><span class="iconsweets-home"></span>Manager Dashboard</a></li>
        <li><a href="#"><span class="iconsweets-home"></span>Employee (Non Manager) Dashboard</a></li>
       */ ?>


      <script>
       jQuery( document ).ready(function() {
        jQuery('#admin_dropdown').show();
       });
      </script>  
      <!--Ogma admin--> <!-- <li class="dropdown"><a href="#"><span class="iconsweets-admin"></span>Grouppers Admin</a> -->
      <!--   <ul id="admin_dropdown"> -->
        
          <li ><a href="<?php echo $this->webroot; ?>admin_user/user_list" ><span class="iconsweets-users"></span>User Management</a></li>                     
          
          
          <?php if($this->Session->read('admin_type') == 'SA'){?>
          <li><a href="<?php echo $this->webroot; ?>admin_category/category_list" ><span class="iconsweets-list"></span>Category Management</a></li>
          <?php } ?>

          <li><a href="<?php echo $this->webroot; ?>admin_group/group_list" ><span class="iconsweets-list2"></span>Groop Management</a></li>

          <li><a href="<?php echo $this->webroot; ?>admin_event/event_list" ><span class="iconsweets-list3"></span>Event Management</a></li>

          <!-- <li><a href="<?php echo $this->webroot; ?>admin_content/all_contents" ><span class="iconsweets-list4"></span>Content Management</a></li> -->
          <?php if($this->Session->read('admin_type') == 'SA'){?>
          <li><a href="<?php echo $this->webroot; ?>admin_content/all_static_contents" ><span class="iconsweets-list4"></span>Static Content Management</a></li>
          <?php } ?>

          <!-- <li><a href="<?php echo $this->webroot; ?>admin_testimonial/all_testimonials" ><span class="iconsweets-list1"></span>Testimonial Management</a></li> -->
          <?php if($this->Session->read('admin_type') == 'SA'){?>
          <li><a href="<?php echo $this->webroot; ?>admin_territory/list_all" ><span class="iconsweets-list1"></span>T.O Management</a></li>
          <?php } ?>

          <?php if($this->Session->read('admin_type') == 'TA'){?>
          <li><a href="<?php echo $this->webroot; ?>admin_territory/inbox" ><span class="iconsweets-list"></span>Inbox Management</a></li>
          <?php } ?>

          <?php if($this->Session->read('admin_type') == 'SA'){?>
          <li><a href="<?php echo $this->webroot; ?>admin_sitesettings/edit_sitesettings" ><span class="iconsweets-admin"></span>Sitesettings</a></li>
          <?php } ?>
       
   <!--  </ul> -->
  </div><!--leftmenu-->

</div><!--mainleft-->
<!-- END OF LEFT PANEL
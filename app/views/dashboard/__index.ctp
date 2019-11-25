<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/css/style.default.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/prettify/prettify.css" type="text/css" />
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery-ui-1.9.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/prettify/prettify.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.flot.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.flot.resize.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.flot.pie.js"></script>

    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/excanvas.min.js"></script><![endif]-->
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <style>
      /* Color Declarations */
      /* Color Config */
      /* Menu Config */

      .logomain
      {
        padding: 5px;
        margin-left: 28px;
        width: 175px;
      }

      nav {
        border-bottom-left-radius: 17px;
        background-color: rgba(140, 140, 140, 1);
        height: 50%;
        position: fixed;
        right: -300px;
        top: 20%;
        -moz-transition: right 0.2s linear;
        -o-transition: right 0.2s linear;
        -webkit-transition: right 0.2s linear;
        transition: right 0.2s linear;
        width: 300px;
        z-index: 9001;/* IT'S OVER 9000! */
      }

      nav #menuToggle {

        display: block;
        position: relative;
        height: 40px;
        left: -45px;
        top:4px;
        width: 50px;
      }

      nav #menuToggle span {
        background: white;
        display: block;
        height: 10%;
        left: 10%;
        position: absolute;
        top: 45%;
        width: 80%;
      }

      nav #menuToggle span:before,
      nav #menuToggle span:after {
        background: white;
        content: '';
        display: block;
        height: 100%;
        position: absolute;
        top: -250%;
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
        width: 100%;
      }

      nav #menuToggle span:after { top: 250%; }

      nav a:nth-child(n+2) {
        color: white;
        display: block;
        font-size: 2.5em;
        margin: 30px 0 30px 30px;
      }

      nav a:nth-child(n+2):after {
        background: #ffa53e;
        content: '';
        display: block;
        height: 2px;
        -moz-transition: width 0.2s;
        -o-transition: width 0.2s;
        -webkit-transition: width 0.2s;
        transition: width 0.2s;
        width: 0;
      }

      nav a:nth-child(n+2):hover:after { width: 100%; }

      .open { right: 0; }

      .open #menuToggle span {
        background: transparent;
        left: 10%;
        top: 45%;
      }

      .open #menuToggle span:before,
      .open #menuToggle span:after {
        background: white;
        top: 0;
        -moz-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
      }

      .open #menuToggle span:after {
        -moz-transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg);
      }

      #menuToggle .navClosed {
        -moz-transition: background 0.1s linear;
        -o-transition: background 0.1s linear;
        -webkit-transition: background 0.1s linear;
        transition: background 0.1s linear;
      }

      #menuToggle .navClosed:before,
      #menuToggle .navClosed:after {
        -moz-transition: top 0.2s linear 0.1s, -moz-transform 0.2s linear 0.1s;
        -o-transition: top 0.2s linear 0.1s, -o-transform 0.2s linear 0.1s;
        -webkit-transition: top 0.2s linear, -webkit-transform 0.2s linear;
        -webkit-transition-delay: 0.1s, 0.1s;
        transition: top 0.2s linear 0.1s, transform 0.2s linear 0.1s;
      }

      #menuToggle .navOpen {
        -moz-transition: background 0.1s linear 0.2s;
        -o-transition: background 0.1s linear 0.2s;
        -webkit-transition: background 0.1s linear;
        -webkit-transition-delay: 0.2s;
        transition: background 0.1s linear 0.2s;
      }

      #menuToggle .navOpen:before,
      #menuToggle .navOpen:after {
        -moz-transition: top 0.2s linear, -moz-transform 0.2s linear;
        -o-transition: top 0.2s linear, -o-transform 0.2s linear;
        -webkit-transition: top 0.2s linear, -webkit-transform 0.2s linear;
        transition: top 0.2s linear, transform 0.2s linear;
      }
    </style>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/amcharts.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/serial.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/none.js"></script>
    <script>
      var chart = AmCharts.makeChart("chartdiv", {
        "type": "serial",
        "theme": "none",
        "pathToImages": "",
        "dataProvider": [{
            "country": "Adminisration",
            "visits": 5,
            "color": "#cb4b4b"
          }, {
            "country": "Engineering",
            "visits": 20,
            "color": "#afd8f8"
          }, {
            "country": "Finance",
            "visits": 3,
            "color": "#edc240"
          }, {
            "country": "IT",
            "visits": 12,
            "color": "#4da74d"
          },
          {
            "country": "Sales",
            "visits": 2,
            "color": "#9440ed"
          },
          {
            "country": "Sub-Units",
            "visits": 9,
            "color": "#bd9b33"
          }],
        "valueAxes": [{
            "axisAlpha": 0,
            "position": "left",
            "title": "No of Employees"
          }],
        "startDuration": 1,
        "graphs": [{
            "balloonText": "<b>[[category]]: [[value]]</b>",
            "colorField": "color",
            "fillAlphas": 0.9,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "visits"
          }],
        "chartCursor": {
          "categoryBalloonEnabled": false,
          "cursorAlpha": 0,
          "zoomable": false
        },
        "categoryField": "country",
        "categoryAxis": {
          "gridPosition": "start",
          "labelRotation": 45
        },
        "amExport": {}

      });
    </script>
    <style>
      #slideout {
        position: fixed;
        top: 261px;
        right: -70px;
        -webkit-transition-duration: 0.3s;
        -moz-transition-duration: 0.3s;
        -o-transition-duration: 0.3s;
        transition-duration: 0.3s;
      }
      #slideout_inner {
        position: fixed;
        top: 193px;
        right: -270px;
        -webkit-transition-duration: 0.3s;
        -moz-transition-duration: 0.3s;
        -o-transition-duration: 0.3s;
        transition-duration: 0.3s;
      }
      #slideout:hover {
        right: 200px;
      }
      #slideout:hover #slideout_inner {
        right: 0;
      }

      .vertical-text
      { 	color:#333;
         border:0px solid red;
         writing-mode:tb-rl;
         -webkit-transform:rotate(90deg);
         -moz-transform:rotate(90deg);
         -o-transform: rotate(90deg);
         white-space:nowrap;
         display:block;
         bottom:0;
         font-family: sans-serif;
         font-size:16px;
      } 
      a.slect
      {
        color:#000000;
      } 
      a.slect:hover
      {
        color:#abdb1b;
      } 

      #chartdiv {
        width		: 100%;
        height		: 300px;
        font-size	: 11px;
      }			   
    </style>
    <style>
      .iconsweetss-logo1 
      {
        background:url(<?php echo $this->webroot; ?>admin/images/noti.png);
      }
    </style>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.canvasjs.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/canvasjs.min.js"></script>
  </head>

  <body>
    <div class="mainwrapper fullwrapper" style="background-position: 0px 0px;">
      <?php echo $this->element('admin_left'); ?>
      <style>
        .logomain
        {
          padding: 5px;
          margin-left: 28px;
          width: 175px;
        }
      </style>
      <!-- START OF RIGHT PANEL -->
      <div class="rightpanel">
        <?php echo $this->element('admin_header'); ?>
        
        <div class="pagetitle">
          <h1><?php echo $pageTitle; ?></h1> <span></span>
        </div><!--pagetitle-->

        <div class="maincontent">
          <div class="contentinner content-dashboard">            	                
            <div class="row-fluid">
              <div class="span8">
                <ul class="widgeticons row-fluid">
                  <li class="one_fifth"><a href="edit-profile.html"><img src="<?php echo $this->webroot; ?>admin/img/gemicon/profile.png" alt="" /><span>My Profile</a></li>
                  <li class="one_fifth"><a href="edit-profile.html"><img src="<?php echo $this->webroot; ?>admin/img/gemicon/event-two.png" alt="" /><span>My Events</span></a></li>
                  <li class="one_fifth"><a href="publishdoc.html"><img src="<?php echo $this->webroot; ?>admin/img/gemicon/doc.png" alt="" /><span>My Document</span></a></li>
                  <li class="one_fifth"><a href="kudos.html"><img src="<?php echo $this->webroot; ?>admin/images/pops.png" alt="" style="width: 94px;"/></a></li>
                  <li class="one_fifth last"><a href="notifi.html"><img src="<?php echo $this->webroot; ?>admin/img/gemicon/notification.png" alt="" /><span>My Notifiations</span></a></li>
                  <li class="one_fifth"><a href="events.html"><img src="<?php echo $this->webroot; ?>admin/img/gemicon/companyevent.png" alt="" /><span>Company Events</span></a></li>
                  <li class="one_fifth"><a href="work.html"><img src="<?php echo $this->webroot; ?>admin/img/gemicon/team.png" alt="" /><span>My Team</span></a></li>
                  <li class="one_fifth"><a href="view.html"><img src="<?php echo $this->webroot; ?>admin/img/gemicon/chart.png" alt="" /><span>Org Chart</span></a></li>
                  <li class="one_fifth"><a href="directory.html"><img src="<?php echo $this->webroot; ?>admin/img/gemicon/directory.png" alt="" /><span>Directory</span></a></li>
                  <li class="one_fifth"><a href="holidays.html"><img src="<?php echo $this->webroot; ?>admin/img/gemicon/holiday.png" alt="" /><span>Holiday</span></a></li>
                </ul><br />
                <div class="widgetcontent">
                  <h4 class="widgettitle1">Organizational Charts</h4>						
                  <div id="accordion" class="accordion">
                    <h3><a href="#">Employee Distribution by Subunit</a></h3>
                    <div id="chartplace2" style="height:300px;"></div>
                    <h3><a href="#">Head Count</a></h3>
                    <div id="piechart" style="height:300px; width:482;"></div>
                    <h3><a href="#">Employee Distribution by Department</a></h3>
                    <div id="piechart1" style="height:300px; width:482;"></div>
                    <h3><a href="#">Vacancy Succession Report</a></h3>
                    <div id="chartContainer" style="height:400px; width:100%;"></div>
                    <h3><a href="#">Age Chart</a></h3>									
                    <div id="piechart2" style="height:400px; width:100%;"></div>				
                    <h3><a href="#">Gender Chart</a></h3>									
                    <div id="piechart3" style="height:400px; width:100%;"></div>	
                    <h3><a href="#">Deparment Chart</a></h3>									
                    <div id="piechart4" style="height:400px; width:100%;"></div>
                    <h3><a href="#">Job Title Chart</a></h3>									
                    <div id="piechart5" style="height:400px; width:100%;"></div>
                    <h3><a href="#">Location Chart</a></h3>									
                    <div id="piechart6" style="height:400px; width:100%;"></div>
                    <h3><a href="#">Manager Chart</a></h3>									
                    <div id="piechart7" style="height:400px; width:100%;"></div>
                    <h3><a href="#">Employee Distribution by Sub Department</a></h3>									
                    <div id="piechart8" style="height:400px; width:100%;"></div>
                  </div><!--#accordion-->
                </div><!--widgetcontent-->
                <h4 class="widgettitle1">Recent Articles</h4>
                <div class="widgetcontent">
                  <div id="tabs">
                    <ul>
                      <li><a href="#tabs-1"><span class="iconsweets-documents"></span> Documents</a></li>
                      <li><a href="#tabs-2"><span class="icon-eye-open"></span> News</a></li>
                      <li><a href="#tabs-3"><span class="iconsweets-speech4"></span> Events</a></li>
                    </ul>
                    <div id="tabs-1">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Title</th>
                            <th>Submitted By</th>
                            <th>Date Added</th>
                            <th class="center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><a href="#"><strong>Insurance Claims </strong></a></td>
                            <td><a href="#">admin</a></td>
                            <td>Jan 02, 2013</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>
                          <tr>
                            <td><a href="#"><strong>Holiday Policy</strong></a></td>
                            <td><a href="#">admin</a></td>
                            <td>Jan 02, 2013</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div id="tabs-2">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Title</th>
                            <th>Submitted By</th>
                            <th>Date Added</th>
                            <th class="center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><a href="#"><strong>01. Special Training - Technical Writing for business </strong></a></td>
                            <td><a href="#">Admin</a></td>
                            <td>Jan 04, 2014</td>
                            <td class="center">
                              <a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>
                          <tr>
                            <td><a href="#"><strong>HRMS User Manual</strong></a></td>
                            <td><a href="#">admin</a></td>
                            <td>Jan 02, 2013</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div id="tabs-3">
                      <table class="table table-bordered">
                        <colgroup>
                          <col class="con0"  />
                          <col class="con1" />
                          <col class="con0" />
                          <col class="con1" />
                          <col class="con0" />
                          <col class="con1" />
                        </colgroup>
                        <thead>
                          <tr>
                            <th class="head0">Event Title</th>
                            <th class="head0">New Date</th>
                            <th class="head0">Owner(s)</th>
                            <th class="head0">Status</th>
                            <th class="head0">Location</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="gradeX">
                            <td><a href="edit-event.html">Intake QA batch in July</a></td>
                            <td>2014-06-17</td>
                            <td>Peter Mac Anderson , Nicky Silverstone</td>
                            <td>Activated</td>
                            <td>ASC_London</td>
                          </tr>
                          <tr class="gradeX">
                            <td><a href="edit-event.html">Intake SE batch in September</a></td>
                            <td>2014-06-18</td>
                            <td>Luke Wright , Melan Peiris</td>
                            <td>Activated</td>
                            <td>ASC_SG</td>
                          </tr>
                          <tr class="gradeX">
                            <td><a href="edit-event.html">Intake Support batch in November</a></td>
                            <td>2014-06-18</td>
                            <td>Nicky Silverstone</td>
                            <td>Created</td>
                            <td>HQ</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div><!--#tabs-->
                </div><!--widgetcontent-->
                <h4 class="widgettitle1">My Direct Reports</h4>
                <div class="widgetcontent">
                  <div id="tabs">
                    <ul>
                      <li><a href="#tabs-1"><span class="icon-globe"></span> Summary</a></li>
                      <li><a href="#tabs-2"><span class="iconsweets-folder"></span> Job Details</a></li>
                      <li><a href="#tabs-3"><span class="iconsweets-phone"></span> Contact</a></li>
                      <li><a href="#tabs-4"><span class=" icon-arrow-up"></span> Appraisals</a></li>
                      <li><a href="#tabs-5"><span class="iconsweets-books"></span> Training</a></li>
                    </ul>
                    <div id="tabs-1">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Actions</th>
                            <th>Job Title</th>
                            <th class="center">Position in Salary Range</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Cynthia Adams</td>
                            <td><span class="field">
                                <select name="select" class="input-small">
                                  <option value="" selected="selected">Action</option>
                                  <option value="2">Insurance Claims</option>
                                  <option value="3">Leave Policy</option>

                                </select>
                              </span></td>
                            <td>Corporate Controller</td>
                            <td class="center"><div id="slider4" ></div></td>
                          </tr>
                          <tr>
                            <td>Diane Palmer</td>
                            <td><span class="field">
                                <select name="select" class="input-small">
                                  <option value="" selected="selected">Action</option>
                                  <option value="2">Insurance Claims</option>
                                  <option value="3">Leave Policy</option>

                                </select>
                              </span></td>
                            <td>Senior Manager Accountant</td>
                            <td class="center"><div id="slider5" ></div></td>
                          </tr>
                          <tr>
                            <td>Jill</td>
                            <td><span class="field">
                                <select name="select" class="input-small">
                                  <option value="" selected="selected">Action</option>
                                  <option value="2">Insurance Claims</option>
                                  <option value="3">Leave Policy</option>

                                </select>
                              </span></td>
                            <td>Analyst</td>
                            <td class="center"><div id="slider6" ></div></td>
                          </tr>
                          <tr>
                            <td>Susan</td>
                            <td>
                              <span class="field">
                                <select name="select" class="input-small">
                                  <option value="" selected="selected">Action</option>
                                  <option value="2">Insurance Claims</option>
                                  <option value="3">Leave Policy</option>
                                </select>
                              </span>
                            </td>
                            <td>Manager-Payroll</td>
                            <td class="center"><div id="slider7" ></div></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div id="tabs-2">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Job Title</th>
                            <th>Job Description</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Web Developer</td>
                            <td>Web Technical Specialist</td>
                          </tr>
                          <tr>
                            <td>Web Developer</td>
                            <td>Web Technical Specialist</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div id="tabs-3">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Works ID</th>
                            <th>Surname</th>
                            <th>First Name</th>
                            <th >Work Tel</th>
                            <th >Work Mobile</th>
                            <th >Work Email</th>
                            <th >Site</th>
                            <th >Department</th>
                            <th >Sub Department</th>
                            <th >Manager</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div id="tabs-4">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Employee Name</th>
                            <th >Evaluators</th>
                            <th >From</th>
                            <th >To</th>
                            <th >Due Date</th>
                            <th >Description</th>
                            <th >Status</th>
                            <th >Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div id="tabs-5">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Title</th>
                            <th>Version</th>
                            <th>Subunit</th>
                            <th >Coordinator</th>
                            <th >Company</th>
                            <th >Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td></td>
                            <td> </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div><!--#tabs-->
                </div><!--widgetcontent-->
                <div class="widgetcontent">
                  <h4 class="widgettitle1">Attendance Charts</h4>						
                  <div id="accordion" class="accordion">
                    <h3><a href="#">Who's Online</a></h3>
                    <div class="widget-body">
                      <div class="widget-main no-padding">
                        <div class="dialogs">
                          <div class="itemdiv dialogdiv">
                            <div class="user">
                              <img src="<?php echo $this->webroot; ?>admin/images/4.png" alt="image">
                            </div>
                            <div class="body">
                              <span class="label label-info arrowed-right arrowed-in">Online</span>
                              <div class="name">
                                <a href="#">Deblina Das</a>
                              </div>
                              <div class="text">
                                Deblina Das has logged in.															
                              </div>
                            </div>
                          </div>
                          <div class="itemdiv dialogdiv">
                            <div class="user">
                              <img src="<?php echo $this->webroot; ?>admin/images/6.png" alt="image">
                            </div>
                            <div class="body">
                              <span class="label21 label-info arrowed-right arrowed-in">Online</span>
                              <div class="name">
                                <a href="#">Ramanuj Kayal</a>
                              </div>
                              <div class="text">
                                Ramanuj Kayal has logged in.															
                              </div>
                            </div>
                          </div>
                          <div class="itemdiv dialogdiv">
                            <div class="user">
                              <img src="<?php echo $this->webroot; ?>admin/images/1.png" alt="image">
                            </div>
                            <div class="body">
                              <span class="label label-info arrowed-right arrowed-in">Online</span>
                              <div class="name">
                                <a href="#">Jeet Banerjee</a>
                              </div>
                              <div class="text">
                                Jeet Banerjee has logged in.															
                              </div>
                            </div>
                          </div>
                          <div class="itemdiv dialogdiv">
                            <div class="user">
                              <img src="<?php echo $this->webroot; ?>admin/images/10.png" alt="image">
                            </div>

                            <div class="body">
                              <span class="label21 label-info arrowed-right arrowed-in">Online</span>
                              <div class="name">
                                <a href="#">Sudeshna De</a>
                              </div>
                              <div class="text">
                                Sudeshna De has logged in.															
                              </div>

                            </div>
                          </div>


                          <div class="itemdiv dialogdiv">
                            <div class="user">
                              <img src="<?php echo $this->webroot; ?>admin/images/9.jpg" alt="image">
                            </div>

                            <div class="body">
                              <span class="label label-info arrowed-right arrowed-in">Online</span>

                              <div class="name">
                                <a href="#">Sajjad Mistri</a>
                              </div>
                              <div class="text">
                                Sajjad Mistri has logged in.															
                              </div>
                            </div>
                          </div>


                          <div class="itemdiv dialogdiv">
                            <div class="user">
                              <img src="<?php echo $this->webroot; ?>admin/images/1.png" alt="image">
                            </div>

                            <div class="body">
                              <span class="label21 label-info arrowed-right arrowed-in">Online</span>

                              <div class="name">
                                <a href="/admins/view/78">Ashit Ghosh</a>
                              </div>
                              <div class="text">
                                Ashit Ghosh has logged in.															
                              </div>

                            </div>
                          </div>


                          <div class="itemdiv dialogdiv">
                            <div class="user">
                              <img src="<?php echo $this->webroot; ?>admin/images/7.png" alt="image">
                            </div>

                            <div class="body">
                              <span class="label label-info arrowed-right arrowed-in">Online</span>

                              <div class="name">
                                <a href="/admins/view/82">Sourav Roy</a>
                              </div>
                              <div class="text">
                                Sourav Roy has logged in.															
                              </div>

                            </div>
                          </div>


                          <div class="itemdiv dialogdiv">
                            <div class="user">
                              <img src="<?php echo $this->webroot; ?>admin/images/5.jpg" alt="image">
                            </div>

                            <div class="body">
                              <span class="label21 label-info arrowed-right arrowed-in">Online</span>

                              <div class="name">
                                <a href="/admins/view/86">Avishek Das</a>
                              </div>
                              <div class="text">
                                Avishek Das has logged in.															
                              </div>

                            </div>
                          </div>



                        </div>

                      </div><!--/widget-main-->
                    </div>

                    <h3><a href="#">Daily Attendance Chart by Department</a></h3>
                    <div class="widgetcontent">
                      <div id="tabs">
                        <ul>
                          <li><a href="#tabs-1"><span class="iconsweets-documentsmain"></span>Management</a></li>
                          <li><a href="#tabs-2"><span class="iconsweets-documentsfull"></span>Designer</a></li>
                          <li><a href="#tabs-3"><span class="iconsweets-documentsfullone"></span>Developer</a></li>
                        </ul>
                        <div id="tabs-1">

                          <table cellspacing="0" class="google-visualization-table-table"><thead><tr class="google-visualization-table-tr-head"><th class="google-visualization-table-th gradient unsorted">&nbsp;</th><th class="google-visualization-table-th gradient unsorted">Date<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Day<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Login Time<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Logout Time<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Working Time<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">IP Address<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Status<span class="google-visualization-table-sortind"></span></th></tr></thead>
                            <tbody>
                              <tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">1</td><td class="google-visualization-table-td">01-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td" style="background-color: rgb(237, 157, 151); color: rgb(255, 255, 255); text-align: center;">absent</td></tr>
                              <tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">2</td><td class="google-visualization-table-td">02-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:18:19 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.145.211</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr>
                              <tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">3</td><td class="google-visualization-table-td">03-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr>
                              <tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">4</td><td class="google-visualization-table-td">04-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr>
                              <tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">5</td><td class="google-visualization-table-td">05-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:14:35 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr>
                              <tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">6</td><td class="google-visualization-table-td">06-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td" style="background-color: rgb(237, 157, 151); color: rgb(255, 255, 255); text-align: center;">absent</td></tr>
                              <tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">7</td><td class="google-visualization-table-td">07-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:05:48 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">8</td><td class="google-visualization-table-td">08-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:13:38 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">9</td><td class="google-visualization-table-td">09-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:51:11 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">10</td><td class="google-visualization-table-td">10-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">11</td><td class="google-visualization-table-td">11-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">12</td><td class="google-visualization-table-td">12-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">12:02:30 PM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.144.185</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">13</td><td class="google-visualization-table-td">13-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:14:15 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.144.185</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">14</td><td class="google-visualization-table-td">14-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:14:07 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.144.185</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">15</td><td class="google-visualization-table-td">15-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:15:55 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.150.182</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">16</td><td class="google-visualization-table-td">16-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">12:34:42 PM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.146.16</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">17</td><td class="google-visualization-table-td">17-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">18</td><td class="google-visualization-table-td">18-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">19</td><td class="google-visualization-table-td">19-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:20:34 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.149.122</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">20</td><td class="google-visualization-table-td">20-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:28:33 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">103.42.173.96</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">21</td><td class="google-visualization-table-td">21-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:08:11 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">103.42.173.96</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">22</td><td class="google-visualization-table-td">22-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:11:06 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.149.72</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">23</td><td class="google-visualization-table-td">23-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">01:10:33 PM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.149.72</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">24</td><td class="google-visualization-table-td">24-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">25</td><td class="google-visualization-table-td">25-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">26</td><td class="google-visualization-table-td">26-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">27</td><td class="google-visualization-table-td">27-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:19:02 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.150.178</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">28</td><td class="google-visualization-table-td">28-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:18:27 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.150.178</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr></tbody></table>



                        </div>
                        <div id="tabs-2">
                          <table cellspacing="0" class="google-visualization-table-table"><thead><tr class="google-visualization-table-tr-head"><th class="google-visualization-table-th gradient unsorted">&nbsp;</th><th class="google-visualization-table-th gradient unsorted">Date<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Day<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Login Time<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Logout Time<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Working Time<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">IP Address<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Status<span class="google-visualization-table-sortind"></span></th></tr></thead><tbody><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">1</td><td class="google-visualization-table-td">01-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td" style="background-color: rgb(237, 157, 151); color: rgb(255, 255, 255); text-align: center;">absent</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">2</td><td class="google-visualization-table-td">02-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:18:19 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.145.211</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">3</td><td class="google-visualization-table-td">03-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">4</td><td class="google-visualization-table-td">04-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">5</td><td class="google-visualization-table-td">05-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:14:35 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">6</td><td class="google-visualization-table-td">06-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td" style="background-color: rgb(237, 157, 151); color: rgb(255, 255, 255); text-align: center;">absent</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">7</td><td class="google-visualization-table-td">07-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:05:48 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">8</td><td class="google-visualization-table-td">08-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:13:38 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">9</td><td class="google-visualization-table-td">09-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:51:11 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">10</td><td class="google-visualization-table-td">10-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">11</td><td class="google-visualization-table-td">11-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">12</td><td class="google-visualization-table-td">12-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">12:02:30 PM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.144.185</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">13</td><td class="google-visualization-table-td">13-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:14:15 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.144.185</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">14</td><td class="google-visualization-table-td">14-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:14:07 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.144.185</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">15</td><td class="google-visualization-table-td">15-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:15:55 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.150.182</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">16</td><td class="google-visualization-table-td">16-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">12:34:42 PM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.146.16</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">17</td><td class="google-visualization-table-td">17-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">18</td><td class="google-visualization-table-td">18-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">19</td><td class="google-visualization-table-td">19-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:20:34 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.149.122</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">20</td><td class="google-visualization-table-td">20-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:28:33 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">103.42.173.96</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">21</td><td class="google-visualization-table-td">21-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:08:11 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">103.42.173.96</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">22</td><td class="google-visualization-table-td">22-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:11:06 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.149.72</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">23</td><td class="google-visualization-table-td">23-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">01:10:33 PM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.149.72</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">24</td><td class="google-visualization-table-td">24-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">25</td><td class="google-visualization-table-td">25-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">26</td><td class="google-visualization-table-td">26-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">27</td><td class="google-visualization-table-td">27-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:19:02 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.150.178</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">28</td><td class="google-visualization-table-td">28-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:18:27 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.150.178</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">29</td><td class="google-visualization-table-td">29-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:23:37 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.150.178</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr></tbody></table>
                        </div>



                        <div id="tabs-3">

                          <table cellspacing="0" class="google-visualization-table-table"><thead><tr class="google-visualization-table-tr-head"><th class="google-visualization-table-th gradient unsorted">&nbsp;</th><th class="google-visualization-table-th gradient unsorted">Date<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Day<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Login Time<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Logout Time<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Working Time<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">IP Address<span class="google-visualization-table-sortind"></span></th><th class="google-visualization-table-th gradient unsorted">Status<span class="google-visualization-table-sortind"></span></th></tr></thead><tbody><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">1</td><td class="google-visualization-table-td">01-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td" style="background-color: rgb(237, 157, 151); color: rgb(255, 255, 255); text-align: center;">absent</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">2</td><td class="google-visualization-table-td">02-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:18:19 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.145.211</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">3</td><td class="google-visualization-table-td">03-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">4</td><td class="google-visualization-table-td">04-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">5</td><td class="google-visualization-table-td">05-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:14:35 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">6</td><td class="google-visualization-table-td">06-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td" style="background-color: rgb(237, 157, 151); color: rgb(255, 255, 255); text-align: center;">absent</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">7</td><td class="google-visualization-table-td">07-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:05:48 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">8</td><td class="google-visualization-table-td">08-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:13:38 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">9</td><td class="google-visualization-table-td">09-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:51:11 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.148.205</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">10</td><td class="google-visualization-table-td">10-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">11</td><td class="google-visualization-table-td">11-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">12</td><td class="google-visualization-table-td">12-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">12:02:30 PM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.144.185</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">13</td><td class="google-visualization-table-td">13-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:14:15 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.144.185</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">14</td><td class="google-visualization-table-td">14-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:14:07 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.144.185</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">15</td><td class="google-visualization-table-td">15-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:15:55 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.150.182</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">16</td><td class="google-visualization-table-td">16-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">12:34:42 PM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.146.16</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">17</td><td class="google-visualization-table-td">17-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">18</td><td class="google-visualization-table-td">18-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">19</td><td class="google-visualization-table-td">19-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:20:34 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.149.122</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">20</td><td class="google-visualization-table-td">20-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:28:33 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">103.42.173.96</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">21</td><td class="google-visualization-table-td">21-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:08:11 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">103.42.173.96</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">22</td><td class="google-visualization-table-td">22-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:11:06 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.149.72</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">23</td><td class="google-visualization-table-td">23-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">01:10:33 PM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.149.72</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">24</td><td class="google-visualization-table-td">24-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Saturday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">25</td><td class="google-visualization-table-td">25-01-2015</td><td class="google-visualization-table-td" style="background-color: red;">Sunday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">26</td><td class="google-visualization-table-td">26-01-2015</td><td class="google-visualization-table-td">Monday</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">--</td><td class="google-visualization-table-td">--</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">27</td><td class="google-visualization-table-td">27-01-2015</td><td class="google-visualization-table-td">Tuesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:19:02 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.150.178</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">28</td><td class="google-visualization-table-td">28-01-2015</td><td class="google-visualization-table-td">Wednesday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:18:27 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.150.178</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-even"><td class="google-visualization-table-td google-visualization-table-seq">29</td><td class="google-visualization-table-td">29-01-2015</td><td class="google-visualization-table-td">Thursday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:23:37 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.150.178</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr><tr class="google-visualization-table-tr-odd"><td class="google-visualization-table-td google-visualization-table-seq">30</td><td class="google-visualization-table-td">30-01-2015</td><td class="google-visualization-table-td">Friday</td><td class="google-visualization-table-td google-visualization-table-td-number">11:22:35 AM</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td google-visualization-table-td-number">--</td><td class="google-visualization-table-td">223.223.145.43</td><td class="google-visualization-table-td" style="background-color: rgb(174, 213, 165); text-align: center;">present</td></tr></tbody></table>


                        </div>

                      </div><!--#tabs-->
                    </div>


                    <h3><a href="#">Vacation Chart by Department</a></h3>
                    <div class="widgetcontent">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Employee Name</th>
                            <th>Vacation Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th class="center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><a href="#"><strong>Nancy </strong></a></td>
                            <td><a href="#">Casual</a></td>
                            <td>Jan 02, 2013</td>
                            <td>Feb 03, 2015</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>
                          <tr>
                            <td><a href="#"><strong>Lance </strong></a></td>
                            <td><a href="#">Unpaid</a></td>
                            <td>Jan 02, 2013</td>
                            <td>Feb 03, 2015</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>

                          <tr>
                            <td><a href="#"><strong>Cindy </strong></a></td>
                            <td><a href="#">Sick</a></td>
                            <td>Jan 02, 2013</td>
                            <td>Feb 03, 2015</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>

                          <tr>
                            <td><a href="#"><strong>Thomas </strong></a></td>
                            <td><a href="#">Annual</a></td>
                            <td>Jan 02, 2013</td>
                            <td>Feb 03, 2015</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>

                          <tr>
                            <td><a href="#"><strong>David </strong></a></td>
                            <td><a href="#">Casual</a></td>
                            <td>Jan 02, 2013</td>
                            <td>Feb 03, 2015</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>

                          <tr>
                            <td><a href="#"><strong>Nancy </strong></a></td>
                            <td><a href="#">Casual</a></td>
                            <td>Jan 02, 2013</td>
                            <td>Feb 03, 2015</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>
                          <tr>
                            <td><a href="#"><strong>Lance </strong></a></td>
                            <td><a href="#">Unpaid</a></td>
                            <td>Jan 02, 2013</td>
                            <td>Feb 03, 2015</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>

                          <tr>
                            <td><a href="#"><strong>Cindy </strong></a></td>
                            <td><a href="#">Sick</a></td>
                            <td>Jan 02, 2013</td>
                            <td>Feb 03, 2015</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>

                          <tr>
                            <td><a href="#"><strong>Thomas </strong></a></td>
                            <td><a href="#">Annual</a></td>
                            <td>Jan 02, 2013</td>
                            <td>Feb 03, 2015</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>

                          <tr>
                            <td><a href="#"><strong>David </strong></a></td>
                            <td><a href="#">Casual</a></td>
                            <td>Jan 02, 2013</td>
                            <td>Feb 03, 2015</td>
                            <td class="center"><a href="#" class="btn"><span class="icon-edit"></span> Edit</a></td>
                          </tr>

                        </tbody>
                      </table>

                    </div>


                  </div><!--#accordion-->
                </div><!--widgetcontent-->
                <!--widgetcontent-->
              </div><!--span8-->
              <div class="span4">                   	
                <h4 class="widgettitle1 nomargin">Social Stream</h4>
                <div class="widgetcontent">
                  <div class="widgetcontent" style="font-size:10px;">
                    <div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
                      <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
                        <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="tabs-1" aria-labelledby="ui-id-1" aria-selected="true"><a href="#tabs-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1"> Update Status</a></li>
                        <li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-2" aria-labelledby="ui-id-2" aria-selected="false"><a href="#tabs-2" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">Upload Images</a></li>
                        <li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-3" aria-labelledby="ui-id-3" aria-selected="false"><a href="#tabs-3" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3">Share Video</a></li>
                      </ul>
                      <div id="tabs-1" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="true" aria-hidden="false">
                        <div class="col-lg-12"><textarea class="input-xxlarge" placeholder="What's on your mind ?"></textarea>
                          <br/>
                          <br/>

                          <span class="span12" style="float:right;"><button class="btn btn-warning">Post</button></span>
                          <br/>
                          <br/>
                        </div>
                      </div>
                      <div id="tabs-2" aria-labelledby="ui-id-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: none;">
                        <div class="col-lg-12"><textarea class="input-xxlarge" placeholder="Say something about these photos"></textarea>
                          <br/>
                          <br/>

                          <span class="span12" style="float:right;"><button class="btn btn-warning">Upload Images</button> &nbsp;<button class="btn btn-warning">Post</button></span>
                          <br/>
                          <br/>
                        </div>
                      </div>
                      <div id="tabs-3" aria-labelledby="ui-id-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: none;">
                        <div class="col-lg-12"><textarea class="input-xxlarge" placeholder="Paste Video URL"></textarea>
                          <br/>
                          <br/>
                          <span class="span12" style="float:right;"><button class="btn btn-warning">Post</button></span> 
                          <br/>
                          <br/>
                        </div>
                      </div>
                    </div><!--#tabs-->

                  </div>


                  <div class="posting" id="record-4265">
                    <a href="#" class="delete_p"><i class="icon-cancel-circle" style="font-size:10px;"></i></a>
                    <div class="postbody" style="border-bottom:1px solid #ccc;">
                      <div class="name">
                        <h5 style="
                    color: #7aba3e;">Shaun Michael</h5>
                      </div>

                      <div id="post" class="posttext">
                        Shaun Michael has been recognised by xyz for: I would like to thank Kieran for all his help with last weeks customer problem - it was really appreciated!			</div>	

                      <div id="timeandlikes" class="postnotes">

                        9 days ago
                        <a href="javascript: void(0)" id="post_id4265" class="showCommentBox">Comment</a> 

                        <span id="like-panel-4265">&nbsp;<a href="javascript: void(0)" id="post_id4265" onClick="javascript: likethis(0, 4265, 1);">Like</a></span>

                        <div class="showPpl" id="ppl_like_div_4265" style="display: none;">
                          <span id="like-stats-4265">0 people like this</span>
                        </div>
                      </div>
                    </div>

                    <div class="postbody">
                      <div class="name">
                        <h5 style="color: #7aba3e;">Octo Ushtar</h5>
                      </div>

                      <div id="post" class="posttext">
                        Octo Ushtar has been recognised by xyz for: I would like to thank Kieran for all his help with last weeks customer problem - it was really appreciated!			</div>	

                      <div id="timeandlikes" class="postnotes">

                        9 days ago
                        <a href="javascript: void(0)" id="post_id4265" class="showCommentBox">Comment</a> 

                        <span id="like-panel-4265">&nbsp;<a href="javascript: void(0)" id="post_id4265" onClick="javascript: likethis(0, 4265, 1);">Like</a></span>

                        <div class="showPpl" id="ppl_like_div_4265" style="display: none;">
                          <span id="like-stats-4265">0 people like this</span>
                        </div>
                      </div>
                    </div>

                    <div id="comments">
                      <div id="CommentPosted4265">
                        <div id="loadComments4265" style="display:none">
                        </div>
                      </div>
                      <!-- end of comments div -->
                    </div>
                    <!-- end of main div -->
                  </div>
                </div><!--widgetcontent-->


                <h4 class="widgettitle1 nomargin">Events this month</h4>
                <div class="widgetcontent">
                  <div id="calendar" class="widgetcalendar"></div>
                </div><!--widgetcontent-->
                <h4 class="widgettitle1">Work Flow</h4>
                <div class="widgetcontent">
                  <div id="accordion" class="accordion">
                    <h3><a href="#">Pending Leave Requests</a></h3>
                    <div>
                      <p>
                        <a href="#">01. Kevin Mathews 2014-09-08</a>
                      </p>
                      <p>
                        <a href="#">02. Jacqueline White 2014-09-23</a>
                      </p>
                      <p>
                        <a href="#">03. Ryan Parker 2014-09-29</a>
                      </p>
                      <p>
                        <a href="#">04. Kevin Mathews 2014-10-06</a>
                      </p>
                      <p>
                        <a href="#">05. Ryan Parker 2014-10-06</a>
                      </p>
                      <p><a href="#" class="btn"><span class="icon-edit"></span> View All Leave Requests</a></p>
                    </div>
                    <h3><a href="#">Pending Timesheets</a></h3>
                    <div>
                      <p>
                        <a href="#">01. John Smith 2013-01-21 to 2013-01-27</a>
                      </p>
                      <p>
                        <a href="#">02. Anthony Nolan 2013-02-11 to 2013-02-17</a>
                      </p>
                      <p>
                        <a href="#">03. Kevin Mathews 2013-03-18 to 2013-03-24</a>
                      </p>
                      <p>
                        <a href="#">04. Nina Patel 2013-04-08 to 2013-04-14</a>
                      </p>
                      <p>
                        <a href="#">05. Nicky Silverstone 2013-04-08 to 2013-04-14</a>
                      </p>
                      <p><a href="#" class="btn"><span class="icon-edit"></span> View All Pending Timesheets</a></p>
                    </div>
                    <h3><a href="#">Scheduled Interviews</a></h3>
                    <div>
                      <p>
                        <a href="#">01. Crishal Powell 2013-08-29</a>
                      </p>
                      <p>
                        <a href="#">02. Daniel Nolan 2013-09-20</a>
                      </p>
                      <p>
                        <a href="#">03. Anne Clinton 2013-09-27</a>
                      </p>
                      <p>
                        <a href="#">04. Lucas Scott 2014-01-02</a>
                      </p>
                      <p>
                        <a href="#">05. andrew Keller 2014-01-07</a>
                      </p>
                      <p><a href="#" class="btn"><span class="icon-edit"></span> View All Scheduled Interview</a></p>
                    </div>

                    <h3><a href="#">Scheduled Change Requests</a></h3>
                    <div>
                      <p>
                        <a href="#">01. Crishal Powell 2013-08-29</a>
                      </p>
                      <p>
                        <a href="#">02. Daniel Nolan 2013-09-20</a>
                      </p>
                      <p>
                        <a href="#">03. Anne Clinton 2013-09-27</a>
                      </p>
                      <p>
                        <a href="#">04. Lucas Scott 2014-01-02</a>
                      </p>
                      <p>
                        <a href="#">05. andrew Keller 2014-01-07</a>
                      </p>
                      <p><a href="#" class="btn"><span class="icon-edit"></span> View All Scheduled Interview</a></p>
                    </div>




                  </div><!--#accordion-->
                </div><!--widgetcontent-->

                <!--<h4 class="widgettitle1">Performance</h4>
               <div class="widgetcontent">
                 <div id="bargraph2" style="height:200px;"></div>
               </div>--><!--widgetcontent-->
                <!--<h4 class="widgettitle1">Leave Taken By Department</h4>
                <div class="widgetcontent" >
                  <div id="chartdiv" ></div>
                </div>--><!--widgetcontent-->

                <h4 class="widgettitle1">Alerts</h4>
                <div class="widgetcontent">
                  <div id="accordion" class="accordion">
                    <h3><a href="#">Anniversaries</a></h3>
                    <div>
                      <p>
                        <a href="#">01. Kevin Mathews 2014-09-08</a>
                      </p>
                      <p>
                        <a href="#">02. Jacqueline White 2014-09-23</a>
                      </p>
                      <p>
                        <a href="#">03. Ryan Parker 2014-09-29</a>
                      </p>
                      <p>
                        <a href="#">04. Kevin Mathews 2014-10-06</a>
                      </p>
                      <p>
                        <a href="#">05. Ryan Parker 2014-10-06</a>
                      </p>
                      <p><a href="#" class="btn"><span class="icon-edit"></span> View All</a></p>
                    </div>
                    <h3><a href="#">Birthdays</a></h3>
                    <div>
                      <p>
                        <a href="#">01. John Smith 2013-01-21 </a>
                      </p>
                      <p>
                        <a href="#">02. Anthony Nolan 2013-02-11 </a>
                      </p>
                      <p>
                        <a href="#">03. Kevin Mathews 2013-03-18</a>
                      </p>
                      <p>
                        <a href="#">04. Nina Patel 2013-04-08 </a>
                      </p>
                      <p>
                        <a href="#">05. Nicky Silverstone 2013-04-08 </a>
                      </p>
                      <p><a href="#" class="btn"><span class="icon-edit"></span> View All</a></p>
                    </div>
                    <h3><a href="#">New Job Openings</a></h3>
                    <div>
                      <p>
                        <a href="#">01. Crishal Powell 2013-08-29</a>
                      </p>
                      <p>
                        <a href="#">02. Daniel Nolan 2013-09-20</a>
                      </p>
                      <p>
                        <a href="#">03. Anne Clinton 2013-09-27</a>
                      </p>
                      <p>
                        <a href="#">04. Lucas Scott 2014-01-02</a>
                      </p>
                      <p>
                        <a href="#">05. andrew Keller 2014-01-07</a>
                      </p>
                      <p><a href="#" class="btn"><span class="icon-edit"></span> View All</a></p>
                    </div>
                    <h3><a href="#">Up Coming Anniversaries</a></h3>
                    <div>
                      <p>
                        <a href="#">01. Crishal Powell 2013-08-29</a>
                      </p>
                      <p>
                        <a href="#">02. Daniel Nolan 2013-09-20</a>
                      </p>
                      <p>
                        <a href="#">03. Anne Clinton 2013-09-27</a>
                      </p>
                      <p>
                        <a href="#">04. Lucas Scott 2014-01-02</a>
                      </p>
                      <p>
                        <a href="#">05. andrew Keller 2014-01-07</a>
                      </p>
                      <p><a href="#" class="btn"><span class="icon-edit"></span> View All</a></p>
                    </div>
                    <h3><a href="#">Props Given</a></h3>
                    <div>
                      <p>
                        <a href="#">01. Crishal Powell 2013-08-29</a>
                      </p>
                      <p>
                        <a href="#">02. Daniel Nolan 2013-09-20</a>
                      </p>
                      <p>
                        <a href="#">03. Anne Clinton 2013-09-27</a>
                      </p>
                      <p>
                        <a href="#">04. Lucas Scott 2014-01-02</a>
                      </p>
                      <p>
                        <a href="#">05. andrew Keller 2014-01-07</a>
                      </p>
                      <p><a href="#" class="btn"><span class="icon-edit"></span> View All</a></p>
                    </div>
                    <h3><a href="#">Most Liked Posts</a></h3>
                    <div>
                      <p>
                        <a href="#">01. Crishal Powell 2013-08-29</a>
                      </p>
                      <p>
                        <a href="#">02. Daniel Nolan 2013-09-20</a>
                      </p>
                      <p>
                        <a href="#">03. Anne Clinton 2013-09-27</a>
                      </p>
                      <p>
                        <a href="#">04. Lucas Scott 2014-01-02</a>
                      </p>
                      <p>
                        <a href="#">05. andrew Keller 2014-01-07</a>
                      </p>
                      <p><a href="#" class="btn"><span class="icon-edit"></span> View All</a></p>
                    </div>
                    <h3><a href="#">Most Common Posts</a></h3>
                    <div>
                      <p>
                        <a href="#">01. Crishal Powell 2013-08-29</a>
                      </p>
                      <p>
                        <a href="#">02. Daniel Nolan 2013-09-20</a>
                      </p>
                      <p>
                        <a href="#">03. Anne Clinton 2013-09-27</a>
                      </p>
                      <p>
                        <a href="#">04. Lucas Scott 2014-01-02</a>
                      </p>
                      <p>
                        <a href="#">05. andrew Keller 2014-01-07</a>
                      </p>
                      <p><a href="#" class="btn"><span class="icon-edit"></span> View All</a></p>
                    </div>
                  </div><!--#accordion-->
                </div><!--widgetcontent-->
                <h4 class="widgettitle1">Company Directory</h4>
                <div class="searchwidget">
                  Search by Name, Job Title, Department or Email
                  <form action="#" method="post">
                    <div class="input-append">
                      <input type="text" class="input-large search-query" placeholder="Search here...">
                      <button type="submit" class="btn"><span class="icon-search"></span></button>
                    </div>
                  </form>
                  <span> <a href="#">Advanced Search</a></span>
                </div>
              </div><!--span4-->
            </div><!--row-fluid-->
          </div><!--contentinner-->
        </div><!--maincontent-->

      </div><!--mainright-->
      <!-- END OF RIGHT PANEL -->

      <div class="clearfix"></div>

      <div class="footer">
        <div class="footerleft">Ogma Conceptions</div>
        <div class="footerright">&copy; Copyrights 2014</div>
      </div><!--footer-->


    </div><!--mainwrapper-->
    <!--<div id="slideout">

    <span class="vertical-text" style="box-shadow:grey 1px 2px 1px 0px;color: black;border-bottom-right-radius: 10px;border-bottom-left-radius: 10px;border: 3px solid whitesmoke;text-align: center;min-width: 170px;padding: 6px 0px 6px 0px;background-color:#abdb1b;">Setup Ogma Links</span>
     <div id="slideout_inner">

         <div   style="width: 270px; min-height: 215px; top: 150px; right: -303px; color: rgb(51, 51, 51); background-color:grey; border-bottom-left-radius:5px;box-shadow: grey 0px 1px 10px 0px; ">
               <div >
               <div style="margin-left:40px;">
               <form>
           <p><strong style="color:#FFFFFF;">Setup steps to complete</strong></p>
           <p style="list-style:none;">
                       <p><a class="slect" href="#" >Enter your Company Information</a> <span>&nbsp;&nbsp;&nbsp;</span><input type="checkbox"/></p>
                       <p><a href="#" class="slect">Setup Email Settings</a>  &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 67px;"/></p>
                                           <p><a href="#" class="slect">Setup Localization</a>  &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 78px;"/></p>
                         <p><a href="#" class="slect">Setup Job Titles</a>  &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 92px;"/></p>
                                               <p><a href="#" class="slect">Setup Job Location</a>  &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 76px;"/></p>
                        <p><a href="#" class="slect">Setup Reporting Method</a>  &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 46px;"/></p>
                                            <p><a href="#" class="slect">Setup Department Structure</a> &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 26px;"/></p>

                                            <p><a href="#" class="slect">Setup Salary Components</a> &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 42px;"/></p>


                         <p><a href="#" class="slect">Setup Pay Grades </a> &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 84px;"/></p>

                         <p><a href="#" class="slect">Setup Employment Status</a> &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 43px;"/></p>

                       <p><a href="#" class="slect">Setup Job Categories</a> &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 67px;"/></p>

                         <p><a href="#" class="slect">Setup Work Sifts </a> &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 91px;"/></p>

                         <p><a href="#"class="slect">Add Employees</a> &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-left: 99px;"/></p>


                                               </p>
                                               </form>
                                               </div>

         </div></div></div></div>-->
    <nav> <a href="#" id="menuToggle" title="show menu" class="vertical-text" style="text-decoration:none;"> <p style="box-shadow:grey 1px 2px 1px 0px;color: black;border-bottom-right-radius: 10px;border-bottom-left-radius: 10px;border: 3px solid whitesmoke;text-align: center;min-width: 147px;padding: 6px 0px 6px 0px;background-color:#abdb1b;text-tranformation:none;">Setup Ogma Links</p> </a>
      <div class="span3" style="margin-top: -39px;">
        <h4 style="color:white;">Setup Steps to Complete </h4>
        <p><span style="float:left;"><a class="slect" href="#" >Enter your Company Information</a></span> <span style="float:right;"><input type="checkbox"/></span></p>
        <br/>
        <p><span style="float:left;"><a href="#" class="slect">Setup Email Settings</a> </span> <span style="float:right;"><input type="checkbox" style="margin-left: 67px;"/></span></p> <br/>
        <p><span style="float:left;"><a href="#" class="slect">Setup Localization</a>  </span> <span style="float:right;"><input type="checkbox" style="margin-left: 78px;"/></span></p> <br/>
        <p><span style="float:left;"><a href="#" class="slect">Setup Job Titles</a>  </span> <span style="float:right;"><input type="checkbox" style="margin-left: 92px;"/></span></p> <br/>
        <p><span style="float:left;"><a href="#" class="slect">Setup Job Location</a> </span> <span style="float:right;"><input type="checkbox" style="margin-left: 76px;"/></span></p> <br/>
        <p><span style="float:left;"><a href="#" class="slect">Setup Reporting Method</a>  </span> <span style="float:right;"><input type="checkbox" style="margin-left: 46px;"/></span></p> <br/>
        <p><span style="float:left;"><a href="#" class="slect">Setup Department Structure</a> </span> <span style="float:right;"><input type="checkbox" style="margin-left: 26px;"/></span></p> <br/>
        <p><span style="float:left;"><a href="#" class="slect">Setup Salary Components</a> </span> <span style="float:right;"><input type="checkbox" style="margin-left: 42px;"/></span></p> <br/>
        <p><span style="float:left;"><a href="#" class="slect">Setup Pay Grades </a> </span> <span style="float:right;"><input type="checkbox" style="margin-left: 84px;"/></span></p> <br/>
        <p><span style="float:left;"><a href="#" class="slect">Setup Employment Status</a> </span> <span style="float:right;"><input type="checkbox" style="margin-left: 43px;"/></span></p> <br/>
        <p><span style="float:left;"><a href="#" class="slect">Setup Job Categories</a> </span> <span style="float:right;"><input type="checkbox" style="margin-left: 67px;"/></span></p> <br/>
        <p><span style="float:left;"><a href="#" class="slect">Setup Work Sifts </a></span> <span style="float:right;"><input type="checkbox" style="margin-left: 91px;"/></span></p> <br/>
        <p><span style="float:left;"><a href="#" class="slect">Add Employees</a> </span> <span style="float:right;"><input type="checkbox" style="margin-left: 99px;"/></span></p> <br/> </div></nav>
    <script>
      (function ($) {
        // Menu Functions
        $(document).ready(function () {
          $('#menuToggle').click(function (e) {
            var $parent = $(this).parent('nav');
            $parent.toggleClass("open");
            var navState = $parent.hasClass('open') ? "hide" : "show";
            $(this).attr("title", navState + " navigation");
            // Set the timeout to the animation length in the CSS.
            setTimeout(function () {
              console.log("timeout set");
              $('#menuToggle > span').toggleClass("navClosed").toggleClass("navOpen");
            }, 200);
            e.preventDefault();
          });
        });
      })(jQuery);
    </script>

    <script type="text/javascript">
      jQuery(document).ready(function () {

        // basic chart
        var flash = [[0, 2], [1, 6], [2, 3], [3, 8], [4, 5], [5, 13], [6, 8]];
        var html5 = [[0, 5], [1, 4], [2, 4], [3, 1], [4, 9], [5, 10], [6, 13]];

        function showTooltip(x, y, contents) {
          jQuery('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css({
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5
          }).appendTo("body").fadeIn(200);
        }


        var plot = jQuery.plot(jQuery("#chartplace2"),
                [{data: flash, label: "Leave Applied", color: "#fb6409"}, {data: html5, label: "Leave Not Applied", color: "#096afb"}], {
          series: {
            lines: {show: true, fill: true, fillColor: {colors: [{opacity: 0.05}, {opacity: 0.15}]}},
            points: {show: true}
          },
          legend: {position: 'nw'},
          grid: {hoverable: true, clickable: true, borderColor: '#ccc', borderWidth: 1, labelMargin: 10},
          yaxis: {min: 0, max: 15}
        });

        var previousPoint = null;
        jQuery("#chartplace2").bind("plothover", function (event, pos, item) {
          jQuery("#x").text(pos.x.toFixed(2));
          jQuery("#y").text(pos.y.toFixed(2));

          if (item) {
            if (previousPoint != item.dataIndex) {
              previousPoint = item.dataIndex;

              jQuery("#tooltip").remove();
              var x = item.datapoint[0].toFixed(2),
                      y = item.datapoint[1].toFixed(2);

              showTooltip(item.pageX, item.pageY,
                      item.series.label + " of " + x + " = " + y);
            }

          } else {
            jQuery("#tooltip").remove();
            previousPoint = null;
          }

        });

        jQuery("#chartplace2").bind("plotclick", function (event, pos, item) {
          if (item) {
            jQuery("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
            plot.highlight(item.series, item.datapoint);
          }
        });


        // bar graph
        var d2 = [];
        for (var i = 0; i <= 10; i += 1)
          d2.push([i, parseInt(Math.random() * 30)]);

        var stack = 0, bars = true, lines = false, steps = false;
        jQuery.plot(jQuery("#bargraph2"), [d2], {
          series: {
            stack: stack,
            lines: {show: lines, fill: true, steps: steps},
            bars: {show: bars, barWidth: 0.6}
          },
          grid: {hoverable: true, clickable: true, borderColor: '#bbb', borderWidth: 1, labelMargin: 10},
          colors: ["#06c"]
        });


        // calendar
        jQuery('#calendar').datepicker();


      });

      var data = [];
      var series = 6;
      for (var i = 0; i < series; i++) {
        data[i] = {label: "Series" + (i + 1), data: Math.floor(Math.random() * 100) + 1}
      }
      jQuery.plot(jQuery("#piechart"), data, {
        colors: ['#b9d6fd', '#fdb5b5', '#c9fdb5', '#f9b5fd', '#d7b5fd', '#bd9b33'],
        series: {
          pie: {show: true}
        }
      });

      var data = [];
      var series = 6;
      for (var i = 0; i < series; i++) {
        data[i] = {label: "Series" + (i + 1), data: Math.floor(Math.random() * 100) + 1}
      }
      jQuery.plot(jQuery("#piechart1"), data, {
        colors: ['#b9d6fd', '#fdb5b5', '#c9fdb5', '#f9b5fd', '#d7b5fd', '#bd9b33'],
        series: {
          pie: {show: true}
        }
      });

      var data = [];
      var series = 6;
      for (var i = 0; i < series; i++) {
        data[i] = {label: "Series" + (i + 1), data: Math.floor(Math.random() * 100) + 1}
      }
      jQuery.plot(jQuery("#piechart2"), data, {
        colors: ['#b9d6fd', '#fdb5b5', '#c9fdb5', '#f9b5fd', '#d7b5fd', '#bd9b33'],
        series: {
          pie: {show: true}
        }
      });

      var data = [];
      var series = 6;
      for (var i = 0; i < series; i++) {
        data[i] = {label: "Series" + (i + 1), data: Math.floor(Math.random() * 100) + 1}
      }
      jQuery.plot(jQuery("#piechart3"), data, {
        colors: ['#b9d6fd', '#fdb5b5', '#c9fdb5', '#f9b5fd', '#d7b5fd', '#bd9b33'],
        series: {
          pie: {show: true}
        }
      });

      var data = [];
      var series = 6;
      for (var i = 0; i < series; i++) {
        data[i] = {label: "Series" + (i + 1), data: Math.floor(Math.random() * 100) + 1}
      }
      jQuery.plot(jQuery("#piechart4"), data, {
        colors: ['#b9d6fd', '#fdb5b5', '#c9fdb5', '#f9b5fd', '#d7b5fd', '#bd9b33'],
        series: {
          pie: {show: true}
        }
      });

      var data = [];
      var series = 6;
      for (var i = 0; i < series; i++) {
        data[i] = {label: "Series" + (i + 1), data: Math.floor(Math.random() * 100) + 1}
      }
      jQuery.plot(jQuery("#piechart5"), data, {
        colors: ['#b9d6fd', '#fdb5b5', '#c9fdb5', '#f9b5fd', '#d7b5fd', '#bd9b33'],
        series: {
          pie: {show: true}
        }
      });

      var data = [];
      var series = 6;
      for (var i = 0; i < series; i++) {
        data[i] = {label: "Series" + (i + 1), data: Math.floor(Math.random() * 100) + 1}
      }
      jQuery.plot(jQuery("#piechart6"), data, {
        colors: ['#b9d6fd', '#fdb5b5', '#c9fdb5', '#f9b5fd', '#d7b5fd', '#bd9b33'],
        series: {
          pie: {show: true}
        }
      });

      var data = [];
      var series = 6;
      for (var i = 0; i < series; i++) {
        data[i] = {label: "Series" + (i + 1), data: Math.floor(Math.random() * 100) + 1}
      }
      jQuery.plot(jQuery("#piechart7"), data, {
        colors: ['#b9d6fd', '#fdb5b5', '#c9fdb5', '#f9b5fd', '#d7b5fd', '#bd9b33'],
        series: {
          pie: {show: true}
        }
      });

      var data = [];
      var series = 6;
      for (var i = 0; i < series; i++) {
        data[i] = {label: "Series" + (i + 1), data: Math.floor(Math.random() * 100) + 1}
      }
      jQuery.plot(jQuery("#piechart8"), data, {
        colors: ['#b9d6fd', '#fdb5b5', '#c9fdb5', '#f9b5fd', '#d7b5fd', '#bd9b33'],
        series: {
          pie: {show: true}
        }
      });


      // bar graph
      var d2 = [];
      for (var i = 0; i <= 10; i += 1)
        d2.push([i, parseInt(Math.random() * 30)]);

      var stack = 2, bars = true, lines = false, steps = false;
      jQuery.plot(jQuery("#bargraph3"), [d2], {
        series: {
          stack: stack,
          lines: {show: lines, fill: true, steps: steps},
          bars: {show: bars, barWidth: 0.6}
        },
        grid: {hoverable: true, clickable: true, borderColor: '#bbb', borderWidth: 1, labelMargin: 10},
        colors: ["#06c", "#bd9b33", "#ad8df9"]
      });
      // bar graph
      var d5 = [];
      for (var i = 0; i <= 10; i += 1)
        d5.push([i, parseInt(Math.random() * 30)]);

      var stack = 2, bars = true, lines = false, steps = false;
      jQuery.plot(jQuery("#bargraph9"), [d5], {
        series: {
          stack: stack,
          lines: {show: lines, fill: true, steps: steps},
          bars: {show: bars, barWidth: 0.6}
        },
        grid: {hoverable: true, clickable: true, borderColor: '#bbb', borderWidth: 1, labelMargin: 10},
        colors: ["#86B402"]
      });
    </script>
    <!-- begin olark code -->
    <script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark || (function (c) {
        var f = window, d = document, l = f.location.protocol == "https:" ? "https:" : "http:", z = c.name, r = "load";
        var nt = function () {

          f[z] = function () {

            (a.s = a.s || []).push(arguments)
          };
          var a = f[z]._ = {
          }, q = c.methods.length;
          while (q--) {
            (function (n) {
              f[z][n] = function () {

                f[z]("call", n, arguments)
              }
            })(c.methods[q])
          }
          a.l = c.loader;
          a.i = nt;
          a.p = {
            0: +new Date};
          a.P = function (u) {

            a.p[u] = new Date - a.p[0]
          };
          function s() {

            a.P(r);
            f[z](r)
          }
          f.addEventListener ? f.addEventListener(r, s, false) : f.attachEvent("on" + r, s);
          var ld = function () {
            function p(hd) {

              hd = "head";
              return["<", hd, "></", hd, "><", i, ' onl' + 'oad="var d=', g, ";d.getElementsByTagName('head')[0].", j, "(d.", h, "('script')).", k, "='", l, "//", a.l, "'", '"', "></", i, ">"].join("")
            }
            var i = "body", m = d[i];
            if (!m) {

              return setTimeout(ld, 100)
            }
            a.P(1);
            var j = "appendChild", h = "createElement", k = "src", n = d[h]("div"), v = n[j](d[h](z)), b = d[h]("iframe"), g = "document", e = "domain", o;
            n.style.display = "none";
            m.insertBefore(n, m.firstChild).id = z;
            b.frameBorder = "0";
            b.id = z + "-loader";
            if (/MSIE[ ]+6/.test(navigator.userAgent)) {

              b.src = "javascript:false"
            }
            b.allowTransparency = "true";
            v[j](b);
            try {

              b.contentWindow[g].open()
            } catch (w) {

              c[e] = d[e];
              o = "javascript:var d=" + g + ".open();d.domain='" + d.domain + "';";
              b[k] = o + "void(0);"
            }
            try {

              var t = b.contentWindow[g];
              t.write(p());
              t.close()
            } catch (x) {

              b[k] = o + 'd.write("' + p().replace(/"/g, String.fromCharCode(92) + '"') + '");d.close();'
            }
            a.P(2)
          };
          ld()
        };
        nt()
      })({
        loader: "static.olark.com/jsclient/loader0.js", name: "olark", methods: ["configure", "extend", "declare", "identify"]});

      /* custom configuration goes here (www.olark.com/documentation) */

      olark.identify('7580-162-10-3140');/*]]>*/</script><noscript><a href="https://www.olark.com/site/7580-162-10-3140/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com/?welcome" title="Olark live chat software">Olark live chat software</a></noscript>

    <!-- end olark code -->
    <script>
      /// slider with fixed minimum
      jQuery("#slider6").slider({
        range: "min",
        value: 37,
        min: 1,
        max: 100,
        slide: function (event, ui) {
          jQuery("#amount6").text("$" + ui.value);
        }
      });
      jQuery("#amount6").text("$" + jQuery("#slider6").slider("value"));

      // slider with fixed minimum
      jQuery("#slider7").slider({
        range: "min",
        value: 37,
        min: 1,
        max: 100,
        slide: function (event, ui) {
          jQuery("#amount7").text("$" + ui.value);
        }
      });
      jQuery("#amount7").text("$" + jQuery("#slider7").slider("value"));

      // slider with fixed minimum
      jQuery("#slider5").slider({
        range: "min",
        value: 37,
        min: 1,
        max: 100,
        slide: function (event, ui) {
          jQuery("#amount5").text("$" + ui.value);
        }
      });
      jQuery("#amount5").text("$" + jQuery("#slider5").slider("value"));

      // slider with fixed minimum
      jQuery("#slider4").slider({
        range: "min",
        value: 37,
        min: 1,
        max: 100,
        slide: function (event, ui) {
          jQuery("#amount4").text("$" + ui.value);
        }
      });
      jQuery("#amount4").text("$" + jQuery("#slider4").slider("value"));

    </script>

    <script type="text/javascript">
      window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer",
                {
                  data: [{
                      type: "column",
                      color: "#cb4b4b",
                      dataPoints: [
                        {label: "Sales", y: 18},
                        {label: "HR", y: 29},
                        {label: "Finance", y: 40},
                        {label: "IT", y: 34},
                        {label: "Controller", y: 24}

                      ]
                    },
                    {
                      type: "column",
                      color: "#afd8f8",
                      dataPoints: [
                        {label: "Sales ", y: 55},
                        {label: "HR", y: 59},
                        {label: "Finance ", y: 60},
                        {label: "IT ", y: 14},
                        {label: "Controller", y: 4}
                      ]
                    },
                    {
                      type: "column",
                      color: "#edc240",
                      dataPoints: [
                        {label: "Sales  ", y: 30},
                        {label: "HR ", y: 9},
                        {label: "Finance ", y: 21},
                        {label: "IT ", y: 64},
                        {label: "Controller", y: 34}
                      ]
                    }
                  ]
                });

        chart.render();


      }

    </script>
  </body>
</html>
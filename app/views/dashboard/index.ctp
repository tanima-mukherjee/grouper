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
                <br />
                
                <h4 class="widgettitle1">Dashboard Coming Soon !!</h4>
                <!--widgetcontent-->
 
              </div><!--span8-->
              <!--span4-->
            </div><!--row-fluid-->
          </div><!--contentinner-->
        </div><!--maincontent-->

      </div><!--mainright-->
      <!-- END OF RIGHT PANEL -->

      <div class="clearfix"></div>

      <div class="footer">
        <div class="footerleft">Ogma Conceptions</div>
        <div class="footerright">&copy; Copyrights 2017</div>
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
// General site functionality
/*function adjustHeader(){
 
  var head = $('.header-wrap');
  if($(document).scrollTop() > 10)
  {
     if(!head.hasClass('small'))
     {
       head.addClass('small');
     }
  }
  else
  {
     if(head.hasClass('small'))
     {
       head.removeClass('small');
     }
  }

}

$(window).on('scroll', function(){
	adjustHeader();
});*/
/*---------------------------------------------*
     * image gallery popup
---------------------------------------------*/
$(document).ready(function() {
	$('.zoom-gallery').magnificPopup({
		delegate: 'a.imgpopup',
		type: 'image',
		closeOnContentClick: false,
		closeBtnInside: false,
		mainClass: 'mfp-with-zoom mfp-img-mobile',
		gallery: {
			enabled: true
		},
		zoom: {
			enabled: true,
			duration: 300, // don't foget to change the duration also in CSS
			opener: function(element) {
				return element.find('img');
			}
		}
		
	});
});
/*---------------------------------------------*
     * video page popup
---------------------------------------------*/
$(document).ready(function() {
	$('.popup-youtube, .popup-vimeo').magnificPopup({
	  delegate: 'a',
	  type: 'iframe',
	  mainClass: 'mfp-img-mobile',
	  removalDelay: 160,
	 gallery: {
		enabled: true,
		navigateByImgClick: true,
		preload: [0,1] // Will preload 0 - before current, and 1 after the current image
	  },

	  fixedContentPos: false
	});
});	

/*---------------------------------------------*
     * banner slide
---------------------------------------------*/

/* Demo Scripts for Bootstrap Carousel and Animate.css article
* on SitePoint by Maria Antonietta Perna
*/
(function( $ ) {

    //Function to animate slider captions 
    function doAnimations( elems ) {
        //Cache the animationend event in a variable
        var animEndEv = 'webkitAnimationEnd animationend';
        
        elems.each(function () {
            var $this = $(this),
                $animationType = $this.data('animation');
            $this.addClass($animationType).one(animEndEv, function () {
                $this.removeClass($animationType);
            });
        });
    }
    
    //Variables on page load 
    var $myCarousel = $('#carousel-example-generic'),
        $firstAnimatingElems = $myCarousel.find('.item:first').find("[data-animation ^= 'animated']");
        
    //Initialize carousel 
    $myCarousel.carousel();
    
    //Animate captions in first slide on page load 
    doAnimations($firstAnimatingElems);
    
    //Pause carousel  
    $myCarousel.carousel('pause');
    
    
    //Other slides to be animated on carousel slide event 
    $myCarousel.on('slide.bs.carousel', function (e) {
        var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
        doAnimations($animatingElems);
    });  
    
})(jQuery);

/*---------------------------------------------*
     * students Carousel
---------------------------------------------*/
$(document).ready(function() { 
	// Activate Carousel
	$("#studentsCarousel").carousel();

	// Enable Carousel Indicators
	$(".item").click(function(){
		$("#studentsCarousel").carousel(1);
	});
});


/*---------------------------------------------*
     * Blog Carousel
---------------------------------------------*/
$(document).ready(function() { 
	// Activate Carousel
	$("#blogCarousel").carousel();

	// Enable Carousel Indicators
	$(".item").click(function(){
		$("#blogCarousel").carousel(1);
	});
});


/*---------------------------------------------*
     * Blog Carousel
---------------------------------------------*/
$(document).ready(function() { 
	// Activate Carousel
	$("#blogCarousel").carousel();

	// Enable Carousel Indicators
	$(".item").click(function(){
		$("#blogCarousel").carousel(1);
	});
});

/*---------------------------------------------*
     * Logo Carousel
---------------------------------------------*/
$(document).ready(function() { 
  $("#owl-demo").owlCarousel({ 
      autoPlay: 5000, //Set AutoPlay to 3 seconds 
      items : 6,
      itemsDesktop : [1199,5],
      itemsDesktopSmall : [979,4]
 
  }); 
});

/*---------------------------------------------*
     * Form input style
     ---------------------------------------------*/

    $('#fancy-inputs input[type="text"]').blur(function () {
        if ($(this).val().length > 0) {
            $(this).addClass('white');
        } else {
            $(this).removeClass('white');
        }
    });
    $('#fancy-inputs textarea').blur(function () {
        if ($(this).val().length > 0) {
            $(this).addClass('white');
        } else {
            $(this).removeClass('white');
        }
    });
	


/*---------------------------------------------*
     * Smooth scroll to anchor
---------------------------------------------*/

$(function() {
  $('a.topbg[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});
	
	
	
 /*$('.region_link').click(function () 
 {
		 alert("fsdf");
		 if $('.region_link').hasClass('region_link')
		 {            
			$("#regionslinks").addClass("hidden");
			$("#subregionslinks").removeClass("hidden");
        } 
		else 
		{
            
			$("#regionslinks").removeClass("hidden");
			$("#subregionslinks").addClass("hidden");
        }
    });*/
	

 /*$(document).ready(function() {
  var stickyNavTop = $('.calendar-heading').offset().top;

  var stickyNav = function(){
    var scrollTop = $(window).scrollTop();

    if (scrollTop > stickyNavTop) { 
      $('.calendar-heading').addClass('sticky');
    } else {
      $('.calendar-heading').removeClass('sticky'); 
    }
  };

  stickyNav();

  $(window).scroll(function() {
    stickyNav();
  });
});*/
	
	
	
	
	
	
	







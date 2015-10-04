jQuery(document).ready(function(jQuery) {

	jQuery(window).load(function() {		 
		
		runAnimations();
		if( jQuerywindow .width() > 800){
			jQuery('.parallax-scroll1').parallax("50%", 0.5);
			jQuery('.parallax-scroll2').parallax("50%", 0.5);
			jQuery('.parallax-scroll3').parallax("50%", 0.5);		 
		}
	})
	
	jQuerywindow = jQuery(window);
	if( jQuerywindow .width() > 800){
		jQuery('.parallax-scroll1').parallax("50%", 0.5);
		jQuery('.parallax-scroll2').parallax("50%", 0.5);
		jQuery('.parallax-scroll3').parallax("50%", 0.5);		 
	}
	
	
	jQuery('.main-navigation').onePageNav({
		
		filter: ':not(.external)',
	    currentClass: 'current',
		scrollOffset: 85,
	    scrollSpeed: 1000,
	    scrollThreshold: 0.5 ,
	    easing: 'easeInOutExpo'
	   
	});
	
	wow = new WOW(
	    {
	      boxClass:     'wow',      // default
	      animateClass: 'animated', // default
	      offset:       0,          // default
	      mobile:       false,       // default
	      live:         true        // default
	    }
	);
	
	wow.init();
	
	//--- fixed header on scroll
	var test = 0;
	
	var jQuerynavbar = jQuery('.navbar');
	var jQuerywhite_logo_img = jQuery('.white-logo-img');
	var jQuerydark_logo_img = jQuery('.dark-logo-img');
	
	function scrolled(test){
		
		if(test === 0){
			jQuerynavbar.stop().addClass("sticky-navbar");
			jQuerywhite_logo_img.fadeOut();
			jQuerydark_logo_img.fadeIn();
		}else{
			jQuerynavbar.stop().removeClass("sticky-navbar");
			jQuerydark_logo_img.fadeOut();
			jQuerywhite_logo_img.fadeIn();
		}
	}
	jQuery(document).on('click','.navbar-collapse.in',function(e) {
		if( jQuery(e.target).is('a') ) {
			jQuery(this).collapse('hide');
		}
	});
	
	if(jQuery(window).scrollTop() > 50){
		scrolled(test);
	}
	
	jQuery(window).scroll(function() {
		if (jQuery(document).scrollTop() > 50) {
	    	if(test===0){
	    		scrolled(test);
	    	}
	    	test=1;
	    } else {
	    	if(test===1){
	        	scrolled(test);        	
	        }
	        test = 0;
	    }
	}); 
	//--- end scroll
	
	jQuery('.curved-text').circleType({radius:200});
	
 
	jQuery("#features-slider").owlCarousel({
		items : 3,
		itemsDesktop : [1199,3],
	});
	
	jQuery("#team-slider").owlCarousel({
		items : 3,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [996,2],
		itemsTablet: [600,1],
		itemsMobile : false
	});

	jQuery("#testi-slider").owlCarousel({
		singleItem: true,
		slideSpeed : 400
	});


});

function runAnimations(){
	jQuery('.video-link img').removeClass('not-visible');
	jQuery('.video-link img').addClass('bounceIn  animated');
	jQuery('.feature-item').each(function(){
		jQuery(this).removeClass('not-visible');
		jQuery(this).addClass('fadeIn animated');
	});
	jQuery('.team-item').each(function(){
		jQuery(this).removeClass('not-visible');
		jQuery(this).addClass('fadeIn animated');
	});	 
	
	jQuery('#partners img').each(function(){
		jQuery(this).removeClass('not-visible');
		jQuery(this).addClass('bounceIn animated');
	});		
	 
}
/*  Script for the dt Slides 1.5.1 slideshow
	
	Copy "slideshow.js" from "/dt-slides/js/" to your theme's directory to replace
	the plugin's default slideshow script.
	
	Learn more about customizing the slideshow script for dt Slides: 
	http://www.jleuze.com/plugins/dt-slides/customizing-the-slideshow-script/
*/

// Set custom shortcut to avoid conflicts
var $j = jQuery.noConflict();

$j(document).ready(function() {

	// Get the slideshow options
	var $slidespeed      = parseInt( dtslidessettings.dtslideshowspeed );
	var $slidetimeout    = parseInt( dtslidessettings.dtslideshowduration );
	var $slideheight     = parseInt( dtslidessettings.dtslideshowheight );
	var $slidewidth      = parseInt( dtslidessettings.dtslideshowwidth );
	var $slidetransition = parseInt( dtslidessettings.dtslideshowtransition );
	var $slidenavigation = dtslidessettings.dtslideshownavigation;
	var $slideautoplay   = dtslidessettings.dtslideshowautoplay;
	
	var $pagination = false;
	var $control = false;
	var $autoplay = true;
	
	if($slidenavigation === "navboth"){
	$control = true;
	$pagination = true;
	}else if ($slidenavigation === "navprevnext"){
	$control = true;
	}else if ($slidenavigation === "navpaged") {
	$pagination = true;
	}
	
	if($slideautoplay === "on"){
		$autoplay = true;
	}else{
		$autoplay = false;
	}
	
	if(!flux.browser.supportsTransitions)
		alert("Flux Slider requires a browser that supports CSS3 transitions");
		
	window.f = new flux.slider('#slider', {
		autoplay: $autoplay,
		pagination: $pagination,
		controls: $control,
		captions: false,
		numb: $slidetransition,
		delay: $slidetimeout
	});
		
});
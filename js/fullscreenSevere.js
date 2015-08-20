// Content Fullscreener - mod for severe dementia patients
var timer;
var transitionTime = 0;
var slideShowAdvanceTime = 10000;
var forceFullscreen = false;
var originalButtonDiv = $('#buttonDiv').css('display');
if(!forceFullscreen) window.onload = function(){//window.onmousemove = function(){disableFullScreen();}
window.timer = setTimeout(function(){enableFullScreen();},transitionTime);advancePicture();}
function enableFullScreen(){
	$('#content').css('min-height','83%');
	$('#content').css('min-width','100%');
	$('#buttonDiv').css('display','none');
	$('#player').css('max-width','90%');
	$('#player').css('margin-right','10%');
	//$('#stopButton').css('animation','blinker 4s infinite');
	//$('#stopButton').css('-webkit-animation','blinker 4s infinite');
	}

function disableFullScreen(){
	$('#content').css('min-height','0%');
	$('#content').css('min-width','0%');
	$('#buttonDiv').css('display',originalButtonDiv);
	$('#stopButton').css('animation','none');
	$('#stopButton').css('-webkit-animation','none');
	$('#stopButton').css('opacity','1');
	$('#player').css('max-width','100%');
	$('#player').css('margin-right','0%');
	clearInterval(window.timer);
	window.timer = setTimeout(function(){enableFullScreen()},transitionTime);}
	
function advancePicture(){ if (window.pictures.length > 0) {
			if (++window.picturesCount == window.pictures.length) window.picturesCount = 0;
			$('#content').animate({opacity: 0},500);
			setTimeout(function(){
			$('#content').css('background-image','url(\"'+window.pictures[window.picturesCount]+'\")');
			$('#content').css('background-size','contain');
			$('#content').css('background-position','center');
			$('#content').css('background-repeat','no-repeat');
			$('#content').animate({opacity: 1},500);},600);
setTimeout(function(){advancePicture();},slideShowAdvanceTime);}}


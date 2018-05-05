// Content Fullscreener
var timer;
var transitionTime = 15000;
var slideShowAdvanceTime = 15000;
//var forceFullscreen = false;
var originalButtonDiv = $('#buttonDiv').css('display');
var originalContentHeight = $('#content').css('height');
if (originalButtonDiv === undefined) originalButtonDiv = "";
var player = document.getElementById('player');
//if(!forceFullscreen)
window.onload = function(){advancePicture(); enableFullScreen(false);}
	//window.onmousedown = function(){disableFullScreen();}
//window.timer = setTimeout(function(){enableFullScreen();},transitionTime);

function toggleFullScreen(){
	$('#content').css('min-height','0%');
	$('#content').css('min-width','0%');
	$('#content').css('height',originalContentHeight);
	$('#buttonDiv').css('display',originalButtonDiv);
	$('#player').css('max-width','100%');
	$('#stopButton').css('animation','none');
	$('#stopButton').css('-webkit-animation','none');
	$('#stopButton').css('opacity','1');
	$('#altPauseButton').css('display','none');
	$('#buttonDisabler').css('display','block');
	$('#music_record').css('animation','');
	setTimeout(function(){if (document.getElementById('pauseButton').onclick == disableFullScreen) enableFullScreen(true);},transitionTime);
	
}

function enableFullScreen(noPlayPause = false){
	$('#content').css('min-height','83%');
	$('#content').css('height','90%');
	$('#content').css('width','74%');
	$('#content').css('margin','0 auto');
	$('#buttonDiv').css('display','none');
	$('#player').css('max-width','100%');
	$('#playbackControl').css('display','none');
	if(!noPlayPause) $('#music_record').css('animation_play_state','running');
	if(!noPlayPause) $('#music_thumb').css('animation_play_state','running');
	$('#music_record').css('animation','none');
	if (document.getElementById('pauseButtonText') != undefined) document.getElementById('pauseButtonText').innerHTML = "<img class=\"button-icon\" src=\"Pause.png\"><br/>Pause";
	if (document.getElementById('pauseButton') != undefined) document.getElementById('pauseButton').onclick = disableFullScreen;
	$('#buttonDisabler').css('display','none');
	$('#altPauseButton').css('display','block');
	if (player != undefined && !noPlayPause) player.play();
	$('.blink').css('animation','none');
	//$('#stopButton').css('-webkit-animation','blinker 4s infinite');
	}

function disableFullScreen(){
	$('#content').css('min-height','0%');
	$('#content').css('min-width','0%');
	$('#content').css('height',originalContentHeight);
	$('#buttonDiv').css('display',originalButtonDiv);
	$('#player').css('max-width','100%');
	$('#stopButton').css('animation','none');
	$('#stopButton').css('-webkit-animation','none');
	$('#stopButton').css('opacity','1');
	$('#playbackControl').css('display','block');
	$('#altPauseButton').css('display','none');
	$('#buttonDisabler').css('display','block');
	$('#music_record').css('animation_play_state','paused');
	$('#music_thumb').css('animation_play_state','paused');
    $('.blink').css('animation','blinker 4s ease infinite alternate');
	if (document.getElementById('pauseButtonText') != undefined) document.getElementById('pauseButtonText').innerHTML = "<img class=\"button-icon\" src=\"Play.png\"><br/>Play";
	if (document.getElementById('pauseButton') != undefined) document.getElementById('pauseButton').onclick = function(){enableFullScreen(false)};
	if (player != undefined) player.pause();
	//clearInterval(window.timer);
	if (window.pictures != undefined) window.timer = setTimeout(function(){enableFullScreen(true)},transitionTime);
}
	
function advancePicture(){ if (window.pictures != undefined && window.pictures.length > 0) {
			if (++window.picturesCount == window.pictures.length) window.picturesCount = 0;
			$('#content').animate({opacity: 0},500);
			setTimeout(function(){
			$('#content').css('background-image','url(\"'+window.pictures[window.picturesCount]+'\")');
			$('#content').css('background-size','contain');
			$('#content').css('background-position','center');
			$('#content').css('background-repeat','no-repeat');
			$('#content').animate({opacity: 1},500);},600);
setTimeout(function(){advancePicture();},slideShowAdvanceTime);}}


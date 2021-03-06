// Content Fullscreener
var timer;
var advance = true;
var transitionTime = 15000;
var slideShowAdvanceTime = 15000;
//var forceFullscreen = false;
var musicmode = false;
var originalButtonDiv = $('#buttonDiv').css('display');
var originalContentHeight = $('#content').css('height');
if (originalButtonDiv === undefined) originalButtonDiv = "";
var player = document.getElementById('player');
if(player==null) player = document.getElementById('slideShowPlayer');
//if(!forceFullscreen)
window.onload = function(){advancePicture(); enableFullScreen(false);}
	//window.onmousedown = function(){disableFullScreen();}
//window.timer = setTimeout(function(){enableFullScreen();},transitionTime);

function toggleFullScreen(){
    if(!severeMode){
	$('#content').css('min-height','0%');
	$('#content').css('min-width','0%');
	$('#content').css('height',originalContentHeight);
	$('#buttonDiv').css('display',originalButtonDiv);
	$('#player').css('max-width','100%');
    $('#altPauseButton').css('display','none');
    $('#record_container').css('width','calc(64%*9/16)');}
	$('#stopButton').css('animation','none');
	$('#stopButton').css('-webkit-animation','none');
	$('#stopButton').css('opacity','1');

	//$('#buttonDisabler').css('display','block');
	$('#music_record').css('animation','');
    document.getElementById('clicker').play();
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
	if(!noPlayPause) $('#music_record').css('animationPlayState','running');
	if(!noPlayPause) $('#music_thumb').css('animationPlayState','running');
	$('#music_record').css('animation','none');
	if (document.getElementById('pauseButtonText') != undefined) document.getElementById('pauseButtonText').innerHTML = "<img class=\"button-icon\" src=\"Pause.png\"><br/>Pause";
	if (document.getElementById('pauseButton') != undefined) document.getElementById('pauseButton').onclick = disableFullScreen;
    if (document.getElementById('pauseButtonText') != undefined) document.getElementById('pauseButtonText').onclick = disableFullScreen;
	$('#buttonDisabler').css('display','none');
	$('#record_container').css('width','calc(100%*9/16)');
	$('#altPauseButton').css('display','block');
	if (player != undefined && !noPlayPause) {player.play(); document.getElementById('clicker').play();}
	advance = true; //advancePicture();
	$('.blink').css('animation','none');
	//$('#stopButton').css('-webkit-animation','blinker 4s infinite');
	}

	function musicDisableFullScreen(){disableFullScreen(true);}

function disableFullScreen(mmode = false){
    document.getElementById('clicker').play();
    if(mmode === true && false ) musicmode = true;
    if(musicmode === true && false) {
	$('#content').css('min-height','0%');
	$('#content').css('min-width','0%');
	$('#content').css('height',originalContentHeight);
	//$('#buttonDiv').css('display',originalButtonDiv);
	//$('#player').css('max-width','100%');
        if (!severeMode) $('#record_container').css('width','calc(64%*9/16)');
    }
	$('#stopButton').css('animation','none');
	$('#stopButton').css('-webkit-animation','none');
	$('#stopButton').css('opacity','1');
	$('#playbackControl').css('display','block');
	if(!severeMode) $('#altPauseButton').css('display','none');
	//$('#buttonDisabler').css('display','block');
	$('#music_record').css('animationPlayState','paused');
	$('#music_thumb').css('animationPlayState','paused');
	$('#music_record').css('animation','');

    $('.blink').css('animation','blinker 4s ease infinite alternate');
	if (document.getElementById('pauseButtonText') != undefined) document.getElementById('pauseButtonText').innerHTML = "<img class=\"button-icon\" src=\"Play.png\"><br/>Play";
	if (document.getElementById('pauseButton') != undefined) document.getElementById('pauseButton').onclick = function(){enableFullScreen(false)};
    if (document.getElementById('pauseButtonText') != undefined) document.getElementById('pauseButtonText').onclick = function(){enableFullScreen(false)};
	if (player != undefined) player.pause();
	advance = false;
	//clearInterval(window.timer);
	//if (window.pictures != undefined) window.timer = setTimeout(function(){enableFullScreen(true)},transitionTime);
}
	setInterval(function(){advancePicture()},slideShowAdvanceTime);
function advancePicture(){ if (window.pictures != undefined && window.pictures.length > 0 && advance) {
			if (++window.picturesCount == window.pictures.length) window.picturesCount = 0;
			$('#content').animate({opacity: 0},500);
			setTimeout(function(){
			$('#content').css('background-image','url(\"'+window.pictures[window.picturesCount]+'\")');
			$('#content').css('background-size','contain');
			$('#content').css('background-position','center');
			$('#content').css('background-repeat','no-repeat');
			$('#content').animate({opacity: 1},500);},600);
			//setTimeout(function(){setTimeout(function(){advancePicture();},slideShowAdvanceTime*0.01)},slideShowAdvanceTime*0.99);
}}


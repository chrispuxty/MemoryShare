<?php include "../config.php";
$query = mysql_query("SELECT * FROM patients WHERE id=".$patientID) or die(mysql_error());
$params = mysql_fetch_assoc($query);
$wakeHour = floor($params['wakeTime']/60); $wakeMinute = $params['wakeTime'] % 60;
$sleepHour = floor($params['sleepTime']/60); $sleepMinute = $params['sleepTime'] % 60;

echo "var screensaver; var lastResetTime; var dayTimeDelay = 1800000; var 
nightTimeDelay = 1200000; var forcedScreensaver; var currDelay;

//nightTimeDelay = 10000; //TESTING ONLY


lastResetTime = Date.now(); var morningCutoff = new Date(); var eveningCutoff = new Date(); var pageLoadTime = lastResetTime;
morningCutoff.setHours(".$wakeHour.",".$wakeMinute.",0,0); eveningCutoff.setHours(".$sleepHour.",".$sleepMinute.",0,0);
morningCutoff = morningCutoff.valueOf(); eveningCutoff = eveningCutoff.valueOf();
var nighttimeMode = (lastResetTime < morningCutoff || lastResetTime > eveningCutoff);
currDelay = (!nighttimeMode?dayTimeDelay:nightTimeDelay);

forcedScreenSaver = setTimeout(function(){window.open('preload.php','_self');}, getForcedScreensaverTimeRemaining()); //8PM or 20min idle after 8PM forced sleep

function setScreenSaver(preTimeout){clearTimeout(screensaver); var delay; var destination; //window.onmousemove = setScreenSaver(0);

if (nighttimeMode) {delay = nightTimeDelay; destination = 'preload.php';} else {delay = dayTimeDelay; destination = 'index.php';}
if (preTimeout > 0) delay = preTimeout; if (delay < 0) delay = 0;
screensaver = setTimeout(function(){window.open(destination,'_self');}, delay);
currDelay = delay;
}

function setScreenSaverInNightTimeOnly(preTimeout){if(nighttimeMode) setScreenSaver(preTimeout);}

function getScreensaverTimeRemaining(){return lastResetTime - Date.now() + currDelay;}

function getForcedScreensaverTimeRemaining(){return pageLoadTime - Date.now() + (nighttimeMode?nightTimeDelay:eveningCutoff - pageLoadTime);}



/*
Between 8AM-8PM: 30min delay (re)started only by autoplay hook or any menu, go to index.php and from there to screensaver
Between 8PM-8AM: 20min delay (re)started by page load anywhere where timer is not already running
At 8PM: Forcibly go to screensaver
At 8AM: From screensaver go to index.php


*/";
?>
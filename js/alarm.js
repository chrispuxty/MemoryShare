var alarm;
alarm = new XMLHttpRequest();
function checkAlarm(){
alarm.open("GET","./bin/Unix/readAlarm.php",true);
alarm.send();}
alarm.onreadystatechange=function(){if (alarm.readyState==4 && alarm.status==200){if(Number(alarm.responseText)>1) randomAutoplay(); setTimeout(function(){checkAlarm();},5000);}}  

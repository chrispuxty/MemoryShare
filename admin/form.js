function reveal(){
var lol = document.getElementById("patientSelector");
if(lol.selectedIndex == lol.length - 1)
{document.getElementById("nameBox").style.display="inline";
 document.getElementById("nameBox").focus();}
else document.getElementById("nameBox").style.display="none";
}

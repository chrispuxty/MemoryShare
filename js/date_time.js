// JavaScript Document
function date_time(id)
{
        date = new Date;
        year = date.getFullYear();
        month = date.getMonth();
        months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        d = date.getDate();
        day = date.getDay();
        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        h = date.getHours();
		ampm = "AM";
	if(h>=12) ampm = "PM";        
	if(h>12) //Edit for 12hr Time - revert to (h<10) - h = "0"+h;
        
                h -= 12;
				
        
        m = date.getMinutes();
        if(m<10)
        {
                m = "0"+m;
        }
        s = date.getSeconds();
        if(s<10)
        {
                s = "0"+s;
        }
        //result = ''+days[day]+' '+months[month]+' '+d+' '+year+' '+h+':'+m+':'+s+' '+ampm;
		result = h+':'+m+':'+s+' '+ampm+' '+days[day]+', '+d+' '+months[month]+' '+year;
        document.getElementById(id).innerHTML = result;
        setTimeout('date_time("'+id+'");','1000');
        return true;
}
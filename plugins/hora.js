function showTime(){
        var time = new Date();
        var hour = time.getHours();
        var minute = time.getMinutes();
        var second = time.getSeconds();
       
        if(hour<10) hour = "0"+hour;
        if(minute<10) hour = "0"+minute;
        if(second<10) hour = "0"+second;
        var tempo = hour+":"+minute+":"+second;
       
        document.getElementById("timer").innerHTML=tempo;
}
function initTime(){
        setInterval(showTime, 1000);   
}
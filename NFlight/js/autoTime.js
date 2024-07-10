//add zero if number less than 10
function timeValLessThanTen(val){
    if(val<10){
        return '0'+val;
    }else{
        return val;
    }
}
//auto time
function getTime(){
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth();
    month+=1;
    month = timeValLessThanTen(month);
    let day = date.getDate();
    day = timeValLessThanTen(day);
    let hour = date.getHours();
    hour = timeValLessThanTen(hour);
    let minutes = date.getMinutes();
    minutes = timeValLessThanTen(minutes);
    let seconds = date.getSeconds();
    seconds = timeValLessThanTen(seconds);
    let timeText = year+"-"+month+"-"+day+" "+hour+":"+minutes+":"+seconds;
    document.getElementsByClassName("autoTime")[0].innerHTML = timeText;
}
setInterval(getTime,1000);
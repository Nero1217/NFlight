function nowCommon(){
    now = new Date();
    now_output = "";
    now_year = now.getFullYear();
    now_month = timeValLessThanTen(now.getMonth()+1); 
    now_date = timeValLessThanTen(now.getDate());
    now_hour = timeValLessThanTen(now.getHours()) ;
    now_minute = timeValLessThanTen(now.getMinutes());
    now_second = timeValLessThanTen(now.getSeconds());
    now_output = now_year+'-'+now_month+'-'+now_date+" "+now_hour+":"+now_minute+":"+now_second;
}
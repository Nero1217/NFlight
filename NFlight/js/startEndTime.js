//1.準備startime & endtime: 前後12小時
// prepare the starttime and endtime for later ajax
function starttimeAction(){
    let date = new Date(); 
    let year = date.getFullYear();
    let month = date.getMonth();
    month+=1;
    let day = date.getDate();
    let hour = date.getHours();
    let minutes = date.getMinutes();
    hour -= 12;
    if(hour<0){
        hour = hour+24;
        day -=1;
        if(day<1){
            day=new Date(year, month, 0).getDate();
            month -= 1;
            if(month<1){
                month=12;
                year-=1;
            }
        }
    }
    return `${year}-${month}-${day} ${hour}:${minutes} `;  
}
function endtimeAction(){
    //reset time based on initial let date
    let date = new Date(); 
    let year = date.getFullYear();
    let month = date.getMonth();
    month+=1;
    let day = date.getDate();
    let hour = date.getHours();
    let minutes = date.getMinutes();
    hour += 12;
    if(hour>=24){
        hour = hour-24;
        day +=1;
        let max_day=new Date(year, month, 0).getDate();
        if(day>max_day){
            day=1;
            month += 1;
            if(month>12){
                month=1;
                year+=1;
            }
        }
    }
    return `${year}-${month}-${day} ${hour}:${minutes}`;
}

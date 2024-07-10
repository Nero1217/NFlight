<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--tab icon-->
    <link rel="icon" type="image/x-icon" href="./images/logo.png">
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="./uikit/css/uikit.min.css">
    <link rel="stylesheet" href="./uikit/css/uikit-rtl.min.css">
    <!-- UIkit JS -->
    <script src="./uikit/js/uikit.min.js"></script>
    <script src="./uikit/js/uikit-icons.min.js"></script>
    <!--jQuery-->
    <script src="./js/jQuery/jquery-3.7.1.min.js"></script>
    <!-- reset all default style -->
    <link rel="stylesheet" href="./css/reset.css"/>
    <link rel="stylesheet" href="./css/common.css"/>
    <!-- css -->
    <link rel="stylesheet" href="./css/info.css"/>
    <!-- js -->
    <script src="./js/toggleMenu.js" defer></script>
    <script src="./js/watermark.js" defer></script>
    <!-- time format-->
    <script src="./js/nowCommon.js" defer></script>
    <script src="./js/autoTime.js" defer></script>
    <script src="./js/timeLessThanZero.js"></script>
    <script src="./js/startEndTime.js" defer></script>
    <title>MP Real-Time Deployment</title>
</head>
<body>
    <div id="main" class="main">
        <!-- 版心 -->
        <div class="main-container">
            <span class="autoTime"></span>
            <div class="toggleMenu">
                <div class="close">
                    <span class="icon" uk-icon="close"></span>
                </div>
                <div class="username">
                    <span class="username-container">Nero</span>
                </div>
                <div class="back" onclick="backAction()">
                    <span class="icon" uk-icon="settings"></span>
                    <span class="text">Back</span>
                </div>
                <div class="logout" onclick="logoutAction()">
                    <span class="icon" uk-icon="sign-out"></span>
                    <span class="text">Logout</span>
                </div>
            </div>
            <!-- top navigation bar -->
            <div class="topNav clearfix">
                <div class="toggle leftfix">
                    <span width="100px" uk-icon="menu"></span>
                </div>
                <div class="logo leftfix" onclick="(()=>{window.location.reload();})()">
                    <img src="./images/logo.png" alt=""/>
                </div>
            </div>
            <!-- manpowerInfo -->
            <div class="manpowerInfo">
                <div class="manpowerInfo-flight-grid clearfix">
                    <div class="manpowerInfo-flight leftfix">
                        <div class="flightRow commonRow clearfix">
                            <div class="flightTitle commonTitle leftfix">Flight</div>
                            <div class="flightVal commonVal leftfix"></div>
                        </div>
                        <div class="originRow commonRow clearfix">
                            <div class="originTitle commonTitle leftfix">Origin/Dest</div>
                            <div class="originVal commonVal leftfix"></div>
                        </div>
                        <div class="estRow commonRow clearfix">
                            <div class="estTitle commonTitle leftfix">STA/STD</div>
                            <div class="estVal commonVal leftfix"></div>
                        </div>
                        <div class="statusRow commonRow clearfix">
                            <div class="statusTitle commonTitle leftfix">Status</div>
                            <div class="statusVal commonVal leftfix"></div>
                        </div>
                        <div class="dateRow commonRow clearfix">
                            <div class="dateTitle commonTitle leftfix">Date</div>
                            <div class="dateVal commonVal leftfix"></div>
                        </div>
                        <div class="acTypeRow commonRow clearfix">
                            <div class="acTypeTitle commonTitle leftfix">Aircraft Type</div>
                            <div class="acTypeVal commonVal leftfix"></div>
                        </div>
                        <div class="acRegRow commonRow clearfix">
                            <div class="acRegTitle commonTitle leftfix">Aircraft Registration</div>
                            <div class="acRegVal commonVal leftfix"></div>
                        </div>
                        <div class="baggageRecRow commonRow clearfix">
                            <div class="baggageRecTitle commonTitle leftfix">Baggage Reclaim</div>
                            <div class="baggageRecVal commonVal leftfix"></div>
                        </div>
                        <div class="parkingStandRow commonRow clearfix">
                            <div class="parkingStandTitle commonTitle leftfix">Parking Stand</div>
                            <div class="parkingStandVal commonVal leftfix"></div>
                        </div>
                    </div> 
                </div>
                
                <div class="manpowerInfo-manpower">
                    <div class="spRow commonRow positionRelative clearfix">
                        <div class="infoTask_spinner-box">
                            <div class="infoTask_three-quarter-spinner"></div>
                        </div>
                        <div class="spTitle commonTitle leftfix">SP</div>
                    </div>
                    <div class="ldRow commonRow positionRelative clearfix">
                         <div class="infoTask_spinner-box">
                            <div class="infoTask_three-quarter-spinner"></div>
                        </div>
                        <div class="ldTitle commonTitle leftfix">LD</div>
                    </div>
                    <div class="eoRow commonRow positionRelative clearfix">
                         <div class="infoTask_spinner-box">
                            <div class="infoTask_three-quarter-spinner"></div>
                        </div>
                        <div class="eoTitle commonTitle leftfix">EO</div>
                    </div>
                    <div class="rwRow commonRow positionRelative clearfix">
                         <div class="infoTask_spinner-box">
                            <div class="infoTask_three-quarter-spinner"></div>
                        </div>
                        <div class="rwTitle commonTitle leftfix">RW</div>
                    </div>
                </div>
                <div class="refreshBtn">
                    <button class="uk-button uk-button-default">Refresh</button>
                </div>
                <div class="backBtn" onclick="backAction()">
                    <button class="uk-button uk-button-primary">Back</button>
                </div>
            </div>
        </div>
    </div>
    <!-- loading animation -->
    <div id="spinner-box">
        <div class="three-quarter-spinner"></div>
    </div>
</body> 
<script>
    //global value
    let flight = '';
    let searchParams = new URLSearchParams(document.location.search);
    let params_page = searchParams.get("p");
    let page = ""; //page types: login, search, info
    let watermark_flag = false; //watermark background
    let now, now_year ,now_month,now_date ,now_hour ,now_minute ,now_second ,now_output; //time
    //data
    let dataObj = { 
        'flight': [], //saved all flights
        'fbreak':[], //dataObj['fbreak'] saved the workers tasks and break
        'card':[],
        'current':null
    };
    //reqeust
    let request = {
        'member':null,
        'fbreak':null,
        'card':null
    };
    let username = "Neroyau";
    //greyDiv text
    let greyDivTimer = null;
    window.onload = ()=>{
        //scrollToTop
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
        let starttime = '2024-5-5 04:00';
        let endtime = '2024-5-6 04:00';
        let searchParams = new URLSearchParams(document.location.search); //get params
        let params_page = searchParams.get("p");
        flight = searchParams.get("flight");
        let page ='';
        if(params_page!==''&&params_page!==null){  //keep the page by query string
            page = params_page;
        }
        let department_list = ['sp','ld','eo','rw'];
        //turn page
        if(params_page!==''&&params_page!==null){
            page = params_page;
        }
        //if unknown error found, back to search page
        try{
            //set the time
            nowCommon();
            
            turnPage(page);

            //turn page function
            function turnPage(newPage){
                page = newPage;
                //detect page
                if(page=='info'){
                    manpower_info();
                }
            }

            //page: manpower_info
            function manpower_info(){
                info_member();
                
                function info_fbreak(){
                    //1.1 ajax request2: get manpower tasks and break
                    request['fbreak'] = $.ajax({
                        type: "POST",
                        url:window.location.href.includes('localhost') || window.location.href.includes('127.0.0.1')? "./api/index.php":"",
                        data: {
                            reqType:"info_fbreak",  
                            starttime:starttime,
                            endtime:endtime
                        }
                    }).done(function (data) {
                        // console.log(JSON.parse(data));
                        if(JSON.parse(data).status=='success'){
                            dataObj['fbreak']=JSON.parse(data).message;
                            for(let x=0; x<document.getElementsByClassName("commonChildNum").length;x++){
                                let res = document.getElementsByClassName("commonChildNum")[x].getElementsByTagName("a")[0].innerText;
                                if(dataObj['fbreak'].hasOwnProperty(res)){
                                    let fb = dataObj['fbreak'][res]['fb'];
                                    let turnAround ="";
                                    let fb_final ="";
                                    let fb_completed = null;
                                    let stand ="";
                                    for(let y=0;y<fb.length;y++){
                                        if(fb[y].indexOf(flight)>-1){
                                            let fb_flight =fb[y].substring(0,fb[y].indexOf('@'));

                                            //turnAround
                                            if(fb_flight.indexOf('/')>-1 && fb_flight ==flight){
                                                turnAround = "T";
                                            }else if(fb_flight.indexOf('/')>-1 && flight.indexOf('/')<0){
                                                let inbound = fb_flight.substring(0,fb_flight.indexOf('/'));
                                                let outbound = fb_flight.substring(fb_flight.indexOf('/')+1,fb_flight.indexOf('@'));
                                                if(inbound==flight){
                                                    turnAround = "A";
                                                }else if(outbound==flight){
                                                    turnAround = "D";
                                                }
                                            }else if(fb_flight.indexOf('/')<0 && fb_flight.indexOf(flight)>-1){
                                                if(fb_flight.indexOf("in_")>-1){
                                                    turnAround = "A";
                                                }else if(fb_flight.indexOf("out_")>-1){
                                                    turnAround = "D";
                                                }
                                            }
                                            //task
                                            if((y-1) <=0){
                                                fb_final='First Task';
                                            }else{
                                                fb_final = fb[y-1];
                                                let endtime = fb_final.substring(fb_final.indexOf("@")+1);
                                                if(now_output >= endtime){
                                                    fb_completed = true;
                                                }else{
                                                    fb_completed =false;
                                                }
                                                fb_final = fb_final.substring(0,fb_final.indexOf("@"));
                                            }
                                            //stand
                                            if(dataObj['flight'].hasOwnProperty(fb_final)){
                                                stand = dataObj['flight'][fb_final]['STAND'];
                                            }
                                            document.getElementsByClassName("commonChildType")[x].innerHTML= turnAround;
                                            document.getElementsByClassName("commonChildVal")[x].innerHTML= 
                                            fb_final=='First Task'?`<span>${fb_final}</span>`:
                                            fb_completed==true?`<span>${fb_final}</span><span uk-icon="icon: check;"></span>`:
                                            fb_completed==false?`<span>${fb_final}</span><img src="./images/hourglass.svg">`:
                                            '';
                                            document.getElementsByClassName("commonChildParkingStand")[x].innerHTML=stand;
                                            
                                        }
                                    }
                                }
                            }
                            
                        }else{
                            alert("request completed..cannot show the break data..");
                            backAction();
                        }
                    }).fail(function (xhr, status) {
                        alert('fail to get the break data..');
                        backAction(); 
                    });
                }
                function info_card(){
                    request['card'] = $.ajax({
                        type: "POST",
                        url:window.location.href.includes('localhost') || window.location.href.includes('127.0.0.1')? "./api/index.php":"",
                        data: {
                            reqType:"info_card"
                        }
                    }).done(function (data){
                            //hide the loading animation
                            document.getElementById("spinner-box").style.display="none";
                            // console.log(JSON.parse(data));
                            if(JSON.parse(data).status=='success'){
                                dataObj['card'] = JSON.parse(data).message;
                                for(let y=0 ; y<document.getElementsByClassName("commonChildNum").length; y++){
                                    for(let z=0 ; z<dataObj['card'].length; z++){
                                        if(dataObj['card'][z]['resNo']==document.getElementsByClassName("commonTeam")[y].innerText){
                                            let now = '2024-05-05 16:00';
                                            if(now>=dataObj['card'][z]['starttime']&&now<=dataObj['card'][z]['endtime']){
                                                document.getElementsByClassName("commonChildAvailable")[y].classList.remove("redLight");
                                                document.getElementsByClassName("commonChildAvailable")[y].classList.add("greenLight");
                                            }
                                            break;
                                        }
                                    }
                                }
                                
                            }else{
                                alert("request completed..cannot show the info card data..");
                                backAction();
                            } 
                    }).fail(function (xhr, status) {
                        //hide the loading animation
                        document.getElementById("spinner-box").style.display="none";
                        alert('fail to get the info card data..');
                        backAction();
                    }); 
                }
                function info_member(){
                    request['member'] = $.ajax({
                    type: "POST",
                    url:window.location.href.includes('localhost') || window.location.href.includes('127.0.0.1')? "./api/index.php":"",
                    data: {
                        reqType:"info_member",  
                        starttime:starttime,
                        endtime:endtime
                    }}).done(function (data) {
                        //hide the loading animation
                        document.getElementById("spinner-box").style.display="none";
                        if(JSON.parse(data).status=='success'){
                            dataObj['flight']=JSON.parse(data).message;
                            //fbreak request
                            info_fbreak();
                            info_card();
                            //print watermark
                            if(!watermark_flag){
                                waterMarkAction(username);
                                watermark_flag=true;
                            }
                            //detect the window resize to change watermark responsively
                            let windowWidth = window.innerWidth;
                            function watermarkResize(e){
                                if(windowWidth>750 &&  window.innerWidth<=750){
                                    document.getElementById("spinner-box").style.display="flex";
                                    waterMarkAction(username,'mobile',1001);
                                    document.getElementById("spinner-box").style.display="none";
                                }else if(windowWidth<=750 &&  window.innerWidth>750){
                                    document.getElementById("spinner-box").style.display="flex";
                                    waterMarkAction(username,'desktop',1001);
                                    document.getElementById("spinner-box").style.display="none";
                                }
                                windowWidth = window.innerWidth;
                            }
                            window.onresize = (e)=>{watermarkResize(e)};
                            
                            let od = "",est="",estVal="",chocks="",date="",ac="",ar="",br="",ps="";
                            //get the current flight 
                            if(flight!==null){
                                if(dataObj['flight'].hasOwnProperty(flight)){
                                    dataObj['current'] = dataObj['flight'][flight];
                                    // console.log(dataObj['current']);
                                    //ORGIN/DEST
                                    let d = dataObj['current'];
                                    od = d['ORIGIN']+'/'+d['DEST'];
                                    //est title
                                    let est_arr = d['ata']!==null ? 'ATA':d['sta']!==null?'STA':'';
                                    let est_dep = d['atd']!==null ? 'ATD':d['std']!==null?'STD':'';
                                    let est_comma = est_arr!==""&&est_dep!=""?"/":"";
                                    est = est_arr+est_comma+est_dep;
                                    //est value
                                    let estVal_arr = d['ata']!==null ? d['ata'].substr(11):d['sta']!==null ? d['sta'].substr(11):"";
                                    let estVal_dep = d['atd']!==null ? d['atd'].substr(11):d['std']!==null ? d['std'].substr(11):"";
                                    let estVal_comma = estVal_arr!==""&&estVal_dep!=""?"/":"";
                                    estVal = estVal_arr+estVal_comma+estVal_dep;
                                    //status
                                    if(est_dep=="ATD"){
                                        chocks= 'At gate: '+d['atd'].substring(d['atd'].indexOf(" ")+1);
                                    }else if(est_arr=="ATA"){
                                        chocks= 'At gate: '+d['ata'].substring(d['ata'].indexOf(" ")+1);;
                                    }
                                    //date
                                    date = d['date'];
                                    ac = d['AC_TYPE'];
                                    ar = d['REG'];
                                    br = d['BAG'];
                                    ps = d['STAND'];
                                }
                            }else{
                                alert("the flight cannot be found..");
                            }
                            
                            document.getElementsByClassName("flightVal")[0].innerHTML = flight;
                            document.getElementsByClassName("originVal")[0].innerHTML = od;
                            document.getElementsByClassName("estTitle")[0].innerHTML = est; 
                            document.getElementsByClassName("estVal")[0].innerHTML = estVal;
                            document.getElementsByClassName("statusVal")[0].innerHTML = chocks;
                            document.getElementsByClassName("dateVal")[0].innerHTML = date;
                            document.getElementsByClassName("acTypeVal")[0].innerHTML = ac;
                            document.getElementsByClassName("acRegVal")[0].innerHTML = ar; 
                            document.getElementsByClassName("baggageRecVal")[0].innerHTML = br;
                            document.getElementsByClassName("parkingStandVal")[0].innerHTML = ps;
                            printRowAction('sp');
                            printRowAction('ld');
                            printRowAction('eo');
                            printRowAction('rw');
                            //print the worker rows
                            function printRowAction(type){
                                let typeArr = dataObj['current'][type].split(',');
                                if(typeArr==''){
                                    //print empty table if no specific type was found
                                    document.getElementsByClassName(`${type}Row`)[0].innerHTML+=`
                                    <div class='${type} commonChildDiv rightfix'>
                                    </div>
                                    `; 
                                }
                                else if(typeArr.length>0){
                                    for(let i =0; i<typeArr.length ; i++){
                                        let teamNumber = typeArr[i].substring(typeArr[i].indexOf('@')+1);
                                        let emId = typeArr[i].substring(typeArr[i].indexOf('#')+1,typeArr[i].indexOf('@'));
                                        
                                        document.getElementsByClassName(`${type}Row`)[0].innerHTML+=`
                                        <div class='${type} commonChildDiv rightfix'>
                                            <div class='commonChildRow clearfix'>
                                                <div class='commonChildStatus leftfix clearfix'>
                                                    <div class='commonChildNum leftfix'>
                                                        <a class='commonTeam'>${teamNumber}</a>
                                                    </div>
                                                    <div class='commonChildAvailable  leftfix redLight'></div>
                                                </div>
                                                <div class='commonChildType leftfix'></div>
                                                <div class='commonChildVal leftfix'></div>
                                                <div class='commonChildParkingStand leftfix'></div>
                                            </div>
                                        </div>`;
                                    }
                                }
                            }
                        }else{
                            alert("request completed..cannot show the info flight and member data..");
                            backAction();
                        } 
                    }).fail(function (xhr, status) {
                        //hide the loading animation
                        document.getElementById("spinner-box").style.display="none";
                        alert('fail to get the info flight and member data..');
                        backAction();
                    }); 
                }
                //refreshAction
                function refreshAction(){
                    document.getElementById("spinner-box").style.display="flex";
                    request['member'].abort();
                    request['fbreak'].abort();
                    dataObj = { 
                        'flight': [], 
                        'fbreak':[], 
                        'card':[],
                        'current':null
                    };
                    document.getElementsByClassName("flightVal")[0].innerHTML = "";
                    document.getElementsByClassName("originVal")[0].innerHTML = "";
                    document.getElementsByClassName("estTitle")[0].innerHTML = ""; 
                    document.getElementsByClassName("estVal")[0].innerHTML = "";
                    document.getElementsByClassName("statusVal")[0].innerHTML = "";
                    document.getElementsByClassName("dateVal")[0].innerHTML = "";
                    document.getElementsByClassName("acTypeVal")[0].innerHTML = "";
                    document.getElementsByClassName("acRegVal")[0].innerHTML = ""; 
                    document.getElementsByClassName("baggageRecVal")[0].innerHTML = "";
                    document.getElementsByClassName("parkingStandVal")[0].innerHTML = "";
                    document.getElementsByClassName("manpowerInfo-manpower")[0].innerHTML = 
                    `<div class="spRow commonRow positionRelative clearfix">
                        <div class="infoTask_spinner-box">
                            <div class="infoTask_three-quarter-spinner"></div>
                        </div>
                        <div class="spTitle commonTitle leftfix">SP</div>
                    </div>
                    <div class="ldRow commonRow positionRelative clearfix">
                         <div class="infoTask_spinner-box">
                            <div class="infoTask_three-quarter-spinner"></div>
                        </div>
                        <div class="ldTitle commonTitle leftfix">LD</div>
                    </div>
                    <div class="eoRow commonRow positionRelative clearfix">
                         <div class="infoTask_spinner-box">
                            <div class="infoTask_three-quarter-spinner"></div>
                        </div>
                        <div class="eoTitle commonTitle leftfix">EO</div>
                    </div>
                    <div class="rwRow commonRow positionRelative clearfix">
                         <div class="infoTask_spinner-box">
                            <div class="infoTask_three-quarter-spinner"></div>
                        </div>
                        <div class="rwTitle commonTitle leftfix">RW</div>
                    </div>`;
                    setTimeout(()=>info_member(),500);
                } 
                document.getElementsByClassName("refreshBtn")[0].onclick = ()=> refreshAction();
            }
        }catch(e){
            alert(e.message);
            backAction();
        }
    };//end of window onload

//back to search
function backAction(){
    const params = new URLSearchParams(window.location.search);

    // remove params
    params.delete("flight");
    params.set('p','search');

    // Update URL
    window.history.replaceState({}, '', `./search.php?${params.toString()}`);
    window.location.reload();
}
//logout to login page
function logoutAction(){
    window.history.replaceState({}, '', `./index.php`);
    window.location.reload();
}
</script>
</html>
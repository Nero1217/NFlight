<!DOCTYPE html>
<html lang="en">
<head>   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1">
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
    <link rel="stylesheet" href="./css/search.css"/>
    <!-- js -->
    <script src="./js/toggleMenu.js" defer></script>
    <script src="./js/watermark.js" defer></script>
    <script src="./js/autoTime.js" defer></script>
    <script src="./js/timeLessThanZero.js"></script>
    <script src="./js/startEndTime.js" defer></script>
    <script src="./js/nowCommon.js" defer></script>
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
                    <span class="username-container">Neroyau</span>
                </div>
                <div class="back" onclick="logoutAction()">
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
            <!-- manpower -->
            <div class="manpower clearfix">
                <!-- flight search -->
                <form class="search clearfix">
                    <input type="text" class="input leftfix" id="search" placeholder="Flight No"/>
                    <button type="reset" class="uk-button uk-button-default clearBtn leftfix">Clear</button>
                </form>
                <!-- Last updated time -->
                <div class="lastUpdated clearfix">
                    <span class="lastUpdated_text">Last Updated Time:</span>
                    <span class="lastUpdated_time">-</span>
                    <a id="refresh" uk-icon="icon: refresh"></a>
                </div>
                <!-- available table -->
                <div class="available clearfix">
                    <div class="available-container displayFlexJ clearfix">
                        <table class="available-table leftfix">
                            <tr>
                                <th></th>
                                <th>SP</th>
                                <th>LD</th>
                                <th>EO</th>
                                <th>RW</th>
                            </tr>
                            <tr >
                                <td>At Work</td>
                                <td id="atWork_sp" class="normal-num">/</td>
                                <td id="atWork_ld" class="normal-num">/</td>
                                <td id="atWork_eo" class="normal-num">/</td>
                                <td id="atWork_rw" class="normal-num">/</td>
                            </tr>
                            <tr>
                                <td>Available</td>
                                <td id="available_sp" class="normal-num">/</td>
                                <td id="available_ld" class="normal-num">/</td>
                                <td id="available_eo" class="normal-num">/</td>
                                <td id="available_rw" class="normal-num">/</td>
                            </tr>
                        </table>
                    </div>
                    
                </div>

                <!-- flight table -->
                <div class="flight"></div>
                <!-- Next button -->
                <div id="nextBtn" class="nextBtn">
                    <button class="uk-button-primary">Next</button>
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
    let starttime = '2024-5-5 04:00';
    let endtime = '2024-5-6 04:00';
    let searchParams = new URLSearchParams(document.location.search); //get params
    let params_page = searchParams.get("p");
    let page ='';
    if(params_page!==''&&params_page!==null){  //keep the page by query string
        page = params_page;
    }
    let department_list = ['sp','ld','eo','rw'];
    //search
    let search = "";
    let searchCounter = null;
    //watermark 
    let watermark_flag = false;
    //date
    let date = false;
    //save ajax
    let searchFlight = ''; 
    let searchWorkAva = ''; 

    //run js after DOM render
    window.onload = ()=>{
        //scrollToTop
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
        //auto logout if user not active about 30 mins
        var timeoutTime = 1800000;
        var timeoutTimer = setTimeout(()=>{alert('User is not active, please login again.');logoutAction();}, timeoutTime);
        document.body.addEventListener.onmousedown = (e)=>{
            clearTimeout(timeoutTimer);
            timeoutTimer = setTimeout(()=>{logoutAction()}, timeoutTime);
        };
        document.body.addEventListener.onkeyup = ()=>{
            clearTimeout(timeoutTimer);
            timeoutTimer = setTimeout(()=>{logoutAction()}, timeoutTime);
        };
        document.getElementById("refresh").onclick = ()=>{
            reloadAction();
        };
        function reloadAction(){
            searchFlight.abort();
            searchWorkAva.abort(); 
            date = false;
            search ="";
            searchCounter = null;
            document.getElementById('search').value = '';
            for(let i=0 ; i< document.getElementsByClassName("normal-num").length; i++){
                document.getElementsByClassName("normal-num")[i].innerHTML ='';
                document.getElementsByClassName("flight")[0].innerHTML = '';
                
            }
            document.getElementById("spinner-box").style.display="flex";
            turnPage('search');
        }
        document.getElementById("nextBtn").onclick = ()=>{
            nextAction();
        };
        function nextAction(){
            const urlParams = new URLSearchParams(window.location.search);
            if(searchCounter!==null){
                const path = window.location.pathname;
                const params = new URLSearchParams(window.location.search);
                
                let inbound = document.getElementById(`row${searchCounter}`).getElementsByClassName("arr-flight")[0].innerText;
                let outbound = document.getElementById(`row${searchCounter}`).getElementsByClassName("dep-flight")[0].innerText;
                let searchFlight = inbound!==''&&outbound!==''?inbound+'/'+outbound:
                inbound==''&& outbound !==''? outbound:
                inbound!=='' && outbound =='' ? inbound:'';
                
                params.set('p','info');
                params.set('flight',searchFlight);
                // Update URL
                window.history.replaceState({}, '', `./info.php?${params}`);
                
                window.location.reload();
            }else{
                alert("Please select a flight row.");
            }
        }
        //if unknown error found, logout
        try{
            turnPage(page);
            
            
            //turn page 
            function turnPage(newPage){
                page = newPage;
                //detect page
                if(page=='search'){
                    manpower_search();
                }
            }
            //page: manpower_search
            function manpower_search(){ 
                //scrollToTop
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
                nowCommon(); //update the time

                //1. ajax to get data: search flight
                searchFlight = $.ajax({ 
                    type: "POST",
                    url:window.location.href.includes('localhost') || window.location.href.includes('127.0.0.1')? "./api/index.php":"",
                    data: {
                        reqType:"search_flight",
                        starttime:starttime,
                        endtime:endtime
                    }
                }).done(function (dataObj){
                    setTimeout(()=>{//hide the loading animation
                        document.getElementById("spinner-box").style.display="none";
                        //print watermark initial
                        let username = 'Neroyau';
                        if(!watermark_flag){
                            waterMarkAction(username);
                            watermark_flag=true;
                        }
                        //detect the window resize to change watermark responsively
                        let windowWidth = window.innerWidth;
                        window.onresize = (e)=>{
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
                        };
                        //get data
                        let data =JSON.parse(dataObj);
                        // console.log(data.message);
                        let flightArr = data.message;
                        
                        function printAction(searchVal){
                            document.getElementsByClassName('flight')[0].innerHTML = '';
                            let counter =0;
                            for(let i=0; i<flightArr.length ; i++){
                                let regex = new RegExp(searchVal,'i');
                                if(regex.test(flightArr[i]['flight_num'])||searchVal==''){
                                    let arr_est,arr_text;
                                    let dep_est,dep_text;
                                    let arr_flight;
                                    let dep_flight;
                                    let sp,ld,eo,rw;
                                    let chock,stand,aircraft;
                                    // 1. arrival
                                    arr_est = flightArr[i]['ata']!==null?'ata':
                                    flightArr[i]['sta']!==null?'sta':'';
                                    if(arr_est==''){
                                        arr_text = '';
                                        arr_flight='';
                                    }else{
                                        arr_text = flightArr[i]['ata']!==null?flightArr[i]['ata']:"";
                                        if(arr_text!==''){
                                            arr_text = arr_text.substring(arr_text.indexOf(' ')+1,arr_text.lastIndexOf(':'));
                                        }
                                        flightArr[i]['sta']!==null?flightArr[i]['sta']:'';
                                        arr_flight= flightArr[i]['flight_num'];
                                        if(arr_flight.indexOf("/")>-1){
                                            arr_flight = arr_flight.substring(0,arr_flight.indexOf("/"));
                                        }
                                    }
                                    //2.dep
                                    dep_est = flightArr[i]['atd']!==null?'atd':
                                    flightArr[i]['std']!==null?'std':'';
                                    if(dep_est==''){
                                        dep_text = '';
                                        dep_flight='';
                                    }else{
                                        dep_text = flightArr[i]['atd']!==null?flightArr[i]['atd']:"";
                                        if(dep_text!==''){
                                            dep_text = dep_text.substring(dep_text.indexOf(' ')+1,dep_text.lastIndexOf(':'));
                                        }
                                        flightArr[i]['std']!==null?flightArr[i]['std']:'';
                                        dep_flight= flightArr[i]['flight_num'];
                                        if(dep_flight.indexOf("/")>-1){
                                            dep_flight = dep_flight.substring(dep_flight.indexOf("/")+1);
                                        }
                                    }
                                    //3. organizations
                                    sp = flightArr[i]['sp'];
                                    ld = flightArr[i]['ld'];
                                    eo = flightArr[i]['eo'];
                                    rw = flightArr[i]['rw'];
                                    //4. info
                                    chock = dep_est=='atd'?"At gate: "+dep_text:
                                    arr_est=='ata'? "At gate: "+arr_text:"";
                                    stand = flightArr[i]['stand']!==null?flightArr[i]['stand']:"";
                                    aircraft = flightArr[i]['ac_type']!==null?flightArr[i]['ac_type']:"";
                                    
                                    if(i==0){
                                        document.getElementsByClassName("flight")[0].innerHTML+=`
                                        <div class="date-row">
                                            05,May,2024
                                        </div>
                                        `;
                                        counter = 0;
                                    }
                                    if(i!==0){
                                        counter +=1;
                                    }
                                    document.getElementsByClassName("flight")[0].innerHTML+=`
                                    <div class="row clearfix" id="row${counter}">
                                        <div class="arr-time leftfix ">
                                            <div class="est displayFlexA">${arr_est}</div>
                                            <div class="time-text displayFlexA">${arr_text}</div>
                                        </div>
                                        <div class="arr-flight leftfix displayFlexA">${arr_flight}</div>
                                        <div class="dep-time leftfix">
                                            <div class="est displayFlexA">${dep_est}</div>
                                            <div class="time-text displayFlexA">${dep_text}</div>
                                        </div>
                                        <div class="dep-flight leftfix displayFlexA">${dep_flight}</div>
                                        <div class="org-num leftfix displayFlexA">${sp}</div>
                                        <div class="org-num leftfix displayFlexA">${ld}</div>
                                        <div class="org-num leftfix displayFlexA">${eo}</div>
                                        <div class="org-num leftfix displayFlexA">${rw}</div>
                                        <div class="info rightfix clearfix">
                                            <div class="chock displayFlexA">🚦${chock}</div>
                                            <div class="stand displayFlexA leftfix">🚏 ${stand}</div>
                                            <div class="aircraft displayFlexA leftfix">✈${aircraft}</div>
                                        </div>
                                    </div>
                                    `; 
                                }
                            }
                            for(let j=0; j< document.getElementsByClassName("row").length ; j++){
                                document.getElementById(`row${j}`).onclick = ()=>{
                                    for(let k=0; k< document.getElementsByClassName("row").length ; k++){
                                        document.getElementById(`row${k}`).style.border = '1px solid rgba(0, 0, 0, 0.157)';
                                    }
                                    document.getElementById(`row${j}`).style.border = '1px solid green';
                                    searchCounter = j;
                                };
                            }
                        }
                        printAction('');
                        document.getElementById("search").onkeyup = (e)=>{
                            if(search!==e.target.value){
                                search = e.target.value;
                                printAction(e.target.value);
                            }
                        };
                        document.getElementsByClassName("clearBtn")[0].onclick = function(){clearAction()};
                        //clear button
                        function clearAction(){
                            if(search!==''){
                                search='';
                                printAction("");
                            }
                            const path = window.location.pathname;
                            const params = new URLSearchParams(window.location.search);
                            const hash = window.location.hash;
                            
                            // remove params
                            params.delete("f");

                            // Update URL
                            window.history.replaceState({}, '', `${path}?${params.toString()}${hash}`);
                        }
                    },500);
                    
                    
                }).fail(function (xhr, status){
                    //hide the loading animation
                    document.getElementById("spinner-box").style.display="none";
                    alert('fail to get the flight data..');
                });  
                //2. ajax to get data: search atWork
                searchWorkAva = $.ajax({ 
                    type: "POST",
                    url:"./api/index.php",
                    data: {
                        reqType:"search_workAva",
                        starttime:starttime,
                        endtime:endtime,
                    }
                }).done(function (dataObj){
                    setTimeout(()=>{
                        let data =JSON.parse(dataObj);
                        // console.log(data.message);
                        if(data.status=='success'){
                            let rdata = data.message;
                            
                            for(let i=0; i<department_list.length;i++){
                                document.getElementById('atWork_'+department_list[i]).innerHTML= rdata['atWork'][department_list[i]];
                                document.getElementById('available_'+department_list[i]).innerHTML = rdata['available'][department_list[i]];
                            }
                        }else{
                            alert('get response but fail status from the workers at work and available..');
                        }
                    },200);
                    
                    
                }).fail(function (xhr, status){
                    alert('fail to get the workers at work and available..');
                    //hide the loading animation
                    document.getElementById("spinner-box").style.display="none";
                }); 
            }
        }catch(e){
            alert(e.message);
        }
};
//end of window onload

//logout action
function logoutAction(){
    window.history.replaceState({}, '', `./index.php`);
    window.location.reload();
}
</script>
</html>
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
    <!-- customized css -->
    <link rel="stylesheet" href="./css/index.css"/>
    <!-- customized js -->
    <script src="./js/toggleMenu.js" defer></script>
    <script src="./js/autoTime.js" defer></script>
    <title>N-Flight</title>
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
                <div class="back">
                    <span class="icon" uk-icon="settings"></span>
                    <span class="text">Back</span>
                </div>
                <div class="logout">
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
            <!-- login form -->
            <div class="login">
                <div class="title">
                    Flight Checking System
                </div>
                <div class="loginFail">
                    <span class="loginFailContainer">Login Failed.</span>
                </div>
                <div class="username">
                    <div class="username-input">
                        <input id="username" type="text"/>
                    </div>
                    <div class="icon">
                        <span uk-icon="user"></span>
                    </div>
                </div>
                <div class="password">
                    <div class="password-input">
                        <input id="password" type="password"/>
                    </div>
                    <div class="icon">
                        <span uk-icon="lock"></span>
                    </div>
                </div>
                <button onclick="loginAction()" id="login-btn" class="login-btn uk-button uk-button-primary">
                    <span uk-icon="sign-in"></span>
                </button>
            </div>
        </div>
    </div>
    
</body>
<script> 
//global value
var username = "";
var password = "";
var uid = "";
var flightSearchCounter = "";
var data1= [];
var page = "login"; //page types: login, search, info
var hightlight_flightRows="";
//turn page
function turnPage(newPage){
    page = newPage;
    //detect page
    if(page=='search'){
        let url = new URL(document.location);
        let params = new URLSearchParams(url.SearchParams);
        params.set("p",'search');
        window.history.pushState("","",`./search.php?${params}`);
        window.location.reload();
    }
} 
function loginAction(){
    let login_btn = document.getElementById('login-btn');
    login_btn.classList.add('block-touch');
    username = document.getElementById('username').value;
    password = document.getElementById('password').value;
    //#region 不可為空
    if(username==''){
        alert("Please fill in the username.");
        login_btn.classList.remove('block-touch');
        document.getElementsByClassName("loginFail")[0].style.display="block";
        resetAction();
    }else if(password==''){
        alert("Please fill in the password.");
        login_btn.classList.remove('block-touch');
        document.getElementsByClassName("loginFail")[0].style.display="block";
        resetAction();
    }
    //#endregion
    //#region 限制長度, 空白鍵
    else if(username.length<4||username.length>16||/[\s]{1,}$/.test(username)
    ||/[^A-z0-9]/.test(username)){
        alert("Please fill in a valid username.");
        login_btn.classList.remove('block-touch');
        document.getElementsByClassName("loginFail")[0].style.display="block";
        resetAction();
    }else if(password.length<4||password.length>16||/[\s]{1,}$/.test(password)
    ||/[^A-z0-9]/.test(password)){
        alert("Please fill in a valid password.");
        login_btn.classList.remove('block-touch');
        document.getElementsByClassName("loginFail")[0].style.display="block";
        resetAction();
    }
    //#endregion 
    else{
        login_btn.classList.remove('block-touch');
        turnPage('search');
    }
    function resetAction(){
        document.getElementById('username').value="";
        document.getElementById('password').value="";
    }
}
window.onload = ()=>{
    //scrollToTop
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    //auto time
    setInterval(getTime,1000);
    
};//end of window onload
</script>
</html>
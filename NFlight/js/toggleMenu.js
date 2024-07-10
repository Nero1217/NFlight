//toggleSideMenu open and close timer
var toggleSideMenuTimer = 1000;
//toggleMenu 
var toggleStatus= false;
var toggleIcon = document.getElementsByClassName('toggle')[0];
var toggleMenu = document.getElementsByClassName('toggleMenu')[0];
var toggleClose = toggleMenu.getElementsByClassName('close')[0].getElementsByClassName('icon')[0];
var main = document.getElementById('main')
//toggleMenu close
function toggleListenClose(){
    document.getElementById('main').classList.add("block-touch");
    toggleMenu.classList.remove("toggleOpen");toggleMenu.classList.add("toggleClose");
    setTimeout(()=>{
        document.getElementById('main').classList.remove("block-touch");
        toggleMenu.style.display="none";
    },toggleSideMenuTimer);
    toggleStatus=false;    
    toggleClose.removeEventListener("click",toggleListenClose);
}
//toggleMenu open
toggleIcon.addEventListener("click",()=>{
    document.getElementById('main').classList.add("block-touch");
    setTimeout(() => {
        document.getElementById('main').classList.remove("block-touch");
    }, toggleSideMenuTimer);
    toggleClose.removeEventListener("click",toggleListenClose);
    toggleStatus= toggleStatus==false?true:false;
    if(toggleStatus==true){
        toggleMenu.style.display="block";
        toggleMenu.classList.remove("toggleClose");
        toggleMenu.classList.add("toggleOpen");
        toggleClose.addEventListener("click",toggleListenClose);
    }
});


//watermark applying style to head
function loadStyleString(css){
    var style = document.createElement("style");
    style.type = "text/css";
    try{
        style.appendChild(document.createTextNode(css));
    }catch(e){
        style.styleSheet.cssText = css;
    }
    var head = document.getElementsByTagName('head')[0];
    head.appendChild(style);
}
//waterMark function
function waterMarkAction(username,type,numOfWaterMark){
    let watermark_row = 0;
    if(numOfWaterMark!=null){
        for(let i=1; i<numOfWaterMark+1; i++){
            // console.log('remove');
            document.getElementById("watermark"+i)?.remove();
        }
    }
    if(type == 'desktop'||window.innerWidth > 750){
        // console.log("desktop");
        for(let i=0; i<1001; i++){
            var div = document.createElement("div");
            div.id="watermark"+(i+1);
            div.classList.add("watermark");
            document.body.appendChild(div);
            
            let name = "";
            if(username !='' && username!= null){
                name = username;
            }
            loadStyleString("#"+div.id+"{position: relative;height: 100%;}"+"#"+div.id+`::before{z-index:-1;content:'${name}';position: fixed;top: ${watermark_row*2}%;left: ${(i*5)-watermark_row*100}%; color:grey;width:5%;height:5%;font-size:1.2vw;display:flex;justify-content:center;align-items:center;opacity:0.25;transform:scaleY(-1);}
            `);
            if((i+1)%20==0&&i!==0){
                watermark_row+=1;
            }
        }
    }else if(type=='mobile' || window.innerWidth <= 750){
        // console.log("mobile");
        for(let i=0; i<1001; i++){
            var div = document.createElement("div");
            div.id="watermark"+(i+1);
            div.classList.add("watermark");
            document.body.appendChild(div);
            
            let name = "";
            if(username !='' && username!= null){
                name = username;
            }
            loadStyleString("#"+div.id+"{position: relative;height: 100%;}"+"#"+div.id+`::before{z-index:-1;content:'${name}';position: fixed;top: ${watermark_row*1}%;left: ${(i*10)-watermark_row*100}%; color:grey;font-size:1.5vw;display:flex;justify-content:center;align-items:center;opacity:0.35;transform:scaleY(-1);}
            `);
            if((i+1)%10==0&&i!==0){
                watermark_row+=1;
            }
        }
    }
    
}

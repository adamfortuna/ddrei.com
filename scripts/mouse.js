<!--//

agent = navigator.userAgent;

browserVer = 2;
if (agent.substring(0,7) == "Mozilla"); {
    if (parseInt(agent.substring(8,9)) == 3) {browserVer = 1;}
    if (parseInt(agent.substring(8,9)) == 4) {browserVer = 1;}
}

if (browserVer == 1) {



navi1a            = new Image();
navi1a.src        = "/images/template/t_news.gif";
navi1b            = new Image();
navi1b.src        = "/images/template/to_news.gif";
navi2a            = new Image();
navi2a.src        = "/images/template/t_events.gif";
navi2b            = new Image();
navi2b.src        = "/images/template/to_events.gif";
navi3a            = new Image();
navi3a.src        = "/images/template/t_locations.gif";
navi3b            = new Image();
navi3b.src        = "/images/template/to_locations.gif";
navi4a            = new Image();
navi4a.src        = "/images/template/t_profiles.gif";
navi4b            = new Image();
navi4b.src        = "/images/template/to_profiles.gif";
navi5a            = new Image();
navi5a.src        = "/images/template/t_info.gif";
navi5b            = new Image();
navi5b.src        = "/images/template/to_info.gif";
navi6a            = new Image();
navi6a.src        = "/images/template/t_media.gif";
navi6b            = new Image();
navi6b.src        = "/images/template/to_media.gif";
navi7a            = new Image();
navi7a.src        = "/images/template/t_forum.gif";
navi7b            =  new Image();
navi7b.src        = "/images/template/to_forum.gif";
navi8a            = new Image();
navi8a.src        = "/images/template/t_articles.gif";
navi8b            =  new Image();
navi8b.src        = "/images/template/to_articles.gif";

}

function hiLite(ImgName, typ) {

if (browserVer == 1) {

   var imge = ImgName + typ + ".src";

   document.images[ImgName].src = eval(imge);

}

}

//-->
<script> 
var userAgent = navigator.userAgent.toLowerCase(); // 접속 핸드폰 정보 
   
// 모바일 홈페이지 바로가기 링크 생성 
if(userAgent.match('iphone')) { 
    document.write('<link rel="apple-touch-icon" href="/na/na_icon.png" />') 
} else if(userAgent.match('ipad')) { 
    document.write('<link rel="apple-touch-icon" sizes="72*72" href="/na/na_icon.png" />') 
} else if(userAgent.match('ipod')) { 
    document.write('<link rel="apple-touch-icon" href="/na/na_icon.png" />') 
} else if(userAgent.match('android')) { 
    document.write('<link rel="shortcut icon" href="/na/na_icon.png" />') 
} 
</script>

<script type="text/javascript"> 
// 접속 핸드폰 정보 
var userAgent = navigator.userAgent.toLowerCase();
var url = "https://blingbeauty.shop/";
var icon = "https://blingbeauty.shop/na/na_icon.png";
var title = "블링뷰티";
var serviceCode = "blingbeauty";

function home_key(){
document.write('<object id="bookmark_obj" type="text/html" data="naversearchapp://addshortcut?url='+url+'&icon='+icon+'&title='+title+'&serviceCode='+serviceCode+'&version=7" width="0" height="0"></object>')
}

if(userAgent.match('iphone')) { 
home_key();
} else if(userAgent.match('ipad')) { 
home_key();
} else if(userAgent.match('ipod')) { 
home_key();
} else if(userAgent.match('android')) { 
home_key();
} 

</script>
<head>
<meta name="apple-mobile-web-app-capable" content="yes" />
<link href="/na/na_icon.png" sizes="2048x2732" rel="apple-touch-startup-image" />
<link href="/na/na_icon.png" sizes="1668x2224" rel="apple-touch-startup-image" />
<link href="/na/na_icon.png" sizes="1536x2048" rel="apple-touch-startup-image" />
<link href="/na/na_icon.png" sizes="1125x2436" rel="apple-touch-startup-image" />
<link href="/na/na_icon.png" sizes="1242x2208" rel="apple-touch-startup-image" />
<link href="/na/na_icon.png" sizes="750x1334" rel="apple-touch-startup-image" />
<link href="/na/na_icon.png" sizes="640x1136" rel="apple-touch-startup-image" />
<script>
$(document).ready(function(e){ 
  //바로가기 링크 추가버튼 클릭시
  $("#addShortCut1, #addShortCut2").click(function(){

    var domain = "http://" + document.domain;
    var iconUrl = domain + "/images/houseChklst/favicon/apple-icon-152x152.png";
    var title = $("title").text();
    var url = "http://junspapa.com/house-checklist";

    util_addShoutCut(url, iconUrl, title);
  });
});

/**
 * 접속한 브라우저가 모바일인지 체크
 * @returns
 */
function util_isMobile(){
  var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ? true : false;
  return isMobile;
}

/**
* 바로가기 추가
*/
function util_addShoutCut(url, iconUrl, title){
	
	if(!util_isMobile()){
		util_dispMsg("모바일에서만 홈 화면에 바로가기를 추가할 수 있습니다.", 'F');
		return;
	}
	
	var userAgent = navigator.userAgent.toLowerCase();
	if(userAgent.match(/android/)){
		var appUrl = "naversearchapp://addshortcut?url=" + encodeURIComponent(url) + "&icon=" + encodeURIComponent(iconUrl) + "&title=" + encodeURIComponent(title) + "&serviceCode=housechecklist&version=7";
		window.open(appUrl);
	}else{
		util_dispMsg("아이폰, 아이패드 계열은 직접 홈 버튼 추가를 사용하셔야 합니다.", 'F');
		return;
	}
}
</script>
</head>
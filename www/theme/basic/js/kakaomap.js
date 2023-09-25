//지도관련
var map = new kakao.maps.Map(document.getElementById('map'), { // 지도를 표시할 div
    center : new kakao.maps.LatLng(mb_6, mb_7), // 지도의 중심좌표
    level : map_level // 지도의 확대 레벨
});

// 마커 클러스터러를 생성합니다
// 마커 클러스터러를 생성할 때 disableClickZoom 값을 true로 지정하지 않은 경우
// 클러스터 마커를 클릭했을 때 클러스터 객체가 포함하는 마커들이 모두 잘 보이도록 지도의 레벨과 영역을 변경합니다
// 이 예제에서는 disableClickZoom 값을 true로 설정하여 기본 클릭 동작을 막고
// 클러스터 마커를 클릭했을 때 클릭된 클러스터 마커의 위치를 기준으로 지도를 1레벨씩 확대합니다
var clusterer = new kakao.maps.MarkerClusterer({
    map: map, // 마커들을 클러스터로 관리하고 표시할 지도 객체
    averageCenter: true, // 클러스터에 포함된 마커들의 평균 위치를 클러스터 마커 위치로 설정
    minLevel: cluster_level, // 클러스터 할 최소 지도 레벨
    disableClickZoom: true // 클러스터 마커를 클릭했을 때 지도가 확대되지 않도록 설정한다
});


// 데이터를 가져오기 위해 jQuery를 사용합니다
// 데이터를 가져와 마커를 생성하고 클러스터러 객체에 넘겨줍니다
$.getJSON(g5_theme_url+"/store_list_json.php?mb_6="+mb_6+"&mb_7="+mb_7, function(data) {

    // 데이터에서 좌표 값을 가지고 마커를 표시합니다
    // 마커 클러스터러로 관리할 마커 객체는 생성할 때 지도 객체를 설정하지 않습니다
    var markers = $(data.positions).map(function(i, position) {
        //console.log(position.lat, position.lng);
        var maks = new kakao.maps.Marker({
            position : new kakao.maps.LatLng(position.lat, position.lng)
        });

        var iwContent = '<div class="newInfo"><div class="info"><div class="title info_title">'+position.store_name+'</div> <div class="body"><div class="img"><img src="/uploads/store_img/'+position.store_thumbnail+'" width="73" height="70"></div><div class="desc"><div class="ellipsis">'+'('+position.mb_zip+") "+position.mb_addr+'</div><div>';
        iwContent += '<div>'+position.mb_hp+'</div>';
        iwContent += '<div>'+position.open_time+'</div>';
        if(position.homepage){
            iwContent += '<a href="'+position.homepage+'" target="_blank" class="link">홈페이지 바로가기</a>';
        }

        iwContent += '</div> </div> </div> </div></div>';
        

        var infowindow = new daum.maps.InfoWindow({
            content: iwContent,    
            removable : true
        });
            
        daum.maps.event.addListener(maks, 'click', makeOverListener(map, maks, infowindow));

        return maks;


    });

    clusterer.addMarkers(markers);

});

    

// 인포윈도우를 표시하는 클로저를 만드는 함수입니다 
function makeOverListener(map, marker, infowindow) {
    infowindow.close();
    return function() {
        infowindow.open(map, marker);
    };
}

// 인포윈도우를 닫는 클로저를 만드는 함수입니다 
function makeOutListener(infowindow) {
    return function() {
        infowindow.close();
    };
}


// 마커 클러스터러에 클릭이벤트를 등록합니다
// 마커 클러스터러를 생성할 때 disableClickZoom을 true로 설정하지 않은 경우
// 이벤트 헨들러로 cluster 객체가 넘어오지 않을 수도 있습니다
kakao.maps.event.addListener(clusterer, 'clusterclick', function(cluster) {

    // 현재 지도 레벨에서 1레벨 확대한 레벨
    var level = map.getLevel()-1;

    // 지도를 클릭된 클러스터의 마커의 위치를 기준으로 확대합니다
    map.setLevel(level, {anchor: cluster.getCenter()});
    
});


//내 위치 가져오기
function geoFindMe() {

    if(!navigator.geolocation) {

        alert("위치를 가져오는데 실패했습니다. ");
        
    } else {

        navigator.geolocation.getCurrentPosition(function(position){

            var lat = position.coords.latitude, // 위도
                lon = position.coords.longitude; // 경도

            // 주소-좌표 변환 객체를 생성합니다
            var geocoder = new kakao.maps.services.Geocoder();

            var coord = new kakao.maps.LatLng(lat, lon);
            var callback = function(result, status) {
                if (status === kakao.maps.services.Status.OK) {
                    $("#address").val(result[0].address.address_name);
                    $.get("/bbs/addr_update.php?addr="+result[0].address.address_name, function(data){});
                }
            };

            geocoder.coord2Address(coord.getLng(), coord.getLat(), callback);

            if(position){
                
                $.get("/bbs/position_update.php?lat="+lat+"&lng="+lon, function(data){
                    console.log(lat, lon);
                    var imageSrc = 'https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/marker_red.png', // 마커이미지의 주소입니다    
                        imageSize = new kakao.maps.Size(64, 69), // 마커이미지의 크기입니다
                        imageOption = {offset: new kakao.maps.Point(27, 69)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.
                        
                    // 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
                    var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize, imageOption),
                        markerPosition = new kakao.maps.LatLng(lat, lon); // 마커가 표시될 위치입니다

                        //지도 확대
                        map.setLevel(8);

                        // 마커를 생성합니다
                        var marker = new kakao.maps.Marker({
                            position: markerPosition, 
                            image: markerImage
                        });

                        marker.setMap(map);
                });
            }

        });

    }

}

function windCategory(url){
    window.open(url, "widCategory", "width=600,height=790,left=200,top=200");
}
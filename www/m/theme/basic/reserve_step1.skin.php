<?php
if(!defined('_MALLSET_')) exit;

$type = $_GET['type'];
$stx = $_GET['stx'];
$category = $_GET['category'];

if($member['mb_lat'] != "" && $member['mb_lng'] != ""){
	$lat = $member['mb_lat'];
	$lng = $member['mb_lng'];
}else{
	$lat = 37.566585446882;
	$lng = 126.978203640984;
}

$sql_search = " where (1) ";

if($stx){
	$sql_search .= " and a.name like '%{$stx}%' ";
}

if($category){
	$sql_search .= " and a.mb_category = '{$category}' ";
	$sql_search2 = " where mb_category = '{$category}' ";
}

$sql_common = " from shop_member a INNER JOIN shop_minishop b ON a.id = b.mb_id ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select a.*, b.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

//echo $sql; 

?>
<style>
	.top_btn{
		text-align: center;
    	margin-bottom: 40px;
	}
	.top_btn a{
		width:120px;
		font-size: 14px;
		font-weight: 600;
		display: inline-block;
		margin: 20px 15px 0;
	}
	.top_btn a:hover{
		text-decoration:none!important;
	}
	.top_btn_active{
		border-bottom: 2px solid #ec008c;
    	padding: 0 10px 5px;
	}
	#submit_form{
		text-align: center;
		background:#ec008c;
		padding-top:30px;
		/*height:115px;width:80%;border-radius:25px;*/
		margin:0 auto;
	}
	#type1 #submit_form .form-wb{
		background:#fff;
		width:70%;
		margin:0 auto;
		border-radius:30px;
	}
	#type1 #submit_form p{
		position:relative;
		padding:10px 0;
		color:#fff;
	}
	#submit_form input[type="text"]{
		background:#fff;
		border: none;
    	padding: 8px;
		width:70%;
	}
	#submit_form input[type="text"]:focus, #submit_form input[type="submit"]:focus{
		outline:none;
	}
	#submit_form input[type="submit"]{
		border:none;
		background: url('../../theme/basic/img/search.png') no-repeat center;
		width:24px;height:24px;
		color: white;
		padding: 8px;
	}
	#submit_form .form-wb input::-webkit-input-placeholder{
		font-size:0.9em;
	}
	#submit_form .form-wb input::-ms-input-placeholder{
		font-size:0.9em;
	}
	#type1{
		text-align:center;
	}
	#type1 h3, #type2 h3{
		font-size:1.3rem;
		padding-top:40px;
	}
	.bgddd{
		background:#eee;
		z-index:-1; padding-bottom:60px; 
	}
	.store_wrap{
		width: 90%;
    	margin: 0 auto;
	}
	.store_thumb{
		float:left;
		margin-right: 20px;
		width:120px; height:120px;
	}
	.store_wrap > ul{
		margin: 0 auto;
		margin-top: 30px;
	}
	.store_wrap > ul > li{
		background: #fff;
		padding: 20px 10px 20px 20px;
		margin:0 auto 20px;
		border-radius:25px;
	}
	.store_wrap > ul > li > a > span{
		width:5px;
		height:120px;
		background:#ec008c;
		float:left;
	}
	.store_info{
		height:120px;
	}
	.store_info h2{
		font-size: 16px;
	}
	.store_info a{
		text-decoration:none;
	}
	.store_info .addr{
		font-size: 12px;
    	margin: 10px 0;
	}
	.store_info .store_link{
		/*height:71px;*/
	}
	.store_bttn{
		display:flex;justify-content:flex-end;
	}
	.store_bttn span{
		position:relative;top:-4px;
		font-size:1em;
	}
	.store_bttn li{
		padding:3px 12px;
		color:#fff;
		border-radius:7px;
		border:1px solid #ec008c;
		cursor:pointer;
	}
	.store_bttn li svg{
		position:relative;
		top:2px;
		margin-right:3px;
	}
	.store_bttn .call:hover a{
		color:#fff;
	}
	.store_bttn .share:hover a{
		color:#ec008c;
	}
	.store_bttn .call{
		margin-right:10px;
		background:#ec008c;
	}
	.store_bttn .share{
		background:#fff;
		color:#ec008c;
	}
	.hide_type{
		display:none;
	}

/*내 주변매장 찾기*/
	#type2 .map_search{
		width:100%;
		background:#ec008c;
	}
	#type2 .m_map{
		width:100% !important;
	}
	#type2 #submit_form{
		text-align:center; 
		background:#ec008c; 
		margin:0 auto; 
		width:80%; 
		padding:25px 0;
	}
	#type2 #submit_form .form-wb{
		background:#fff; 
		margin:0 auto;
		border-radius:30px; 
	}
	#type2 .store_wrap{
		text-align:center;
	}
		#type2 .c_location{
		width: 40px;
		height: 60px;
		display: inline-block;
		float: right;
		position: relative;
		top: -235px;
		left:-10px;
		z-index: 2;
		text-align:center;
		border-radius:15px;
		padding:5px;
		box-shadow:0 3px 7px rgba(0,0,0,0.2);
		background:rgba(255,255,255,0.3);
	}
	#type2 .c_location{
		color:#EC008C;
		text-decoration:none;
	}
	#type2 .c_location:hover{
		cursor:pointer;
	}
	#type2 .c_location span{
		font-size:0.9em;
		font-weight:600;
	}
	#type2 .c_location svg{
		background:#fff;
		border-radius:60px;
		padding:5px;
	}

.cover_info .ico_star {    width: 16px;    height: 16px;    background-image: url('/../img/tabler-icon-star222.png'); background-size:cover;}
.cover_info .ico_review {    width: 14px;    height: 14px;   background-image: url('/../img/tabler-icon-message-circle-222.png'); background-size:cover;}
.ico_hair {    display: inline-block;    overflow: hidden;    font-size: 0;    line-height: 0;    background-repeat: no-repeat;    text-indent: -9999px;    vertical-align: top;}
.cover_info .ico_point {    padding-left: 5px;    font-size: 14px;    line-height: 15px;    color: #111;}
.cover_info {    padding-top: 6px;    height: 34px;    font-size: 0;}
.cover_info > div:first-child{margin-top:5px;}
.cover_info > div:nth-child(2){margin-top:3px;}



</style>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=ac4ff377303ec171373bc69bc9a30030&libraries=services,clusterer,drawing"></script>
<div id="con_lf">

	<div class="top_btn">
		<a href="./reserve_step1.php?category=<? echo $category; ?>&stx=<? echo $stx; ?>&type=search" class="<? echo $type == "search" || $type == "" ? "top_btn_active":""; ?>">검색으로 찾기</a>
		<a href="./reserve_step1.php?category=<? echo $category; ?>&stx=<? echo $stx; ?>&type=location" class="<? echo $type == "location" ? "top_btn_active":""; ?>">내 주변매장 찾기</a>
	</div>

	<div id="type1" class="<? echo $type == 'location' ? 'hide_type':''; ?>">
		<form method="get" id="submit_form">
			<input type="hidden" name="type" id="type" value="<? echo $type; ?>">
			<input type="hidden" name="category" id="category" value="<? echo $category; ?>">
			<div class="form-wb">
				<input type="text" name="stx" id="stx" value="<? echo $stx; ?>" placeholder="미용실명 또는 지점명을 검색하세요">
				<input type="submit" value="">
			</div>
			<p>예시) 블링헤어, 블링헤어 인천점 등</p>
		</form>
<div class="bgddd">
		<div class="store_wrap">
			<h3>검색결과</h3>
			<ul>
				<?
					while($row = sql_fetch_array($result)){
						$book = sql_fetch("SELECT AVG(score) as score, count(*) as cnt, count(review_file) as rcnt FROM review_list where booking_id = '{$row['id']}' ");
				?>
				<li>
					<a href="<? echo MS_MBBS_URL; ?>/reserve_step2.php?type=<? echo $type; ?>&id=<? echo $row['id']; ?>">
						<span></span>
						<img src="<? echo MS_DATA_URL; ?>/store_img/<? echo $row['store_thumb']; ?>" width="100" height="100" class="store_thumb">
						<div class="store_info">
							<div class="store_link">
								<h2><? echo $row['name']; ?></h2>

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------>
									<div class="cover_info">
										<div>
											<span class="ico_hair ico_star">별점</span>
											<span class="ico_point"><? echo number_format(floor($book['score'])); ?><span>
										</div>
										<div>
											<span class="ico_hair ico_review">리뷰수</span>
											<span class="ico_point"><? echo number_format($book['cnt']); ?></span>
										</div>
									</div>
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------>
								<p class="addr"><? echo $row['addr1']; ?> <? echo $row['addr2']; ?></p>
								<p><? echo $row['telephone']; ?></p>
							</div>
							<ul class="store_bttn">
									<li class="call"><a><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path></svg><span>전화걸기</span></a></li>
									<!--<li class="share"><a><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-share" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="6" r="3"></circle><circle cx="18" cy="18" r="3"></circle><line x1="8.7" y1="10.7" x2="15.3" y2="7.3"></line><line x1="8.7" y1="13.3" x2="15.3" y2="16.7"></line></svg><span>공유</span></a></li>-->
								</ul>
							</div>
						</a>
					</li>
					
				<? } ?>
			</ul>
		</div>

	</div>
</div>

	<div id="type2" class="<? echo $type == 'search' || $type == '' ? 'hide_type':''; ?>">
		
<!--		<div class="map_search">
			<form method="get" id="submit_form">
			<input type="hidden" name="type" id="type" value="<? echo $type; ?>">
				<div class="form-wb">
					<input type="text" name="stx" id="stx" value="<? echo $stx; ?>" placeholder="미용실명 또는 지점명을 검색하세요">
					<input type="submit" value="">
				</div>
			</form>
		</div>
	-->	
		<div class="m_map" id="map" style="width:90%;margin:0 auto;height:250px;">
		</div>

		<div class="c_location"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-current-location" width="30" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="3"></circle><circle cx="12" cy="12" r="8"></circle><line x1="12" y1="2" x2="12" y2="4"></line><line x1="12" y1="20" x2="12" y2="22"></line><line x1="20" y1="12" x2="22" y2="12"></line><line x1="2" y1="12" x2="4" y2="12"></line></svg><span>내 위치</span></div>

<div class="bgddd">
		<div class="store_wrap">
			<h3>주변매장 검색결과</h3>
			<ul>
				<?
				//전체중 거리에서 가까운 순으로
				//echo $mb_6;
				$sql = sql_query("SELECT * , ( 6371 * ACOS( COS( RADIANS( {$lat} ) ) * COS( RADIANS( lat ) ) * COS( RADIANS( lng ) - RADIANS( {$lng} ) ) + SIN( RADIANS( {$lat} ) ) * SIN( RADIANS( lat ) ) ) ) AS distance FROM shop_member {$sql_search2} having distance <= 1 ");

				//echo "SELECT * , ( 6371 * ACOS( COS( RADIANS( {$lat} ) ) * COS( RADIANS( lat ) ) * COS( RADIANS( lng ) - RADIANS( {$lng} ) ) + SIN( RADIANS( {$lat} ) ) * SIN( RADIANS( lat ) ) ) ) AS distance FROM shop_member having distance <= 1 "; 

					while($row = sql_fetch_array($sql)){
				?>
				<li>
					<a href="<? echo MS_MBBS_URL; ?>/reserve_step2.php?type=<? echo $type; ?>&id=<? echo $row['id']; ?>">
						<span></span>
						<img src="<? echo MS_DATA_URL; ?>/store_img/<? echo $row['store_thumb']; ?>" width="115" height="100" class="store_thumb">
						<div class="store_info">
							<div class="store_link">
								<h2><? echo $row['name']; ?></h2>
								<p class="addr"><? echo $row['addr1']; ?> <? echo $row['addr2']; ?></p>
								<p><? echo $row['telephone']; ?></p>
							</div>
							<ul class="store_bttn">
								<li class="call"><a><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path></svg><span>전화걸기</span></a></li>
								<!--<li class="share"><a><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-share" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="6" r="3"></circle><circle cx="18" cy="18" r="3"></circle><line x1="8.7" y1="10.7" x2="15.3" y2="7.3"></line><line x1="8.7" y1="13.3" x2="15.3" y2="16.7"></line></svg><span>공유</span></a></li>-->
							</ul>
						</div>
					</a>
				</li>
				<? } ?>
			</ul>
		</div>
</div>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
	?>
</div>

<script>
function js_qna(id){
	var $con = $("#sod_qa_con_"+id);
	if($con.is(":visible")) {
		$con.hide();
	} else {
		$(".sod_qa_con:visible").hide();
		$con.show();
	}
}

var mb_id = "<? echo $member['id']; ?>";
var type = "<? echo $type; ?>";
var mb_6 = "<? echo $lat; ?>";
var mb_7 = "<? echo $lng; ?>";
//console.log(mb_id, type);

var mapContainer = document.getElementById('map'), // 지도를 표시할 div  
    mapOption = { 
        center: new kakao.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
        level: 3 // 지도의 확대 레벨
};

var map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

$(".c_location").on("click", function(){
	geoFindMe();
})

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
					//$("#address").val(result[0].address.address_name);
					console.log(lat, lon, result[0].address.address_name);
					$.get("/bbs/addr_update.php?mb_id="+mb_id+"&lat="+lat+"&lng="+lon+"&addr="+result[0].address.address_name, function(data){});

					var iwContent = '<div style="padding:5px;">현재 위치</div>', // 인포윈도우에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
    				iwPosition = new kakao.maps.LatLng(lat, lon); //인포윈도우 표시 위치입니다

					// 마커를 생성합니다
					var marker = new kakao.maps.Marker({  
						map: map, 
						position: coord
					}); 

					// 인포윈도우를 생성합니다
					var infowindow = new kakao.maps.InfoWindow({
						position : iwPosition, 
						content : iwContent 
					});

					// 마커 위에 인포윈도우를 표시합니다. 두번째 파라미터인 marker를 넣어주지 않으면 지도 위에 표시됩니다
					infowindow.open(map, marker); 

					map.setCenter(coord);    
					map.setLevel(6);

				}
			};

			geocoder.coord2Address(coord.getLng(), coord.getLat(), callback);


		});

	}

}

if(type == "location"){
	geoFindMe();
}

//지도관련
var map = new kakao.maps.Map(document.getElementById('map'), { // 지도를 표시할 div
    center : new kakao.maps.LatLng(mb_6, mb_7), // 지도의 중심좌표
    level : 4 // 지도의 확대 레벨
});

// 마커 클러스터러를 생성합니다
// 마커 클러스터러를 생성할 때 disableClickZoom 값을 true로 지정하지 않은 경우
// 클러스터 마커를 클릭했을 때 클러스터 객체가 포함하는 마커들이 모두 잘 보이도록 지도의 레벨과 영역을 변경합니다
// 이 예제에서는 disableClickZoom 값을 true로 설정하여 기본 클릭 동작을 막고
// 클러스터 마커를 클릭했을 때 클릭된 클러스터 마커의 위치를 기준으로 지도를 1레벨씩 확대합니다
var clusterer = new kakao.maps.MarkerClusterer({
    map: map, // 마커들을 클러스터로 관리하고 표시할 지도 객체
    averageCenter: true, // 클러스터에 포함된 마커들의 평균 위치를 클러스터 마커 위치로 설정
    minLevel: 5, // 클러스터 할 최소 지도 레벨
    disableClickZoom: true // 클러스터 마커를 클릭했을 때 지도가 확대되지 않도록 설정한다
});


// 데이터를 가져오기 위해 jQuery를 사용합니다
// 데이터를 가져와 마커를 생성하고 클러스터러 객체에 넘겨줍니다
var imageSrc = "https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/markerStar.png"; 

// 마커 이미지의 이미지 크기 입니다
var imageSize = new kakao.maps.Size(24, 35); 

// 마커 이미지를 생성합니다    
var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize); 

$.getJSON("/bbs/store_list_json.php?mb_6="+mb_6+"&mb_7="+mb_7, function(data) {

    // 데이터에서 좌표 값을 가지고 마커를 표시합니다
    // 마커 클러스터러로 관리할 마커 객체는 생성할 때 지도 객체를 설정하지 않습니다
    var markers = $(data.positions).map(function(i, position) {
        //console.log(position.lat, position.lng);
        var maks = new kakao.maps.Marker({
            position : new kakao.maps.LatLng(position.lat, position.lng),
			image : markerImage // 마커 이미지 
        });

		var iwContent = '<div style="padding:5px;">'+position.name+'</div>', // 인포윈도우에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
    	iwPosition = new kakao.maps.LatLng(position.lat, position.lng); //인포윈도우 표시 위치입니다

		// 인포윈도우를 생성합니다
		var infowindow = new kakao.maps.InfoWindow({
			position : iwPosition, 
			content : iwContent 
		});

		// 마커 위에 인포윈도우를 표시합니다. 두번째 파라미터인 marker를 넣어주지 않으면 지도 위에 표시됩니다
		infowindow.open(map, maks); 


        return maks;


    });

    clusterer.addMarkers(markers);

});

    

// 마커 클러스터러에 클릭이벤트를 등록합니다
// 마커 클러스터러를 생성할 때 disableClickZoom을 true로 설정하지 않은 경우
// 이벤트 헨들러로 cluster 객체가 넘어오지 않을 수도 있습니다
kakao.maps.event.addListener(clusterer, 'clusterclick', function(cluster) {

    // 현재 지도 레벨에서 1레벨 확대한 레벨
    var level = map.getLevel()-1;

    // 지도를 클릭된 클러스터의 마커의 위치를 기준으로 확대합니다
    map.setLevel(level, {anchor: cluster.getCenter()});
    
});

</script>

<?php
if(!defined('_MALLSET_')) exit;
include_once(MS_LIB_PATH.'/thumbnail.lib.php');
$type = $_GET['type'];
$stx = $_GET['stx'];
$id = $_GET['id'];

$sql_common = " from g5_wzb3_room ";

$sql_search = " where store_mb_id = '{$id}' ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$store = sql_fetch("SELECT * FROM shop_member where id = '{$id}' ");

$minishop = sql_fetch("SELECT * FROM shop_minishop where mb_id = '{$id}' ");

$file_save_dir  = '/wzb_room/';
$file_save_path = MS_DATA_PATH.$file_save_dir;


function passing_time($datetime) {
	$time_lag = time() - strtotime($datetime);
	
	if($time_lag < 60) {
		$posting_time = "방금";
	} elseif($time_lag >= 60 and $time_lag < 3600) {
		$posting_time = floor($time_lag/60)."분 전";
	} elseif($time_lag >= 3600 and $time_lag < 86400) {
		$posting_time = floor($time_lag/3600)."시간 전";
	} elseif($time_lag >= 86400 and $time_lag < 2419200) {
		$posting_time = floor($time_lag/86400)."일 전";
	} else {
		$posting_time = date("y-m-d", strtotime($datetime));
	} 
	
	return $posting_time;
}

?>
<style>
	.top_content{
		width: 100%;
		margin: 0 auto;
	}
	.top_content h2{
		text-align: center;
		font-size: 20px;
		margin-bottom: 20px;
	}
	.img_box{
		text-align:center; 
		width:100%;
		/*height:300px;*/
		background:#ddd;
	}
	.img_box img{
		width:100%;
		height:275px;
	}
	.store_top_info{
		/*background:#eee;*/
		padding:30px 20px;
	}
	.store_top_info p{
		font-size:15px;
		margin-bottom:10px;
	}
	.store_top_info p a{
		text-decoration:none;
		cursor:pointer;
	}
	.store_top_info .icon-tabler{
		float:left; 
		margin-right:10px;
	}
	.store_content_wrap:after{
		content:"";
		display:block;
		clear:both;
	}
	.store_wrap{
		text-align:center;
		background:#eee;
	}
	.store_wrap h3{
		font-size:1rem; 
		padding-top:40px;
	}
	.store_content_wrap{
		margin: 30px 0;
	}

	.btn_box{
		margin-top:20px;
	}
	.reserve_btn{
		border: 1px solid #ec008c;
		background: #ec008c;
		color: white!important;
		padding: 8px;
		margin-right: 15px;
		border-radius:20px;
	}
	.cancel_btn{
		/*border:1px solid #555;*/
		background:#fff;
		padding: 8px 10px;
		 border-radius:20px;
	}
	.reserve_btn:hover, .cancel_btn:hover{
		text-decoration:none;
		font-weight:600;
	}
	.btn_box svg{
		position:relative;
		top:6px;
	}
	.top_btn{
		text-align: center;
    	margin-bottom: 40px;
	}
	.top_btn a{
		font-size: 14px;
		font-weight: 600;
		display: inline-block;
		margin-right: 30px;
		margin-top: 20px;
	}
	.top_btn a:hover{
		text-decoration:none!important;
	}
	.top_btn_active{
		border-bottom: 2px solid #333;
    	padding-bottom: 5px;
	}
	#submit_form{
		text-align: center;
	}
	#submit_form input[type="text"]{
		border: 1px solid #ec008c;
    	padding: 8px;
	}
	#submit_form input[type="submit"]{
		border: 1px solid #ec008c;
		background: #ec008c;
		color: white;
		padding: 8px;
	}
	.store_thumb{
		float:left;
		margin-right: 30px;
	}
	.store_thumb img{
	width:100px;
	border-radius:50px;
	}
	.store_wrap ul{
		width: 90%;
		margin: 0 auto;
		margin-top: 15px; 
	}
	.store_wrap li{
		padding-top:20px; 
		border-bottom:2px dotted #fff;
	}
	.store_info h2{
		font-size: 18px;
	}
	.store_info .desc{
		font-size: 14px;
    	margin: 10px 0;
	}


	ul.tabs {    margin: 0;    padding: 100px 0 0 0;    float: left;    list-style: none;    height: 45px;    border-bottom: 1px solid #eee;    border-left: 1px solid #eee;    width: 100%;    font-family:"dotum";    font-size:12px;}
ul.tabs li {    float: left;    text-align:center;    cursor: pointer;    width:120px;    height: 45px;    line-height: 45px;  font-weight: bold;    overflow: hidden;    position: relative; font-size:1rem; color:#aaa;}
ul.tabs li.active {border-bottom: 1px solid #FFFFFF; color:#454545;}
.tab_container {    border: 1px solid #eee;    border-top: none;    clear: both;    float: left;    width:100%;    background: #FFFFFF;}
.tab_content {    padding: 5px;    font-size: 12px;}
/*.tab_container .tab_content ul {    width:100%;    margin:0px;    padding:0px;}
.tab_container .tab_content ul li {    padding:5px;    list-style:none}*/
;
 #container {    width: 249px;    margin: 0 auto;}
ul.tabs li.active:after{    content: "";    position: absolute;    z-index: -1;    bottom: 12px;    width:60px;height: 10px;    background-color: #ffbee5;	left: 30px;    right: 0;}

/*리뷰 css*/
.panel_shop_review .item_shop_review {    padding: 0 16px;}
.group_starScore.large {    text-align: center; padding-top:24px;}
.group_starScore {    line-height: 0;}
.screen_out {    position: absolute;    width: 0;    height: 0;}
.ir_caption, .screen_out {    overflow: hidden;    line-height: 0;    text-indent: -9999px;}
.group_starScore.large .txt_score {    margin-left: 0;    font-family: KHDS-Bold,system-ui,AppleSDGothicNeo-Regular,맑은 고딕,Malgun Gothic,돋움,dotum,sans-serif;    font-size: 48px;    line-height: 57px;    vertical-align: top;	display: inline-block; font-weight:900;}
.group_starScore.large {    text-align: center;}
.group_starScore.large .item_score {    float: none;    margin: 0 auto;	font-size: 0;}
.group_starScore.large .item_score .ico_hair {    width: 22px;    height: 22px;    margin-right: 0;    background: url('../../img/star-fill-grey.png');	position: relative;	margin: 2px 1px 0 0;	display: inline-block;    overflow: hidden;    font-size: 0;    line-height: 0;	text-indent: -9999px;    vertical-align: top;}
.group_starScore .item_score .ico_hair:after {    position: absolute;    top: 0;    right: 0;    bottom: 0;    left: 0;    width: 100%;    background-image: url('../../img/star-fill.png');    background-size: cover;    content: "";}
.group_starScore .item_score .ico_hair.ico_half:after {    width: 50%;}

.group_photo_review {padding-top: 32px; text-align:left;}
.item_shop_review .review_num {    padding-top: 10px;    font-size: 15px;    line-height: 18px;    color: #888;    text-align: center;}
.desc_box_menu{padding-top:10px;text-align:center;}
.bundle_reviewImageList {    overflow: hidden;}
.bundle_reviewImageList2 {
	/* width: 30%;
    position: absolute;
    top: 50%;
    left: 40%; */
	width: 90%;
    position: relative;
    top: 50%;
    margin: 0 auto;
	z-index: 9000;
}
.swiper-container {    margin-left: auto;    margin-right: auto;    position: relative;    overflow: hidden;    list-style: none;    padding: 0;    z-index: 1;}
.bundle_reviewImageList .swiper-wrapper {    width: auto;}
.swiper-container-android .swiper-slide, .swiper-wrapper {    transform: translateZ(0);}
.swiper-wrapper {    position: relative;    height: 100%;    z-index: 1;    display: flex;    transition-property: transform;    box-sizing: content-box;}
.title_group {    position: relative;    overflow: hidden;    display: block;    padding: 0 16px 16px;  font-size: 16px;    line-height: 19px;}
.title_group .btn_more {    float: right;    margin-top: 2px;  font-size: 14px;    line-height: 17px;    color: #888;}
/*리뷰>예약고객리뷰*/
.group_txt_review {    padding-top: 32px;    overflow: hidden;}
.title_group {    position: relative;    overflow: hidden;    display: block;    padding: 0 16px 16px;    font-size: 16px;    line-height: 19px;}
.group_txt_review .title_group:after {    position: absolute;    bottom: 0;    right: 16px;    left: 16px;    height: 1px;    background-color: #f3f3f3;    content: "";}
.item_review_filter {    position: relative;    height: 39px;}
.item_review_filter .inner_review_filter {    padding: 16px 16px 4px;    overflow: hidden;    background-color: #fff;}
.item_review_filter .txt_review_amount {    font-size: 14px;    line-height: 17px;    color: #111;}
.item_review_filter .piece_review_filter {    top: 18px;}
.piece_review_filter {    position: absolute;    top: 0;    right: 16px;}
.piece_review_filter .label_item {    position: absolute;    top: 50%;    right: 14px;    font-family: KHDS-Regular,system-ui,AppleSDGothicNeo-Regular,맑은 고딕,Malgun Gothic,돋움,dotum,sans-serif;    font-weight: 400;    font-size: 14px;    color: #555;    transform: translateY(-50%);}
.piece_review_filter .ico_arrow {    position: absolute;    top: 50%;    right: 0;    margin-top: -3px;    width: 9px;    height: 6px;    background: url('../../img/tabler-icon-chevron-down.png');}
.ico_hair {    display: inline-block;    overflow: hidden;    font-size: 0;    line-height: 0;    background-repeat: no-repeat;    text-indent: -9999px;    vertical-align: top;}
.item_card_review {    position: relative;    overflow: hidden;    padding: 0 16px;    background-color: #fff;}
.item_card_review .info_card_review {    overflow: hidden;    padding: 24px 0;}
.item_card_review .info_card_review .cover_tit_info {    position: relative;    display: inline-block;    overflow: hidden;    margin-bottom: 8px;    font-size: 0;    vertical-align: top;}
.item_card_review .info_card_review .cover_tit_info .designer_info {    float: right;    margin: 2px 0 0 6px;    font-size: 14px;    color: #888;    line-height: 18px;    vertical-align: top;}
.item_card_review .info_card_review .cover_tit_info .tit_info {    display: -webkit-box;    overflow: hidden;    font-family: KHDS-Medium,system-ui,AppleSDGothicNeo-Regular,맑은 고딕,Malgun Gothic,돋움,dotum,sans-serif;    font-weight: 500;    font-size: 16px;    color: #111;    line-height: 19px;    -webkit-box-orient: vertical;    -webkit-line-clamp: 1;}
.item_card_review .info_card_review .group_starScore {    margin-bottom: 6px;}
.group_starScore {    line-height: 0;}
.screen_out {    position: absolute;    width: 0;    height: 0;}
.ir_caption, .screen_out {    overflow: hidden;    line-height: 0;    text-indent: -9999px;}
.group_starScore .txt_score {    display: inline-block;    margin-left: 3px;    font-size: 15px;    line-height: 18px;    vertical-align: top;}
.group_starScore .item_score {    float: left;    font-size: 0;}
.group_starScore .item_score .ico_hair {    position: relative;    width: 13px;    height: 13px;    margin: 2px 1px 0 0;    background: url(data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 13 13'%3E%3Cpath fill='%23CCC' fill-rule='evenodd' d='M10.1107752,12.2119099 L6.50019397,9.58882336 L2.88892849,12.2119099 C2.76919157,12.3000751 2.60634936,12.2993917 2.48661244,12.2119099 C2.36687552,12.1244281 2.31624391,11.9699681 2.36277026,11.8291771 L3.7503501,7.61364877 L0.142505669,5.02951897 C0.0220845385,4.94272064 -0.0292312841,4.78826062 0.0166108507,4.64678618 C0.0617687745,4.50531173 0.19313728,4.40962854 0.342295271,4.40962854 L4.80540341,4.40962854 L6.17450955,0.211186487 C6.26687803,-0.0703954958 6.73350991,-0.0703954958 6.82519418,0.211186487 L8.19498454,4.40962854 L12.6580927,4.40962854 C12.8065665,4.40962854 12.937935,4.50531173 12.9837771,4.64678618 C13.028935,4.78826062 12.9776192,4.94272064 12.8571981,5.02951897 L9.24935364,7.61364877 L10.6369335,11.8291771 C10.6834598,11.9699681 10.6335124,12.1244281 10.5130913,12.2119099 C10.4535649,12.2556508 10.3830912,12.2775212 10.3119333,12.2775212 C10.2414595,12.2775212 10.1703016,12.2556508 10.1107752,12.2119099 Z'/%3E%3C/svg%3E%0A);}
.ico_hair {    display: inline-block;    overflow: hidden;    font-size: 0;    line-height: 0;    background-repeat: no-repeat;    text-indent: -9999px;    vertical-align: top;}
.group_starScore .item_score .ico_hair:after {    position: absolute;    top: 0;    right: 0;    bottom: 0;    left: 0;    width: 100%;    background-image: url(data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 13 13'%3E%3Cpath fill='%23111' fill-rule='evenodd' d='M10.1107752,12.2119099 L6.50019397,9.58882336 L2.88892849,12.2119099 C2.76919157,12.3000751 2.60634936,12.2993917 2.48661244,12.2119099 C2.36687552,12.1244281 2.31624391,11.9699681 2.36277026,11.8291771 L3.7503501,7.61364877 L0.142505669,5.02951897 C0.0220845385,4.94272064 -0.0292312841,4.78826062 0.0166108507,4.64678618 C0.0617687745,4.50531173 0.19313728,4.40962854 0.342295271,4.40962854 L4.80540341,4.40962854 L6.17450955,0.211186487 C6.26687803,-0.0703954958 6.73350991,-0.0703954958 6.82519418,0.211186487 L8.19498454,4.40962854 L12.6580927,4.40962854 C12.8065665,4.40962854 12.937935,4.50531173 12.9837771,4.64678618 C13.028935,4.78826062 12.9776192,4.94272064 12.8571981,5.02951897 L9.24935364,7.61364877 L10.6369335,11.8291771 C10.6834598,11.9699681 10.6335124,12.1244281 10.5130913,12.2119099 C10.4535649,12.2556508 10.3830912,12.2775212 10.3119333,12.2775212 C10.2414595,12.2775212 10.1703016,12.2556508 10.1107752,12.2119099 Z'/%3E%3C/svg%3E%0A);    background-size: cover;    content: "";}
.item_card_review .info_card_review .cont_card_review {    overflow: hidden;    margin-bottom: 1px;}
.item_card_review .info_card_review .cont_card_review .txt_cont {    display: block;    overflow: hidden;    position: relative;    margin-bottom: 6px;    font-size: 15px;    line-height: 22px;    color: #111;    white-space: pre-wrap;}
.item_card_review .cover_info {    display: inline-block;    height: 17px;}
.screen_out {    position: absolute;    width: 0;    height: 0;}
.ir_caption, .screen_out {    overflow: hidden;    line-height: 0;    text-indent: -9999px;}
.item_card_review .cover_info .txt_info:first-of-type {    padding-left: 0;}
.item_card_review .txt_info {    position: relative;    display: inline-block;    padding: 0 7px 0 5px;    font-size: 14px;    line-height: 17px;    color: #888;    vertical-align: top;}
.item_card_review .txt_info:after {    position: absolute;    top: 4px;    right: 0;    width: 1px;    height: 10px;    background-color: #f3f3f3;    content: "";}
.item_card_review .txt_info:last-child {    padding-right: 5px;}
.item_card_review .txt_info {    position: relative;    display: inline-block;    padding: 0 7px 0 5px;    font-size: 14px;    line-height: 17px;    color: #888;    vertical-align: top;}
.item_card_review .btn_report {    position: relative;    display: inline-block;    padding: 0 6px;    font-size: 14px;    line-height: 17px;    color: #888;    vertical-align: top;}
.item_card_review .btn_report:before {    position: absolute;    top: 4px;    left: 0;    width: 1px;    height: 10px;    background-color: #f3f3f3;    content: "";}
.item_card_review .info_card_review:after {    position: absolute;    bottom: 0;    left: 16px;    right: 16px;    height: 1px;    background-color: #f3f3f3;    content: "";}
.item_card_review .reply_card_review {    position: relative;    padding: 10px 0 16px;}
.item_card_review .reply_card_review .inner_reply {    padding: 15px 16px 16px;    background-color: #f7f7f7;    border-radius: 0 3px 3px 3px;}
.item_card_review .reply_card_review .inner_reply .head_reply {    display: -moz-box;    display: flex;    margin-bottom: 8px;    font-size: 0;    -moz-box-align: center;    align-items: center;}
.item_card_review .reply_card_review .inner_reply .head_reply .thumb_head {    display: inline-block;    overflow: hidden;    width: 42px;    height: 42px;    margin-right: 8px;    border-radius: 50%;}
.img_g, .link_g {    display: block;}
.img_g { width: 100%;}
fieldset, img { border: 0;}
.item_card_review .reply_card_review .inner_reply .head_reply .designer_head { display: inline-block;    margin: 0 10px 0 0;    font-size: 15px;    font-weight: 500;    line-height: 21px;    vertical-align: top;}
.item_card_review .reply_card_review .inner_reply .cont_reply { margin-bottom: 8px;    font-size: 15px;    line-height: 22px;    color: #111;    white-space: pre-wrap;}
.item_card_review .cover_info { display: inline-block;    height: 17px;}

.item_review{margin-right: 6px;}
.item_review>div{display: block; position: static;}


.img_gray_box{
	background: rgba(0,0,0,0.4);
    width: 100%;
    height: 100%;
    position: absolute;
	z-index: 1000;
}

.img_gray_box .big_img{
	position: absolute;
    top: 50%;
    left: 40%;
}
.review_close{
	position: absolute;
    z-index: 9;
    top: 10px;
    right: 10px;
    cursor: pointer;
}

</style>
<div id="con_lf">
<!--
	<div class="top_btn">
		<a href="./reserve_step1.php?stx=<? echo $stx; ?>&type=search" class="<? echo $type == "search" || $type == "" ? "top_btn_active":""; ?>">매장검색으로 찾기</a>
		<a href="./reserve_step1.php?stx=<? echo $stx; ?>&type=location" class="<? echo $type == "location" ? "top_btn_active":""; ?>">내 주변 매장 찾기</a>
	</div>
-->
	<div class="top_content">
		<div class="img_box">
			<img src="<? echo MS_DATA_URL; ?>/store_img/<? echo $store['store_thumb']; ?>" width="700">
		</div>
		<div class="store_top_info">
			<h2><? echo $store['name']; ?>미용실</h2>
			<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path></svg>
			<p><? echo $store['telephone']; ?>0100000000</p>
			<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="11" r="3"></circle><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path></svg>
			<p><? echo $row['addr1']; ?> <? echo $row['addr2']; ?>서울시 강남구 도곡동&nbsp;&nbsp;&nbsp;<a href="#"><span>지도보기</span></a></p>
			<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="9"></circle><polyline points="12 7 12 12 15 15"></polyline></svg>
			<? if($minishop['company_hours']){ ?>
			<p><? echo $minishop['company_hours']; ?></p>
			<? } ?>
			<? if($minishop['company_url']){ ?>
			<p><a href="<? echo $minishop['company_url']; ?>"><? echo $minishop['company_url']; ?></a></p>
			<? } ?>
		</div>
	</div>

	<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
	<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
	<!-- #container 시작 -->
	<div id="container">
	<ul class="tabs">
		<li id="stab0" onClick="ViewLayer0()" class="active">HOME</li>
		<li id="stab1" rel="tab1" onclick="ViewLayer1()">디자이너</li>
		<li id="stab2" rel="tab2" onclick="ViewLayer2()">리뷰</li>
	</ul>

	<div class="tab_container">
		<div id="tab1" class="tab_content">
			<div class="store_wrap">
				<h3>디자이너</h3>
				<ul>
					<?
						while($row = sql_fetch_array($result)){
							// 시설이미지
							$query2 = "select rmp_photo from g5_wzb3_room_photo where rm_ix = '{$row['rm_ix']}' order by rmp_ix asc limit 1";
							$rmp = sql_fetch($query2);
							$bimg = $file_save_path.$rmp['rmp_photo'];
							if (file_exists($bimg) && $rmp['rmp_photo']) {
								$file_name_thumb = thumbnail($rmp['rmp_photo'], $file_save_path, $file_save_path, 80, 80, true, true);
								$designImg = MS_DATA_URL.$file_save_dir.$file_name_thumb;
							}
					?>
					<li>
						<div class="store_content_wrap">
							<div class="store_thumb">
								<img src="<?php echo $designImg; ?>">
							</div>
							<div class="store_info">
								<h2><? echo $row['rm_subject']; ?></h2>
								<p class="desc"><? echo $row['rm_desc']; ?></p>
							</div>
							<div class="btn_box">
								<a href="<? echo MS_MBBS_URL; ?>/board.php?bo_table=tttttt&id=<? echo $id; ?>&rm_ix=<? echo $row['rm_ix']; ?>" class="reserve_btn">
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-plus" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><rect x="4" y="5" width="16" height="16" rx="2"></rect><line x1="16" y1="3" x2="16" y2="7"></line><line x1="8" y1="3" x2="8" y2="7"></line><line x1="4" y1="11" x2="20" y2="11"></line><line x1="10" y1="16" x2="14" y2="16"></line><line x1="12" y1="14" x2="12" y2="18"></line></svg>
									예약
								</a>
								<a href="<? echo MS_MBBS_URL; ?>/board.php?bo_table=tttttt&mode=orderlist" class="cancel_btn">
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-minus" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><rect x="4" y="5" width="16" height="16" rx="2"></rect><line x1="16" y1="3" x2="16" y2="7"></line><line x1="8" y1="3" x2="8" y2="7"></line><line x1="4" y1="11" x2="20" y2="11"></line><line x1="10" y1="16" x2="14" y2="16"></line></svg>
									예약취소
								</a>
							</div>
						</div>
					</li>
					<? } ?>
				</ul>
			</div>
		</div>
		<?
			$book = sql_fetch("SELECT AVG(score) as score, count(*) as cnt, count(review_file) as rcnt FROM review_list where booking_id = '{$id}' ");
			$rcnt = sql_fetch("SELECT count(*) as cnt FROM review_list where booking_id = '{$id}' and review_file <> '' ");

		?>
		<div id="tab2" class="tab_content">
			<div class="store_wrap">
        	<h3 style="text-align:center;">리뷰</h3>
				<div class="item_shop_review">
					<div class="group_starScore large">
						<strong class="screen_out">별점</strong>
						<span class="txt_score num_g"><? echo number_format(floor($book['score'])); ?></span>
						<div class="item_score">
							<? for($i=0; $i<floor($book['score']); $i++){ ?>
								<span class="ico_hair"></span>
							<? } ?>
						</div>
					</div>
					<div class="review_num">
						<span class="num_g"><? echo number_format($book['cnt']); ?></span>개
					</div>
					<p class="desc_box_menu">시술완료한 회원의 리뷰입니다.</p>
				</div>

				<div class="group_photo_review">
					<strong class="title_group">포토리뷰
						<span class="num_b"><? echo number_format($rcnt['cnt']); ?></span>
						<!-- <button type="button" class="btn_more">전체보기</button> -->
					</strong>
					<div class="bundle_reviewImageList">
						<div class="mySwiper swiper-container swiper-container-initialized swiper-container-horizontal">
						<div class="swiper-wrapper">

							<?
								$photo = sql_query("SELECT * FROM review_list where booking_id = '{$id}' and review_file <> '' order by re_idx desc ");
								for($n=0; $p_row = sql_fetch_array($photo); $n++){

							?>
								<button class="swiper-slide item_review" style="margin-right: 6px;" data-img="<? echo $p_row['review_file']; ?>" num="<? echo $n+1; ?>">
									<div style="display: block; position: static;">
										<img src="<? echo MS_URL; ?>/review_img/<? echo $p_row['thumbnail_img']; ?>" alt="" class="img_g">
									</div>
								</button>
								
							<? } ?>

						</div>

					</div>
					<div class="group_txt_review">
						<strong class="title_group">예약고객 리뷰<!-- --> <span class="num_b"><? echo number_format($book['cnt']); ?></span></strong>
						<div class="item_review_filter">
							<div class="inner_review_filter">
								<span class="txt_review_amount">전체<!-- --> <span class="num_g"><? echo number_format($book['cnt']); ?></span></span>
							</div>
						</div>
						<div class="bunch_card_review">
							<?
								$review = sql_query("SELECT * FROM review_list where booking_id = '{$id}' order by re_idx desc ");
								while($r_row = sql_fetch_array($review)){

									$boo = sql_fetch("SELECT bk_subject FROM g5_wzb3_booking where bk_ix = '{$r_row['bk_ix']}' ");

									$boo2 = sql_fetch("SELECT * FROM g5_wzb3_booking_option where bk_ix = '{$r_row['bk_ix']}' ");

									$boo3 = sql_fetch("SELECT count(*) as cnt FROM review_list where bk_ix = '{$r_row['bk_ix']}' and mb_id = '{$r_row['mb_id']}' ");

									$passTime = passing_time($r_row['reg_time']);
									$mb = get_member($r_row['mb_id']);
									$first = mb_substr($mb['name'],0,1);
									$last = mb_substr($mb['name'],1,1);

									$masking = $first."*".$last;
							?>
							<div class="item_card_review">
								<div class="info_card_review">
									<div class="cover_tit_info">
										<span class="designer_info" style="cursor: pointer;"><? echo $boo['bk_subject']; ?></span>
										<strong class="tit_info"><? echo $boo2['odo_name']; ?></strong>
									</div>
									<div class="group_starScore">
										<strong class="screen_out">별점</strong>
										<span class="txt_score num_g"><? echo number_format($r_row['score']); ?></span>
										<div class="item_score">
											<? for($i=0; $i<$r_row['score']; $i++){ ?>
											<span class="ico_hair"></span>
											<? } ?>

										</div>
									</div>
									<div class="cont_card_review">
										<p class="txt_cont"><? echo $r_row['memo']; ?></p>
									</div>
									<dl class="cover_info">
										<dt class="screen_out">작성자</dt>
										<dd class="txt_info"><? echo $masking; ?></dd>
										<dt class="screen_out">작성 시간</dt>
										<dd class="txt_info"><? echo $passTime; ?></dd>
										<dt class="screen_out">방문 수</dt>
										<?
											if($boo3['cnt'] > 1){
										?>
										<dd class="txt_info">재예약</dd>
										<? }else{ ?>
										<dd class="txt_info">신규예약</dd>
										<? } ?>
									</dl>
								</div>
							</div>
							<? } ?>
							
						</div>
					</div>
				</div>
			</div>
    	</div>
    <!-- #tab3 -->
  </div>
    <!-- .tab_container -->

		<?php
		echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
		?>


</div><!--end-->
<script>

	$(function(){

		var swiper = new Swiper(".mySwiper", {
			slidesPerView: 6,
			spaceBetween: 30,
			freeMode: true,
			pagination: {
				el: ".swiper-pagination",
				clickable: true,
			},
			/* on: {
				progress: function() {
					num = "<? echo $num; ?>";
					pos = 930;
					innerTranslate = "-"+pos;
					document.getElementsByClassName("bundle_reviewImageList2").style.transform =
						"translate3d(" + innerTranslate + "px, 0, 0)";    
				}
			}, */
		});


		var swiper2 = new Swiper(".mySwiper2", {
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev",
			},
		});


		$(".item_review").on("click", function(){
			img_url = $(this).attr("data-img");
			img_path = "<? echo MS_URL; ?>/review_img/"+img_url;

			swiper2.slideTo($(this).attr("num")-1, 1000);
/* 
			num = $(this).attr("num");
			swiper.slideTo(num, 0);
			
			$("#bundle_reviewImageList").css("transform", "translate3d(-1860px, 0px, 0px)"); */

			$("body").css("position","relative");
			$(".img_gray_box .big_img").attr("src", img_path);
			$(".img_gray_box").show();
		})

		$(".review_close").on("click", function(){
			$("body").css("position","");
			$(".img_gray_box").hide();
		})

	})

</script>
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

function ViewLayer0(){
	document.getElementById("stab0").setAttribute('class','active');
	document.getElementById("stab1").setAttribute('class','');
	document.getElementById("stab2").setAttribute('class','');
	document.getElementById("tab1").style.display='inline';
	document.getElementById("tab2").style.display='inline'
}

function ViewLayer1(){
	document.getElementById("stab0").setAttribute('class','');
	document.getElementById("stab1").setAttribute('class','active');
	document.getElementById("stab2").setAttribute('class','');
	document.getElementById("tab2").style.display='none';
	if(document.getElementById("tab1").style.display=="none"){
		document.getElementById("tab1").style.display='inline'
	}
}

function ViewLayer2(){
	document.getElementById("stab0").setAttribute('class','');
	document.getElementById("stab1").setAttribute('class','');
	document.getElementById("stab2").setAttribute('class','active');
	document.getElementById("tab1").style.display='none';
	if(document.getElementById("tab2").style.display=="none"){
	document.getElementById("tab2").style.display='inline'
	}
}

/*
$(function () { //탭메뉴
    $(".tab_content").hide();
    $(".tab_content:first").show();

    $("ul.tabs li").click(function () {
        $("ul.tabs li").removeClass("active").css("color", "#aaa");
        //$(this).addClass("active").css({"color": "darkred","font-weight": "bolder"});
        $(this).addClass("active").css("color", "#454545");
        $(".tab_content").hide()
        var activeTab = $(this).attr("rel");
        $("#" + activeTab).fadeIn()
    });
});
*/

function js_qna(id){
	var $con = $("#sod_qa_con_"+id);
	if($con.is(":visible")) {
		$con.hide();
	} else {
		$(".sod_qa_con:visible").hide();
		$con.show();
	}
}
</script>

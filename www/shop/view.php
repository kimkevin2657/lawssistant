<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MSHOP_URL.'/view.php?gs_id='.$index_no);
}

if($member['id']){
	if($gs_id){
		// 기존 장바구니 자료를 먼저 삭제
		$sql = "select * from shop_cart where gs_id='$gs_id' and ct_select='0' and ct_direct='{$member['id']}'";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			 $gradeInfo = sql_fetch("select * from shop_order
					  where od_id = '{$row['od_id']}'
						and od_no = '{$row['od_no']}'
						and gs_id = '{$row['gs_id']}'
						and dan = '0' ");
			if($gradeInfo['od_id']){
				add_io_stock($row['od_no'], $row['od_id']);
			}
			$sql = " delete from shop_order
					  where od_id = '{$row['od_id']}'
						and od_no = '{$row['od_no']}'
						and gs_id = '{$row['gs_id']}'
						and dan = '0' ";
			sql_query($sql, FALSE);
		}
	}
}
$is_seometa = 'it'; // SEO 메타태그

$gs = get_goods($index_no);
if(!$gs['index_no'])
	alert('등록된 상품이 없습니다');
else if(!is_admin() && $gs['shop_state'])
	alert('현재 판매가능한 상품이 아닙니다.');


// 오늘 본 상품 저장 시작
if(get_cookie('ss_pr_idx')) {
	$arr_ss_pr_idx = get_cookie('ss_pr_idx');
	$arr_tmps = explode("|",$arr_ss_pr_idx);
	if(!in_array($index_no,$arr_tmps)) {
		$ss_pr_idx = $index_no."|".get_cookie('ss_pr_idx');
		set_cookie('ss_pr_idx', $ss_pr_idx, 86400 * 1);
	}
} else {
	set_cookie('ss_pr_idx', $index_no, 86400 * 1);
}

// 공급업체 정보
$sr = get_seller_cd($gs['mb_id']);
if($gs['use_aff']) {
	$sr = get_minishop($gs['mb_id']);
}

$goods_kv_basic = $gs['goods_kv_basic'];
$gpoint_basic = $gs['gpoint_basic'];

include $_SERVER["DOCUMENT_ROOT"]."/extend/_point_kv.php";


//상품평 건수 구하기
$sql = "select count(*) as cnt from shop_goods_review where gs_id = '$index_no'";
if($default['de_review_wr_use']) {
	$sql .= " and pt_id = '$pt_id' ";
}
$row = sql_fetch($sql);
$item_use_count = (int)$row['cnt'];

// 고객선호도 별점수
$star_score = get_star_image($index_no);

// 고객선호도 평점
$aver_score = ($star_score * 10) * 2;

// 대표 카테고리
$sql = "select * from shop_goods_cate where gs_id='$index_no' order by index_no asc limit 1 ";
$ca = sql_fetch($sql);

// 상품조회 카운터하기
sql_query("update shop_goods set readcount = readcount + 1 where index_no='$index_no'");

// 페이지경로
$navi = "<a href='".MS_URL."' class='fs11'>HOME</a>".get_move($ca['gcate']);

// 수량체크
if(!$gs['stock_mod']) {
	$gs['stock_qty'] = 999999999;
}

if($gs['odr_min']) // 최소구매수량
	$odr_min = (int)$gs['odr_min'];
else
	$odr_min = 1;

if($gs['odr_max']) // 최대구매수량
	$odr_max = (int)$gs['odr_max'];
else
	$odr_max = 0;

$is_only = false;
$is_buy_only = false;
$is_pr_msg = false;
$is_social_end = false;
$is_social_ing = false;

// 품절체크
$is_soldout = is_soldout($index_no);

if($is_soldout) {
	$script_msg = "현재상품은 품절 상품입니다."."(".__LINE__.")";
} else {
	if($gs['price_msg']) {
		$is_pr_msg = true;
		$script_msg = "현재상품은 구매신청 하실 수 없습니다."."(".__LINE__.")";
	} else if($gs['buy_only'] == 1 && $member['grade'] > $gs['buy_level']) {
		$is_only = true;
		$script_msg = "현재상품은 구매신청 하실 수 없습니다."."(".__LINE__.")";
	} else if($gs['buy_only'] == 0 && $member['grade'] > $gs['buy_level']) {
		if(!$is_member) {
			$is_buy_only = true;
			$script_msg = "현재상품은 회원만 구매 하실 수 있습니다."."(".__LINE__.")";
		} else {
			$script_msg = "현재상품을 구매하실 권한이 없습니다."."(".__LINE__.")";
		}
	} else {
		$script_msg = "";
	}

	if(substr($gs['sb_date'],0,1) != '0' && substr($gs['eb_date'],0,1) != '0') {
		if($gs['eb_date'] < MS_TIME_YMD) {
			$is_social_end	= true;
			$is_social_txt	= "<span>[판매종료]</span>&nbsp;&nbsp;시작일 : ".substr($gs['sb_date'],0,4)."년 ";
			$is_social_txt .= substr($gs['sb_date'],5,2)."월 ";
			$is_social_txt .= substr($gs['sb_date'],8,2)."일 ~ ";
			$is_social_txt .= "종료일 : ".substr($gs['eb_date'],0,4)."년 ";
			$is_social_txt .= substr($gs['eb_date'],5,2)."월 ";
			$is_social_txt .= substr($gs['eb_date'],8,2)."일";

			$script_msg	= "현재 상품은 판매기간이 종료 되었습니다.";
		} else if($gs['sb_date'] > MS_TIME_YMD) {
			$is_social_end	= true;
			$is_social_txt	= "<span>[판매대기]</span>&nbsp;&nbsp;시작일 : ".substr($gs['sb_date'],0,4)."년 ";
			$is_social_txt .= substr($gs['sb_date'],5,2)."월 ";
			$is_social_txt .= substr($gs['sb_date'],8,2)."일 ~ ";
			$is_social_txt .= "종료일 : ".substr($gs['eb_date'],0,4)."년 ";
			$is_social_txt .= substr($gs['eb_date'],5,2)."월 ";
			$is_social_txt .= substr($gs['eb_date'],8,2)."일";

			$script_msg	= "현재 상품은 판매대기 상품 입니다.";
		} else if($gs['sb_date'] <= MS_TIME_YMD && $gs['eb_date'] >= MS_TIME_YMD) {
			$is_social_ing	= true;
		}
	}
}
// 필수 옵션
$option_item = get_item_options($index_no, $gs['opt_subject']);

// 추가 옵션
$supply_item = get_item_supply($index_no, $gs['spl_subject']);

// 가맹점상품은 쿠폰발급안함
if(!$gs['use_aff'] && $config['coupon_yes']) {
	$cp_used = is_used_coupon('0', $index_no);

	// 쿠폰발급 (적용가능쿠폰)
	if($is_member)
		$cp_btn = "<a href=\"".MS_SHOP_URL."/pop_coupon.php?gs_id=$index_no\" onclick=\"win_open(this,'win_coupon','670','500','yes');return false\" class=\"btn_ssmall bx-blue\">적용가능쿠폰</a>";
	else
		$cp_btn = "<a href=\"javascript:alert('로그인 후 이용 가능합니다.')\" class=\"btn_ssmall bx-blue\">적용가능쿠폰</a>";
}

/* 이용재 추가 : 가격비교 */
if($gs['compare']=="Y") {
  if($gs['compare_0']) {
    $compare_0 = explode(">", $gs['compare_0']);
  }
  if($gs['compare_1']) {
    $compare_1 = explode(">", $gs['compare_1']);
  }
  if($gs['compare_2']) {
    $compare_2 = explode(">", $gs['compare_2']);
  }
  if($gs['compare_3']) {
    $compare_3 = explode(">", $gs['compare_3']);
  }
  if($gs['compare_4']) {
    $compare_4 = explode(">", $gs['compare_4']);
  }
  if($gs['compare_5']) {
    $compare_5 = explode(">", $gs['compare_5']);
  }
  if($gs['compare_6']) {
    $compare_6 = explode(">", $gs['compare_6']);
  }
  if($gs['compare_7']) {
    $compare_7 = explode(">", $gs['compare_7']);
  }
  if($gs['compare_8']) {
    $compare_8 = explode(">", $gs['compare_8']);
  }
  if($gs['compare_9']) {
    $compare_9 = explode(">", $gs['compare_9']);
  }
}

// SNS
/*$sns_title = get_text($gs['gname']).' | '.get_head_title('head_title', $pt_id);
$sns_url = MS_SHOP_URL.'/view.php?index_no='.$index_no;
$sns_share_links .= get_sns_share_link('facebook', $sns_url, $sns_title, MS_IMG_URL.'/sns/facebook.gif');
$sns_share_links .= get_sns_share_link('twitter', $sns_url, $sns_title, MS_IMG_URL.'/sns/twitter.gif');
$sns_share_links .= get_sns_share_link('kakaostory', $sns_url, $sns_title, MS_IMG_URL.'/sns/kakaostory.gif');
$sns_share_links .= get_sns_share_link('naverband', $sns_url, $sns_title, MS_IMG_URL.'/sns/naverband.gif');
$sns_share_links .= get_sns_share_link('googleplus', $sns_url, $sns_title, MS_IMG_URL.'/sns/googleplus.gif');
$sns_share_links .= get_sns_share_link('naver', $sns_url, $sns_title, MS_IMG_URL.'/sns/naver.gif');
$sns_share_links .= get_sns_share_link('pinterest', $sns_url, $sns_title, MS_IMG_URL.'/sns/pinterest.gif'); */

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$ms['title'] = $gs['gname'];
include_once("./_head.php");
include_once(MS_LIB_PATH.'/goodsinfo.lib.php');
//include_once(MS_SHOP_PATH.'/settle_naverpay.inc.php');

Theme::get_theme_part(MS_THEME_PATH,'/view.skin.php');

include_once("./_tail.php");
?>
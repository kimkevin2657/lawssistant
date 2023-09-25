<?php
if(!defined('_MALLSET_')) exit;

function printMenu1($svc_class, $subject)
{
	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	return '<dt class="'.$svc_class.' menu_toggle">'.$subject.'</dt>';
}

function printMenu2($svc_class, $subject, $url, $menu_cnt='', $target='')
{
	global $pg_title2;

	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	if($pg_title2 == $subject)
		$svc_class .= ' active';

	$current_class = '';
	$count_class = '';
	if(is_numeric($menu_cnt)) {
		if($menu_cnt > 0)
			$current_class = ' class="snb_air"';
		$count_class = '<em'.$current_class.'>'.$menu_cnt.'</em>';
	}
	if($target == ''){
		$target = "_self";
	}

	return '<dd class="'.$svc_class.'"><a href="'.$url.'" target="'.$target.'">'.$subject.$count_class.'</a></dd>';
}

?>

<div id="snb">
	<div class="snb_header ico_config">
		<h2><?php echo $snb_icon; ?><?php echo $pg_title; ?></h2>
	</div>
	<?php
	if($pg_title == ADMIN_MENU1) { ?>
	<dl>
		<?php echo printMenu1('m10', '회원관리'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_01, MS_ADMIN_URL.'/member.php?code=list'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_02, MS_ADMIN_URL.'/member.php?code=level_form'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_03, MS_ADMIN_URL.'/member.php?code=register_form'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_04, MS_ADMIN_URL.'/member.php?code=xls'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_05, MS_ADMIN_URL.'/member.php?code=month'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_06, MS_ADMIN_URL.'/member.php?code=day'); ?>
		<?php echo printMenu1('m20', '쇼핑포인트관리'); ?>
        <?php echo printMenu2('m20', ADMIN_MENU1_07, MS_ADMIN_URL.'/member.php?code=point'); ?>

	</dl>
	<?php } 	
	else if($pg_title == ADMIN_MENU2) {
		$anewCnt = admRequest("shop_minishop");
		$termCnt = admRequest("shop_minishop_term");
		$runCnt  = admRequest("shop_minishop_payrun");
        $spRunCnt  = admRequest("shop_minishop_shopping_payrun");
	?>
	<dl>
		<?php echo printMenu1('p10', '미니샵 관리'); ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_01, MS_ADMIN_URL.'/minishop.php?code=pform'); ?>
	    <?php echo printMenu2('p10', ADMIN_MENU2_02, MS_ADMIN_URL.'/minishop.php?code=pbasic'); ?>
        <?php echo printMenu2('p10', ADMIN_MENU2_19, MS_ADMIN_URL.'/minishop.php?code=pcenter'); ?> 
		<?php echo printMenu2('p10', ADMIN_MENU2_05, MS_ADMIN_URL.'/minishop.php?code=plist'); ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_03, MS_ADMIN_URL.'/minishop.php?code=anewlist', $anewCnt); ?>
		<?php if($config['pf_expire_use']) { // 관리비를 사용중인가? ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_04, MS_ADMIN_URL.'/minishop.php?code=termlist', $termCnt); ?>
		<?php } ?>
	    <?php echo printMenu1('p20', '미니샵 수수료'); ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_06, MS_ADMIN_URL.'/minishop.php?code=paylist'); ?>
		<?php if($config['pf_payment_type']) { // 미니샵 직접출금 ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_08, MS_ADMIN_URL.'/minishop.php?code=payrun', $runCnt); ?>
		<?php } else { // 관리자 고정정산 ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_07, MS_ADMIN_URL.'/minishop.php?code=balancelist'); ?>
		<?php } ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_09, MS_ADMIN_URL.'/minishop.php?code=payhistory'); ?> 

        <?php echo printMenu1('p40', '쇼핑포인트관리'); ?>
        <?php echo printMenu2('p40', ADMIN_MENU1_07, MS_ADMIN_URL.'/minishop.php?code=point'); ?>

        <?php if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY ) : ?>
        <?php echo printMenu1('p50', '미니샵 쇼핑페이'); ?>
        <?php echo printMenu2('p50', ADMIN_MENU2_16, MS_ADMIN_URL.'/minishop.php?code=sphistory'); ?>
        <?php if( defined('USE_SHOPPING_PAY_EXCHANGE') && USE_SHOPPING_PAY_EXCHANGE ) echo printMenu2('p50', ADMIN_MENU2_17, MS_ADMIN_URL.'/minishop.php?code=sprun', $spRunCnt); ?>
        <?php endif; ?>
        <?php echo printMenu1('p60', '미니샵 매칭'); ?>
        <?php echo printMenu2('p60', ADMIN_MENU2_18, MS_ADMIN_URL.'/minishop.php?code=lphistory'); ?>
        <?php if( defined('ZEN_USE_MATCHING_ADMIN')&&ZEN_USE_MATCHING_ADMIN) echo printMenu2('p60', ADMIN_MENU2_14, MS_ADMIN_URL.'/minishop.php?code=matchlist'); ?> 
        <?php if( defined('USE_LINE_UP') && USE_LINE_UP && defined('ZEN_USE_LINE_ADMIN')&&ZEN_USE_LINE_ADMIN) echo printMenu2('p60', ADMIN_MENU2_15, MS_ADMIN_URL.'/minishop.php?code=linelist'); ?>
		<?php echo printMenu1('p30', '미니샵 기타'); ?>
		<?php echo printMenu2('p30', ADMIN_MENU2_10, MS_ADMIN_URL.'/minishop.php?code=leave'); ?>
		<?php echo printMenu2('p30', ADMIN_MENU2_11, MS_ADMIN_URL.'/minishop.php?code=tree'); ?>
        <?php if( defined('USE_ORGCHART') && USE_ORGCHART)/* 가맹정 조직도 추가 */ echo printMenu2('p30', ADMIN_MENU2_12, MS_ADMIN_URL.'/minishop.php?code=orgchart'); ?>
		<?php echo printMenu2('p30', ADMIN_MENU2_14, MS_ADMIN_URL.'/minishop.php?code=category_manage'); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU3) {
		$anewCnt = admRequest("shop_seller");
	?>
	<dl>
		<?php echo printMenu1('s10', '공급사 관리'); ?>
		<?php echo printMenu2('s10', ADMIN_MENU3_01, MS_ADMIN_URL.'/seller.php?code=list', $anewCnt); ?>
		<?php echo printMenu2('s10', ADMIN_MENU3_02, MS_ADMIN_URL.'/seller.php?code=register'); ?>
		<?php echo printMenu2('s10', ADMIN_MENU3_03, MS_ADMIN_URL.'/seller.php?code=xls'); ?>
		<?php echo printMenu1('s20', '공급사 정산'); ?>
		<?php echo printMenu2('s20', ADMIN_MENU3_04, MS_ADMIN_URL.'/seller.php?code=pay'); ?>
		<?php echo printMenu2('s20', ADMIN_MENU3_05, MS_ADMIN_URL.'/seller.php?code=pay_history'); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU4) { ?>
	<dl>
		<?php echo printMenu1('c10', '카테고리 관리'); ?>
		<?php echo printMenu2('c10', ADMIN_MENU4_01, MS_ADMIN_URL.'/category.php?code=list'); ?>
		<?php echo printMenu2('c10', ADMIN_MENU4_02, MS_ADMIN_URL.'/category.php?code=view'); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU5) { 		
		$regCnt = admRequest("shop_goods");
		$qaCnt  = admRequest("shop_goods_qa", "and (left(seller_id,3)='AP-' or seller_id = '".encrypted_admin()."')");
		$jaego1 = admin_gs_jaego_bujog("and shop_state = 0 and use_aff = 0"); // 재고부족
		$jaego2 = admin_io_jaego_bujog("and b.shop_state = 0 and b.use_aff = 0"); // 옵션재고부족
	?>
	<dl>
		<?php echo printMenu1('g10', '상품관리'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_01, MS_ADMIN_URL.'/goods.php?code=list'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_02, MS_ADMIN_URL.'/goods.php?code=type'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_27, MS_ADMIN_URL.'/goods.php?code=cate'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_03, MS_ADMIN_URL.'/goods.php?code=brand_list'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_04, MS_ADMIN_URL.'/goods.php?code=plan'); ?>
	    <?php echo printMenu2('g10', ADMIN_MENU5_05, MS_ADMIN_URL.'/goods.php?code=price'); ?>
		<?php echo printMenu1('g11', '재고관리'); ?>
		<?php echo printMenu2('g11', ADMIN_MENU5_06, MS_ADMIN_URL.'/goods.php?code=stock', $jaego1); ?>
		<?php echo printMenu2('g11', ADMIN_MENU5_07, MS_ADMIN_URL.'/goods.php?code=optstock', $jaego2); ?>
		<?php echo printMenu1('g20', '일괄처리'); ?>
        <?php echo printMenu2('g20', ADMIN_MENU5_08, MS_ADMIN_URL.'/goods.php?code=xls_reg'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_09, MS_ADMIN_URL.'/goods.php?code=xls_option_reg'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_10, MS_ADMIN_URL.'/goods.php?code=xls_mod'); ?> 
		<?php echo printMenu2('g20', ADMIN_MENU5_11, MS_ADMIN_URL.'/goods.php?code=getprice'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_12, MS_ADMIN_URL.'/goods.php?code=getpoint'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_13, MS_ADMIN_URL.'/goods.php?code=getuse'); ?>
        <?php echo printMenu2('g20', ADMIN_MENU5_14, MS_ADMIN_URL.'/goods.php?code=getmove'); ?>
        <?php echo printMenu2('g20', ADMIN_MENU5_15, MS_ADMIN_URL.'/goods.php?code=getbrand'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_16, MS_ADMIN_URL.'/goods.php?code=getdelivery'); ?> 
		<?php echo printMenu2('g20', ADMIN_MENU5_17, MS_ADMIN_URL.'/goods.php?code=getbuylevel'); ?>
		<?php echo printMenu1('g30', '대기상품'); ?>
		<?php echo printMenu2('g30', ADMIN_MENU5_18, MS_ADMIN_URL.'/goods.php?code=supply', $regCnt); ?>
	    <?php echo printMenu2('g30', ADMIN_MENU5_19, MS_ADMIN_URL.'/goods.php?code=userlist'); ?> 
		<?php echo printMenu1('g40', '문의 / 후기'); ?>
		<?php echo printMenu2('g40', ADMIN_MENU5_20, MS_ADMIN_URL.'/goods.php?code=qa', $qaCnt); ?>
		<?php echo printMenu2('g40', ADMIN_MENU5_21, MS_ADMIN_URL.'/goods.php?code=review'); ?>
		<?php echo printMenu1('g50', '쿠폰관리'); ?>
		<?php echo printMenu2('g50', ADMIN_MENU5_22, MS_ADMIN_URL.'/goods.php?code=gift'); ?>
		<?php echo printMenu2('g50', ADMIN_MENU5_23, MS_ADMIN_URL.'/goods.php?code=coupon'); ?>
		<?php echo printMenu1('g60', '네이버최저가크롤링'); ?>
		<?php echo printMenu2('g60', ADMIN_MENU5_24, MS_ADMIN_URL.'/goods.php?code=excel_ing'); ?> 
		<?php echo printMenu2('g60', ADMIN_MENU5_25, MS_ADMIN_URL.'/goods.php?code=naver_list'); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU6) { 
		$sodrr = admin_order_status_sum("where dan > 0 "); // 총 주문내역
		$sodr1 = admin_order_status_sum("where dan = 1 "); // 총 입금대기
		$sodr2 = admin_order_status_sum("where dan = 2 "); // 총 입금완료
		$sodr3 = admin_order_status_sum("where dan = 3 "); // 총 배송준비
		$sodr4 = admin_order_status_sum("where dan = 4 "); // 총 배송중
		$sodr5 = admin_order_status_sum("where dan = 5 "); // 총 배송완료
		$sodr6 = admin_order_status_sum("where dan = 6 "); // 총 입금전 취소
		$sodr7 = admin_order_status_sum("where dan = 7 "); // 총 배송후 반품
		$sodr8 = admin_order_status_sum("where dan = 8 "); // 총 배송후 교환
		$sodr9 = admin_order_status_sum("where dan = 9 "); // 총 배송전 환불
		$memoCnt = admin_order_memo(); // 총 관리자메모
	?>
	<dl>
		<?php echo printMenu1('o10', '주문관리'); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_01, MS_ADMIN_URL.'/order.php?code=list', $sodrr['cnt']); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_02, MS_ADMIN_URL.'/order.php?code=1', $sodr1['cnt']); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_03, MS_ADMIN_URL.'/order.php?code=2', $sodr2['cnt']); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_04, MS_ADMIN_URL.'/order.php?code=3', $sodr3['cnt']); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_05, MS_ADMIN_URL.'/order.php?code=4', $sodr4['cnt']); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_06, MS_ADMIN_URL.'/order.php?code=5', $sodr5['cnt']); ?>
	    <?php echo printMenu2('o10', ADMIN_MENU6_07, MS_ADMIN_URL.'/order.php?code=delivery'); ?>
		<?php echo printMenu2('o30', ADMIN_MENU6_13, MS_ADMIN_URL.'/order.php?code=excel_ing'); ?> 
		<?php echo printMenu1('o20', '취소/교환/반품/환불 관리'); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_08, MS_ADMIN_URL.'/order.php?code=6', $sodr6['cnt']); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_09, MS_ADMIN_URL.'/order.php?code=9', $sodr9['cnt']); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_10, MS_ADMIN_URL.'/order.php?code=7', $sodr7['cnt']); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_11, MS_ADMIN_URL.'/order.php?code=8', $sodr8['cnt']); ?>
		<?php echo printMenu1('o30', '기타관리'); ?>
		<?php echo printMenu2('o30', ADMIN_MENU6_12, MS_ADMIN_URL.'/order.php?code=memo', $memoCnt); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU7) { ?>
	<dl>
		<?php echo printMenu1('v10', '접속자통계'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_01, MS_ADMIN_URL.'/visit.php?code=hour'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_02, MS_ADMIN_URL.'/visit.php?code=date'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_03, MS_ADMIN_URL.'/visit.php?code=week'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_04, MS_ADMIN_URL.'/visit.php?code=month'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_05, MS_ADMIN_URL.'/visit.php?code=year'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_06, MS_ADMIN_URL.'/visit.php?code=browser'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_07, MS_ADMIN_URL.'/visit.php?code=os'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_08, MS_ADMIN_URL.'/visit.php?code=domain'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_09, MS_ADMIN_URL.'/visit.php?code=search'); ?>
		<?php echo printMenu1('v20', '주문통계'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_10, MS_ADMIN_URL.'/visit.php?code=order1'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_11, MS_ADMIN_URL.'/visit.php?code=order2'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_12, MS_ADMIN_URL.'/visit.php?code=cancel'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_13, MS_ADMIN_URL.'/visit.php?code=return'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_14, MS_ADMIN_URL.'/visit.php?code=change'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_15, MS_ADMIN_URL.'/visit.php?code=refund'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU8) { ?>
	<dl>
		<?php echo printMenu1('h10', '고객지원'); ?>
		<?php echo printMenu2('h10', ADMIN_MENU8_01, MS_ADMIN_URL.'/help.php?code=qa'); ?>
		<?php echo printMenu2('h10', ADMIN_MENU8_02, MS_ADMIN_URL.'/help.php?code=leave'); ?>
		<?php echo printMenu1('h20', 'FAQ 관리'); ?>
		<?php echo printMenu2('h20', ADMIN_MENU8_03, MS_ADMIN_URL.'/help.php?code=faq_group'); ?>
		<?php echo printMenu2('h20', ADMIN_MENU8_04, MS_ADMIN_URL.'/help.php?code=faq'); ?>
		<?php echo printMenu1('h30', '리뷰 관리'); ?>
		<?php echo printMenu2('h30', ADMIN_MENU8_05, MS_ADMIN_URL.'/help.php?code=review'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU9) { ?>
	<dl>
		<?php echo printMenu1('d10', '배너관리'); ?>
		<?php echo printMenu2('d10', ADMIN_MENU9_01, MS_ADMIN_URL.'/design.php?code=banner_list'); ?>
		<?php echo printMenu2('d10', ADMIN_MENU9_02, MS_ADMIN_URL.'/design.php?code=mbanner_list'); ?>
		<?php echo printMenu1('d20', '로고/페이지관리'); ?>
		<?php echo printMenu2('d20', ADMIN_MENU9_03, MS_ADMIN_URL.'/design.php?code=logo'); ?>
		<?php echo printMenu2('d20', ADMIN_MENU9_04, MS_ADMIN_URL.'/design.php?code=contentlist'); ?>
		<?php echo printMenu1('d30', '기타관리'); ?>
		<?php echo printMenu2('d30', ADMIN_MENU9_05, MS_ADMIN_URL.'/design.php?code=best_item'); ?>
		<?php echo printMenu2('d30', ADMIN_MENU9_06, MS_ADMIN_URL.'/design.php?code=popup_list'); ?>
		<?php echo printMenu1('d40', '이북관리'); ?>
		<?php echo printMenu2('d40', ADMIN_MENU9_07, MS_ADMIN_URL.'/design.php?code=ebook_list'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU10) { ?>
	<dl>
		<?php echo printMenu1('q10', '기본환경설정'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_01, MS_ADMIN_URL.'/config.php?code=default'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_02, MS_ADMIN_URL.'/config.php?code=meta'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_03, MS_ADMIN_URL.'/config.php?code=sns'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_04, MS_ADMIN_URL.'/config.php?code=register'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_05, MS_ADMIN_URL.'/config.php?code=sendmail_test'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_06, MS_ADMIN_URL.'/config.php?code=sms'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_07, MS_ADMIN_URL.'/config.php?code=supply'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_08, MS_ADMIN_URL.'/config.php?code=super'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_21, '/na/index_cr.php','','_blank'); ?>
		<?php echo printMenu1('q20', '결제관리'); ?>
		<?php echo printMenu2('q20', ADMIN_MENU10_09, MS_ADMIN_URL.'/config.php?code=pg'); ?>
        <?php echo printMenu2('q20', ADMIN_MENU10_10, MS_ADMIN_URL.'/config.php?code=kakaopay'); ?>
		<?php echo printMenu2('q20', ADMIN_MENU10_11, MS_ADMIN_URL.'/config.php?code=naverpay'); ?>  
		<?php echo printMenu1('q30', '배송관리'); ?>
		<?php echo printMenu2('q30', ADMIN_MENU10_12, MS_ADMIN_URL.'/config.php?code=baesong'); ?>
		<?php echo printMenu2('q30', ADMIN_MENU10_13, MS_ADMIN_URL.'/config.php?code=islandlist'); ?>
		<?php echo printMenu1('q40', '보안관리'); ?>
		<?php echo printMenu2('q40', ADMIN_MENU10_14, MS_ADMIN_URL.'/config.php?code=nicecheck'); ?>
		<?php echo printMenu2('q40', ADMIN_MENU10_15, MS_ADMIN_URL.'/config.php?code=ipaccess'); ?>
		<?php echo printMenu1('q50', '게시판관리'); ?>
		<?php echo printMenu2('q50', ADMIN_MENU10_16, MS_ADMIN_URL.'/config.php?code=board_group_list'); ?>
		<?php echo printMenu2('q50', ADMIN_MENU10_17, MS_ADMIN_URL.'/config.php?code=board_list'); ?>
        <?php echo printMenu2('q50', ADMIN_MENU10_18, MS_ADMIN_URL.'/config.php?code=keyword_list'); ?>
		<?php echo printMenu2('q50', ADMIN_MENU10_19, MS_ORG_URL.'/board_org_list.php?code=board_list'); ?>
		<?php echo printMenu1('q60', '룰렛관리'); ?>
		<?php echo printMenu2('q60', ADMIN_MENU10_20, MS_ADMIN_URL.'/config.php?code=roulette'); ?>
	</dl>
	<?php } else if($pg_title == ADMIN_MENU11) { ?>
	<dl>
		<?php echo printMenu1('y10', '예약관리'); ?>
		<?php echo printMenu2('y10', ADMIN_MENU11_01, MS_ADMIN_BOOK_URL.'/wzb_booking_list2.php?code=wzb_booking_list'); ?>
		<?php echo printMenu2('y10', ADMIN_MENU11_02, MS_ADMIN_BOOK_URL.'/wzb_booking_list2.php?code=wzb_booking_status'); ?>
		<?php echo printMenu2('y10', ADMIN_MENU11_03, MS_ADMIN_BOOK_URL.'/wzb_booking_list2.php?code=wzb_booking_calendar'); ?>
		<?php echo printMenu2('y10', ADMIN_MENU11_04, MS_ADMIN_BOOK_URL.'/wzb_booking_list2.php?code=wzb_room_list'); ?>
		<?php echo printMenu2('y10', ADMIN_MENU11_05, MS_ADMIN_BOOK_URL.'/wzb_booking_list2.php?code=wzb_room_status'); ?>
		<?php echo printMenu2('y10', ADMIN_MENU11_06, MS_ADMIN_BOOK_URL.'/wzb_booking_list2.php?code=wzb_price_list'); ?>
		<?php echo printMenu2('y10', ADMIN_MENU11_07, MS_ADMIN_BOOK_URL.'/wzb_booking_list2.php?code=wzb_room_option_list'); ?>
		<?php echo printMenu2('y10', ADMIN_MENU11_08, MS_ADMIN_BOOK_URL.'/wzb_booking_list2.php?code=wzb_holiday_list'); ?>
		<?php echo printMenu2('y10', ADMIN_MENU11_09, MS_ADMIN_BOOK_URL.'/wzb_booking_list2.php?code=wzb_pay_list'); ?>
		<?php echo printMenu2('y10', ADMIN_MENU11_10, MS_ADMIN_BOOK_URL.'/wzb_booking_list2.php?code=wzb_popup_list'); ?>
		<?php echo printMenu2('y10', ADMIN_MENU11_11, MS_ADMIN_BOOK_URL.'/wzb_booking_list2.php?code=wzb_config'); ?>
	</dl>
	<?php } else if($pg_title == ADMIN_MENU12) { ?>
	<dl>
		<?php echo printMenu1('p12', '포인트충전관리'); ?>
		<?php echo printMenu2('p12', ADMIN_MENU12_01, MS_ADMIN_POINT_URL.'/point_list.php?code=order_list'); ?>
		<?php echo printMenu2('p12', ADMIN_MENU12_02, MS_ADMIN_POINT_URL.'/point_list.php?code=pay_list'); ?>
		<?php echo printMenu2('p12', ADMIN_MENU12_03, MS_ADMIN_POINT_URL.'/point_list.php?code=config'); ?>
	</dl>
	<?php } else if($pg_title == ADMIN_MENU13) { ?>
	<dl>
		<?php echo printMenu1('p13', 'PUSH관리'); ?>
		<?php echo printMenu2('p13', ADMIN_MENU13_01, MS_PUSH_URL.'/push_index.php?code=push_list'); ?>
		<?php echo printMenu2('p13', ADMIN_MENU13_02, MS_PUSH_URL.'/push_index.php?code=push_form'); ?>
	</dl>
	<?php } else if($pg_title == ADMIN_MENU14) { ?>
	<dl>
		<?php echo printMenu1('p14', '게시판관리'); ?>
		<?php echo printMenu2('p14', ADMIN_MENU14_01, MS_ORG_URL.'/board_org_list.php?code=board_list'); ?>
		<?php echo printMenu2('p13', ADMIN_MENU13_02, MS_PUSH_URL.'/push_index.php?code=push_form'); ?>
	</dl>
	<?php } ?>
</div>

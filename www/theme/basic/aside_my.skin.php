<?php
if(!defined('_MALLSET_')) exit;


function printMenu1($key, $subject)
{
    $svc_class = 'pmenu'.$key;
    if(get_cookie("ck_{$svc_class}")) {
        $svc_class .= ' menu_close';
    }

    return '<dt class="'.$svc_class.' menu_toggle">'.$subject.'</dt>';
}

function printMenu2($key, $subject, $url, $menu_cnt='')
{
    $svc_class = 'pmenu'.$key;
    if(get_cookie("ck_{$svc_class}")) {
        $svc_class .= ' menu_close';
    }

    $current_class = '';
    $count_class = '';
    if(is_numeric($menu_cnt)) {
        if($menu_cnt > 0)
            $current_class = ' class="snb_air"';
        $count_class = '<em'.$current_class.'>'.$menu_cnt.'</em>';
    }

    return '<dd class="'.$svc_class.'"><a href="'.$url.'">'.$subject.$count_class.'</a></dd>';
}

define('asideUrl', MS_MYPAGE_URL.'/page.php');
define('boardUrl', MS_BBS_URL.'/list.php');
if( is_minishop($member['id']) ) :
    $jaego1  = admin_gs_jaego_bujog("and mb_id = '{$member['id']}'"); // 재고부족
    $jaego2  = admin_io_jaego_bujog("and b.mb_id = '{$member['id']}'"); // 옵션재고부족
    $starCnt = admin_goods_review("and seller_id = '{$member['id']}'"); // 총 상품평점
    $qaCnt   = admRequest("shop_goods_qa", "and seller_id = '{$member['id']}'"); // 총 상품문의

    $sql_where = " where seller_id = '{$member['id']}' ";
    $sodrr = admin_order_status_sum("{$sql_where} and dan > 0 "); // 총 주문내역
    $sodr1 = admin_order_status_sum("{$sql_where} and dan = 1 "); // 총 입금대기
    $sodr2 = admin_order_status_sum("{$sql_where} and dan = 2 "); // 총 입금완료
    $sodr3 = admin_order_status_sum("{$sql_where} and dan = 3 "); // 총 배송준비
    $sodr4 = admin_order_status_sum("{$sql_where} and dan = 4 "); // 총 배송중
    $sodr5 = admin_order_status_sum("{$sql_where} and dan = 5 "); // 총 배송완료
    $sodr6 = admin_order_status_sum("{$sql_where} and dan = 6 "); // 총 입금전 취소
    $sodr7 = admin_order_status_sum("{$sql_where} and dan = 7 "); // 총 배송후 반품
    $sodr8 = admin_order_status_sum("{$sql_where} and dan = 8 "); // 총 배송후 교환
    $sodr9 = admin_order_status_sum("{$sql_where} and dan = 9 "); // 총 배송전 환불

    unset($sql_where);
endif;

?>
<!-- 좌측메뉴 시작 { -->
<aside id="aside" style="margin-bottom: 10px;">
	<div class="aside_hd">
		<p class="eng">MY PAGE</p>
		<p class="kor">마이페이지</p>
	</div>
	<div class="aside_name"><i class="fa grade-<?php echo $member['grade']; ?>">[<?php echo get_grade($member['grade']); ?>]</i> <?php echo get_text($member['name']); ?></div>
    <?php if( (!defined('USE_PG_TEST') || ! USE_PG_TEST) && is_minishop($member['id'])) : ?>
        <ul class="aside_bx">

            <li>마일리지 <span><a href="<?php echo asideUrl; ?>?code=minishop_paylist"><?php echo display_price(get_pay_sum($member['id']), ''); ?></a>원</span></li>
 <!--           <li>가맹점 점수 <span><a href="<?php echo MS_SHOP_URL; ?>/lpoint.php"><?php echo display_point($member['line_point'], ''); ?></a>P</span></li>
            <li>쇼핑페이 <span><a href="<?php echo MS_SHOP_URL; ?>/sppoint.php"><?php echo display_point($member['sp_point'], ''); ?></a>P</span></li>
-->
			<li>쇼핑포인트 <span><a href="<?php echo MS_SHOP_URL; ?>/point.php"><?php echo display_point($member['point'], ''); ?></a>P</span></li>
		</ul>
    <?php endif; ?>
    <?php if( (!defined('USE_PG_TEST') || ! USE_PG_TEST) && is_minishop($member['id']) ) : ?>
    <dl class="aside_my">
        <?php echo printMenu1(1, '기본환경 설정'); ?>
        <?php echo printMenu2(1, '기본정보 관리', asideUrl.'?code=mypage_minishop_info'); ?>
        <?php if($config['pf_expire_use']) { // 월관리비를 사용중인가? ?>
            <?php echo printMenu2(1, '미니샵 연장신청', asideUrl.'?code=mypage_minishop_term'); ?>
        <?php } ?>

        <?php echo printMenu1(4, '회원관리'); ?>
        <?php echo printMenu2(4, '회원목록', asideUrl.'?code=mypage_minishop_member_list'); ?>
        <?php if( FALSE ) : ?>
        <?php echo printMenu2(4, '신규 회원등록', asideUrl.'?code=mypage_minishop_register_form'); ?>
        <?php endif; ?>
        <?php echo printMenu2(4, '트리 회원조회', asideUrl.'?code=mypage_minishop_tree'); ?>
        <?php if( defined('USE_ORGCHART') && USE_ORGCHART ) :/* 조직도  회원조회 */ ?>
        <?php echo printMenu2(4, '조직도 회원조회', asideUrl.'?code=mypage_minishop_orgchart'); ?>
        <?php endif; ?>
        <?php echo printMenu2(4, '일별 가입통계분석', asideUrl.'?code=mypage_minishop_stats_day'); ?>
        <?php echo printMenu2(4, '월별 가입통계분석', asideUrl.'?code=mypage_minishop_stats_month'); ?>
        <?php echo printMenu2(4, '접속자검색', asideUrl.'?code=mypage_minishop_visit'); ?>


        <?php echo printMenu1(11, '마일리지 리포트'); ?>
        <?php echo printMenu2(11, '마일리지 정산', asideUrl.'?code=mypage_minishop_paylist'); ?>
        <?php echo printMenu2(11, '마일리지 건별내역', asideUrl.'?code=mypage_minishop_payhistory'); ?>
        <?php echo printMenu2(11, '공지사항', boardUrl.'?boardid=22'); ?>
        <?php echo printMenu2(11, '질문과답변', boardUrl.'?boardid=36'); ?>
    </dl>
    <?php endif; ?>

	<dl class="aside_my">
		<dt>주문현황</dt>
		<dd><a href="<?php echo MS_SHOP_URL; ?>/orderinquiry.php">주문/배송조회</a></dd>
        <?php if( !defined('USE_PG_TEST') || !USE_PG_TEST) : ?>
		<dt>쇼핑통장</dt>
		<dd><a href="<?php echo MS_SHOP_URL; ?>/point.php">쇼핑포인트 조회</a></dd>
		<?php if($config['gift_yes']) { ?>
		<dd><a href="<?php echo MS_SHOP_URL; ?>/gift.php">쿠폰인증</a></dd>
		<?php } ?>
		<?php if($config['coupon_yes']) { ?>
		<dd><a href="<?php echo MS_SHOP_URL; ?>/coupon.php">쿠폰관리</a></dd>
		<?php } ?>
        <?php endif; ?>
		<dt>관심상품</dt>
		<dd><a href="<?php echo MS_SHOP_URL; ?>/cart.php">장바구니</a></dd>
		<dd><a href="<?php echo MS_SHOP_URL; ?>/wish.php">내가 찜한상품</a></dd>
		<dt>회원정보</dt>
		<dd><a href="<?php echo MS_BBS_URL; ?>/register_mod.php">회원정보수정</a></dd>
		<dd class="marb5"><a href="<?php echo MS_BBS_URL; ?>/leave_form.php">회원탈퇴</a></dd>
	</dl>

</aside>
<!-- } 좌측메뉴 끝 -->

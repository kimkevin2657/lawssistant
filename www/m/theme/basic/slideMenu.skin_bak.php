<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가
?>

<div id="slideMenu">
	<dl class="top_btn">
		<dd <?php if( MS_LIMIT_MYPAGE ) { ?> style="width:100%;" <?php } ?>>
			<?php if($is_member) { ?>
			<a href="<?php echo MS_MBBS_URL; ?>/logout.php" class="btn_medium">로그아웃</a>
			<?php } else { ?>
			<a href="<?php echo MS_MBBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" class="btn_medium">로그인</a>
			<?php } ?>
		</dd>
        <?php if( ! MS_LIMIT_MYPAGE ) :?>
		<dd>
			<?php if($is_member) { ?>
			<a href="<?php echo MS_MBBS_URL; ?>/register_form.php?w=u" class="btn_medium bx-white">정보수정</a>
			<?php } else { ?>
			<a href="<?php echo MS_MBBS_URL; ?>/register.php" class="btn_medium bx-white">회원가입</a>
			<?php } ?>
		</dd>
        <?php endif; ?>
	</dl>
    <?php if( ! MS_LIMIT_MYPAGE ) : ?>
	<ul class="sm_icbt">
        <li><a href="<?php echo MS_MSHOP_URL; ?>/mypage.php"><i class="ionicons ion-android-contact"></i> 마이페이지</a></li>
		<li><a href="<?php echo MS_MSHOP_URL; ?>/cart.php"><i class="ionicons ion-android-cart"></i> 장바구니</a></li>
		<li><a href="<?php echo MS_MSHOP_URL; ?>/orderinquiry.php"><i class="ionicons ion-ios-list-outline"></i> 주문/배송</a></li>
		<li><a href="<?php echo MS_MBBS_URL; ?>/review.php"><i class="ionicons ion-android-camera"></i> 구매후기</a></li>
	</ul>
	<ul class="smtab">
        <li data-tab="shop_cate">카테고리</li>
        <li data-tab="mypage">마이페이지</li>
		<li data-tab="custom">고객센터</li>
	</ul>
    <?php else : ?>
        <ul class="smtab">
            <li data-tab="mypage" style="width:100%;border-bottom:1px solid #333;"><a href="/m/shop/mypage.php"><?php _e('마이페이지'); ?></a></li>
        </ul>
    <?php endif ?>
    <?php if( ! MS_LIMIT_MYPAGE ) : ?>
    <div id="shop_cate" class="sm_body">
        <?php
        $r = sql_query_cgy('all', 'COUNT');
        if($r['cnt'] > 0){
            ?>
            <ul>
                <?php
				$res = sql_query_cgy('all');
                //$res = sql_query_cgy('all','','',$member['grade'], $member['mb_category']);
                $menus = array();
                while($row = sql_fetch_array($res)) {
                    $href = MS_MSHOP_URL.'/list.php?ca_id='.$row['catecode'];
                    ?>
                    <li class="bm"><?php echo $row['catename'];?></li>
                    <li class="subm">
                        <ul>
                            <li><a href="<?php echo $href;?>">전체</a></li>
                            <?php
                            $res2 = sql_query_cgy($row['catecode']);
                            while($row2 = sql_fetch_array($res2)) {
                                $href2 = MS_MSHOP_URL.'/list.php?ca_id='.$row2['catecode'];
                                ?>
                                <li><a href="<?php echo $href2;?>"><?php echo $row2['catename']; ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php
                    $menu = ['href'=>$href, 'catename'=>$row['catename']];
                    array_push($menus, $menu);
                } ?>
            </ul>
        <?php } else { ?>
            <p class="sct_noitem">등록된 분류가 없습니다.</p>
        <?php } ?>
    </div>
    <?php endif; ?>
    <div id="mypage" class="sm_body">
        <ul>
            <?php if( is_minishop($member['id'])): ?>
            <li><a href="<?php echo MS_MSHOP_URL; ?>/paylist.php">마일리지</a></li>
            <?php if( defined('USE_LINE_UP') && USE_LINE_UP ) : ?>
            <li><a href="<?php echo MS_MSHOP_URL; ?>/lpoint.php">라인점수</a></li>
            <?php endif; ?>
            <?php endif; ?>
            <li><a href="<?php echo MS_MSHOP_URL; ?>/orderinquiry.php">주문/배송 조회</a></li>
            <li><a href="<?php echo MS_MSHOP_URL; ?>/point.php">Oh!포인트 조회</a></li>
            <?php if($config['gift_yes']) { ?>
                <li><a href="<?php echo MS_MSHOP_URL; ?>/gift.php">쿠폰인증</a></li>
            <?php } ?>
            <?php if($config['coupon_yes']) { ?>
                <li><a href="<?php echo MS_MSHOP_URL; ?>/coupon.php">쿠폰관리</a></li>
            <?php } ?>
            <li><a href="<?php echo MS_MSHOP_URL; ?>/wish.php">찜한상품</a></li>
            <li><a href="<?php echo MS_MSHOP_URL; ?>/today.php">최근 본 상품</a></li>
            <?php if($is_member) { ?>
                <li><a href="<?php echo MS_MBBS_URL; ?>/leave_form.php">회원탈퇴</a></li>
            <?php } ?>
        </ul>
    </div>
    <?php if( ! MS_LIMIT_MYPAGE ) : ?>
	<div id="custom" class="sm_body">
		<ul>			
			<?php
			$sql = " select * from shop_board_conf where gr_id='gr_mall' order by index_no asc ";
			$res = sql_query($sql);
			for($i=0; $row=sql_fetch_array($res); $i++) { 

			?>
			<? if($member['grade'] == 1){ ?>
				<li><a href="<?php echo MS_MBBS_URL; ?>/board_list.php?boardid=<?php echo $row['index_no']; ?>"><?php echo $row['boardname']; ?></a></li>
			<? }else{ ?>

				<? if($row['mb_grade'] == ""){ ?>
					<li><a href="<?php echo MS_MBBS_URL; ?>/board_list.php?boardid=<?php echo $row['index_no']; ?>"><?php echo $row['boardname']; ?></a></li>
				<? }else{ ?>
					<? if($member['grade'] == $row['mb_grade'] && $member['mb_category'] == $row['mb_category'] || $member['grade'] == $row['mb_grade'] && $row['mb_category'] == ""){ ?>
						<li><a href="<?php echo MS_MBBS_URL; ?>/board_list.php?boardid=<?php echo $row['index_no']; ?>"><?php echo $row['boardname']; ?></a></li>
					<? } ?>
				<? } ?>

			<? } ?>

			<?php } ?>	
			<?php
			$sql = " select * from shop_board_conf where gr_id='gr_normal' order by index_no asc ";
			$res = sql_query($sql);
			for($i=0; $row=sql_fetch_array($res); $i++) { 

			?>
				<li><a href="<?php echo MS_MBBS_URL; ?>/board_list.php?boardid=<?php echo $row['index_no']; ?>"><?php echo $row['boardname']; ?></a></li>
			<?php } ?>	
			<li><a href="<?php echo MS_MBBS_URL; ?>/review.php">구매후기</a></li>
			<li><a href="<?php echo MS_MBBS_URL; ?>/qna_list.php">1:1 상담문의</a></li>
			<li><a href="<?php echo MS_MBBS_URL; ?>/faq.php">자주묻는 질문</a></li>			
		</ul>
	</div>
    <?php endif; ?>

	<dl class="sm_cs">
		<dt>고객센터</dt>
		<dd class="cs_tel"><?php echo $config['company_tel']; ?></dd>
		<dd>상담 : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
		<dd>점심 : <?php echo $config['company_lunch']; ?></dd>
	</dl>
	<dl class="sm_cs">
		<dt>입금계좌안내</dt>
		<?php $bank = unserialize($default['de_bank_account']); ?>
		<dd><?php echo $bank[0]['name']; ?> <b><?php echo $bank[0]['account']; ?></b></dd>
		<dd>예금주명 : <?php echo $bank[0]['holder']; ?></dd>
	</dl>
	<p class="mart20"><a href="tel:<?php echo $config['company_tel']; ?>" class="btn_medium grey wfull">고객센터 전화연결</a></p>
</div>
<script>
$(function(){
	// 왼쪽 슬라이드메뉴의 서브메뉴 동작
	$('#slideMenu .subm').hide();
	$('#slideMenu .bm').click(function(){
		if($(this).hasClass('active')){
			$(this).next().slideUp(250);
			$(this).removeClass('active');
		} else {
			$('#slideMenu .bm').removeClass('active');
			$('#slideMenu .subm').slideUp(250);
			$(this).addClass('active');
			$(this).next().slideDown(250);
		}
	});

	// 상단 메뉴버튼 클릭시 메뉴페이지 슬라이드
	$(".btn_sidem").click(function () {
		$("#slideMenu, #wrapper, .page_cover, html").addClass("m_open");
		window.location.hash = "#Menu";
		$("#wrapper, html").css({
			height: $(window).height()
		});
	});
	window.onhashchange = function () {
		if(location.hash != "#Menu") {
			$("#slideMenu, #wrapper, .page_cover, html").removeClass("m_open");
			$("#wrapper, html").css({
				height:'100%'
			});
		}
	};

	//탭기능
	$(document).ready(function(){
		$(".smtab>li:eq(0)").addClass('active');
		$("#mypage").addClass('active');

		$(".smtab>li").click(function() {
			var activeTab = $(this).attr('data-tab');
			$(".smtab>li").removeClass('active');
			$(".sm_body").removeClass('active');
			$(this).addClass('active');
			$("#"+activeTab).addClass('active');
		});

        $(".smtab>li:eq(0)").trigger('click');
	});
});
</script>

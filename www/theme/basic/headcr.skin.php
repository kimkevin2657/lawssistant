<?php
################################################################################################
################################################################################################
################################################################################################
##################   삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지   #####################
##################   삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지   #####################
##################   삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지  삭 제 금 지   #####################
################################################################################################
################################################################################################
################################################################################################
if(!defined('_MALLSET_')) exit;

if(defined('_INDEX_')) { // index에서만 실행
	include_once(MS_LIB_PATH.'/popup.inc.php'); // 팝업레이어
}
?>
<div class="img_gray_box" style="display:none;">

	<div class="bundle_reviewImageList bundle_reviewImageList2">
		<div class="mySwiper2">
			<div class="swiper-wrapper">

				<?
					$photo2 = sql_query("SELECT * FROM review_list where booking_id = '{$id}' and review_file <> '' order by re_idx desc ");
					for($p=0; $p_row2 = sql_fetch_array($photo2); $p++){

				?>
					<button class="swiper-slide" style="margin-right: 6px;" data-img="<? echo $p_row2['review_file']; ?>" num="<? echo $p+1; ?>">
						<div style="display: block; position: static;">
							<img src="<? echo MS_URL; ?>/review_img/<? echo $p_row2['thumbnail_img']; ?>" alt="" class="img_g">
						</div>
					</button>
					
				<? } ?>

			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		</div>
		<img src="<? echo MS_THEME_URL; ?>/img/close.png" class="review_close">
	</div>

</div>

<div id="wrapper">
	<div id="header">
		<?php if(!get_cookie("ck_hd_banner")) { // 상단 큰배너 ?>
    <?php if(defined('_INDEX_')) { ?>
		<div id="hd_banner">
			<?php if($banner1 = display_banner_rows2(1, $pt_id, $member['grade'], $member['mb_category'])) { // 배너가 있나? ?>
			<?php echo $banner1; ?>
			<img src="<?php echo MS_IMG_URL; ?>/bt_close.png" id="hd_close">
			<?php }} // banner end ?>
		</div>
		<?php } // cookie end ?>
		<div id="tnb">
			<div id="tnb_inner">
				<ul class="fr">
					<?php
					$tnb = array();

					if( (!defined('USE_PG_TEST') || ! USE_PG_TEST) && is_minishop($member['id'])) :
                        $tnb[] = '<li><a href="'.MS_MYPAGE_URL.'/page.php?code=mypage_minishop_payhistory">마일리지: <span class="text-blue">'.display_price(get_pay_sum($member['id']), '') .'</span>원</a></li>';
					    if( defined('USE_LINE_UP') && USE_LINE_UP ) :
                        $tnb[] = '<li><a href="'.MS_SHOP_URL.'/lpoint.php">라인점수: <span class="text-red">'.display_point($member['total_line_cnt'], '').'</span>점</a></li>';
					    endif;
                    endif;
					if($member['id']) {
						$tnb[] = '<li><a href="'.MS_BBS_URL.'/logout.php">로그아웃</a></li>';
					} else {
						$tnb[] = '<li><a href="'.MS_BBS_URL.'/login.php?url='.$urlencode.'">로그인</a></li>';
						$tnb[] = '<li><a href="'.MS_BBS_URL.'/register.php">회원가입</a></li>';
					}
                    if($is_admin) :
                        $admin_text = is_minishop($member['id']) ? '가맹점' : '관리자';
                        if((!defined('USE_PG_TEST') || ! USE_PG_TEST) && is_seller($member['id'])) :
                            $tnb[] = '<li><a href="'.MS_MYPAGE_URL.'/page.php?code=seller_main" target="_blank" class="fc_eb7">공급사 관리</a></li>';
                        endif;
                        if((!defined('USE_PG_TEST') || ! USE_PG_TEST) && is_minishop($member['id'])) :
                            $tnb[] = '<li><a href="'.MS_MYPAGE_URL.'/page.php?code=minishop_info" target="_blank" class="fc_eb7">가맹점 관리</a></li>';
                        endif;
                        if( is_admin($member['grade'])):
                            $tnb[] = '<li><a href="'.$is_admin.'" target="_blank" class="fc_eb7">관리자</a></li>';
                        endif;
                    endif;
					$tnb[] = '<li><a href="'.MS_SHOP_URL.'/mypage.php">마이페이지</a></li>';
					$tnb[] = '<li><a href="'.MS_SHOP_URL.'/cart.php">장바구니<span class="ic_num">'. get_cart_count().'</span></a></li>';
					$tnb[] = '<li><a href="'.MS_SHOP_URL.'/orderinquiry.php">주문/배송조회</a></li>';
					$tnb[] = '<li><a href="'.MS_BBS_URL.'/faq.php?faqcate=1">고객센터</a></li>';

                    if( is_minishop($member['id'])) :
                        $tnb[] = minishop::impersonation($member['id'], array('<li>', '</li>'));
                    endif;

					$tnb_str = implode(PHP_EOL, $tnb);
					echo $tnb_str;
                     ?>
				</ul>
			</div>
		</div>
		<div id="hd">
			<!-- 상단부 영역 시작 { -->
			<div id="hd_inner">
                 
             <div class="kakao_link">
			  <!-- 카카오채널 시작 -->
		    <div id="kakao-talk-channel-chat-button"
            data-channel-public-id="_xmrxmuxj"
            data-title="consult"
            data-size="small"
            data-color="yellow"
            data-shape="pc"
            data-support-multiple-densities="true"></div>
			
        <script>
  window.kakaoAsyncInit = function() {
    Kakao.Channel.createChatButton({
      container: '#kakao-talk-channel-chat-button',
    });
  };

  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://developers.kakao.com/sdk/js/kakao.channel.min.js';
    fjs.parentNode.insertBefore(js, fjs);
  })(document, 'script', 'kakao-js-sdk');
</script>
</div>	

				<div class="hd_bnr">
               <!-- 카카오채널 끝 -->	
					<!--<span><a href="/shop/cart.php"><img src="/theme/basic/img/cart.png"></a></span>-->
					<span><?php echo display_banner(2, $pt_id, $member['grade'], $member['mb_category']); ?></span>
					<!--div class="hd_intr">
						<h3>방문해주셔서 감사드립니다.</h3>
            <?php if($pt_name['name']) { ?>
						<h1><span><?php echo $pt_name['name']; ?></span>입니다.</h1>
            <?php } ?>
					</div-->
     
				</div>
				<h1 class="hd_logo">
					<?php echo display_logo(); ?>
				</h1>
				<div id="hd_sch">
					<fieldset class="sch_frm">
						<legend>사이트 내 전체검색</legend>
						<form name="fsearch" id="fsearch" method="post" action="<?php echo MS_SHOP_URL; ?>/search_update.php" onsubmit="return fsearch_submit(this);" autocomplete="off">
						<input type="hidden" name="hash_token" value="<?php echo MS_HASH_TOKEN; ?>">
						<input type="text" name="ss_tx" class="sch_stx" maxlength="20" placeholder="검색어를 입력해주세요">
						<button type="submit" class="sch_submit fa fa-search" value="검색"></button>
						</form>
						<script>
						function fsearch_submit(f){
							if(!f.ss_tx.value){
								alert('검색어를 입력하세요.');
								return false;
							}
							return true;
						}
						</script>
					</fieldset>

<!--금주검색어기능-->

					<!--div class="hdkeword">
                    <ul id="ticker">
					<?php echo display_tick("", 10); ?>
					</ul></div-->
					<div class="hdkBx">
						<h2>인기 쇼핑 키워드 <span class="btn_close">닫기 <i class="ionicons ion-close-round"></i></span></h2>
					<ul><?php echo display_rank(); ?></ul>
					</div>
			


	<script>
					// 인기검색어 펼침
					$(".sch_frm .sch_stx").click(function(){
						$(".hdkBx").show();
					});
					// 인기검색어 닫음
					$(".hdkBx .btn_close").click(function(){
						$(".hdkBx").hide();
					});
					</script>
				


<!--금주검색어기능 끝-->
				</div>
			</div>
<?php				
include_once(MS_PATH.'/category.php');
?>

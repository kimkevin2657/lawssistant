<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가
?>

	</div>
    <div class="qBn">
	<span class="btn_kakao"><a href="http://pf.kakao.com/_xmrxmuxj" target="_blank"><img src="/img/icon/kakao_icon.png"></a></span>
	<span class="btn_top fa fa-chevron-up"></span>
	<span class="btn_bottom fa fa-chevron-down"></span>
    </div>

    <?php if( !defined('MS_FROM_APP') ) : ?>
	<?php
	if($default['de_insta_access_token']) { // 인스타그램
	   $userId = explode(".", $default['de_insta_access_token']);
	?>
	<script src="<?php echo MS_JS_URL; ?>/instafeed.min.js"></script>
	<script>
		var userFeed = new Instafeed({
			get: 'user',
			userId: "<?php echo $userId[0]; ?>",
			limit: 6,
			template: '<li class="ins_li"><a href="{{link}}" target="_blank"><img src="{{image}}" /></a></li>',
			accessToken: "<?php echo $default['de_insta_access_token']; ?>"
		});
		userFeed.run();
	</script>

	<div class="insta">
		<h2 class="tac"><i class="fa fa-instagram"></i> INSTAGRAM<a href="https://www.instagram.com/<?php echo $default['de_insta_url']; ?>" target="_blank">@ <?php echo $default['de_insta_url']; ?></a></h2>
		<ul id="instafeed">
		</ul>
	</div>
	<?php } ?>

	<div class="sns_wrap">
		<?php if($default['de_sns_facebook']) { ?><a href="<?php echo $default['de_sns_facebook']; ?>" target="_blank" class="sns_fa"><img src="<?php echo MS_MTHEME_URL; ?>/img/sns_fa.png" title="facebook"></a><?php } ?>
		<?php if($default['de_sns_twitter']) { ?><a href="<?php echo $default['de_sns_twitter']; ?>" target="_blank" class="sns_tw"><img src="<?php echo MS_MTHEME_URL; ?>/img/sns_tw.png" title="twitter"></a><?php } ?>
		<?php if($default['de_sns_instagram']) { ?><a href="<?php echo $default['de_sns_instagram']; ?>" target="_blank" class="sns_in"><img src="<?php echo MS_MTHEME_URL; ?>/img/sns_in.png" title="instagram"></a><?php } ?>
		<?php if($default['de_sns_pinterest']) { ?><a href="<?php echo $default['de_sns_pinterest']; ?>" target="_blank" class="sns_pi"><img src="<?php echo MS_MTHEME_URL; ?>/img/sns_pi.png" title="pinterest"></a><?php } ?>
		<?php if($default['de_sns_naverblog']) { ?><a href="<?php echo $default['de_sns_naverblog']; ?>" target="_blank" class="sns_bl"><img src="<?php echo MS_MTHEME_URL; ?>/img/sns_bl.png" title="naverblog"></a><?php } ?>
		<?php if($default['de_sns_naverband']) { ?><a href="<?php echo $default['de_sns_naverband']; ?>" target="_blank" class="sns_ba"><img src="<?php echo MS_MTHEME_URL; ?>/img/sns_ba.png" title="naverband"></a><?php } ?>
		<?php if($default['de_sns_kakaotalk']) { ?><a href="<?php echo $default['de_sns_kakaotalk']; ?>" target="_blank" class="sns_kt"><img src="<?php echo MS_MTHEME_URL; ?>/img/sns_kt.png" title="kakaotalk"></a><?php } ?>
		<?php if($default['de_sns_kakaostory']) { ?><a href="<?php echo $default['de_sns_kakaostory']; ?>" target="_blank" class="sns_ks"><img src="<?php echo MS_MTHEME_URL; ?>/img/sns_ks.png" title="kakaostory"></a><?php } ?>
	</div>
    <?php endif; ?>

	<footer id="ft">
        <?php if( ! MS_LIMIT_MYPAGE ) : ?>
		<ul class="ft_menu">
            <?php if( !MS_FROM_APP ) : ?>
			<?php if(!MS_FROM_APP && MS_DEVICE_BUTTON_DISPLAY && MS_IS_MOBILE) { ?>
			<li><a href="<?php echo MS_URL; ?>/index.php?device=pc">PC버전</a></li>
			<?php } ?>
			<?php if($config['minishop_reg_yes']) { ?>
			<li><a href="<?php echo MS_MBBS_URL; ?>/minishop_reg.php">미니샵 신청</a></li>
			<?php } ?>  
			<?php if($config['seller_reg_yes']) { ?>
			<li><a href="<?php echo MS_MBBS_URL; ?>/seller_reg.php">파트너 입점신청</a></li>
			<?php } ?>
            <?php endif; ?>
			<li><a href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');">사업자정보확인</a></li>
		</ul>
        <?php endif; ?>
		
		<dl class="ft_cs">
			<dt>고객센터 / 계좌안내</dt>
			<dd class="tel"><?php echo $config['company_tel']; ?></dd>
			<dd>상담 : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
			<dd>점심 : <?php echo $config['company_lunch']; ?></dd>
			<?php $bank = unserialize($default['de_bank_account']); ?>
			<dd><?php echo $bank[0]['name']; ?> <span class="bank_num"><?php echo $bank[0]['account']; ?></span> 예금주 : <?php echo $bank[0]['holder']; ?></dd>
		</dl>
        
		<?php if( ! MS_LIMIT_MYPAGE ) : ?>
		
		<dl class="ft_address">
			<dd><strong><?php echo $config['company_name']; ?></strong> <span class="marl15">대표자 : <?php echo $config['company_owner']; ?></span></dd>
			<dd><?php echo $config['company_addr']; ?></dd>
			<dd>고객센터 : <?php echo $config['company_tel']; ?> <span class="marl15">FAX : <?php echo $config['company_fax']; ?></span></dd>
			<dd>사업자등록번호 : <?php echo $config['company_saupja_no']; ?></dd>
			<dd>통신판매업신고 : <?php echo $config['tongsin_no']; ?></dd>
			<dd>E-mail : <?php echo $super['email']; ?></dd>
			<dd>개인정보보호책임자 : <?php echo $config['info_name']; ?> (<?php echo $config['info_email']; ?>)</dd>
		</dl>
		
        <?php endif; ?>
		<p class="ft_crt">COPYRIGHT © <?php echo $config['company_name']; ?> ALL RIGHTS RESERVED.</p>
	</footer>

<!--모바일 하단 탭 시작-->
<div class="fxMenu">
		<li class="btn_sidem"><i class="ionicons ion-navicon-round"></i> 카테고리</li>
        <li class="btn_search"><i class="ion-android-search"></i> 검색하기</a></li>
		<li><a href="/" data-gtm-category="하단탭" data-gtm-action="홈"><i class="ionicons ion-ios-home-outline"></i> HOME</a></li>
        <li><a href="/m/shop/mypage.php" data-gtm-category="하단탭" data-gtm-action="마이페이지"><i class="ionicons ion-android-contact"></i> 마이페이지</a></li>
		<li> <a href="/m/shop/cart.php" data-layout-button="cart" data-render-position="main" data-gtm-category="하단탭" data-gtm-action="장바구니" class="cart"><i class="ionicons ion-bag"></i> 장바구니 <span class="ic_num"><?php echo get_cart_count(); ?></span></a></li>
		</div>

</div>
<!--모바일 하단 탭 끝-->


<script>
$(function() {
	// 상위로이동
	$(".btn_top").click(function(){
		$("html, body").animate({ scrollTop: 0 }, 250);
	});
	// 하위로이동
    $(".btn_bottom").click(function(){
		$("html, body").animate({ scrollTop: $(document).height() }, 250);
    });

	$(window).scroll(function () {
		if($(this).scrollTop() > 0) {
			$(".btn_top, .btn_bottom, .btn_kakao").fadeIn(250);
		} else {
			$(".btn_top, .btn_bottom, .btn_kakao").fadeOut(250);
		}
	});

	// 상단메뉴 스크롤시 fixed
	var adheight = $(".top_ad").height() + $("#gnb").height();
	$(window).scroll(function () {
		if($(this).scrollTop() > adheight) {
			$("#header").addClass('active');
		//	$("#container").addClass('padt45');
		} else {
			$("#header").removeClass('active');
		//	$("#container").removeClass('padt45');
		}
	});

	// 리스트 가로 갯수 조절
	$('.sct_li_type > a').on('click', function() {
		var this_type = $(this).closest('.sct_li_type');
		var this_a = $(this);
		var listtype = $(this).attr('href');

		// 링크 > 이미지 초기화
		this_type.find('a').each(function() {
			var imgSrc = $(this).find('img').attr('src').replace('<?php echo MS_MTHEME_URL; ?>/img/', '').split('.');
			$(this).find('img').attr('src', '<?php echo MS_MTHEME_URL; ?>/img/'+imgSrc[0].replace('_on','')+'.'+imgSrc[1]);
		});

		// 선택한 링크 > 이미지 _on 붙임
		var img_src = this_a.find('img').attr('src').replace('<?php echo MS_MTHEME_URL; ?>/img/', '').split('.');
		this_a.find('img').attr('src', '<?php echo MS_MTHEME_URL; ?>/img/'+img_src[0]+'_on'+'.'+img_src[1]);

		this_type.next('ul').removeClass('wli1 wli2 wli3');
		this_type.next('ul').addClass(listtype);

		return false;
	});
});
</script>


<div class="msbackground">
	<div class="msinner">
		<div class="textCenter">
			<div class="inheaderlogo"><?php echo mobile_display_logo(); ?></div>
			<div class="txt-ms">몰셋과 함께 시작하세요!	</div>
			<div class="txt-ms1">국내외 인플루언서 최신 쇼핑 웹 몰셋!	</div>
            <div class="txt-ms1">나만의 패션 쇼핑몰을 완성해보세요!	</div>
			<div class="insearch">
				<form name="fsearch" id="fsearch" method="post" action="<?php echo MS_SHOP_URL; ?>/search_update.php" onsubmit="return fsearch_submit(this);" autocomplete="off">
					<div class="search_area">
						<fieldset class="sch_frm">
							<input type="hidden" name="hash_token" value="<?php echo MS_HASH_TOKEN; ?>">							 
							<input type="text" name="ss_tx" class="sch_stx" maxlength="20" placeholder="검색어를 입력해주세요">
							<button type="submit" class="sch_submit fa fa-search" value="검색"></button>
						</fieldset>
					   <div class="keyword-list">
                     <dl>	
		               <dd>
                        <?php
	                 	$sql = " select * from shop_keyword where pt_id = '$pt_id' order by scount desc limit 20";
	                	$res = sql_query($sql);
	                	while($row=sql_fetch_array($res)) {
		            	echo "<a href='".MS_SHOP_URL."/search.php?ss_tx=".$row['keyword']."'>".$row['keyword']."</a>";
		               }
                      ?>		
                     </dd>
                  </dl>
	          </div>               

					</div>
				</form>
			</div>


			<div class="inadm">	
			    <?php
					$tnb = array();

					 if($is_admin) :
                        $admin_text = is_minishop($member['id']) ? '가맹점' : '관리자';
                        if((!defined('USE_PG_TEST') || ! USE_PG_TEST) && is_seller($member['id'])) :
                            $tnb[] = '<span><a href="'.MS_MYPAGE_URL.'/page.php?code=seller_main" target="_blank" class="inadm smsico_seller">공급사 관리</a></span>';
                        endif;
                        if((!defined('USE_PG_TEST') || ! USE_PG_TEST) && is_minishop($member['id'])) :
                            $tnb[] = '<span><a href="'.MS_MYPAGE_URL.'/page.php?code=minishop_info" target="_blank" class="inadm smsico_minishop">가맹점 관리</a></span>';
                        endif;
                        if( is_admin($member['grade'])):
                            $tnb[] = '<span><a href="'.$is_admin.'" target="_blank" class="inadm smsico_admin">관리자</a></span>';
                        endif;

                    endif;

					$tnb_str = implode(PHP_EOL, $tnb);
					echo $tnb_str;
                     ?>

			</div>
			
			<!--<div class="inapp">
					<p>앱 설치후 더욱 더 편리하게 쇼핑하세요~</p>
				<span class="snsico_google"><a href="https://play.google.com/store/apps/details?id=com.nechingu.benecia" target="_blank"><img src="//benecia.shop/data/benecia/banner/labang_google.png" border="0"></a></span>
				<span class="snsico_appstore"><a href="https://apps.apple.com/us/app/%EB%B2%A0%EB%84%A4%EC%8B%9C%EC%95%84/id1505338681" target="_blank"><img src="//benecia.shop/data/benecia/banner/labang_app.png" border="0"></a></span>
			</div> -->

		</div>

	</div>
</div>

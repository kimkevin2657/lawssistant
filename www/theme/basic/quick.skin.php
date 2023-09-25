<?php
if(!defined('_MALLSET_')) exit;
?>

<!-- 퀵메뉴 좌측날개 시작 { -->
<?
	if(!$_GET['bo_table']){	
?>
<div id="qcl">
	<?php echo display_banner_rows2(90, $pt_id, $member['grade'], $member['mb_category']); ?>
<? } ?>
</div>
<!-- } 퀵메뉴 좌측날개 끝 -->

<!-- 퀵메뉴 우측날개 시작 { -->
<div id="qcr">
	<ul>
		<li><a href="https://ownermarket.co.kr" class="ionicons ion-ios-home"></a><span class="qm_txt">HOME</span></li>
		<li><a href="https://ownermarket.co.kr/shop/cart.php" class="ionicons ion-android-cart"></a> <span class="qm_txt">장바구니</span></li>
		<li><a href="https://ownermarket.co.kr/shop/mypage.php" class="ionicons ion-android-contact"></a> <span class="qm_txt">마이페이지</span></li>
		<li class="today_btn active"><i class="ionicons ion-eye"></i> <span class="qm_txt">최근본상품</span></li>
		<li class="qbtn_bx" id="anc_up"><i class="ionicons ion-chevron-up"></i> <span class="qm_txt">위로</span></li>
		<li class="qbtn_bx" id="anc_dw"><i class="ionicons ion-chevron-down"></i> <span class="qm_txt">아래로</span></li>
	</ul>
      <div class="today">
		<h2 class="tit">최근 본 상품</h2>
         <?php
			$pr_tmp = get_cookie('ss_pr_idx');
			$pr_idx = explode('|',$pr_tmp);
			$pr_tot_count = 0;
			$k = 0;
			$mod = 2;
			foreach($pr_idx as $idx)
			{
				$rowx = get_goods($idx, 'simg1');
				if(!$rowx['simg1'])
					continue;

				$href = MS_SHOP_URL.'/view.php?index_no='.$idx;

				if($pr_tot_count % $mod == 0) $k++;

				$pr_tot_count++;
			?>
			<p class="dn c<?php echo $k; ?>">
				<a href="<?php echo $href; ?>"><?php echo get_it_image($idx, $rowx['simg1'], 65, 65); ?></a>
			</p>
			<?php
			}
			if(!$pr_tot_count)
				echo '<p class="no_item">없음</p>'
			?>
        <p class="tdclose_btn">닫기</p>        		
		</li>
		<?php if($pr_tot_count > 0){ ?>
		<li class="stv_wrap">
			<img src="<?php echo MS_IMG_URL; ?>/bt_qcr_prev.gif" id="up">
			<span id="stv_pg"></span>
			<img src="<?php echo MS_IMG_URL; ?>/bt_qcr_next.gif" id="down">
 		</li>		
		<?php } ?>
	</div>	
</div>
<!-- } 퀵메뉴 우측날개 끝 -->

<!--div class="qbtn_bx">
	<button type="button" id="anc_up">TOP</button>
	<button type="button" id="anc_dw">DOWN</button>
</div-->

<script>
$(function() {
	var itemQty = 4; // 총 아이템 수량
	var itemShow = 2; // 한번에 보여줄 아이템 수량
	var Flag = 1; // 페이지
	var EOFlag = parseInt(itemQty/itemShow); // 전체 리스트를 나눠 페이지 최댓값을 구하고
	var itemRest = parseInt(itemQty%itemShow); // 나머지 값을 구한 후
	if(itemRest > 0) // 나머지 값이 있다면
	{
		EOFlag++; // 페이지 최댓값을 1 증가시킨다.
	}
	$('.c'+Flag).css('display','block');
	$('#stv_pg').text(Flag+'/'+EOFlag); // 페이지 초기 출력값
	$('#up').click(function() {
		if(Flag == 1)
		{
			alert('목록의 처음입니다.');
		} else {
			Flag--;
			$('.c'+Flag).css('display','block');
			$('.c'+(Flag+1)).css('display','none');
		}
		$('#stv_pg').text(Flag+'/'+EOFlag); // 페이지 값 재설정
	})
	$('#down').click(function() {
		if(Flag == EOFlag)
		{
			alert('더 이상 목록이 없습니다.');
		} else {
			Flag++;
			$('.c'+Flag).css('display','block');
			$('.c'+(Flag-1)).css('display','none');
		}
		$('#stv_pg').text(Flag+'/'+EOFlag); // 페이지 값 재설정
	});

	// 퀵메뉴 상위로이동
    $("#anc_up").click(function(){
        $("html, body").animate({ scrollTop: 0 }, 400);
    });

	// 하위로이동
    $("#anc_dw").click(function(){
		$("html, body").animate({ scrollTop: $(document).height() }, 400);
    });

	// 좌/우 퀵메뉴 높이 자동조절
		var Theight = $("#hd_banner").height() + $("#mbn_wrap").height();
	
	$(window).scroll(function () {
		if($(this).scrollTop() > Theight) {
			$("#qcr").css({'position':'fixed','top':'80px','margin-top':'0'});
			$("#qcl").css({'position':'fixed','top':'80px'});
		} else {
			$("#qcr").css({'position':'absolute','top':'30px','margin-top':'0'});
			$("#qcl").css({'position':'absolute','top':'30px'});
		}
	});

	$('.today_btn, .tdclose_btn').click(function() {
		if($('.today_btn').hasClass('active')) {
			$('.today_btn').removeClass('active');
			$('.today').fadeOut(150);
		}else {
			$('.today_btn').addClass('active');
			$('.today').fadeIn(150);
		}
	});
});
</script>
<!-- } 우측 퀵메뉴 끝 -->

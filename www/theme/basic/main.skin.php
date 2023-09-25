<?php
if(!defined('_MALLSET_')) exit;

define('USE_MAIN_BEST_ROL', false); // 쇼핑특가
define('USE_MAIN_CATE_BEST', false);// 카테고리별 베스트
define('USE_MAIN_QTYPE1', false);
define('USE_MAIN_QTYPE2', false);
define('USE_MAIN_QTYPE3', false);
define('USE_MAIN_QTYPE4', true); //가맹점상품
define('USE_MAIN_QTYPE5', false);

$dpLabels = Shop::dpLabel($pt_id, array('use_yn'=>'Y', 'use_shop_main'=>'Y'));
?>

<!--
<div id="pc_intro">
	<div>
		<h2><?php echo $member['name']; ?>님! 예약을 도와드리겠습니다.</h2>
	</div>
</div>
-->



<!--Mall Service 추가-->
		
		<div class="cont_wrap mart30">
			<h2 class="mtit">
				<span>#Mall <b>Service</b></span>
			</h2>
			<div class="pr_desc wli4 msico">
				<ul>
					<?php echo main_menu('12', '10'); ?>
				</ul>
			</div>
		</div>
	 

<!--Mall Service 추가 끝-->

<!-- 메인 타임 -->
<script language="javascript">
function CountDownTimer(dt, id)
{
	var end = new Date(dt);

	var _second = 1000;
	var _minute = _second * 60;
	var _hour = _minute * 60;
	var _day = _hour * 24;
	var timer;

	function showRemaining() {
		var now = new Date();
		var distance = end - now;
		if (distance < 0) {
			clearInterval(timer);
			document.getElementsByClassName(id)[0].innerHTML = 'EXPIRED!';
			document.getElementsByClassName(id)[1].innerHTML = 'EXPIRED!';
			return;
		}
		var days = Math.floor(distance / _day);
		var hours = Math.floor((distance % _day) / _hour);
		var minutes = Math.floor((distance % _hour) / _minute);
		var seconds = Math.floor((distance % _minute) / _second);
		var str = "";
		str += '남은시간 <span class="num">'+days + '</span> 일 ';
		str += '<span class="num">'+pad(hours,2) + '</span> 시간 ';
		str += '<span class="num">'+pad(minutes,2) + '</span> 분 ';
		str += '<span class="num">'+pad(seconds,2) + '</span> 초';
		document.getElementsByClassName(id)[0].innerHTML = str;
		document.getElementsByClassName(id)[1].innerHTML = str;
	}

	timer = setInterval(showRemaining, 1000);
}

function pad(n, width) {
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}
</script>

<!-- 메인 타임세일노출 -->
<?php
$sql = "select a.* FROM shop_goods a LEFT JOIN shop_goods_cate b ON ( a.index_no = b.gs_id ) LEFT JOIN shop_cate c ON ( b.gcate = c.catecode ) WHERE a.shop_state = '0' AND a.isopen = '1' AND c.u_hide = '0' AND (a.use_aff = '0') AND find_in_set('admin', a.use_hide) = '0' AND c.p_hide = '0' AND c.p_oper = 'y' and a.sb_date <= '".MS_TIME_YMD."' and a.eb_date >= '".MS_TIME_YMD."' group by a.index_no order by a.eb_date asc limit 0, 20";
$res = sql_query($sql);
$type1_count = sql_num_rows($res);
if($type1_count) {
?>
<div class="mTwrap timesale mart60">
	<h2 class="timetit"><span><i class="ionicons ion-android-alarm-clock"></i> 오늘의 <b>타임특가</b></span></h2>
			<div class="mainTime">
				<?php
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];

					if($row['dongurl']){
						$it_imager = get_it_image($row['index_no'], $row['simg1'], 238, 238);
						$it_image = "<video width='238' height='238' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='{$row['dongurl']}' type='video/mp4'></video>";
					}elseif($row['dongfile']){
						$it_imager = get_it_image($row['index_no'], $row['simg1'], 238, 238);
						$it_image = "<video width='238' height='238' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='".MS_URL."/data/goods/{$row['dongfile']}' type='video/mp4'></video>";
					}else{
						$it_image = get_it_image($row['index_no'], $row['simg1'], 238, 238);
					}
					$it_name = cut_str($row['gname'], 100);
				if($member['grade'] > '10'){
			/*	$it_sprice = "<p class=\"spr\">".number_format($row['normal_price'])."<span>원</span>"; */
				$it_price = "<p class='mpr'>회원전용가</p>";
				$sett = round((($row['normal_price'] - $row['goods_price'])/$row['normal_price'])*100);
			/*	$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>'; */
			}else{
				$it_price = get_price($row['index_no']);
				$it_amount = get_sale_price($row['index_no']);
				$it_point = display_point($row['gpoint']);

				// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
				$it_sprice = $sale = '';
				if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
					$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
					$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
					$it_sprice = display_price2($row['normal_price']);
				}
			}
			$beasong = '';
			$beasong = get_sendcost_amt2($row['index_no'], $it_price);
			

				$eb_date = date("Y-m-d",strtotime("+1 day", strtotime($row['eb_date'])));
				$yy = substr($eb_date, 0, 4);
				$mm = substr($eb_date, 5, 2);
				$dd = substr($eb_date, 8, 2);
				?>
                     
                  <dl class="prli">                       
		  			   <a href="<?php echo $it_href; ?>">
						<dt><?php echo $it_image; ?></dt>
						<dd class="ptime"><i class="ionicons ion-android-alarm-clock"></i><span class="countdown_<?php echo $i; ?>"></span></dd>
                        <dd class="pname"><?php echo $it_name; ?></dd>
						<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?><?php echo $sale; ?></dd>
                        <dd class="icon">
               <span class="naver"> <?php 		
		if($row['isnaver']=="1") {
				echo "<img class=\"naver\" src=\"/img/icon_new.png\">\n";
		} ?>
         </span><span class="qty">남은수량 <?php echo number_format($row['stock_qty']);?>개</span><?php if($beasong == '0'){ ?><span class="delivery">무료배송</span><?php } ?>
			</dd>               
		       </a>
                
		<p class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></p>
			<script language="javascript">
			CountDownTimer("<?php echo $mm; ?>/<?php echo $dd; ?>/<?php echo $yy; ?> 00:00 AM", "countdown_<?php echo $i; ?>");
			</script>                  
				</dl>
				<?php } ?>
			</div>
          </div>
	<script>
	$(document).ready(function(){
     		$('.mainTime').slick({
			autoplay: true,
			dots: false,
			arrows: true,
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 1
		});
	});

	</script>
<?php } ?>



<!-- #메인video 시작  
<div class="cont_wrap">
	<h2 class="mtit mart40"><p>Market<span class="bold"> VIDEO</span></p></h2>
	<ul class="mvideo">
		<li><iframe width="590" height="332" src="//www.youtube.com/embed/LkOe6j2psfM" frameborder="0" allowfullscreen="" volume="10"></iframe></li>
		<li><iframe width="590" height="332" src="//www.youtube.com/embed/rY4Nb2qlCk4" frameborder="0" allowfullscreen="" volume="10"></iframe></li>
	</ul>
  </div> -->


<!-- #인기상품 TYPE2 시작 -->
<div class="pickpr_wrap mart60" id="menu2">
<div class="cont_wrap">		
     	<h2 class="mtit"><span><?php echo $dpLabels['2']['type_label']; //신상품; ?> <b>상품</b></span><a href="/shop/listtype.php?type=2" class="btnmore">더보기<i class="ionicons ion-ios-arrow-right"></i></a></h2>
			<?php
			$res = display_itemtype($pt_id, 2, 20);
			$type1_count = sql_num_rows($res);
			if($type1_count) {
			?>
			<div class="pickpr">
				<?php
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];
					if($row['dongurl']){
						$it_imager = get_it_image($row['index_no'], $row['simg1'], 238, 238);
						$it_image = "<video width='238' height='238' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='{$row['dongurl']}' type='video/mp4'></video>";
					}elseif($row['dongfile']){
						$it_imager = get_it_image($row['index_no'], $row['simg1'], 238, 238);
						$it_image = "<video width='238' height='238' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='".MS_URL."/data/goods/{$row['dongfile']}' type='video/mp4'></video>";
					}else{
						$it_image = get_it_image($row['index_no'], $row['simg1'], 238, 238);
					}
					$it_name = cut_str($row['gname'], 100);
			if($member['grade'] > '10'){
			/*	$it_sprice = "<p class=\"spr\">".number_format($row['normal_price'])."<span>원</span>"; */
				$it_price = "<p class='mpr'>회원전용가</p>";
				$sett = round((($row['normal_price'] - $row['goods_price'])/$row['normal_price'])*100);
			/*	$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>'; */
			}else{
				$it_price = get_price($row['index_no']);
				$it_amount = get_sale_price($row['index_no']);
				$it_point = display_point($row['gpoint']);

			$beasong = '';
			$beasong = get_sendcost_amt2($row['index_no'], $it_price);

				// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
				$it_sprice = $sale = '';
				if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
					$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
					$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
					$it_sprice = display_price2($row['normal_price']);
				}
			}
				?>
                     
                  <dl class="prli">
                        <p><?php echo $sale; ?></p>
		  			   <a href="<?php echo $it_href; ?>">
						<dt><?php echo $it_image; ?></dt>
                        <dd class="pname"><?php echo $it_name; ?></dd>
						<dd class="price"><?php echo $it_sprice; ?><span><?php echo $it_price; ?></span></dd>
                        <dd class="icon">
               <span class="naver"> <?php 		
		if($row['isnaver']=="1") {
				echo "<img class=\"naver\" src=\"/img/icon_new.png\">\n";
		} ?>
         </span><?php if($beasong == '0'){ ?><span class="delivery">무료배송</span><?php } ?>
			</dd>              
		       </a>
                
		<p class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></p>
                  
				</dl>
				<?php } ?>
			</div>
          </div>
			<?php } ?>
			<script>
		$(document).ready(function(){
		$(".pickpr").on('init reInit afterChange', function(event, slick, currentSlide, nextSlide) {
			var index = (currentSlide ? currentSlide : slick.currentSlide) + 1;
			$('.pickpr .slick-dots').html('<li>' + index + '/' + (slick.slideCount)+'</li>');//dots 가 나올자리에 <li>로 원하는 페이지 문자열 삽입
		});
		$('.pickpr').slick({
			autoplay: true,
			dots: true,
			arrows: true,
			infinite: true,
			slidesToShow: 5,
			slidesToScroll: 1
		});
	});
			</script>
		</div>

<!-- #인기상품 TYPE2 끝-->

<!-- (# NEW UPDATE) TYPE1 시작 
<div class="cont_wrap mart60">
	<div class="spc_wrap">
		<div class="spc_con">
			<h2># NEW UPDATE</h2>
			<?php
			$res = display_itemtype($pt_id, 1, 20);
			$type1_count = sql_num_rows($res);
			if($type1_count) {
			?>
			<div class="spclist">
				<?php
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];
					$it_image = get_it_image($row['index_no'], $row['simg1'], 186, 186);
					$it_name = cut_str($row['gname'], 100);
				if($member['grade'] > '9'){
						$it_sprice = "<p class='spr'>".number_format($row['normal_price'])."<span>원</span></p><p class='mpr'>회원전용가<span></span></p>";
						$sett = round((($row['normal_price'] - $row['goods_price'])/$row['normal_price'])*100);
						$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
				}else{
					$it_price = get_price($row['index_no']);
					$it_amount = get_sale_price($row['index_no']);
					$it_point = display_point($row['gpoint']);
					// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
					$it_sprice = $sale = '';
					if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
						$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
						$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
						$it_sprice = display_price2($row['normal_price']);
					}
				}
				?>
				<dl>
					<a href="<?php echo $it_href; ?>">
					<!--<?php if($row['isnaver']=="1") { ?>
						<img class="naver" src="/img/icon_new.png">
						<?php 
							$arr = explode(",", $row['icon_img']);
							for($i=1; $i<3; $i++) {
								$z = $i -1;
									echo "<img class=\"naver".$i."\" src=\"/icon/icon_".$arr[$z].".png?ver=1\" alt=\"\">";
							} ?>					
					<?php }else{ ?>
						<?php 
							$arr = explode(",", $row['icon_img']);
							for($i=0; $i<2; $i++) {
								$z = $i;
								if($z == '0'){ $z = ''; }
									echo "<img class=\"naver".$z."\" src=\"/icon/icon_".$arr[$i].".png?ver=1\" alt=\"\">";
							} ?>
					<?php } ?>
						<dt>
							
						<dt><?php echo $it_image; ?></dt>
						<dd class="pname"><?php echo $it_name; ?></dd>
						<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?><?php echo $sale; ?></dd>
						</a>
					<dd class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></dd>
				</dl>
				<?php } ?>
			</div>
			<?php } ?>
			<script>
			$(document).ready(function(){
				$('.spclist').slick({
					autoplay: true,
					dots: false,
					arrows: true,
					infinite: true,
					slidesToShow: 3,
					slidesToScroll: 1,
				});
			});
			</script>
		</div>
<!--#NEW UPDATE TYPE1 끝-->

<!-- BRAND 시작 
		<div class="mbrand">
			<h2>#BRAND</h2>
			<div class="mbrlist">
			<ul>
				<?php
				$sql = " select *
						   from shop_brand
						  where sho_go = '1' and br_user_yes = 0 or (br_user_yes = 1 and mb_id = '$pt_id')
							and br_logo <> ''";
				$res = sql_query($sql);
				$mod = 12;
				$i=0;
				while($row=sql_fetch_array($res)) {
             if($row['br_logo']){
					if($i && $i%$mod==0) echo "</ul><ul>";
					$href = MS_SHOP_URL."/brandlist.php?br_id=".$row['br_id'];
					$bimg = ($row['br_logo']) ? MS_DATA_URL.'/brand/'.$row['br_logo'] : MS_IMG_URL.'/brlogo_sam.jpg';
					$i++;
				?>
				<li><a href="<?php echo $href; ?>"><img src="<?php echo $bimg; ?>"></a></li>
				<?php } } 
				$empty_li = $mod - ($i%$mod);
				for($j=0;$j<$empty_li;$j++) echo "<li></li>";
				?>
			</ul>
		</div>
		<script>
		$(document).ready(function(){
			$('.mbrlist').slick({
				autoplay: true,
				autoplaySpeed: 4500,
				fade: true,
				dots: false,
				arrows: true
			});
		});
		</script>
	</div>
</div> 
<!-- BRAND 끝 -->

<!-- 인키키워드 -->
<div class="mainKw mart60">
	<dl>
		<dt><p class="txt1">Recommended</p><h2>KEYWORD</h2><p class="txt2">오마켓이 제안하는<br> 추천 키워드 </p></dt>
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
<!-- 인키키워드 끝-->

   <div class="cont_wrap">

   <!-- 관리자 [고정]메인>카테고리별 베스트 하단 배너 위치 시작-->
   <div class="wide_bn"><?php echo display_banner(6, $pt_id, $member['grade'], $member['mb_category']); ?></div>
    <!-- 관리자 [고정]메인>카테고리별 베스트 하단 배너 위치 끝-->

<!-- #카테고리별 추천 {-->
	<?php
	if($default['de_maintype_best']) {
		$list_best = unserialize(base64_decode($default['de_maintype_best']));
		$list_count = count($list_best);
		$tab_width = (float)(100 / $list_count);
	?>
	<h2 class="mtit mart60" id="menu1"><span><?php echo $default['de_maintype_title']; ?> <b>상품</b></span></h2>
	<ul class="bestca_tab">
			<?php for($i=0; $i<$list_count; $i++) { ?>
			<li data-tab="bstab_c<?php echo $i; ?>" style="width:<?php echo $tab_width; ?>%"><span><?php echo trim($list_best[$i]['subj']); ?></span></li>
			<?php } ?>
		</ul>
		<div class="pr_desc wli4" id="bestca_body">
			<?php echo get_listtype_cate($list_best, '280', '280'); ?>
		</div>
		<script>
		$(document).ready(function(){
			$(".bestca_tab>li:eq(0)").addClass('active');
			$("#bstab_c0").show();

			$(".bestca_tab>li").click(function() {
				var activeTab = $(this).attr('data-tab');
				$(".bestca_tab>li").removeClass('active');
				$("#bestca_body ul").hide();
				$(this).addClass('active');
				$("#"+activeTab).fadeIn(250);
			});
		});
		</script>
		<?php } ?>
	<!-- } #카테고리별 추천 끝  -->
	
<!--<?php if( isset($dpLabels['2'])) : ?>
<!-- #인기추천 시작 
<div class="cont_bg mart40">
	<h2 class="mtit"><span><?php echo $dpLabels['2']['type_label']; //#인기추천; ?> <b>상품</b></span></h2>
	<a href="/shop/listtype.php?type=2" class="aftarrw">전체보기</a>
	<?php echo get_listtype_best("2", '400', '400', '7', 'mart20'); ?>
</div>
<!--  #인기추천 끝 
<?php endif; ?> -->

<?php if( isset($dpLabels['3'])) : ?>
<!-- #공구추천상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span><?php echo $dpLabels['3']['type_label']; //#공구추천상품; ?> <b>상품</b></span>
	<a href="/shop/listtype.php?type=3" class="btnview">더보기<i class="ionicons ion-ios-arrow-right"></i></a></h2>
	<?php echo get_listtype_skin("3", '113', '113', '16', 'wli5 mart5'); ?>
    <!-- get_listtype_skin('영역', '이미지가로', '이미지세로', '총 출력수', '추가 class')-->
</div>
<!-- } #공구추천상품 끝 -->
<?php endif; ?>

<?php if( isset($dpLabels['4']) ) : ?>
<!-- #폐쇄몰추천상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span><?php echo $dpLabels['4']['type_label']; //#폐쇄몰추천상품; ?> <b>상품</b></span>
    <a href="/shop/listtype.php?type=4" class="btnview">더보기<i class="ionicons ion-ios-arrow-right"></i></a></h2>
	<?php echo get_listtype_skin("4", '235', '235', '16', 'wli4 mart5'); ?>
</div>
<!-- } #폐쇄몰추천상품 끝 -->
<?php endif; ?>

<?php if( false ) : ?>
<!-- 중간 배너영역 시작 { -->
<ul class="mmd_bn mart60">
	<li class="bnr1"><?php echo display_banner(8, $pt_id, $member['grade'], $member['mb_category']); ?></li>
	<li class="bnr2"><?php echo display_banner(9, $pt_id, $member['grade'], $member['mb_category']); ?></li>
	<li class="bnr3"><?php echo display_banner(10, $pt_id, $member['grade'], $member['mb_category']); ?></li>
	<li class="bnr4"><?php echo display_banner(11, $pt_id, $member['grade'], $member['mb_category']); ?></li>
</ul>
<!-- } 중간 배너영역 끝 -->
<?php endif; ?>

<?php if( isset($dpLabels['5']) ) : ?>
<!-- #오픈몰추천상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span><?php echo $dpLabels['5']['type_label']; //#오픈몰추천상품; ?> <b>상품</b></span>
	<a href="/shop/listtype.php?type=5" class="btnview">더보기<i class="ionicons ion-ios-arrow-right"></i></a></h2>
	<?php echo get_listtype_skin("5", '280', '280', '72', 'wli4 mart5'); ?>
</div>
<!-- } #오픈몰추천상품 끝 -->
<?php endif; ?>

<!-- 배너3+쇼핑특가 시작 { -->
    <?php if( isset($dpLabels['6']) ) : ?>
	<!-- <div style="margin-top:30px;">
	<a href="/shop/planlist.php?pl_no=10" target="_self"><img src="/theme/basic/main_banner_new1.jpg" /></a>
	</div> -->
	<div class="best_wrap">
		<div class="bnr1"><?php echo display_banner(3, $pt_id, $member['grade'], $member['mb_category']); ?></div>
		<div class="bnr2"><?php echo display_banner(4, $pt_id, $member['grade'], $member['mb_category']); ?></div>
		<div class="bnr3"><?php echo display_banner(5, $pt_id, $member['grade'], $member['mb_category']); ?></div>
        <div class="best_rol_slide">
			<h2>쇼핑특가</h2>
			<?php
			$res = display_itemtype($pt_id, 6, 20);
			$type1_count = sql_num_rows($res);
			if($type1_count) {
			?>
			<div class="best_rol">
				<?php
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];
					$it_image = get_it_image($row['index_no'], $row['simg1'], 190, 190);
					$it_name = cut_str($row['gname'], 100);
					$it_price = get_price($row['index_no']);
					$it_amount = get_sale_price($row['index_no']);
					$it_point = display_point($row['gpoint']);

					// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
					$it_sprice = $sale = '';
					if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
						$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
						$sale = '<dd class="sale">'.number_format($sett,0).'%</dd>';
						$it_sprice = display_price2($row['normal_price']);
					}
                    $usablePoint = '<p class="mpr fc_red">'.Good::displayUsablePoint($row).'</p>';
				?>
				<dl>
					<?php echo $sale; ?>
					<a href="<?php echo $it_href; ?>">
						<dt class="pimg"><?php echo $it_image; ?></dt>
						<dd class="pname" style="text-align:center;"><?php echo $it_name; ?></dd>
						<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
       					<!--dd class="price"><?php echo $it_price; ?></dd-->
					</a>
					<dd class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></dd>
				</dl>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
		<?php if($type1_count) { ?>
		<script>
		$(document).ready(function(){
			$('.best_rol').slick({
				autoplay: true,
				dots: false
			});
		});
		</script>
		<?php } ?>
	</div>
	<!-- } 배너3+쇼핑특가 끝 -->
    <?php endif; ?>


	<?php if( isset($dpLabels['7']) ) : ?>
<!-- #기타 시작 { -->
<?php echo mask_banner(7, $pt_id); ?>
<div class="cont_wrap mart60">
<h2 class="mtit"><span><?php echo $dpLabels['7']['type_label']; //#기타; ?> <b>상품</b></span>
	<a href="/shop/listtype.php?type=3" class="btnview">더보기<i class="ionicons ion-ios-arrow-right"></i></a></h2>
	<?php echo get_listtype_skin("7", '235', '235', '15', 'wli4 mart5'); ?>
</div>
<!-- } #기타 끝 -->
<?php endif; ?>
</div>
<!-- BRAND NEW 시작 -->      
	  <div class="cont_wrap ">
       <div class="m_brand"><h2><p>브랜드상품</p> 바로가기</h2>			
			<div class="mbr_inner">       
				<?php
				$sql = " select *
						   from shop_brand
						  where sho_go = '1' and br_user_yes = 0 or (br_user_yes = 1 and mb_id = '$pt_id')
							and br_logo <> ''";
				$res = sql_query($sql);
				$mod = 7;
				$i=0;
				while($row=sql_fetch_array($res)) {
             if($row['br_logo']){
					$href = MS_SHOP_URL."/brandlist.php?br_id=".$row['br_id'];
					$bimg = ($row['br_logo']) ? MS_DATA_URL.'/brand/'.$row['br_logo'] : MS_IMG_URL.'/brlogo_sam.jpg';
					$i++;
				?>
				<li class="mbr_li"><a href="<?php echo $href; ?>"><img src="<?php echo $bimg; ?>"></a></li>
				<?php } } 
				?>
		 </div>
        
		<script>
		$(document).on('ready', function() {
		  $(".mbr_inner").slick({
			autoplay: true,
			autoplaySpeed: 2000,
			dots: false,
			arrows: true,
			infinite: true,
			slidesToShow: 7,
			slidesToScroll: 1
		  });
		});
		</script>
    </div>
	</div>

<!-- BRAND NEW 끝 -->
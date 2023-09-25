<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가

$dpLabels = Shop::dpLabel($pt_id, array('use_yn'=>'Y', 'use_mshop_main'=>'Y'));

/* echo "aaa:".$member['grade'];
echo $member['mb_category']; */
?>

<?php if($slider1 = mobile_slider(0, $pt_id, $member['grade'], $member['mb_category'])) { ?>
    <!-- 메인배너 시작 { -->
    <div id="main_bn">
        <?php echo $slider1; ?>
    </div>
    <script>
    $(document).on('ready', function() {
	$("#main_bn").on('init reInit afterChange', function(event, slick, currentSlide, nextSlide) {
		var index = (currentSlide ? currentSlide : slick.currentSlide) + 1;
		$('#main_bn .slick-dots').html('<li>' + index + '/' + (slick.slideCount)+'</li>');//dots 가 나올자리에 <li>로 원하는 페이지 문자열 삽입
	});
	$('#main_bn').slick({
		autoplay: true,
		autoplaySpeed: 4000,
		dots: true,
		fade: true
	});
});
</script>
    <!-- } 메인배너 끝 -->
<?php } ?>



<!--Mall Service 아이콘 추가-->
	
   		<div class="msico mart20">
   <!--     <h2 class="mtit"><span>#Mall <b>Service</b></span></h2> -->
				<ul>
<!--메인메뉴 추가 mobile_main_menu('메뉴코드 9 고정이니 건들지마시오', '메뉴갯수')-->
					<?php echo mobile_main_menu('9', '10'); ?>
				</ul>
		</div>
	
<!--Mall Service 아이콘 추가 끝-->



<!-- 메인배너 하단 시작 { -->
<ul class="mbm_bn01">
	<li class="bnr1"><?php echo mobile_banner(2, $pt_id, $member['grade'], $member['mb_category']); ?></li>
	<li class="bnr2"><?php echo mobile_banner(3, $pt_id, $member['grade'], $member['mb_category']); ?></li>
	<li class="bnr3"><?php echo mobile_banner(4, $pt_id, $member['grade'], $member['mb_category']); ?></li>
</ul>
<!-- } 메인배너 하단 끝 -->

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
		str += '<span class="num">'+days + '</span> 일 ';
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
<!-- 메인 타임세일 시작 { -->
<!-- 메인 타임세일노출 -->
<?php
$sql = "select a.* FROM shop_goods a LEFT JOIN shop_goods_cate b ON ( a.index_no = b.gs_id ) LEFT JOIN shop_cate c ON ( b.gcate = c.catecode ) WHERE a.shop_state = '0' AND a.isopen = '1' AND c.u_hide = '0' AND (a.use_aff = '0') AND find_in_set('admin', a.use_hide) = '0' AND c.p_hide = '0' AND c.p_oper = 'y' and a.sb_date <= '".MS_TIME_YMD."' and a.eb_date >= '".MS_TIME_YMD."' group by a.index_no order by a.eb_date asc limit 0, 20";
$res = sql_query($sql);
$type1_count = sql_num_rows($res);
if($type1_count) {
?>
 <div class="mTwrap">

	<h2 class="mtit"><span>#타임 <i class="ionicons ion-android-alarm-clock" style="color:#2daa4a;"></i><b> Deal</b></span><a href="<?php echo MS_MSHOP_URL; ?>/timesale.php" class="btnview">더보기<i class="ionicons ion-ios-arrow-right"></i></a></h2>
		<div class="mainTime mart20">
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

			$beasong = '';
			$beasong = get_sendcost_amt2($row['index_no'], $it_price);

			$eb_date = date("Y-m-d",strtotime("+1 day", strtotime($row['eb_date'])));
			$yy = substr($eb_date, 0, 4);
			$mm = substr($eb_date, 5, 2);
			$dd = substr($eb_date, 8, 2);
			?>

			<div class="prli" style="width:197px;">
				<a href="<?php echo $it_href; ?>">
				 <dl>
					<dt class="pimg"><?php echo $it_image; ?></dt>
					<dd class="ptime"><i class="ionicons ion-android-alarm-clock"></i><span class="countdown_<?php echo $i; ?>"></span></dd>
					<dd class="pname"><?php echo $it_name; ?></dd>
					<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?><?php echo $sale; ?></dd>
                    <dd class="qty">남은수량  <?php echo number_format($row['stock_qty']);?>개</dd>
					<dd class="icon">
						<span class="naver"> <?php 		
		if($row['isnaver']=="1") {
				echo "<img class=\"naver\" src=\"/img/icon_new_m.png\">\n";
		} ?>
         </span>
        	 <?php if($beasong == '0'){ ?><span class="delivery">무료배송</span><?php } ?>
					</dd>
				</dl>
				<script language="javascript">
				CountDownTimer("<?php echo $mm; ?>/<?php echo $dd; ?>/<?php echo $yy; ?> 00:00 AM", "countdown_<?php echo $i; ?>");
				</script>  
				</a>
<!-- <p class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></p> -->			
			</div>
			<?php } ?>
		 </div>
	<script>
	$(document).ready(function(){
		$('.mainTime').slick({
			autoplay: true,
			dots: false,
			arrows: false,
			infinite: true,
			slidesToShow: 2,
			slidesToScroll: 1
		});
	});
	</script>
</div>
<?php } ?>
<!-- #인기상품 TYPE2 시작 -->
<div class="pickpr_wrap" id="menu2">
<div class="cont_wrap">		
     	<h2 class="mtit"><span><?php echo $dpLabels['2']['type_label']; //신상품; ?> <b>상품</b></span><a href="<?php echo MS_MSHOP_URL; ?>/listtype.php?type=2" class="btnview">더보기<i class="ionicons ion-ios-arrow-right"></i></a></h2>
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

			$beasong = '';
			$beasong = get_sendcost_amt2($row['index_no'], $it_price);
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
				echo "<img class=\"naver\" src=\"/img/icon_new_m.png\">\n";
		} ?>
         </span><?php if($beasong == '0'){ ?><span class="delivery">무료배송</span><?php } ?><span class="amt"><?php echo mobile_sendcost_amt(); ?></span>
			</dd>              
		       </a>                
	<!--	<p class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></p> -->
                  
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
			autoplay: false,
			dots: false,
			arrows: false,
			infinite: true,
			slidesToShow: 2,
			slidesToScroll: 2
		});
	});
			</script>
		</div>

<!-- # NEW UPDATE 시작 { 

<div class="pr_slide">
<h2 class="mtit"><span><?php echo $dpLabels['1']['type_label']; //신상품; ?> <b>상품</b></span></h2>
	<?php echo mobile_slide_goods('1', '20', 'slider'); ?>
</div>
<script>
		$(document).ready(function(){
			$('.slider').slick({
				autoplay: true,
				dots: false,
                arrows: false,
				infinite: true,
                slidesToShow: 3,
		    	slidesToScroll: 3
			});
		});
		</script>

<!-- } # NEW UPDATE  -->


<!-- 인기키워드 시작  -->
<div class="mainKw">
	<dl>
		<dt><p class="txt1">Recommended</p><h2>KEYWORD</h2><p class="txt2">오마켓이 제안하는 추천 키워드 </p></dt>
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

<!-- 인기키워드 끝  -->


<!-- 카테고리별 베스트 시작 {-->
<?php
if($default['de_maintype_best']) {
	$list_best = unserialize(base64_decode($default['de_maintype_best']));
	$list_count = count($list_best);
	$tab_width = (float)(100 / $list_count);
    ?>

<div class="bscate mart40">
	<h2 class="mtit"><span><?php echo $default['de_maintype_title']; ?> <b>상품</b></span></h2>
	<div class="bscate_tab">
		<?php for($i=0; $i<$list_count; $i++) { ?>
		<li data-tab="bstab_c<?php echo $i; ?>"style="width:<?php echo $tab_width; ?>auto"><span><?php echo trim($list_best[$i]['subj']); ?></span></li>
		<?php } ?>
       </div>
    <div class="bestca pr_desc wli3">
		<?php echo mobile_listtype_cate($list_best); ?>
	</div>
<script>
	$(document).ready(function(){
		$(".bscate_tab>li:eq(0)").addClass('active');
		$("#bstab_c0").show();

		$(".bscate_tab>li").click(function() {
			var activeTab = $(this).attr('data-tab');
			$(".bscate_tab>li").removeClass('active');
			$(".bscate ul").hide();
			$(this).addClass('active');
			$("#"+activeTab).fadeIn(250);
		});
	});
	</script>
</div>

<!-- } 카테고리별 베스트 끝 -->

<?php } ?>

<?php if( isset($dpLabels['3']) ) : ?>
<?php if($banner = mobile_banner(5, $pt_id, $member['grade'], $member['mb_category'])) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- 베스트셀러 시작 {-->
<div class="mart30">
    <?php echo mobile_display_goods('3', '8', $dpLabels['3']['type_label'], 'pr_desc wli2'); ?>
</div>
<!-- } 베스트셀러 끝 -->
<?php endif; ?>

<?php if( isset($dpLabels['4']) ) : ?>
<?php if($banner = mobile_banner(6, $pt_id, $member['grade'], $member['mb_category'])) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- 신상품 시작 { -->
<div class="mart30">
    <?php echo mobile_display_goods('4', '8', $dpLabels['4']['type_label'], 'pr_desc wli2'); ?>
</div>
<!-- } 신상품 끝 -->

<?php endif; ?>


<?php if( isset($dpLabels['5']) ) : // 가맹점상품 ?>
<?php if($banner = mobile_banner(7, $pt_id, $member['grade'], $member['mb_category'])) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- 브랜드 Weel 시작 // 가맹점 상품 { -->
<div class="mart30">
    <?php echo mobile_display_goods('5', '8', $dpLabels['5']['type_label'], 'pr_desc wli2'); ?>
</div>
<!-- } 브랜드 Weel 끝 -->
<?php endif; ?>


<?php if( isset($dpLabels['6']) ) : ?>
    <?php if($banner = mobile_banner(8, $pt_id, $member['grade'], $member['mb_category'])) { ?>
        <div class="ad mart30"><?php echo $banner; ?></div>
    <?php } ?>

    <!-- 후원상품 시작 { -->
    <div class="mart30">
        <?php echo mobile_display_goods('6', '72', $dpLabels['5']['type_label'], 'pr_desc wli2'); ?>
    </div>
    <!-- } 후원상품 끝 -->
<?php endif; ?>

<!-- BRAND NEW 시작 -->    
	     <div class="m_brand"><h2 >#브랜드상품</h2>			
			<div class="m_br_inner">       
				<?php
				$sql = " select *
						   from shop_brand
						  where sho_go = '1' and br_user_yes = 0 or (br_user_yes = 1 and mb_id = '$pt_id')
							and br_logo <> ''";
				$res = sql_query($sql);
				$mod = 3;
				$i=0;
				while($row=sql_fetch_array($res)) {
             if($row['br_logo']){
					$href = MS_SHOP_URL."/brandlist.php?br_id=".$row['br_id'];
					$bimg = ($row['br_logo']) ? MS_DATA_URL.'/brand/'.$row['br_logo'] : MS_IMG_URL.'/brlogo_sam.jpg';
					$i++;
				?>
				<li class="m_br_li"><a href="<?php echo $href; ?>"><img src="<?php echo $bimg; ?>"></a></li>
				<?php } } 
				?>
		 </div>
        
		<script>
		$(document).on('ready', function() {
		  $(".m_br_inner").slick({
			autoplay: true,
			autoplaySpeed: 3000,
			dots: false,
			arrows: true,
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 1
		  });
		});
		</script>
    </div>
	</div>

<!-- BRAND NEW 끝 -->
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

<div class="cont_wrap">
	<div class="spc_wrap">
		<div class="spc_con">
			<h2>BEST SELLER</h2>
			<?php
			$res = display_itemtype($pt_id, 2, 20);
			$type1_count = sql_num_rows($res);
			if($type1_count) {
			?>
			<div class="spclist">
				<?php
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];
					$it_image = get_it_image($row['index_no'], $row['simg1'], 186, 186);
					$it_name = cut_str($row['gname'], 100);
				if($member['grade'] > '6'){
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
					slidesToScroll: 1
				});
			});
			</script>
		</div>
		<div class="mbrand">
			<h2>BRAND</h2>
			<div class="mbrlist">
			<ul>
				<?php
				$sql = " select *
						   from shop_brand
						  where br_user_yes = 0 or (br_user_yes = 1 and mb_id = '$pt_id')
							and br_logo <> '' ";
				$res = sql_query($sql);
				$mod = 12;
				$i=0;
				while($row=sql_fetch_array($res)) {
					if($i && $i%$mod==0) echo "</ul><ul>";
					$href = MS_SHOP_URL."/brandlist.php?br_id=".$row['br_id'];
					$bimg = ($row['br_logo']) ? MS_DATA_URL.'/brand/'.$row['br_logo'] : MS_IMG_URL.'/brlogo_sam.jpg';
					$i++;
				?>
				<li><a href="<?php echo $href; ?>"><img src="<?php echo $bimg; ?>"></a></li>
				<?php }
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

<div class="cont_wrap">

<!-- 관리자 [고정]메인>카테고리별 베스트 하단 배너 위치 시작-->
<div class="wide_bn mart40"><?php echo display_banner(6, $pt_id); ?></div>
<!-- 관리자 [고정]메인>카테고리별 베스트 하단 배너 위치 끝-->


<!-- 카테고리별 베스트 시작 {-->
	<?php
	if($default['de_maintype_best']) {
		$list_best = unserialize(base64_decode($default['de_maintype_best']));
		$list_count = count($list_best);
		$tab_width = (float)(100 / $list_count);
	?>
	<h2 class="mtit mart60"><span><?php echo $default['de_maintype_title']; ?></span></h2>
	<ul class="bestca_tab">
		<?php for($i=0; $i<$list_count; $i++) { ?>
		<li data-tab="bstab_c<?php echo $i; ?>" style="width:<?php echo $tab_width; ?>%"><span><?php echo trim($list_best[$i]['subj']); ?></span></li>
		<?php } ?>
	</ul>
	<div class="pr_desc wli4" id="bestca_body">
		<?php echo get_listtype_cate($list_best, '209', '209'); ?>
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
	<!-- } 카테고리별 베스트 끝 -->


	<!-- 베스트 쇼핑특가 및 배너 시작 { -->
    <?php if( isset($dpLabels['1']) ) : ?>
	<!-- <div style="margin-top:30px;">
	<a href="/shop/planlist.php?pl_no=10" target="_self"><img src="/theme/basic/main_banner_new1.jpg" /></a>
	</div> -->
	<div class="best_wrap">
		<div class="bnr1"><?php echo display_banner(3, $pt_id); ?></div>
		<div class="bnr2"><?php echo display_banner(4, $pt_id); ?></div>
		<div class="bnr3"><?php echo display_banner(5, $pt_id); ?></div>
        <div class="best_rol_slide">
			<h2>쇼핑특가</h2>
			<?php
			$res = display_itemtype($pt_id, 1, 20);
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
						<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price.$usablePoint; ?></dd>
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
	<!-- } 베스트 쇼핑특가 및 배너 끝 -->
    <?php endif; ?>
</div>



<?php if( isset($dpLabels['2'])) : ?>
<!-- 베스트상품 시작 {-->
<div class="cont_bg mart40">
	<h2 class="mtit"><span><?php echo $dpLabels['2']['type_label']; //베스트셀러; ?></span></h2>
	<?php echo get_listtype_best("2", '400', '400', '7', 'mart20'); ?>
</div>
<!-- } 베스트상품 끝 -->
<?php endif; ?>

<?php if( isset($dpLabels['3'])) : ?>
<!-- 신상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span><?php echo $dpLabels['3']['type_label']; //신상품; ?></span></h2>
	<?php echo get_listtype_skin("3", '235', '235', '12', 'wli4 mart5'); ?>
</div>
<!-- } 신상품 끝 -->
<?php endif; ?>

<!-- 큰 배너 배경 및 문구 시작 { -->
<?php echo mask_banner(7, $pt_id); ?>
<!-- } 큰 배너 배경 및 문구 끝 -->

<?php if( isset($dpLabels['4']) ) : ?>
<!-- 인기상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span><?php echo $dpLabels['4']['type_label']; //인기상품; ?></span></h2>
	<?php echo get_listtype_skin("4", '235', '235', '72', 'wli4 mart5'); ?>
</div>
<!-- } 인기상품 끝 -->
<?php endif; ?>

<?php if( false ) : ?>
<!-- 중간 배너영역 시작 { -->
<ul class="mmd_bn mart60">
	<li class="bnr1"><?php echo display_banner(8, $pt_id); ?></li>
	<li class="bnr2"><?php echo display_banner(9, $pt_id); ?></li>
	<li class="bnr3"><?php echo display_banner(10, $pt_id); ?></li>
	<li class="bnr4"><?php echo display_banner(11, $pt_id); ?></li>
</ul>
<!-- } 중간 배너영역 끝 -->
<?php endif; ?>

<?php if( isset($dpLabels['5']) ) : ?>
<!-- 후원상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span><?php echo $dpLabels['5']['type_label']; //후원상품; ?></span></h2>
	<?php echo get_listtype_skin("5", '235', '235', '72', 'wli4 mart5'); ?>
</div>
<!-- } 후원상품 끝 -->
<?php endif; ?>

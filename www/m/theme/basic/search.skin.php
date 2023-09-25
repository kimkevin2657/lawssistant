<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가

$qstr1 = 'ss_tx='.$ss_tx.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'ss_tx='.$ss_tx;

$sort_str = '';
for($i=0; $i<count($gw_msort); $i++) {
	list($tsort, $torder, $tname) = $gw_msort[$i];

	$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?'.$qstr2.'&sort='.$tsort.'&sortodr='.$torder;

	if($sort == $tsort && $sortodr == $torder)
		$sort_name = $tname;
	if($i==0 && !($sort && $sortodr))
		$sort_name = $tname;

	$sort_str .= '<li><a href="'.$sct_sort_href.'">'.$tname.'</a></li>'.PHP_EOL;
}
?>

<style>
.price{overflow:visible;}
.naverrp{position:absolute;top:1%;z-index:998;float:right;right:1%;left:5px;}
</style>



<!-- 상품 정렬 선택 시작 { -->
<div id="sct_sort">
	<div class="count">전체 <strong><?php echo number_format($total_count); ?></strong>개</div>&nbsp;&nbsp;<a href="./search.php?ss_tx=<?php echo $ss_tx;?>&sort=isnaver&sortodr=desc">네이버최저가 보러가기</a>
	<span id="btn_sort"><?php echo $sort_name; ?></span>
</div>
<div id="sort_li">
	<h2>상품 정렬</h2>
	<ul>
		<?php echo $sort_str; // 탭메뉴 ?>
	</ul>
	<span id="sort_close" class="ionicons ion-ios-close-empty"></span>
</div>
<div id="sort_bg"></div>

<script>
$(function() {
	var mbheight = $(window).height();

	$('#btn_sort').click(function(){
		$('#sort_bg').fadeIn(300);
		$('#sort_li').slideDown('fast');
		$('html').css({'height':mbheight+'px', 'overflow':'hidden'});
	});

	$('#sort_bg, #sort_close').click(function(){
		$('#sort_bg').fadeOut(300);
		$('#sort_li').slideUp('fast');
		$('html').css({'height':'100%', 'overflow':'scroll'});
	});
});
</script>
<!-- } 상품 정렬 선택 끝 -->
<div>
	<p class="sct_li_type">
		<a href=""><img src="<?php echo MS_MTHEME_URL; ?>/img/bt_litype1.gif"></a>
		<a href="wli2"><img src="<?php echo MS_MTHEME_URL; ?>/img/bt_litype2_on.gif"></a>
		<a href="wli3"><img src="<?php echo MS_MTHEME_URL; ?>/img/bt_litype3.gif"></a>
	</p>

	<?php
	if(!$total_count) {
		echo "<p class=\"empty_list\">게시글이 없습니다.</p>";
	} else {
		echo "<ul class=\"pr_desc wli2\">";
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$it_href = MS_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
			$it_name = cut_str($row['gname'], 50);
			$it_imageurl = get_it_image_url($row['index_no'], $row['simg1'], 400, 400);
			if($member['grade'] == '10'){
				$it_sprice = "<p class=\"spr\">".number_format($row['normal_price'])."<span>원</span>";
				$it_price = "<p class='mpr'>회원전용가</p>";
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

			$usablePoint = "";
			if( ($row['point_pay_allow'] || $row['point_pay_max'] || $row['point_pay_per']) && Good::usablePoint($row)){
				$usablePoint = '<p class="mpr fc_red"><em class="fc_90" style="font-size:13px;">쇼핑포인트 할인</em> '.Good::displayUsablePoint($row).'</p>';
			}

			$goods_kv_basic = $row['goods_kv_basic'];
			$gpoint_basic = $row['gpoint_basic'];
			$gs['goods_kv'] = $row['goods_kv'];
			$gs['goods_kv_per'] = $row['goods_kv_per'];
			$gs['gpoint'] = $row['gpoint'];
			$gs['gpoint_per'] = $row['gpoint_per'];
			$gs['goods_price'] = $row['goods_price'];

			include $_SERVER["DOCUMENT_ROOT"]."/extend/_point_kv.php";

            $beasong = '';
			$beasong = get_sendcost_amt2($row['index_no'], $it_price);

			echo "<li>";
				echo "<a href=\"{$it_href}\">";
				echo "<dl>";
				if($row['dongurl']){
					echo "<dt><video style='display:block;' width='188.11' height='188.11' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='{$row['dongurl']}' type='video/mp4'></video></dt>";
				}elseif($row['dongfile']){
					echo "<dt><video style='display:block;' width='188.11' height='188.11' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='".MS_URL."/data/goods/{$row['dongfile']}' type='video/mp4'></video></dt>";
				}else{
					echo "<dt><img src=\"{$it_imageurl}\"></dt>";
				}
          if($row['isnaver']=="1") {
            echo "<dd class=\"pname\" style=\"text-align:center;\">{$it_name}&nbsp;&nbsp;</dd>\n";
          } else {
            echo "<dd class=\"pname\" style=\"text-align:center;\">{$it_name}</dd>\n";
          }
					if($row['info_color']) {
						echo "<dd class=\"op_color\">\n";
						$arr = explode(",", trim($row['info_color']));
						for($g=0; $g<count($arr); $g++) {
							echo get_color_boder(trim($arr[$g]), 1);
						}
						echo "</dd>\n";
					}
				echo "<dd class=\"price\">{$sale}{$it_sprice}{$it_price}";
						echo "<p class=\"icon\">";
		if($row['isnaver']=="1") {
				echo "<img class=\"naver\" src=\"/img/icon_new_m.png\">\n";
		}
				echo "</p>";	
	if($beasong == '0'){
			echo "<span class=\"delivery\">무료배송</span>\n";
	}
							echo "</p>";
					echo"</dd>\n";
				echo "</dl>";
				echo "</a>";
			echo "</li>";
		}
		echo "</ul>";
	}

	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&page=');
	?>
</div>

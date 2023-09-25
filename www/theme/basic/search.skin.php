<?php
if(!defined('_MALLSET_')) exit;

$qstr1 = 'ss_tx='.$ss_tx.'&page_rows='.$page_rows.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'ss_tx='.$ss_tx.'&page_rows='.$page_rows;
$qstr3 = 'ss_tx='.$ss_tx.'&sort='.$sort.'&sortodr='.$sortodr;

$sort_str = '';
for($i=0; $i<count($gw_psort); $i++) {
	list($tsort, $torder, $tname) = $gw_psort[$i];

	$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?'.$qstr2.'&sort='.$tsort.'&sortodr='.$torder;

	$active = '';
	if($sort == $tsort && $sortodr == $torder)
		$active = ' class="active"';
	if($i==0 && !($sort && $sortodr))
		$active = ' class="active"';

	$sort_str .= '<li><a href="'.$sct_sort_href.'"'.$active.'>'.$tname.'</a></li>'.PHP_EOL;
}
?>

<style>
.price{overflow:visible;}
.naverrp{position:absolute;top:0;z-index:998;left:0px;}
</style>

<h2 class="pg_tit"><span><?php echo $ms['title']; ?></span></h2>

<div class="bd list1 marb10">
	<fieldset style="padding:0 10px;">
		<legend>검색</legend>
		<form name="fsearch2" id="fsearch2" action="<?php echo MS_SHOP_URL; ?>/search_update.php" method="post">
		<input type="hidden" name="hash_token" value="<?php echo MS_HASH_TOKEN; ?>">
		<table class="wfull">
		<tr height="40">
			<td class="tal padr10 padl10 bb">
			<strong><span class="fc_255">"<?php echo $ss_tx; ?>"</span> 통합검색 결과 총 <span class="fc_255"><?php echo number_format($total_count); ?></span>개의 상품이 검색 되었습니다. <strong></td>
		</tr>
		<tr height="40">
			<td class="tal padr10 padl10">
				<span class="padr5">검색</span>
				<input name="ss_tx" value="<?php echo $ss_tx; ?>" size="40" class="frm_input">
				<input type="submit" value="검색" class="btn_small grey">
			</td>
		</tr>
		</table>
		</form>
	</fieldset>
</div>

<div class="tab_sort">&nbsp;&nbsp;<a href="./search.php?ss_tx=<?php echo $ss_tx;?>&sort=isnaver&sortodr=desc">네이버최저가 보러가기</a>
	<ul>
		<?php echo $sort_str; // 탭메뉴 ?>
	</ul>
	<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$qstr3}";?>&page_rows='+this.value;">
		<?php echo option_selected(($mod*5),  $page_rows, '5줄 정렬'); ?>
		<?php echo option_selected(($mod*10), $page_rows, '10줄 정렬'); ?>
		<?php echo option_selected(($mod*15), $page_rows, '15줄 정렬'); ?>
	</select>
</div>

<div class="pr_desc wli4">
	<ul>
		<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		if($row['dongurl']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], 235, 235);
			$it_image = "<video style='display:block;' width='235' height='235' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='{$row['dongurl']}' type='video/mp4'></video>";
		}elseif($row['dongfile']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], 235, 235);
			$it_image = "<video style='display:block;' width='235' height='235' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='".MS_URL."/data/goods/{$row['dongfile']}' type='video/mp4'></video>";
		}else{
			$it_image = get_it_image($row['index_no'], $row['simg1'], 235, 235);
		}
		$it_name = cut_str($row['gname'], 100);

				$it_name = cut_str($row['gname'], 100);
			if($member['grade'] == '9'){
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
          $beasong = '';
		  $beasong = get_sendcost_amt2($row['index_no'], $it_price);
	?>
		<li>
            <a href="<?php echo $it_href; ?>">
			<dl>
			    <dt><?php echo $it_image; ?></dt>
				<dd class="pname"><?php echo $it_name; ?></dd>
				<dd class="price"><?php echo $sale; ?><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
                <dd class="icon">
               <span class="naver"> <?php 		
		if($row['isnaver']=="1") {
				echo "<img class=\"naver\" src=\"/img/icon_new.png\">\n";
		} ?>
         </span><?php if($beasong == '0'){ ?><span class="delivery">무료배송</span><?php } ?>
            </dd>
			</dl>
			</a>
			<p class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></p>
		</li>
	<?php } ?>
	</ul>
</div>

<?php if(!$total_count) { ?>
<div class="empty_list bb">자료가 없습니다.</div>
<?php } ?>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&page=');
?>

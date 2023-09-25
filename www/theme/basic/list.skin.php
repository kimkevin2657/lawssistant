<?php
if(!defined('_MALLSET_')) exit;

$qstr1 = 'ca_id='.$ca_id.'&page_rows='.$page_rows.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'ca_id='.$ca_id.'&page_rows='.$page_rows;
$qstr3 = 'ca_id='.$ca_id.'&sort='.$sort.'&sortodr='.$sortodr;

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

<?php
$cgy = get_category_head_image($ca_id);
echo $cgy['img_head']; // 분류별 상단이미지 

echo tree_category($ca_id); // 하위분류
?>
<h2 class="pg_tit">
 	<span><?php echo get_catename($ca_id); ?></span>
	<p class="pg_nav">HOME<?php echo get_move($ca_id); ?></p>
</h2>

<div class="tab_sort">
	<span class="total">전체상품 <b class="fc_90" id="total"><?php echo number_format($total_count); ?></b>개</span><a href="./list.php?ca_id=<?php echo $ca_id;?>&sort=isnaver&sortodr=desc" style="float:left;color:#19ce60;font-weight:700;"><img src="/img/ic_naver2.svg" style="width:13px;position:relative;">&nbsp;네이버최저가 상품순보기</a>
	<ul>
		<?php echo $sort_str; // 탭메뉴 ?>
	</ul>
  <?php if($bestgood=="N") { ?>
	<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$qstr3}";?>&page_rows='+this.value;">
		<?php echo option_selected(($mod*5),  $page_rows, '5줄 정렬'); ?>
		<?php echo option_selected(($mod*10), $page_rows, '10줄 정렬'); ?>
		<?php echo option_selected(($mod*15), $page_rows, '15줄 정렬'); ?>
	</select>
  <?php } ?>
</div>

<div class="pr_desc wli4">
	<ul>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		if($row['dongurl']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], 235, 235);
			$it_image = "<video width='235' height='235' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='{$row['dongurl']}' type='video/mp4'></video>";
		}elseif($row['dongfile']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], 235, 235);
			$it_image = "<video width='235' height='235' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='".MS_URL."/data/goods/{$row['dongfile']}' type='video/mp4'></video>";
		}else{
			$it_image = get_it_image($row['index_no'], $row['simg1'], 235, 235);
		}
		$it_name = cut_str($row['gname'], 100);

				$it_name = cut_str($row['gname'], 100);
				if($member['grade'] > '9'){
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
					$sale = '<span class="sale">'.number_format($sett,0).'%</span>';
					$it_sprice = display_price2($row['normal_price']);
				}
			}
            $beasong = '';
			$beasong = get_sendcost_amt2($row['index_no'], $it_price);
    //  if($_SERVER['REMOTE_ADDR']=="222.98.175.233") {
        //var_dump($row);
    //  }

	?>
		<li>
           	<a href="<?php echo $it_href; ?>">
			<dl>
				<?php if($bestgood=="Y" &&  $page=='1') { 
					if($i < 50){ ?>
        <dd class="bstnbr"> BEST <?=$i+1?></dd>
        <?php }} ?>
		
        <dt><?php echo $it_image; ?></dt>
				<dd class="pname"><?php echo $it_name; ?></dd>
				<dd class="price"><?php echo $sale; ?><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
				<!--dd class="price"><?php echo $it_price; ?><?php echo $sale; ?></dd-->
                <dd class="icon">
		       <span class="naver"> <?php 		
		if($row['isnaver']=="1") {
				echo "<img class=\"naver\" src=\"/img/icon_new.png\">\n";
				$arr = explode(",", $row['icon_img']);
				for($a=1; $a<3; $a++) {
					$z = $a -1;
					echo "<img class=\"naver".$a."\" src=\"/icon/icon_".$arr[$z].".png?ver=1\" alt=\"\">";
				}
		}else{
			$arr = explode(",", $row['icon_img']);
			for($b=0; $b<2; $b++) {
				$z = $b;
				if($z == '0'){ $z = ''; }
				echo "<img class=\"naver".$z."\" src=\"/icon/icon_".$arr[$b].".png?ver=1\" alt=\"\">"; 
			} 
		} ?>
         </span><?php if($beasong == '0'){ ?><span class="delivery">무료배송</span><?php } ?></dd>
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
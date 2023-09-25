<?php
if(!defined('_MALLSET_')) exit;

$qstr1 = 'page_rows='.$page_rows.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'page_rows='.$page_rows;
$qstr3 = 'sort='.$sort.'&sortodr='.$sortodr;

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
			document.getElementById(id).innerHTML = 'EXPIRED!';
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
		document.getElementById(id).innerHTML = str;
	}

	timer = setInterval(showRemaining, 1000);
}

function pad(n, width) {
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}
</script>


 <h2 class="pg_title">
  <img src="/img/timesale.jpg">
</h2> 
<h2 class="pg_tit">
    <span><?php //echo $ms['title']; ?></span>
	<p class="pg_nav">HOME<i>&gt;</i>TIME ATTACK</p>
</h2>


<div class="tab_sort">
	<span class="total">전체상품 <b class="fc_90" id="total"><?php echo number_format($total_count); ?></b>개</span><a href="./list.php?ca_id=<?php echo $ca_id;?>&sort=isnaver&sortodr=desc" style="padding-left:15px;color:#19ce60;font-weight:700;"><img src="/img/ic_naver2.svg" style="width:13px;position:relative;">&nbsp;네이버최저가 상품순보기</a>
	<ul>
		<?php echo $sort_str; // 탭메뉴 ?>
	</ul>
	<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$qstr3}";?>&page_rows='+this.value;">
		<?php echo option_selected(($mod*5),  $page_rows, '5줄 정렬'); ?>
		<?php echo option_selected(($mod*10), $page_rows, '10줄 정렬'); ?>
		<?php echo option_selected(($mod*15), $page_rows, '15줄 정렬'); ?>
	</select>
</div>

<div class="timesale">
	<ul>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = MS_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		if($row['dongurl']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], 315, 315);
			$it_image = "<video style='display:block;' width='315' height='315' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='{$row['dongurl']}' type='video/mp4'></video>";
		}elseif($row['dongfile']){
			$it_imager = get_it_image($row['index_no'], $row['simg1'], 315, 315);
			$it_image = "<video style='display:block;' width='315' height='315' autoplay='autoplay' loop preload='metadata' muted='muted' playsinline='playsinline'><source src='".MS_URL."/data/goods/{$row['dongfile']}' type='video/mp4'></video>";
		}else{
			$it_image = get_it_image($row['index_no'], $row['simg1'], 315, 315);
		}
		$it_name = cut_str($row['gname'], 100);
	
		
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

        $beasong = '';
		$beasong = get_sendcost_amt2($row['index_no'], $it_price);

		$eb_date = date("Y-m-d",strtotime("+1 day", strtotime($row['eb_date'])));
		$yy = substr($eb_date, 0, 4);
		$mm = substr($eb_date, 5, 2);
		$dd = substr($eb_date, 8, 2);
	?>
		<li>
            <p><?php echo $sale; ?></p>
			<a href="<?php echo $it_href; ?>">
			<dl>
				<dt><?php echo $it_image; ?></dt>
				<dd class="ptime"><i class="ionicons ion-android-alarm-clock"></i><span id="countdown_<?php echo $i; ?>"></span></dd>
				<dd class="pname"><?php echo $it_name; ?></dd>
				<?php
				if($row['info_color']) {
					echo "<dd class=\"op_color\">\n";
					$arr = explode(",", trim($row['info_color']));
					for($g=0; $g<count($arr); $g++) {
						echo get_color_boder(trim($arr[$g]), 1);
					}
					echo "</dd>\n";
				}
				?>
				<dd class="price"><span><?php echo $it_sprice; ?><?php echo $it_price; ?></span></dd>
                <dd class="icon">
               <span class="naver"> <?php 		
		if($row['isnaver']=="1") {
				echo "<img class=\"naver\" src=\"/img/icon_new.png\">\n";
		} ?>
             </span><span class="qty">남은수량 <?php echo number_format($row['stock_qty']);?>개</span><?php if($beasong == '0'){ ?><span class="delivery">무료배송</span><?php } ?>
            </dd>
		 	</dl>			
			</a>
		<p class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></p>
			<script language="javascript">
			CountDownTimer("<?php echo $mm; ?>/<?php echo $dd; ?>/<?php echo $yy; ?> 00:00 AM", "countdown_<?php echo $i; ?>");
			</script>
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

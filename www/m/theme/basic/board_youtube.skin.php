<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가
?>

<script src="<?php echo MS_MJS_URL; ?>/masonry.pkgd.js"></script>
<script src="<?php echo MS_MJS_URL; ?>/imagesloaded.pkgd.min.js"></script>

<?php if($board['fileurl1']) { ?>
<div class="m_bo_hd"><img src='<?php echo MS_DATA_URL; ?>/board/boardimg/<?php echo $board['fileurl1']; ?>'></div>
<?php } ?>
<div class="m_gall">
	<?php if($board['use_category']) { ?>
	<select name="faq_type" class="faq_sch" onchange="location=this.value;">
		<option value="<?php echo MS_MBBS_URL; ?>/board_list.php?boardid=<?php echo $boardid; ?>">전체보기</option>
		<?php
		for($i=0; $i<count($usecate); $i++) {
			$selected = "";
			if($usecate[$i]==$ca_name) {
				$selected = ' selected';
			}
		?>
		<option value="<?php echo MS_MBBS_URL; ?>/board_list.php?boardid=<?php echo $boardid; ?>&ca_name=<?php echo $usecate[$i]; ?>"<?php echo $selected; ?>><?php echo $usecate[$i]; ?></option>
		<?php } ?>
	</select>
	<?php } ?>

	<?php
	if(!$total_count) {
		echo "<p class=\"empty_list\">게시글이 없습니다.</p>";
	} else {
	?>
	<ul id="m_gall_ul">
		<?php
		$sql = " select * from shop_board_{$boardid} where btype = '1' {$sql_search2} order by fid desc ";
		$rst = sql_query($sql);
		for($i=0; $row=sql_fetch_array($rst); $i++) {
			$href = MS_MBBS_URL.'/board_read.php?index_no='.$row['index_no'].'&boardid='.$boardid.'&page='.$page;

			$bo_subject = '<strong class="fc_eb7">[공지]</strong> '.get_text($row['subject']);
			$bo_wdate = get_text($row['writer_s'])."<span class='padl10'>".date("y/m/d",$row['wdate']);

			if((MS_SERVER_TIME - $row['wdate']) < (60*60*24)) {
				$bo_subject .= " <img src='".MS_IMG_URL."/iconY.gif' class='marl3'>";
			}

			$thumb = get_list_thumbnail($boardid, $row, 400, 0);
			if(!$thumb['src']) {
				$thumb['src'] = MS_IMG_URL.'/noimage.gif';
			}
		?>
		<li class="gall_item">
			<a href="<?php echo $href; ?>">
			<dl>
				<dt><img src="<?php echo $thumb['src']; ?>" class="lazyload"></dt>
				<dd class="subj"><?php echo $bo_subject; ?></dd>
				<dd class="date"><?php echo $bo_wdate; ?></dd>
			</dl>
			</a>
		</li>
		<?php
		}

		for($i=0; $row=sql_fetch_array($result); $i++) {
			$href = MS_MBBS_URL.'/board_read.php?index_no='.$row['index_no'].'&boardid='.$boardid.'&page='.$page;

			$bo_subject = '';
			$bo_wdate_c = '';
			$spacer = strlen($row['thread'] != 'A');
			if($spacer>$reply_limit) {
				$spacer = $reply_limit;
			}

			for($i2=0; $i2<$spacer; $i2++) {
				$bo_subject = "<img src='{$bo_img_url}/img/icon_reply.gif'> ";
				$bo_wdate_c = " padl13";
			}

			$bo_subject = $bo_subject .get_text($row['subject']);
			$bo_wdate = get_text($row['writer_s'])."<span class='padl10'>".date("y/m/d",$row['wdate']);

			if($row['issecret'] == 'Y') {
				$bo_subject .= " <img src='{$bo_img_url}/img/icon_secret.gif'>";
			}

			if((MS_SERVER_TIME - $row['wdate']) < (60*60*24)) {
				$bo_subject .= " <img src='{$bo_img_url}/img/iconY.gif'>";
			}

			$thumb = get_list_thumbnail($boardid, $row, 400, 0);
			if(!$thumb['src']) {
				$thumb['src'] = MS_IMG_URL.'/noimage.gif';
			}
      $midx = explode("/",$row["etc_2"]);
		?>
		<li class="gall_item">
			<a href="<?php echo $href; ?>">
			<dl>
				<dt><img src="https://img.youtube.com/vi/<?=$midx['4']?>/mqdefault.jpg" class="lazyload"></dt>
				<dd class="subj"><?php echo $bo_subject; ?></dd>
        <dd class="date">&nbsp;</dd>
        <dd class="date"><?php echo $row['etc_1']; ?></dd>
        <dd class="date">&nbsp;</dd>
				<dd class="date"><?php echo $bo_wdate; ?></dd>
			</dl>
			</a>
		</li>
		<?php } ?>
	</ul>
	<?php } ?>

	<?php
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?boardid='.$boardid.'&page=');
	?>

	<?php if($member['grade'] <= $board['write_priv'] && $LYJ=="CIA") { ?>
	<div class="btn_confirm">
		<a href="<?php echo MS_MBBS_URL; ?>/board_write.php?boardid=<?php echo $boardid;?>" class="btn_medium">글쓰기</a>
	</div>
	<?php } ?>

	<form name="searchform" method="get">
	<input type="hidden" name="boardid" value="<?php echo $boardid; ?>">
	<div class="bottom_sch">
		<select name="sfl">
		<?php
		for($i=0;$i<sizeof($gw_search_value);$i++) {
			echo "<option value='{$gw_search_value[$i]}'".get_selected($gw_search_value[$i], $sfl).">{$gw_search_text[$i]}</option>\n";
		}
		?>
		</select>
		<input type="text" name="stx" class="frm_input" value="<?php echo $stx; ?>">
		<input type="submit" value="검색" class="btn_small grey">
	</div>
	</form>
</div>

<script>
/* Mobile 브라우져 체크 함수 */
function mobile_chk(){
	var user_device = navigator.userAgent.toLowerCase();
	var mobile_device = new Array('iphone','ipad', 'firefox', 'android');
	for(var i=0;i<mobile_device.length;i++){
		if(user_device.indexOf(mobile_device[i]) != -1)	return true;
	}
	return false;
}

var $masgall = $('#m_gall_ul').masonry({
  itemSelector: '.gall_item',
  percentPosition: true,
  columnWidth: '.gall_item'
});
// layout Masonry after each image loads
$masgall.imagesLoaded().progress( function() {
  $masgall.masonry();
});
</script>

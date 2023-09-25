<?php
if(!defined('_MALLSET_')) exit;
?>
<h2 class="pg_title">
   <img src="/img/plan_banner.jpg">
   </h2>
<ul class="plan">
	<?php
	$sql = "select * from shop_plan where pl_use = '1' ";
	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		$href = MS_MSHOP_URL.'/planlist.php?pl_no='.$row['pl_no'];
		$bimg = MS_DATA_PATH.'/plan/'.$row['pl_limg'];
/*		if(is_file($bimg) && $row['pl_limg']) { // 이미지 없으면 아예 목록에서 빠지게 변경 2022-02-17
			$pl_limgurl = rpc($bimg, MS_PATH, MS_URL);
		} else {
			$pl_limgurl = MS_IMG_URL.'/plan_noimg.gif';
		}
*/
	if(is_file($bimg) && $row['pl_limg']) { // 이미지 없으면 아예 목록에서 빠지게 변경 2022-02-17
			$pl_limgurl = rpc($bimg, MS_PATH, MS_URL);
	?>
	<li>
		<a href="<?php echo $href; ?>">
		<p class="plan_img"><img src="<?php echo $pl_limgurl; ?>"></p>
		<p class="plan_tit"><?php echo $row['pl_name']; ?></p>
		</a>
	</li>
	<?php } } ?>
</ul>

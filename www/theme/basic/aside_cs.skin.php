<?php
if(!defined('_MALLSET_')) exit;
?>
<?
	if(!$_GET['bo_table']){	
?>
<!-- 좌측메뉴 시작 { -->
<aside id="aside">
	<div class="aside_hd">
		<p class="eng">CS CENTER</p>
		<p class="kor">고객센터</p>
	</div>
	<dl class="aside_cs">	
		<?php
		$aboardid = $_GET['boardid'];
		$dor = sql_fetch("select gr_id from shop_board_conf where index_no='$aboardid' ");
		$gr_id = $dor['gr_id'];
		if(!$gr_id) { $gr_id = 'gr_mall'; }
		$sql = " select * from shop_board_conf where gr_id='$gr_id' order by index_no asc ";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
			
			if($member['grade'] == 1){
				$bo_href = MS_BBS_URL.'/list.php?boardid='.$row['index_no'];
				echo '<dt><a href="'.$bo_href.'">'.$row['boardname'].'</a></dt>'.PHP_EOL;
			}else{
				if($row['mb_grade'] == ""){
					$bo_href = MS_BBS_URL.'/list.php?boardid='.$row['index_no'];
					echo '<dt><a href="'.$bo_href.'">'.$row['boardname'].'</a></dt>'.PHP_EOL;
				}else{
					if($member['grade'] == $row['mb_grade'] && $member['mb_category'] == $row['mb_category'] || $member['grade'] == $row['mb_grade'] && $row['mb_category'] == ""){
						$bo_href = MS_BBS_URL.'/list.php?boardid='.$row['index_no'];
						echo '<dt><a href="'.$bo_href.'">'.$row['boardname'].'</a></dt>'.PHP_EOL;
					}
				}
			}



			/* if($row['index_no'] == "52"){
				if($member['grade'] == $row['mb_grade'] && $member['mb_category'] == $row['mb_category']){
					$bo_href = MS_BBS_URL.'/list.php?boardid='.$row['index_no'];
					echo '<dt><a href="'.$bo_href.'">'.$row['boardname'].'</a></dt>'.PHP_EOL;
				}
			}else{
				$bo_href = MS_BBS_URL.'/list.php?boardid='.$row['index_no'];
				echo '<dt><a href="'.$bo_href.'">'.$row['boardname'].'</a></dt>'.PHP_EOL;
			} */
		}
		?>	
		<?php
		$sql = " select * from shop_board_conf where gr_id='gr_normal' order by index_no asc ";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) { 
		?>
		<dt><a href="<?php echo MS_BBS_URL; ?>/list.php?boardid=<?php echo $row['index_no']; ?>"><?php echo $row['boardname']; ?></a></dt>
	<?php } ?>	
		<dt><a href="<?php echo MS_BBS_URL; ?>/review.php">고객상품평</a></dt>
		<dt><a href="<?php echo MS_BBS_URL; ?>/qna_list.php">1:1 상담문의</a></dt>		
		<dt><a href="<?php echo MS_BBS_URL; ?>/faq.php?faqcate=1">자주묻는질문</a></dt>		
		<?php
		// FAQ MASTER
		$fm_sql = "select * from shop_faq_cate order by index_no asc";
		$fm_result = sql_query($fm_sql);
		for($i=0;$row=sql_fetch_array($fm_result);$i++){
			if($i==0) echo "<dd>\n<ul>\n";
			$fm_href = MS_BBS_URL.'/faq.php?faqcate='.$row['index_no'];
			echo '<li><a href="'.$fm_href.'">'.$row['catename'].'</a></li>'.PHP_EOL;
		}
		if($i > 0) echo "</ul>\n</dd>\n";
		?>
	</dl>
</aside>
<? } ?>
<!-- } 좌측메뉴 끝 -->

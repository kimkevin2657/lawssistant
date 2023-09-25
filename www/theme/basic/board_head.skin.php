<?php
if(!defined('_MALLSET_')) exit;
		$aboardid = $_GET['boardid'];
		$dor = sql_fetch("select gr_id from shop_board_conf where index_no='$aboardid' ");
		$gr_id = $dor['gr_id'];
		if(!$gr_id) { $gr_id = 'gr_mall'; }
if($gr_id == 'gr_mall'){
$con_lf = "con_lf";
Theme::get_theme_part(MS_THEME_PATH,'/aside_cs.skin.php');
}else{
$con_lf = "con_lf_s";
}
?>

<div id="<?php echo $con_lf; ?>">
	<h2 class="pg_tit">
		<span><?php echo $board['boardname']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>고객센터<i>&gt;</i><?php echo $board['boardname']; ?></p>
	</h2>

	<?php if($board['fileurl1']) { ?>
		<?php if($_GET['boardid'] == '103'){ ?>
	<p class="marb10"><a href="<?php echo MS_BBS_URL; ?>/seller_reg.php"><img src="<?php echo MS_DATA_URL; ?>/board/boardimg/<?php echo $board['fileurl1']; ?>"></a></p>
		<?php }elseif($_GET['boardid'] == '104'){ ?>
	<p class="marb10"><a href="<?php echo MS_BBS_URL; ?>/minishop_reg.php"><img src="<?php echo MS_DATA_URL; ?>/board/boardimg/<?php echo $board['fileurl1']; ?>"></a></p>
		<?php }else{ ?>
	<p class="marb10"><img src="<?php echo MS_DATA_URL; ?>/board/boardimg/<?php echo $board['fileurl1']; ?>"></p>
		<?php } ?>
	<?php } ?>
<?php
if(!defined('_MALLSET_')) exit;
?>

<div><img src="<?php echo MS_IMG_URL; ?>/seller_reg.gif" style="width: 1200px;"></div>
<div class="mart20">
	<?php echo get_view_thumbnail(conv_content($config['seller_reg_guide'], 1), 1000); ?>
</div>
<div class="btn_confirm">
<?php if($member['id']){ ?>
	<a href="<?php echo MS_BBS_URL; ?>/seller_reg_from.php" class="btn_large wset">확인</a>
<?php }else{ ?>
	<a href="<?php echo MS_BBS_URL; ?>/seller_reg_plus_form.php" class="btn_large wset">확인</a>
<? } ?>
	<a href="<?php echo MS_URL; ?>" class="btn_large bx-white">취소</a>
</div>
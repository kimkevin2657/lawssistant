<?php
if(!defined('_MALLSET_')) exit;

$nw = sql_fetch("select * from shop_ebook_view where no='$no' and bpage='$bpage'");
if(!$nw['b_no']){ $w = ""; }else{ $w = "u"; }


?>

<form name="fregform" method="post" action="./design/ebook_view_form_update.php" onsubmit="return fregform_submit(this);" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="bpage" value="<?php echo $bpage; ?>">
<input type="hidden" name="no" value="<?php echo $no; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">페이지</th>
		<td>
			<?php echo $bpage; ?>
		</td>
	</tr>
	<tr>
		<th scope="row">대표이미지(필수)</th>
		<td>
			<input type="file" name="img" id="img">
			<?php
			$bimg_str = "";
			$bimg = MS_DATA_PATH.'/ebook/'.$nw['img'];
			if(is_file($bimg) && $nw['img']) {
				$size = @getimagesize($bimg);
				if($size[0] && $size[0] > 100)
					$width = 100;
				else
					$width = $size[0];

				$bimg = rpc($bimg, MS_PATH, MS_URL);

				echo '<input type="checkbox" name="bn_file_del" value="'.$nw['img'].'" id="bn_file_del"> <label for="bn_file_del">삭제</label>';
				$bimg_str = '<img src="'.$bimg.'" width="'.$width.'">';
			}
			if($bimg_str) {
				echo '<div class="banner_or_img">'.$bimg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">html(선택)</th>
		<td>
			<?php echo editor_html('con', get_text($nw['con'], 0)); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./design.php?code=ebook_view_list&page=<?php echo $page; ?>&no=<?php echo $no; ?>" class="btn_large bx-white">목록</a>
</div>
</form>

<script>
function fregform_submit(f) {
	<?php echo get_editor_js('con'); ?>

    return true;
}
</script>

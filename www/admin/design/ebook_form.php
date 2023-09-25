<?php
if(!defined('_MALLSET_')) exit;

if($w == "") {
	$nw['state'] = 0;
} else if($w == "u") {
	$nw = sql_fetch("select * from shop_ebook where no='$pp_id'");
    if(!$nw['no'])
        alert("ebook이 존재하지 않습니다.");
}
?>

<form name="fregform" method="post" action="./design/ebook_form_update.php" onsubmit="return fregform_submit(this);">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="pp_id" value="<?php echo $pp_id; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">이북제목</th>
		<td><input type="text" name="title" value="<?php echo get_text($nw['title']); ?>" required itemname="이북 제목" class="required frm_input" size="80"></td>
	</tr>
	<tr>
		<th scope="row">페이지수</th>
		<td><input type="text" name="bpage" value="<?php echo $nw['bpage']; ?>" required itemname="페이지수" class="required frm_input" size="80"></td>
	</tr>
	<tr>
		<th scope="row">노출여부</th>
		<td class="td_label">
			<input type="radio" name="state" value="0" id="state_yes"<?php echo ($nw['state']==0)?" checked":""; ?>> <label for="state_yes">노출함</label>
			<input type="radio" name="state" value="1" id="state_no"<?php echo ($nw['state']==1)?" checked":""; ?>> <label for="state_no">노출안함</label>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./design.php?code=ebook_list<?php echo $qstr; ?>&page=<?php echo $page; ?>" class="btn_large bx-white">목록</a>
</div>
</form>

<script>
function fregform_submit(f) {
    return true;
}
</script>

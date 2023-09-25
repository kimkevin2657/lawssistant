<?php
if(!defined('_MALLSET_')) exit;

$bk_ix = $_GET['bk_ix'];
$booking_id = $_GET['booking_id'];
$bk = sql_fetch("SELECT * FROM g5_wzb3_booking where bk_ix = '{$bk_ix}' ");

$options = sql_fetch("SELECT * FROM g5_wzb3_booking_option where bk_ix = '{$bk_ix}' ");
?>

<div id="sit_use_write" class="new_win">
	<h1 id="win_title"><?php echo $ms['title']; ?></h1>

	<form name="forderreview" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return forderreview_submit(this);" enctype="multipart/form-data">
	<input type="hidden" name="bk_ix" value="<?php echo $bk_ix; ?>">
	<input type="hidden" name="mb_id" value="<?php echo $member['id']; ?>">
	<input type="hidden" name="token" value="<?php echo $token; ?>">
	<input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w90">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">디자이너명</th>
			<td><?php echo get_text($bk['bk_subject']); ?></td>
		</tr>
		<tr>
			<th scope="row">상품명</th>
			<td><?php echo get_text($options['odo_name']); ?></td>
		</tr>
		<tr>
			<th scope="row">주문번호</th>
			<td><?php echo $bk['od_id']; ?></td>
		</tr>
		<tr>
			<th scope="row">이름</th>
			<td><?php echo $member['name']; ?></td>
		</tr>
		<tr>
			<th scope="row">평점</th>
			<td>
				<input type="radio" name="score" value="5" checked>
				<img src="<?php echo MS_IMG_URL ?>/sub/score_5.gif">
				<input type="radio" name="score" value="4">
				<img src="<?php echo MS_IMG_URL ?>/sub/score_4.gif">
				<input type="radio" name="score" value="3">
				<img src="<?php echo MS_IMG_URL ?>/sub/score_3.gif">
				<input type="radio" name="score" value="2">
				<img src="<?php echo MS_IMG_URL ?>/sub/score_2.gif">
				<input type="radio" name="score" value="1">
				<img src="<?php echo MS_IMG_URL ?>/sub/score_1.gif">
			</td>
		</tr>
		<tr>
			<th scope="row">내용</th>
			<td><textarea name="memo" class="frm_textbox wufll"></textarea></td>
		</tr>
		<tr>
			<th scope="row">이미지</th>
			<td>
				<input type="file" name="review_img">
			</td>
		</tr>
		</tbody>
		</table>
	</div>

    <div class="win_btn">
        <input type="submit" value="작성완료" class="btn_lsmall">
		<a href="javascript:window.close();" class="btn_lsmall bx-white">창닫기</a>
    </div>

	</form>
</div>

<script>
function forderreview_submit(f) {
	if(!f.memo.value) {
		alert('내용을 입력하세요.');
		f.memo.focus();
		return false;
	}

	if(confirm("등록 하시겠습니까?") == false)
		return false;

    return true;
}
</script>
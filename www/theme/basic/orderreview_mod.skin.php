<?php
if(!defined('_MALLSET_')) exit;
?>

<div id="sit_use_write" class="new_win">
	<h1 id="win_title"><?php echo $ms['title']; ?></h1>

	<form name="forderreview" method="post" action="<?php echo $form_action_url; ?>" enctype="multipart/form-data" onsubmit="return forderreview_submit(this);">
	<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">
  <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
	<input type="hidden" name="seller_id" value="<?php echo $gs['mb_id']; ?>">
	<input type="hidden" name="token" value="<?php echo $token; ?>">

	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w90">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">상품명</th>
			<td><?php echo get_text($gs['gname']); ?></td>
		</tr>
		<tr>
			<th scope="row">주문번호</th>
			<td><?php echo $od_id; ?></td>
		</tr>
		<tr>
			<th scope="row">이름</th>
			<td><?php echo $member['name']; ?></td>
		</tr>
		<tr>
			<th scope="row">평점</th>
			<td>
				<input type="radio" name="score" value="5" <?php if($rd['score'] == '5'){ echo "checked"; } ?>>
				<img src="<?php echo MS_IMG_URL ?>/sub/score_5.gif">
				<input type="radio" name="score" value="4" <?php if($rd['score'] == '4'){ echo "checked"; } ?>>
				<img src="<?php echo MS_IMG_URL ?>/sub/score_4.gif">
				<input type="radio" name="score" value="3" <?php if($rd['score'] == '3'){ echo "checked"; } ?>>
				<img src="<?php echo MS_IMG_URL ?>/sub/score_3.gif">
				<input type="radio" name="score" value="2" <?php if($rd['score'] == '2'){ echo "checked"; } ?>>
				<img src="<?php echo MS_IMG_URL ?>/sub/score_2.gif">
				<input type="radio" name="score" value="1" <?php if($rd['score'] == '1'){ echo "checked"; } ?>>
				<img src="<?php echo MS_IMG_URL ?>/sub/score_1.gif">
			</td>
		</tr>
		<tr>
			<th scope="row">내용</th>
			<td><textarea name="memo" class="frm_textbox wufll"><?=$rd['memo']?></textarea></td>
		</tr>
		<tr>
			<th scope="row">사진첨부 #1</th>
			<td><input type="file" name="photo_file_1" accept="image/jpeg, image/jpg, image/png">
			 <?php if($rd['photo_file_1']) { ?><img src="<?=$rd['photo_file_1']?>" style="width:50px;height:50px;border:1px solid">&nbsp;&nbsp;&nbsp;<input type='checkbox' name='simg1_del' value='<?=$rd['photo_file_1']?>'>삭제<?php } ?>
			</td>
		</tr>
		<tr>
			<th scope="row">사진첨부 #2</th>
			<td><input type="file" name="photo_file_2" accept="image/jpeg, image/jpg, image/png">
			<?php if($rd['photo_file_2']) { ?><img src="<?=$rd['photo_file_2']?>" style="width:50px;height:50px;border:1px solid">&nbsp;&nbsp;&nbsp;<input type='checkbox' name='simg2_del' value='<?=$rd['photo_file_2']?>'>삭제<?php } ?>
			</td>
		</tr>
		<tr>
			<th scope="row">사진첨부 #3</th>
			<td><input type="file" name="photo_file_3" accept="image/jpeg, image/jpg, image/png">
			<?php if($rd['photo_file_3']) { ?><img src="<?=$rd['photo_file_3']?>" style="width:50px;height:50px;border:1px solid">&nbsp;&nbsp;&nbsp;<input type='checkbox' name='simg3_del' value='<?=$rd['photo_file_3']?>'>삭제<?php } ?>
			</td>
		</tr>
		</tbody>
		</table>
	</div>

    <div class="win_btn">
        <input type="submit" value="수정완료" class="btn_lsmall">
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

	if(f.memo.value.length < 10) {
		alert('내용을 10자 이상 입력하세요.');
		f.memo.focus();
		return false;
	}

	if(confirm("수정 하시겠습니까?") == false)
		return false;

    return true;
}
</script>
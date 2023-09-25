<?php
include_once("./_common.php");

$ms['title'] = '쇼핑포인트적립, 차감';
include_once(MS_ADMIN_PATH."/admin_head.php");

$mb	= get_member($mb_id);
?>

<form name="fpointform" method="post" action="./member_point_req_update.php">
<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
<input type="hidden" name="token" value="">

<h2 class="newp_tit"><?php echo $ms['title']; ?></h2>
<div class="newp_wrap">
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">아이디</th>
			<td><?php echo $mb['id']; ?></td>
		</tr>
		<tr>
			<th scope="row">회원명</th>
			<td><?php echo $mb['name']; ?></td>
		</tr>
		<tr>
			<th scope="row">쇼핑포인트잔액</th>
			<td><?php echo number_format($mb['point']); ?> P</td>
		</tr>
		<tr>
			<th scope="row"><label for="po_content">쇼핑포인트내용</label></th>
			<td><input type="text" name="po_content" id="po_content" required class="required frm_input wfull"></td>
		</tr>		
		<tr>
			<th scope="row"><label for="po_point">쇼핑포인트</label></th>
			<td><input type="text" name="po_point" id="po_point" required class="required frm_input" size="10"> P (음수 입력시 쇼핑포인트차감)</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="쇼핑포인트적용" class="btn_medium" accesskey="s">
		<button type="button" onclick="self.close();" class="btn_medium bx-white">닫기</button>
	</div>
</div>
</form>

<?php
include_once(MS_ADMIN_PATH.'/admin_tail.sub.php');
?>
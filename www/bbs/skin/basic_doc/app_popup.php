<?php
include_once("./_common.php");
include_once(MS_PATH."/head.sub.php");
include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php");

if(!$bo_table) {
	alert("정상적인 접근이 아닙니다.");
}

$board_skin_url = 'https://blingbeauty.shop/bbs/skin/basic_doc';

$cfg = array(
	"wr_id" => $wr_id,
    "bo_table" => 'approval',
    "app_state" => $APP_STATE,
	"board_skin_url" => $board_skin_url,
	"current" => $current,
	"z" => $z
);

add_javascript(G5_POSTCODE_JS, 0);
/* add_javascript('<script src="'.$board_skin_url.'/js/doc.js"></script>');
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0); */
?>
<script src="<? echo MS_BBS_URL; ?>/skin/basic_doc/js/doc.js"></script>
<link rel="stylesheet" href="<? echo MS_BBS_URL; ?>/skin/basic_doc/style.css">
<script>
var cfg = <?php echo json_encode($cfg); ?>;
console.log(cfg);
function frmSubmit() {
	var memo = $("#memo").val();
	memo = memo.replace(/ /gi, "");
	
	// 추가 의견을 필수 항목으로 설정시 아래 코드 주석 해제
	// if(!memo) {
	// 	alert("추가 의견은 필수 항목입니다.");
	// 	return false;
	// }

	cfg['memo'] = $("#memo").val();
	app_me(cfg);
}
</script>

<div id="mem_edit">
	<form name="fmData"  method="post" action="./mem_edit.php" onsubmit="return fmData_check(this);">
	<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>" />

	<table>
        <caption>* 추가 의견을 작성후 "확인" 버튼을 클릭해 주세요. (추가 의견은 필수 입력 항목입니다.)</caption>
		<colgroup>
            <col width="15%">
            <col width="85%">
		</colgroup>
		<tr>
			<th>결재 구분</th>
			<td colspan="2" class="td_left"><?php echo $APP_STATE[$_GET['current']]; ?></td>
		</tr>
		<tr>
			<th>추가 의견</th>
			<td colspan="2">
				<textarea name="memo" id="memo" class="memo required" rows="10" required><?php echo $rs['mb_content']; ?></textarea>
			</td>
		</tr>
	</table>
	<div class="bottom_msg">
	</div>

	<div class="button_zone">
		<button type="button" name="save" id="save" onclick="frmSubmit();">저장</button>
	</div>
	</form>
</div>
<?php include_once(G5_PATH."/tail.sub.php");
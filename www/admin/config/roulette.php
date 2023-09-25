<?php
if(!defined('_MALLSET_')) exit;

$sql = " select * from shop_roulette where no = '1' ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="수정" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
EOF;
?>

<form name="fislandlist" id="fislandlist" method="post" action="./config/rouletteupdate.php" onsubmit="return fislandlist_submit(this);">

<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">위치1 포인트</a></th>
		<th scope="col">위치2 포인트</a></th>
		<th scope="col">위치3 포인트</a></th>
		<th scope="col">위치4 포인트</a></th>
		<th scope="col">위치5 포인트</a></th>
		<th scope="col">위치6 포인트</a></th>
		<th scope="col">위치7 포인트</a></th>
		<th scope="col">위치8 포인트</a></th>
		<th scope="col">위치1 당첨확률</a></th>
		<th scope="col">위치2 당첨확률</a></th>
		<th scope="col">위치3 당첨확률</a></th>
		<th scope="col">위치4 당첨확률</a></th>
		<th scope="col">위치5 당첨확률</a></th>
		<th scope="col">위치6 당첨확률</a></th>
		<th scope="col">위치7 당첨확률</a></th>
		<th scope="col">위치8 당첨확률</a></th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td><input type="text" name="point1" value="<?php echo$row['point1']; ?>" class="frm_input"></td>
		<td><input type="text" name="point2" value="<?php echo$row['point2']; ?>" class="frm_input"></td>
		<td><input type="text" name="point3" value="<?php echo$row['point3']; ?>" class="frm_input"></td>
		<td><input type="text" name="point4" value="<?php echo$row['point4']; ?>" class="frm_input"></td>
		<td><input type="text" name="point5" value="<?php echo$row['point5']; ?>" class="frm_input"></td>
		<td><input type="text" name="point6" value="<?php echo$row['point6']; ?>" class="frm_input"></td>
		<td><input type="text" name="point7" value="<?php echo$row['point7']; ?>" class="frm_input"></td>
		<td><input type="text" name="point8" value="<?php echo$row['point8']; ?>" class="frm_input"></td>
		<td><input type="text" name="point_per1" value="<?php echo$row['point_per1']; ?>" class="frm_input"></td>
		<td><input type="text" name="point_per2" value="<?php echo$row['point_per2']; ?>" class="frm_input"></td>
		<td><input type="text" name="point_per3" value="<?php echo$row['point_per3']; ?>" class="frm_input"></td>
		<td><input type="text" name="point_per4" value="<?php echo$row['point_per4']; ?>" class="frm_input"></td>
		<td><input type="text" name="point_per5" value="<?php echo$row['point_per5']; ?>" class="frm_input"></td>
		<td><input type="text" name="point_per6" value="<?php echo$row['point_per6']; ?>" class="frm_input"></td>
		<td><input type="text" name="point_per7" value="<?php echo$row['point_per7']; ?>" class="frm_input"></td>
		<td><input type="text" name="point_per8" value="<?php echo$row['point_per8']; ?>" class="frm_input"></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>


<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ입력한 포인트 만큼 사용자에게 지급됩니다.</p>
			<p>ㆍ마이페이지 - 쇼핑통장 - 블링포인트 조회를 통해 지급받은 포인트를 보실 수 있습니다. </p>
		</div>
	 </div>
</div>

<script>
function fislandlist_submit(f)
{

    if(document.pressed == "수정") {
        if(!confirm("정말 수정하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>
<?php
if(!defined('_MALLSET_')) exit;

// 테이블의 전체 레코드수만 

$sql_common = "from shop_down_excel";
$sql_search = "where 1=1 group by muc_code";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search ";
$result = sql_query($sql);
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>엑셀 다운로드 리스트</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">NO</th>
		<td class="td_label">
			<label>제목</label>
		</td>
		<td class="td_label">
			<label>관리</label>
		</td>
	</tr>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) { ?>
	<tr>
		<th scope="row"><?php echo $row['no']; ?></th>
		<td class="td_label">
			<label><?php echo $row['title']; ?></label>
		</td>
		<td class="td_label">
			<label><span class="btn_small"><a href="goods.php?code=list_supply_form&muc_code=<?php echo $row['muc_code']; ?>&w=u" style="color:white" >수정</a></span>
			<span class="btn_small"><a href="goods.php?code=list_supply_form_update&muc_code=<?php echo $row['muc_code']; ?>&w=d" style="color:white" >삭제</a></span></label>
		</td>
	</tr>
<? } ?>
	</tbody>
	</table>
</div>


<div class="btn_confirm">
<span class="btn_small"><a href="goods.php?code=list" style="color:white" >상품리스트로 돌아가기</a></span>
<span class="btn_small"><a href="goods.php?code=list_supply_form" style="color:white" >신규등록하기</a></span>
</div>
</form>


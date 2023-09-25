<?php
if(!defined('_MALLSET_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods_review ";
$sql_search = " where (1) ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if(!$orderby) {
    $filed = "index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

// ���̺��� ��ü ���ڵ���� ����
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // ��ü ������ ���
if($page == "") { $page = 1; } // �������� ������ ù ������ (1 ������)
$from_record = ($page - 1) * $rows; // ���� ���� ����
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="���û���" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
EOF;
?>

<h2>�⺻�˻�</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">�˻���</th>
		<td>
			<select name="sfl">				
				<?php echo option_selected('mb_id', $sfl, '�ۼ���'); ?>
				<?php echo option_selected('seller_id', $sfl, '�Ǹ���'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="�˻�" class="btn_medium">
	<input type="button" value="�ʱ�ȭ" id="frmRest" class="btn_medium grey">
</div>
</form>

<form name="freviewlist" id="freviewlist" method="post" action="./goods/goods_review_delete.php" onsubmit="return freviewlist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	��ü : <b class="fc_red"><?php echo number_format($total_count); ?></b> �� ��ȸ
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w50">
		<col class="w50">
		<col class="w60">
		<col>
		<col class="w100">
		<col class="w100">
		<col class="w80">
		<col class="w100">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">��ȣ</th>
		<th scope="col">�̹���</th>
		<th scope="col">����</th>
		<th scope="col"><?php echo subject_sort_link('seller_id',$q2); ?>�Ǹ���</a></th>
		<th scope="col"><?php echo subject_sort_link('mb_id',$q2); ?>�ۼ���</a></th>
		<th scope="col"><?php echo subject_sort_link('reg_time',$q2); ?>�ۼ���</a></th>
		<th scope="col"><?php echo subject_sort_link('score',$q2); ?>����</a></th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs = get_goods($row['gs_id'], 'simg1, gname');		

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>			
			<input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><a href="<?php echo MS_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>" target="_blank"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 40, 40); ?></a></td>
		<td class="tal"><a href="<?php echo MS_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>" target="_blank"><b><?php echo get_text($gs['gname']); ?></b></a><p class="mart5 fc_137"><?php echo get_text($row['memo']); ?></p></td>
		<td><?php echo $row['seller_id']; ?></td>
		<td><?php echo $row['mb_id']; ?></td>
		<td><?php echo substr($row['reg_time'],0,10); ?></td>
		<td><img src="<?php echo MS_IMG_URL; ?>/sub/score_<?php echo $row['score']; ?>.gif"></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="8" class="empty_table">�ڷᰡ �����ϴ�.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
function freviewlist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" �Ͻ� �׸��� �ϳ� �̻� �����ϼ���.");
        return false;
    }

    if(document.pressed == "���û���") {
        if(!confirm("������ �ڷḦ ���� �����Ͻðڽ��ϱ�?")) {
            return false;
        }
    }

    return true;
}
</script>
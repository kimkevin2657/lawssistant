<?php
if(!defined('_MALLSET_')) exit;

if($sel_ca1) $sca = $sel_ca1;
if($sel_ca2) $sca = $sel_ca2;
if($sel_ca3) $sca = $sel_ca3;
if($sel_ca4) $sca = $sel_ca4;
if($sel_ca5) $sca = $sel_ca5;
if($sel_ca6) $sca = $sel_ca6;
if($sel_ca7) $sca = $sel_ca7;
if($sel_ca8) $sca = $sel_ca8;

if(isset($sel_ca1)) $qstr .= "&sel_ca1=$sel_ca1";
if(isset($sel_ca2)) $qstr .= "&sel_ca2=$sel_ca2";
if(isset($sel_ca3)) $qstr .= "&sel_ca3=$sel_ca3";
if(isset($sel_ca4)) $qstr .= "&sel_ca4=$sel_ca4";
if(isset($sel_ca5)) $qstr .= "&sel_ca5=$sel_ca5";
if(isset($sel_ca6)) $qstr .= "&sel_ca6=$sel_ca6";
if(isset($sel_ca7)) $qstr .= "&sel_ca7=$sel_ca7";
if(isset($sel_ca8)) $qstr .= "&sel_ca8=$sel_ca8";


//dd($_GET);
$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods a ";
$sql_search = " where a.use_aff='0' and a.shop_state='0' ";



if($sca) {
	$len = strlen($sca);
    $sql_common .= " left join shop_goods_cate c on (a.index_no=c.gs_id) ";
    $sql_search .= " and (left(c.gcate,$len) = '$sca') ";
}

// 검색어
if($stx) {
    switch($sfl) {
        case "gname" :
		case "explan" :
		case "maker" :
		case "origin" :
		case "model" :
            $sql_search .= " and a.$sfl like '%$stx%' ";
            break;
        default :
            $sql_search .= " and a.$sfl like '$stx%' ";
            break;
    }
}

$sql_order = " group by a.index_no order by a.index_no desc ";

if($sca) {
	$len = strlen($sca);
	if($len == '3'){
		$sql_order = " group by a.index_no order by c.rank1 desc ";
	}elseif($len == '6'){
		$sql_order = " group by a.index_no order by c.rank2 desc ";
	}elseif($len == '9'){
		$sql_order = " group by a.index_no order by c.rank3 desc ";
	}elseif($len == '12'){
		$sql_order = " group by a.index_no order by c.rank4 desc ";
	}elseif($len == '15'){
		$sql_order = " group by a.index_no order by c.rank5 desc ";
	}else{

	}
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(DISTINCT a.index_no) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($_SESSION['ss_page_rows'])
	$page_rows = $_SESSION['ss_page_rows'];
else
	$page_rows = 30;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

if($sca) {
	$sql = " select a.*,c.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
	$result = sql_query($sql);
}else{
	$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
	$result = sql_query($sql);
}
//echo $sql;
$target_table = 'shop_cate';
include_once(MS_LIB_PATH."/categoryinfo.lib.php");

$btn_frmline = <<<EOF
<button type="submit" class="btn_lsmall red"><i class="fa fa-refresh fa-spin"></i> 일괄적용</button>
EOF;
?>

<h2>기본검색</h2>
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
		<th scope="row">검색어</th>
		<td>
			<select name="sfl">
				<?php echo option_selected('gname', $sfl, '상품명'); ?>
				<?php echo option_selected('gcode', $sfl, '상품코드'); ?>
				<?php echo option_selected('mb_id', $sfl, '업체코드'); ?>
				<?php echo option_selected('maker', $sfl, '제조사'); ?>
				<?php echo option_selected('origin', $sfl, '원산지'); ?>
				<?php echo option_selected('model', $sfl, '모델명'); ?>
				<?php echo option_selected('explan', $sfl, '짧은설명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리</th>
		<td colspan="3">
			<script>multiple_select('sel_ca');</script>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

<form name="fgoodslist" method="post" action="./goods/goods_cate_update.php">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
	<span class="ov_a">
		<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$q1}&page=1"; ?>&page_rows='+this.value;">
			<?php echo option_selected('30',  $page_rows, '30줄 정렬'); ?>
			<?php echo option_selected('50',  $page_rows, '50줄 정렬'); ?>
			<?php echo option_selected('100', $page_rows, '100줄 정렬'); ?>
			<?php echo option_selected('150', $page_rows, '150줄 정렬'); ?>
			<?php echo option_selected('300', $page_rows, '300줄 정렬'); ?>
			<?php echo option_selected('500', $page_rows, '500줄 정렬'); ?>
		</select>
	</span>
</div>
<?php
if($sca) {?>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<?php } ?>

<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w60">
		<col class="w60">
		<col class="w80">
		<col>
		<col class="w50">
		<col class="w50">
		<col class="w50">
		<col class="w50">
		<col class="w50">
		<col class="w80">
		<col class="w80">
		<col class="w80">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">이미지</th>
		<th scope="col">상품코드</th>
		<th scope="col">상품명</th>
		<th scope="col" class="th_bg lh2">1차순번</th>
		<th scope="col" class="th_bg lh2">2차순번</th>
		<th scope="col" class="th_bg lh2">3차순번</th>
		<th scope="col" class="th_bg lh2">4차순번</th>
		<th scope="col" class="th_bg lh2">5차순번</th>
		<th scope="col">진열</th>
		<th scope="col">재고</th>
		<th scope="col">판매가</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs_id = $row['index_no'];
		$href = MS_SHOP_URL.'/view.php?index_no='.$gs_id;


		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
			<input type="hidden" name="gcate[<?php echo $i; ?>]" value="<?php echo $sca; ?>">
			<?php echo $num--; ?>
		</td>
		<td><a href="<?php echo $href; ?>" target="_blank"><?php echo get_it_image($gs_id, $row['simg1'], 40, 40); ?></a></td>
		<td><?php echo get_text($row['gcode']); ?></td>
		<td class="tal"><?php echo get_text($row['gname']); ?></td>
        <td><input type="text" name="rank1[<?php echo $i; ?>]" size="5" value="<?php echo $row['rank1'];?>"></td>
        <td><input type="text" name="rank2[<?php echo $i; ?>]" size="5" value="<?php echo $row['rank2'];?>"></td>
        <td><input type="text" name="rank3[<?php echo $i; ?>]" size="5" value="<?php echo $row['rank3'];?>"></td>
        <td><input type="text" name="rank4[<?php echo $i; ?>]" size="5" value="<?php echo $row['rank4'];?>"></td>
        <td><input type="text" name="rank5[<?php echo $i; ?>]" size="5" value="<?php echo $row['rank5'];?>"></td>
		<td><?php echo $gw_isopen[$row['isopen']]; ?></td>
		<td class="tar"><?php echo number_format($row['stock_qty']); ?></td>
		<td class="tar"><?php echo number_format($row['goods_price']); ?></td>
	</tr>
	<?php
	}
	if($i==0)
		echo '<tbody><tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<?php
if($sca) {?>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
<?php } ?>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
$(function(){
	<?php if($sel_ca1) { ?>
	$("select#sel_ca1").val('<?php echo $sel_ca1; ?>');
	categorychange('<?php echo $sel_ca1; ?>', 'sel_ca2');
	<?php } ?>
	<?php if($sel_ca2) { ?>
	$("select#sel_ca2").val('<?php echo $sel_ca2; ?>');
	categorychange('<?php echo $sel_ca2; ?>', 'sel_ca3');
	<?php } ?>
	<?php if($sel_ca3) { ?>
	$("select#sel_ca3").val('<?php echo $sel_ca3; ?>');
	categorychange('<?php echo $sel_ca3; ?>', 'sel_ca4');
	<?php } ?>
	<?php if($sel_ca4) { ?>
	$("select#sel_ca4").val('<?php echo $sel_ca4; ?>');
	categorychange('<?php echo $sel_ca4; ?>', 'sel_ca5');
	<?php } ?>
	<?php if($sel_ca5) { ?>
	$("select#sel_ca5").val('<?php echo $sel_ca5; ?>');
	<?php } ?>
});
</script>

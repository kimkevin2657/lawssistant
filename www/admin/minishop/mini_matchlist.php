<?php
if(!defined('_MALLSET_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_member a, (
select pt_id match_mb_id, match_id, min(mb_id) as min_mb_id, max(mb_id) as max_mb_id, max(reg_price) match_reg_price, date_format(max(match_at), '%Y-%m-%d') match_at, count(1) matching_cnt from shop_minishop_matching group by pt_id, match_id having count(1) > 1 ) b  ";
$sql_search = " where a.id = b.match_mb_id and a.grade between 2 and 6 ";

if($sfl && $stx) {
    if( $sfl == 'id') $sql_search .= " and $sfl like '%".$stx."%' ";
    else $sql_search .= " and $sfl like '%$stx%' ";
}

if(isset($sst) && is_numeric($sst))
	$sql_search .= " and a.grade = '$sst' ";

if($fr_date && $to_date)
    $sql_search .= " and b.match_at between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$sql_search .= " and b.match_at between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and b.match_at between '$to_date' and '$to_date' ";

if(!$orderby) {
    $filed = "index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";

$result = sql_query($sql);

include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<style>
    .btn-matching{margin:10px 5px; }
</style>

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
				<?php echo option_selected('id', $sfl, '아이디'); ?>
				<?php echo option_selected('name', $sfl, '회원명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">매칭일</th>
		<td>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">레벨검색</th>
		<td>				
			<?php echo get_search_level('sst', $sst, 2, 6); ?>
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

<form name="fpaylist" id="fpaylist" method="post" action="./minishop/mini_plistupdate.php" onsubmit="return fpaylist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
</div>

<div class="tbl_head02">
	<table id="matching_list">
	<colgroup>
		<col class="w50">
		<col class="w130">
		<col class="w130">
		<col class="w130">
        <col class="w80">
		<col class="w80">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col"><?php echo subject_sort_link('a.name',$q2); ?>회원명</a></th>
		<th scope="col"><?php echo subject_sort_link('a.id',$q2); ?>아이디</a></th>
		<th scope="col"><?php echo subject_sort_link('a.grade',$q2); ?>레벨</a></th>
        <th scope="col"><?php echo subject_sort_link('a.match_cnt',$q2); ?>직매칭수</a></th>
		<th scope="col"><?php echo subject_sort_link('b.match_at',$q2); ?>매칭일</a></th>
		<th scope="col">매치ID</th>
	</tr>
	</thead>
    <tbody class="<?php if(sql_num_rows($result) > 0 ) echo 'list';?>">
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {		

		$info  = get_pay_sheet($row['id']); // 누적
		$sale  = get_pay_status($row['id'], 'sale'); // 판매
		$anew  = get_pay_status($row['id'], 'anew'); // 후원
        $anew_match  = get_pay_status($row['id'], 'anew_match'); // 매칭
		$visit = get_pay_status($row['id'], 'visit'); // 접속
		$admin = get_pay_status($row['id'], 'passive'); // 본사

		$bg = 'list'.($i%2);

		$button = "<button type=\"button\" data-id=\"{$row['min_mb_id']}\" data-pt-id=\"{$row['id']}\" data-reg-price=\"{$row['match_reg_price']}\" data-anew-grade=\"{$row['grade']}\" class=\"btn-matching bx-red btn_small\"><i class=\"fa fa-users\"></i> 매칭 실행 
</button>({$row['min_mb_id']},{$row['max_mb_id']})";
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['id']; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['id']; ?> 님</label>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
		</td>
		<td class="tal"><?php echo get_sideview($row['id'], $row['name']); ?></td>
		<td class="tal"><?php echo $row['id']; ?></td>
		<td><?php echo get_grade($row['grade']); ?></td>
        <td><?php echo display_point($row['match_cnt'], ''); ?></td>
		<td class="<?php echo $expr; ?>"><?php echo $row['match_at']; ?></td>
		<td class="tal"><?php
            if( ! empty( $row['match_id'] ) ) echo Match::displayMatchId($row['match_id']);
            else echo $button;
        ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
            <p class="fc_red">ㆍ일시적인 장애 등으로 매칭이 정상 처리 되지 않은 경우 매칭 실행 하시길 바랍니다.</p>
		</div>
	</div>
</div>

<script>
    (function($){
        $(document).ready(function(){
            $('.btn-matching').on('click', function(){

                var pt_id = $(this).data('ptId');
                var mb_id = $(this).data('id');
                var reg_price = $(this).data('regPrice');
                var anew_grade= $(this).data('anewGrade');

                data = {pt_id : pt_id, mb_id : mb_id, reg_price : reg_price, anew_grade : anew_grade};

                $.ajax({
                    url : "<?php echo Match::matchAjaxUrl(); ?>",
                    data: data,
                    type: "POST",
                    dataType: "json",
                    success : function(data){
                        if( data.result == 'success' ) {
                            document.location.reload();
                        } else {
                            alert(data.msg);
                        }
                    }
                })
            });
        });
    }(jQuery));
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});
</script>

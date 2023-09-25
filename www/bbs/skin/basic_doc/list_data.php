<?php
include_once("./_common.php");
include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php");

// 선택삭제
if($_POST['oper']=="del") {

	$id = $_POST['id'];

	// 문서삭제 ( 자신이 등록한 자료중 "미결재" 상태인 자료만 삭제 )
	$sql="delete from {$write_table} where wr_id in ({$id}) and mb_id = '{$member['id']}' and  wr_3 like '0|%' ";
	sql_query($sql,true);
	$cnt = sql_affected_rows();

	if($cnt>0) {
		// 문서세부내역 삭제
		sql_query("delete from {$write_table}_sub where wr_id in ({$id})");

		// 문서결재진행상태 삭제
		sql_query("delete from {$write_table}_log where wr_id in ({$id})");
	}

	echo json_encode($cnt);
	exit;
}


$member = $_SESSION['app_mb_info'];
$sql_common = " from `{$write_table}` a left join `{$write_table}_member` b on (a.mb_id = b.mb_id)";

// 원글만 검색 (게시판 특성상 댓글은 검색에서 제외)
$sql_search = " where a.wr_is_comment = 0";

// 기타검색
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "a.wr_subject" :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
        case "a.wr_name" :
			$sql_search .= " (b.mb_name like '%{$stx}%') ";
			//$sql_search .= " ((select count(*) from {$write_table}_member where mb_name like '%{$stx}%' limit 1) > 0)";
            break;
		default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

//분류검색
if($ca_name) {
	$sql_search .= " and a.ca_name = '{$ca_name}' ";
}

// 등록일 구간 검색
if($fd && $td) {
	$sql_search .= " and left(a.wr_datetime, 10) between '{$fd}' and '{$td}' ";
}

$sql_search .= " and ( wr_2 = '{$member['id']}' or wr_4 = '{$member['id']}' or wr_6 = '{$member['id']}' or wr_8 = '{$member['id']}' ) ";

// 여분필드1 검색
if($w1) {
	$w1 = str_replace(",","','", $w1);
	$sql_search .= " and a.wr_1 in ('{$w1}') ";
}

// 여분필드2 검색
if($w2) {
	$sql_search .= " and a.wr_2 = '{$w2}' ";
}

// 여분필드3 검색
if($w3) {
	$sql_search .= " and a.wr_3 = '{$w3}' ";
}

// 여분필드4 검색
if($w4) {
	$sql_search .= " and a.wr_4 = '{$w4}' ";
}

// 여분필드5 검색
if($w5) {
	$sql_search .= " and a.wr_5 = '{$w5}' ";
}

// 여분필드6 검색
if($w6) {
	$sql_search .= " and a.wr_6 = '{$w6}' ";
}

// 여분필드7 검색
if($w7) {
	$sql_search .= " and a.wr_7 = '{$w7}' ";
}

// 여분필드8 검색
if($w8) {
	$sql_search .= " and a.wr_8 = '{$w8}' ";
}

// 여분필드9 검색
if($w9) {
	$sql_search .= " and a.wr_9 = '{$w9}' ";
}


$sidx = ($sidx) ? $sidx : "a.wr_num, a.wr_reply";
$sord = ($sord)? $sord : "asc";
$sql_order = " order by {$sidx} {$sord} ";

$sql = "select
			count(*) as cnt,
			sum(a.wr_10) as hap
       $sql_common
       $sql_search
       $sql_order ";
$row = sql_fetch($sql, true);
$total_count = $row['cnt'];

//한페이지에 보여질 리스트 숫자..
$rows = ($rows) ? $rows : 50;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = " select
			a.*,
			b.mb_name
		$sql_common
		$sql_search
		$sql_order
		limit $from_record, $rows ";

//echo $sql; 

$result = sql_query($sql, true);
// DB 처리 끝

// unset으로 고정값 제거
$RST = $_POST;
unset($RST['_search']);
unset($RST['bo_table']);
unset($RST['nd']);
unset($RST['rows']);
unset($RST['page']);
unset($RST['sidx']);
unset($RST['sord']);
unset($RST['sfl']);
$RST= array_filter($RST); // 배열의 빈값을 모두 제거

$response = new stdClass();
$response->hap = number_format($row['hap']);
//$response->category_list = $list;

// 검색을 실행한 경우 resetn 값을 돌려준다. (엑셀저장버튼을 보이게 하거나 숨긴다.)
if(count($RST)>0) {
	$response->resetn = 'Y';
}

$response->page = ($page) ? $page : 1; // 현재페이지
$response->total = $total_page; // 총페이지
$response->records = $total_count; // 총 자료수
$response->para = array_filter($_POST);

$i=0;
if($page!=1){
	$num = $total_count - ($rows * ($page - 1));
} else {
	$num = $total_count;
}

while($rs=sql_fetch_array($result)) {
	
	

	// 열람가능 여부
	if(check_id($rs) == false) {
		$reper = "열람불가";
		$cls = "txt_gray"; 
	} else {
		$reper = "열람가능";
		$cls = "txt_blue"; 
	}
	///$sss = check_id($rs);
	//print_r2($sss);
	
	$comment_cnt = ($rs['wr_comment']>0)? "&nbsp;(<span style='color:red;'>".$rs['wr_comment']."</span>)":"";

	$response->rows[$i]['num'] = $num;
	$response->rows[$i]['wr_id'] = $rs['wr_id'];
	$response->rows[$i]['ca_name'] = $rs['ca_name'];
	$response->rows[$i]['wr_subject'] = "<span class='{$cls}'>{$rs['wr_subject']} {$comment_cnt}";
	$response->rows[$i]['wr_name'] = mb_name($rs['mb_id']);
	$response->rows[$i]['wr_datetime'] = $rs['wr_datetime'];
	$response->rows[$i]['wr_3'] = approval($rs['wr_2'], $rs['wr_3']);
	$response->rows[$i]['wr_5'] = approval($rs['wr_4'], $rs['wr_5']);
	$response->rows[$i]['wr_7'] = approval($rs['wr_6'], $rs['wr_7']);
	$response->rows[$i]['wr_9'] = approval($rs['wr_8'], $rs['wr_9']);
	$response->rows[$i]['wr_10'] = $rs['wr_10'];
	$response->rows[$i]['view'] = "<span class='{$cls}'>{$reper}</span>";
	//$response->rows[$i]['edit'] = ($member['id']==$rs['mb_id']) ? "true" : "false";
	$response->rows[$i]['href'] = get_pretty_url($bo_table, $rs['wr_id'], $qstr);
	//$response->rows[$i]['href'] = MS_BBS_URL."/bbs/board.php?bo_table=".$bo_table."&".$qstr;
	$response->rows[$i]['edit'] = ($member['id']==$rs['mb_id']) ? "true" : "false";

	$num--;
	$i++;
}

echo json_encode($response);
?>
<?php
include_once("./_common.php");
include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php"); // 여분필드 배열 담긴 파일

// 최고관리자만 삭제 가능
if($_POST['oper']=="del" && $is_admin) {

	$id = $_POST['id'];

	// 직원삭제
	$sql="delete from {$write_table}_member where id_no in ({$id}) ";
	sql_query($sql,true);
	$cnt = sql_affected_rows();
	
	echo json_encode($cnt);
	exit;
}

include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php");
$Search_box = new Frm_search(); // 검색폼 관련 Class

$table = "{$write_table}_member";

#### 자료 삭제
if($oper=="del"){
	$sql="delete from {$write_table}_member where id_no = '{$id_no}' ";
	sql_query($sql);
}

$sql_common = " from {$write_table}_member";
$sql_search = " where (1) ";

//print_r($mk2);

// 부서명
if($mk2 != "") {
	$sql_search .= " and mb_kind = '{$mk2}' ";
}

// 담당구분
if(isset($ms) && $ms!= '') {
	$sql_search .= " and mb_section = '{$ms}' ";
}

if ($stx) {
	$stxx = explode(" ", $stx);
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "mb_name" :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
		case "mb_id" :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
		case "mb_hp" :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
		case "mb_tel" :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
		default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

$sql_order = " order by id_no desc ";


$sql = "select
			count(*) as cnt
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

$sql = "select
			*
        $sql_common
        $sql_search
        $sql_order
		limit
			$from_record, $rows ";
$result = sql_query($sql,true);

//echo $sql; 

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
    
	$response->rows[$i]['num'] = $num;
	$response->rows[$i]['id_no'] = $rs['id_no'];
	$response->rows[$i]['mb_section'] = $MB_SECTION[$rs['mb_section']];
	$response->rows[$i]['mb_kind'] = $rs['mb_kind'];
	$response->rows[$i]['mb_id'] = $rs['mb_id'];
	$response->rows[$i]['mb_name'] = $rs['mb_name'].' '.$rs['mb_position'];
	$response->rows[$i]['mb_hp'] = $rs['mb_hp'];
	$response->rows[$i]['mb_tel'] = $rs['mb_tel'];
	$response->rows[$i]['mb_fax'] = $rs['mb_fax'];

	$num--;
	$i++;
}

echo json_encode($response);
?>
<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php");
$Search_box = new Frm_search(); // 검색폼 관련 Class

// 전자결재시 필수 변수값 체크
// 항상 list.skin.php 파일을 통해서 접속해야 함.
if(!$_SESSION['app_mb_info']) {
    goto_url("?bo_table={$bo_table}");
} else {
    $member = $_SESSION['app_mb_info'];
}

// 직원으로 등록되어 있는지 체크
checkStaff($member['id']);

$table = "{$write_table}_member";

#### 자료 삭제
if($mode=="drop"){
	for($i=0; $i < count($check); $i++) {
		$sql="delete from {$write_table}_member where id_no = '{$check[$i]}' ";
		sql_query($sql);
	}
	goto_url("?bo_table={$bo_table}&pg=member");
}

$sql_common = " from {$write_table}_member";
$sql_search = " where (1) ";

// 부서명
if($mk2) {
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
$rows = 100;
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

//검색 변수값
$qstr = "sfl={$sfl}&stx={$stx}";
if($sfn) { $qstr .= "&sfn={$sfn}"; }

// 페이지 처리
$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr2.'&amp;page=');

//검색조건
$sfl_array = array(
	'mb_name' => '이름',
	'mb_id' => '회원아이디', 
	'mb_hp' => '휴대전화',
	'mb_tel' => '일반전화'
);

$mb_kind  = get_teamlist(); // 직원 부서
unset($mb_kind["전체공개"]);

add_stylesheet("<link rel='stylesheet' href='".TB_PLUGIN_URL."/rumipopup/rumiPopup.css'>");
add_javascript("<script src='".TB_PLUGIN_URL."/rumipopup/jquery.rumiPopup.js'></script>");
add_javascript("<script src='".TB_BBS_URL."/skin/basic_doc/js/doc.js'></script>");

// 선택자
$grid_list	= "list";		// 그리드가 그려질 식별자 ID
$grid_pager	= "pager";		// 그리드 페이지가 표시될 식별자 ID
$grid_sch	= "searchbox";	// 그리드 검색박스 식별자 ID
$grid_form	= "fmSearch";	// 그리드 상단 검색 FORM NAMEE
$grid_js_url= TB_BBS_URL."/skin/basic_doc/member/mem.js?ver=".time("Now"); // JS 파일명 및 경로
$edit_url	= TB_BBS_URL."/skin/basic_doc/member/mem_data.php?bo_table=".$bo_table; // DATA 파일명 및 경로
?>
<script>

// ****.js 파일에서 사용될 전역 변수
var Grid = {
	search:"<?php echo '#'.$grid_sch; ?>",
	list : "<?php echo '#'.$grid_list; ?>",
	pager: "<?php echo '#'.$grid_pager; ?>",
	form : "<?php echo '#'.$grid_form; ?>",
	editUrl : "<?php echo $edit_url; ?>",
	bo_table : "<?php echo $bo_table; ?>",
	board_skin_url : "<? echo TB_BBS_URL; ?>/skin/basic_doc"
}

// doc.js 파일 함수에서 사용될 전역 변수
var cfg = Grid; 
</script>

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo TB_PLUGIN_URL; ?>/jqGrid/css/ui.jqgrid.css" />
<script type="text/ecmascript" src="<?php echo TB_PLUGIN_URL; ?>/jqGrid/js/i18n/grid.locale-kr.js"></script>
<script type="text/javascript">
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;
</script>
<script type="text/ecmascript" src="<?php echo TB_PLUGIN_URL; ?>/jqGrid/js/jquery.jqGrid.js"></script>
<script type="text/ecmascript" src="<?php echo $grid_js_url;?>"></script>

<style>
.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
	background: #FFFFDD !important;
	border: 1px solid #FFFFDD !important;
	color: #000 !important;
}
</style>
	
<div id="grid_basic">
	<div id="<?php echo $grid_sch;?>" class="grid_fm_search">
		<form name="<?php echo $grid_form;?>" id="<?php echo $grid_form;?>">
		<input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>" />
		<input type="hidden" name="pg" value="member" />
		<table>
			<colgroup>
				<col width="9%" />
				<col width="16%" />
				<col width="9%" />
				<col width="16%" />
				<col width="9%" />
				<col width="16%" />
				<col width="9%" />
				<col width="16%" />
			</colgroup>
			<tr>
				<th>구분</th>
				<td><?php
					$Search_box->var_mode('A', $MB_SECTION);
					echo $Search_box->Select('= 담당종류 =', 'ms', 'ms', 'ms', $ms);
					?>
				</td>
				<th>부서</th>
				<td><?php
					$Search_box->var_mode('A', $mb_kind);
					echo $Search_box->Select('= 부서명 =', 'mk', 'mk', 'mk', $mk2);
					?>
				</td>
				<th></th>
				<td></td>
				<th></th>
				<td></td>
			</tr>
			<tr>
				<th>기타검색</th>
				<td colspan="7">
					<?php
					$Search_box->var_mode('A', $sfl_array);
					echo $Search_box->Select('', 'sfl', 'sfl', 'sfl', $sfl);
					?>
					<input type="text" name="stx" id="stx" class="stx" size="20" maxlength="40" value="<?php echo $stx; ?>" />
					<button type="button" id="btn_submit" class="btn_submit" title="검색"><i class='fa fa-search' aria-hidden='true'></i></button>
					<button type="button" id="reset" class="reset" title='초기화'><i class="fa fa-refresh" aria-hidden="true"></i></button>

					<button type="button" name="new_member" class="new" id="new_member" onclick="memberadd('')";>신규등록</button>
					<button type="button" name="doc_list" class="new" id="doc_list">문서목록</button>
				</td>
			</tr>
		</table>
	</form>
	</div>
</div>

<table id="<?php echo $grid_list;?>"></table>
<div id="<?php echo $grid_pager;?>"></div>

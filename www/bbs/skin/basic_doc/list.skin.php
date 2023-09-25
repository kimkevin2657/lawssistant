<?php
if (!defined('_MALLSET_')) exit; // 개별 페이지 접근 불가
//echo "skin:"."/home/pulo/www/bbs/skin/basic_doc";
include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php");
$Search_box = new Frm_search(); // 검색폼 관련 Class

add_stylesheet('<link rel="stylesheet" href="'.MS_BBS_URL.'/skin/basic_doc/style.css">', 0);
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php'); // 달력(한글로 출력)
add_javascript('<script src="'.MS_BBS_URL.'/skin/basic_doc/js/doc.js"></script>');
add_stylesheet('<link rel="stylesheet" href="'.MS_BBS_URL.'/skin/basic_doc/style.css">', 0);

//세션 생성 (정상적인 경로를 통하여 삭제하도록..)
set_session("ss_estimate_key", G5_TIME_YMD);

$write_href = MS_BBSORG_URL.'/write.php?bo_table='.$bo_table;

if($_GET['pg']) {
    
    switch($_GET['pg']) {
        case "member" : // 직원관리
            include_once("/home/pulo/www/bbs/skin/basic_doc/member/mem_list.php");
            break;
        case "install" : // 초기설치
            include_once("/home/pulo/www/bbs/skin/basic_doc/install/install.php");
            break;
        default :
			alert("정상적인 방법으로 이용해 주세요.", "?bo_table={$bo_table}&pg=list");
			break;
    }
    
} else {

	db_check(); // DB 체크 

	
	if(!$_SESSION['app_mb_info']) {
		// 회원정보와 직원등록정보를 합쳐서 $member 변수로 사용하기
		$ss_member = mb_myinfo($member['mb_id']);
		$member = array_merge($member, $ss_member);
		$_SESSION['app_mb_info'] = $member;
	} else {
		$member = $_SESSION['app_mb_info'];
	}

	
	
	// 검색 : 기타 검색 옵션 설정
	$sfl_array = array(
		"a.wr_subject"=> "문서제목",
		"a.wr_name"=> "작성자이름"
	);

	$cateBtns = get_categoryList($member['id']);

	// 선택자
	$grid_list	= "list";		// 그리드가 그려질 식별자 ID
	$grid_pager	= "pager";		// 그리드 페이지가 표시될 식별자 ID
	$grid_sch	= "searchbox";	// 그리드 검색박스 식별자 ID
	$grid_form	= "fmSearch";	// 그리드 상단 검색 FORM NAMEE
	$grid_js_url= MS_BBS_URL."/skin/basic_doc/list.js?ver=".time("now"); // JS 파일명 및 경로
	$edit_url	= MS_BBS_URL."/skin/basic_doc/list_data.php?bo_table=".$bo_table; // DATA 파일명 및 경로
	?>

	<script>
	// GRID JS에서 사용할 전역변수 설정.
	var Grid = {
		search:"<?php echo '#'.$grid_sch; ?>",
		list : "<?php echo '#'.$grid_list; ?>",
		pager: "<?php echo '#'.$grid_pager; ?>",
		form : "<?php echo '#'.$grid_form; ?>",
		editUrl : "<?php echo $edit_url; ?>",
		bo_table : "<?php echo $bo_table; ?>",
		board_skin_url : "<?php echo $board_skin_url; ?>"
	}

	// jQuery.browser = {};
	// (function () {
	// 	jQuery.browser.msie = false;
	// 	jQuery.browser.version = 0;
	// 	if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
	// 		jQuery.browser.msie = true;
	// 		jQuery.browser.version = RegExp.$1;
	// 	}
	// })();
	</script>

	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo G5_PLUGIN_URL; ?>/jqGrid/css/ui.jqgrid.css" />
	<script type="text/ecmascript" src="<?php echo G5_PLUGIN_URL; ?>/jqGrid/js/i18n/grid.locale-kr.js"></script>
	<script type="text/javascript">
		$.jgrid.no_legacy_api = true;
		$.jgrid.useJSON = true;
	</script>
	<script type="text/ecmascript" src="<?php echo G5_PLUGIN_URL; ?>/jqGrid/js/jquery.jqGrid.js"></script>
	<script type="text/ecmascript" src="<?php echo $grid_js_url;?>"></script>

	<style>
	/* .btns { margin-bottom:8px; }
	.btns ul { }
	.btns li { display:inline-block; text-align:center; width:119px; border:0px; background:#878787; color:#fff; padding:6px 5px; margin:0px 1px 1px 0px; border-radius:0px; cursor:pointer; }
	.btns li:hover { background:#dd6666; }
	.ui-jqgrid.ui-widget.ui-widget-content.ui-corner-all {width:100% !important;}
	.ui-jqgrid .ui-jqgrid-view { z-index: 14; } */
	.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
		background: #FFFFDD !important;
    	border: 1px solid #FFFFDD !important;
    	color: #000 !important;
	}

	.hm_buttons{display:flex; flex-direction:row;padding:10px;}
	.hm_buttons > a > div{background:#62677a; color:#fff; padding:7px 5px; border-radius:3px; margin-right:5px;}
	.hm_buttons > a{text-decoration:none;}
	.hm_buttons > a > div:hover{background:#dd6666;}
	</style>
<!-- 게시판 카테고리 시작 { -->
	<?php if ($is_category) { ?>
	<!--
    <nav id="bo_cate">
        <h2><?php // echo $board['bo_subject'] ?> 카테고리</h2>
        <ul id="bo_cate_ul">
            <?php // echo $category_option ?>
        </ul>
	</nav>
	-->
    <?php } ?>

		<div class="hm_buttons">
			<a href="https://blingbeauty.shop/bbs/list.php?boardid=71" target="_blank"><div class="b_notice" >공지사항</div></a>
			<a href="https://blingbeauty.shop/bbs/list.php?boardid=70" target="_blank"><div class="b_eform" >전자결재 양식</div></a>
		</div>



	<div id="grid_basic">
		<div id="<?php echo $grid_sch;?>" class="grid_fm_search">
			<form name="<?php echo $grid_form;?>" id="<?php echo $grid_form;?>">
			<input type="hidden" name="ca_name" id="ca_name" value="<?php echo $ca_name; ?>" />
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
					<th>작성일자</th>
					<td colspan="5"><?php echo $Search_box->Month('.fd', '.td', $fd, $td); ?></td>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th>기타검색</th>
					<td colspan="7">
						<?php
						$Search_box->var_mode('A',$sfl_array);
						echo $Search_box->Select('', 'sfl', 'sfl', 'sfl', $sfl);
						?>
						<input type="text" name="stx" id="stx" class="stx" size="20" maxlength="40" value="<?php echo $stx; ?>" />
						<button type="button" id="btn_submit" class="btn_submit" title="검색"><i class='fa fa-search' aria-hidden='true'></i></button>
						<button type="button" id="reset" class="reset" title='초기화'><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<?php if($is_admin) { ?>
						<button type="button" class="new" onclick="location.href='<?php echo $admin_href ?>';" title='관리자 게시판 설정'><i class='fa fa-gear' aria-hidden='true'></i></button>
						<?php  } ?>


						<!--
						<button type="button" id="excel" class="excel" onclick="prInt('excel');" ><i class='fa fa-file-excel-o' aria-hidden='true'></i> 엑셀저장</button>
						<button type="button" id="print" class="print" onclick="prInt('pdf');" ><i class='fa fa-print' aria-hidden='true'></i> 인쇄</button>
						-->
						
						
						<button type="button" id="new" class="new" onclick="location.href='<?php echo $write_href ?>';" title="문석작성하기"><i class='fa fa-pencil' aria-hidden='true'></i></button>
						<button type="button" id="staff" class="new" onclick="location.href='?bo_table=<?php echo $bo_table; ?>&pg=member';" title="직원관리"><i class='fa fa-user-plus' aria-hidden='true'></i></button>
					</td>
				</tr>
			</table>
		</form>
		</div>
	</div>

	<div id="category_info">
		<div class="cate_left"><?php echo $cateBtns;?></div>
		<div class="cate_right"></div>
	</div>
	
	<table id="<?php echo $grid_list;?>"></table>
	<div id="<?php echo $grid_pager;?>"></div>

<?php } ?>

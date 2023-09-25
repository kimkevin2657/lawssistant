<?php
if(!defined('_MALLSET_')) exit;

if($sel_ca1) $sca = $sel_ca1;
if($sel_ca2) $sca = $sel_ca2;
if($sel_ca3) $sca = $sel_ca3;
if($sel_ca4) $sca = $sel_ca4;
if($sel_ca5) $sca = $sel_ca5;

if(isset($sel_ca1))			$qstr .= "&sel_ca1=$sel_ca1";
if(isset($sel_ca2))			$qstr .= "&sel_ca2=$sel_ca2";
if(isset($sel_ca3))			$qstr .= "&sel_ca3=$sel_ca3";
if(isset($sel_ca4))			$qstr .= "&sel_ca4=$sel_ca4";
if(isset($sel_ca5))			$qstr .= "&sel_ca5=$sel_ca5";
if(isset($q_date_field))	$qstr .= "&q_date_field=$q_date_field";
if(isset($q_isopen))		$qstr .= "&q_isopen=$q_isopen";

$sql_common = " from shop_goods a";
$sql_search = " where a.use_aff = 0 and a.shop_state = 0";

include_once(MS_ADMIN_PATH.'/goods/goods_query.inc.php');

if(!$orderby) {
    $filed = "a.index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " group by a.index_no order by $filed $sod ";


$target_table = 'shop_cate';
include_once(MS_LIB_PATH."/categoryinfo.lib.php");
include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');

/*
$sql2 = " select a.* $sql_common $sql_search $sql_order ";
$result2 = sql_query($sql2);
for($i=0; $row2=sql_fetch_array($result2); $i++) {
	if($row2['gpoint']>0&&!$row2['marper']){
		$marper = round($row2['gpoint']/$row2['goods_price']*100);

		sql_query("update shop_goods set marper='$marper' where index_no=".$row2['index_no']);
	}
}
*/
$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택판매가수정" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
EOF;

?>

<h2>검색설정</h2>
<script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.js"></script>
 <script>

    	/*
    	[JS 요약 설명]
    	1. window.onload : 브라우저 로드 완료 상태를 나타냅니다 
    	2. spin js : 브라우저 내에서 로딩 스핀 상태를 나타낼 수 있습니다 
    	3. 로직 : 사용자 통신 요청 시 >> spinnerStart 호출 >> 리턴 응답 받을 시 >> spinnerStop 호출    	
    	4. 옵션 참고 공식 사이트 : https://spin.js.org/
    	*/

   	
    	
    	/* [html 최초 로드 및 이벤트 상시 대기 실시] */
    	window.onload = function() {
    		console.log("");
    		console.log("[window onload] : [start]");
    		console.log("");

    		// 로딩 스피너 호출
    		spinnerStart();

    		/* setTimeout 호출 : 일정 시간 후 실행 : [함수호출] 일회용 */
    		setTimeout(spinnerStop, 3000); //5초후에 spinnerStop() 함수 호출
    	};



    	/* [spinnerStart 시작 이벤트 호출] */
    	function spinnerStart(){
    		console.log(""); 
    		console.log("[spinnerStart] : " + "[start]");           
    		console.log("");

    		// [로딩 부모 컨테이너 동적 생성]
    		var createLayDiv = document.createElement("div");
    		createLayDiv.setAttribute("id", "spinnerLay1000");
    		var createLayDivStyle = "width:100%; height:100%; margin:0 auto; padding:0; border:none;";
    		createLayDivStyle = createLayDivStyle + " float:top; position:fixed; top:0%; z-index:1000;";
    		createLayDivStyle = createLayDivStyle + " background-color:rgba(0, 0, 0, 0.3);"; // 불투명도 설정 >> 자식에게 적용 안됨
    		createLayDiv.setAttribute("style", createLayDivStyle);
    		document.body.appendChild(createLayDiv); //body에 추가 실시


    		// [실제 스핀 수행 컨테이너 동적 생성]
    		var createSpinDiv = document.createElement("div");
    		createSpinDiv.setAttribute("id", "spinnerContainer1000");
    		var createSpinDivStyle = "width:100px; height:100px; margin:0 auto; padding:0; border:none;"; // 스핀 컨테이너 크기 조절
    		createSpinDivStyle = createSpinDivStyle + " float:top; position:relative; top:40%;";
    		//createSpinDivStyle = createSpinDivStyle + " background-color:#ff0000;";  
    		createSpinDiv.setAttribute("style", createSpinDivStyle);
    		document.getElementById("spinnerLay1000").appendChild(createSpinDiv); //spinnerLay에 추가 실시


    		// [스핀 옵션 지정 실시]
    		var opts = {
    			lines: 10, // 그릴 선의 수 [20=원형 / 10=막대] [The number of lines to draw]
    			length: 10, // 각 줄의 길이 [0=원형 / 10=막대] [The length of each line]
    			width: 15, // 선 두께 [The line thickness]
    			radius: 42, // 내부 원의 반지름 [The radius of the inner circle]
    			scale: 0.85, // 스피너의 전체 크기 지정 [Scales overall size of the spinner]
    			corners: 1, // 모서리 라운드 [Corner roundness (0..1)]
    			color: '#003399', // 로딩 CSS 색상 [CSS color or array of colors]
    			fadeColor: 'transparent', // 로딩 CSS 색상 [CSS color or array of colors]
    			opacity: 0.05, // 선 불투명도 [Opacity of the lines]
    			rotate: 0, // 회전 오프셋 각도 [The rotation offset]
    			direction: 1, // 회전 방향 시계 방향, 반시계 방향 [1: clockwise, -1: counterclockwise]
    			speed: 1, // 회전 속도 [Rounds per second]
    			trail: 74, // 꼬리 잔광 비율 [Afterglow percentage]
    			fps: 20, // 초당 프레임 수 [Frames per second when using setTimeout() as a fallback in IE 9]
    			zIndex: 2e9 // 인덱스 설정 [The z-index (defaults to 2000000000)]
    		};


    		// [스피너 매핑 및 실행 시작]
    		var target = document.getElementById("spinnerContainer1000");
    		var spinner = new Spinner(opts).spin(target);
    	};




    	/* [spinnerStop 중지 이벤트 호출] */
    	function spinnerStop(){
    		console.log("");
    		console.log("[spinnerStop] : " + "[start]");
    		console.log("");
    		try {
    			// [로딩 부모 컨테이너 삭제 실시]
    			var tagId = document.getElementById("spinnerLay1000");
    			document.body.removeChild(tagId); //body에서 삭제 실시 
    		}
    		catch (exception) {
    			// console.error("catch : " + "not find spinnerLay1000");
    		}

    	};
    	
    </script>

<div class="price_engine">
	<p class="lh6">
		해당페이지에서는 크로링이 되지 않습니다.<br>
		상품이 많을 경우 매우 많은 시간이 소요 될 수 있으므로 검색설정을 꼼꼼히 하여주시기 바랍니다.<br>
		최저가 보다 판매가가 비싼 상품만 검색됩니다.<br>
	</p>
</div>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<input type="hidden" name="excelType_tt">
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w100">
		<col>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">카테고리</th>
		<td colspan="3">
			<script>multiple_select('sel_ca');</script>
		</td>
	</tr>
	<tr>
		<th scope="row">기간검색</th>
		<td colspan="3">
			<select name="q_date_field" id="q_date_field">
				<?php echo option_selected('update_time', $q_date_field, "최근수정일"); ?>
				<?php echo option_selected('reg_time', $q_date_field, "최초등록일"); ?>
			</select>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">판매여부</th>
		<td>
			<?php echo radio_checked('q_isopen', $q_isopen,  '', '전체'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '1', '진열'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '2', '품절'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '3', '단종'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '4', '중지'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<?php if($count){ ?>
<div>
총 <?php echo $total_count; ?>개의 상품을 크롤링 했습니다.
</div>
<?php } ?>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>
<?php
// 테이블의 전체 레코드수만 얻음
$sql_search .= " AND a.goods_price > (SELECT price FROM naver_list WHERE gcode = a.gcode ORDER BY price ASC LIMIT 1)";
$sql = " select count(DISTINCT a.index_no) as cnt $sql_common $sql_search ";
//echo $sql;
$row = sql_fetch($sql);
$total_count = $row['cnt'];
?>

<form name="fgoodslist" id="fgoodslist" method="post" action="./goods/goods_list_update.php" onsubmit="return fgoodslist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head02">
	<table id="sodr_list" class="tablef">
	<colgroup>
		<col class="w50">
		<col class="w50">
		<col class="w60">
        <?php if( defined("USE_BUY_PARTNER_GRADE") && USE_BUY_PARTNER_GRADE ) : ?>
        <col class="w80">
        <?php endif; ?>
		<col class="w120">
		<col>
		<col>
		<col class="w80">
		<col class="w80">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w100">
		<col class="w80">
		<col class="w80">
		<col class="w100">
		<col class="w60">
		<col class="w60">
	</colgroup>
	<thead>
	<tr>
		<th scope="col" rowspan="2"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col" rowspan="2">번호</th>
		<th scope="col" rowspan="2">이미지</th>
		<th scope="col"><?php echo subject_sort_link('a.gcode',$q2); ?>상품코드</a></th>
		<th scope="col" colspan="<?php echo defined("USE_BUY_PARTNER_GRADE") && USE_BUY_PARTNER_GRADE ? "3" :"2"?>"><?php echo subject_sort_link('a.gname',$q2); ?>상품명</a></th>
        <th scope="col"><?php echo subject_sort_link('a.reg_time',$q2); ?>최초등록일</a></th>
		<th scope="col"><?php echo subject_sort_link('a.isopen',$q2); ?>진열</a></th>
		<th scope="col" colspan="4" class="th_bg">가격정보</th>
		<th scope="col" colspan="3" class="th_bg">적립정보</th>
		<th scope="col" rowspan="2"><?php echo subject_sort_link('a.rank',$q2); ?>순위</a></th>
		<th scope="col" rowspan="2">관리</th>
	</tr>
	<tr class="rows">
		<th scope="col"><?php echo subject_sort_link('a.mb_id',$q2); ?>업체코드</a></th>
        <?php if( defined("USE_BUY_PARTNER_GRADE") && USE_BUY_PARTNER_GRADE ) : ?>
		<th scope="col">가맹상품</th>
        <?php endif; ?>
        <th scope="col">공급사명</th>
		<th scope="col">카테고리</th>
		<th scope="col"><?php echo subject_sort_link('a.update_time',$q2); ?>최근수정일</a></th>
		<th scope="col"><?php echo subject_sort_link('a.stock_qty',$q2); ?>재고</a></th>
		<th scope="col" class="th_bg">신판매가</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.supply_price',$q2); ?>공급가</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.goods_price',$q2); ?>판매가</a></th>
		<th scope="col" class="th_bg">네이버최저가</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.goods_kv',$q2); ?>마일리지</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.gpoint',$q2); ?>쇼핑포인트</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.point_pay_point',$q2); ?>쇼핑포인트결제</a></th>
	</tr>
	</thead>
	<tbody>
	<?php

	if($_SESSION['ss_page_rows'])
		$page_rows = $_SESSION['ss_page_rows'];
	else
		$page_rows = 30;


	$rows = $page_rows;
	$total_page = ceil($total_count / $rows); // 전체 페이지 계산
	if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함
	$num = $total_count - (($page-1)*$rows);
	$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
	//echo $sql;
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs_id = $row['index_no'];

		if($row['stock_mod'])
			$stockQty = number_format($row['stock_qty']);
		else
			$stockQty = '<span class="txt_false">무제한</span>';

		$bg = 'list'.($i%2);
    $QUERY_STRING = htmlspecialchars(urlencode($row[gname]));
	$data = explode("(",$row['gname']);
	$dcount = sizeof($data)-1;
	$row['gname'] = str_replace("(".$data[$dcount],'',$row['gname']);
	$between_link = "https://search.shopping.naver.com/search/all?query=".urlencode($row['gname']);

	$row2 = sql_fetch(" select * from naver_list where gcode='{$row['gcode']}' order by price asc limit 1"); //db에 등록되었었는지 여부 검사

	?>
	<tr class="<?php echo $bg; ?>">
		<td rowspan="2">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $gs_id; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td rowspan="2"><?php echo $num--; ?></td>
		<td rowspan="2"><a href="<?php echo MS_SHOP_URL; ?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank"><?php echo get_it_image($gs_id, $row['simg1'], 40, 40); ?></a></td>
		<td><?php echo $row['gcode']; ?></td>
		<td colspan="3" class="tal"><?php echo get_text($row['gname']); ?></td>
		<td><?php echo substr($row['reg_time'],2,8); ?></td>
		<td><?php echo $gw_isopen[$row['isopen']]; ?></td>
		<td rowspan="2" class="tar"><input type="text" name="goods_price[<?php echo $i; ?>]" value="" class="frm_input"></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['supply_price']); ?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['goods_price']); ?></td>
		<td rowspan="2" class="tar"><?php echo $row2['img_com']; ?>|<?php echo number_format($row2['price']); ?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['goods_kv']); ?>원<br><?php echo number_format($row['goods_kv_per']); ?>%</td>
		<td rowspan="2" class="tar"><?php echo number_format($row['gpoint']); ?>P<br><?php echo number_format($row['gpoint_per']); ?>%</td>
		<td rowspan="2" class="tar"><?if($row['point_pay_allow']==1){?><?php echo number_format($row['point_pay_point']); ?>P<br><?php echo number_format($row['point_pay_per']); ?>%<?}?></td>
		<td rowspan="2"><input type="text" name="rank[<?php echo $i; ?>]" value="<?php echo $row['rank']; ?>" class="frm_input"></td>
		<td rowspan="2">
      <a href="./goods.php?code=form&w=u&gs_id=<?php echo $gs_id.$qstr; ?>&page=<?php echo $page; ?>&bak=<?php echo $code; ?>" class="btn_small">수정</a><br/>
      <a href="<?=$between_link?>" target="_blank" class="btn_small red" style="margin-top:3px;">가격비교</a>
  	  <a href="./naver_price_url.php?gcode=<?php echo $row['gcode']; ?>" onclick="win_open(this,'pop_naver_price_url','550','500','no');return false" class="btn_small" style="margin-top:3px;">최저가가져오기</a>
    </td>
	</tr>
	<tr class="<?php echo $bg; ?>">
		<td class="fc_00f"><?php echo $row['mb_id']; ?></td>
        <?php if( defined("USE_BUY_PARTNER_GRADE") && USE_BUY_PARTNER_GRADE ) : ?>
        <td scope="tal"><?php echo minishop::minishopLevelSelect('buy_minishop_grade', $row['buy_minishop_grade'], "가맹상품아님"); ?></td>
        <?php endif; ?>
		<td class="tal txt_succeed"><?php echo get_seller_name($row['mb_id']); ?></td>
		<td class="tal txt_succeed"><?php echo get_cgy_info($row); ?></td>
		<td class="fc_00f"><?php echo substr($row['update_time'],2,8); ?></td>
		<td><?php echo $stockQty; ?></td>
	</tr>
	<?php
	}
	if($i==0)
		echo '<tr><td colspan="17" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
function downExcel_guid_select() {
	var f = document.fgoodslist;
	var c = document.fsearch;
	
	c.excelType_tt.value = f.excelType.value;
	msg = "검색된 상품을 EXCEL로 저장하겠습니까?";

	if (confirm(msg)) {
		c.method = "post";
		c.action = "./goods/goods_list_excel_down.php";
		c.submit();
	}
}
function fgoodslist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

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

	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date,#to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>


<?php
include_once("./_common.php");

// 자료가 많을 경우 대비 설정변경
set_time_limit ( 0 );
ini_set('memory_limit', '50M');


if($_FILES['excelfile']['tmp_name']) {
    $file = $_FILES['excelfile']['tmp_name'];

    include_once(MS_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');

    /***
    * if you want you can change 'iconv' to mb_convert_encoding:
    * $data->setUTFEncoder('mb');
    *
    **/

    /***
    * By default rows & cols indeces start with 1
    * For change initial index use:
    * $data->setRowColOffset(0);
    *
    **/

    /***
    * Some function for formatting output.
    * $data->setDefaultFormat('%.2f');
    * setDefaultFormat - set format for columns with unknown formatting
    *
    * $data->setColumnFormat(4, '%.3f');
    * setColumnFormat - set format for column (apply only to number fields)
    *
    **/

    $data->read($file);

    /*
	$data->sheets[0]['numRows'] - count rows
	$data->sheets[0]['numCols'] - count columns
	$data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

	$data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell

	$data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
	if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
	$data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
	$data->sheets[0]['cellsInfo'][$i][$j]['colspan']
	$data->sheets[0]['cellsInfo'][$i][$j]['rowspan']
    */

//    error_reporting(E_ALL ^ E_NOTICE);
	
    $fail_od_no = array();
    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;

	for($k=2; $k<=$data->sheets[0]['numRows']; $k++)
	{

        $total_count++;
		$j = 1;

		$jumunp			  		 = addslashes(trim($data->sheets[0]['cells'][$k][1]));  //외부채널 상품주문번호
		$jumun					 = addslashes(trim($data->sheets[0]['cells'][$k][2]));  //외부채널 상품주문번호
		$delivery				 = addslashes(trim($data->sheets[0]['cells'][$k][3])); //배송회사
		$delivery_no			 = addslashes(trim($data->sheets[0]['cells'][$k][4])); //운송장번호
		$delivery_date			 = addslashes(trim($data->sheets[0]['cells'][$k][5]));  //배송일시
		$delivery_ok			 = addslashes(trim($data->sheets[0]['cells'][$k][6]));  //배송완료
		$od_id					 = addslashes(trim($data->sheets[0]['cells'][$k][7]));  //주문번호
//		$od_no					 = addslashes(trim($data->sheets[0]['cells'][$k][3]));  //일련번호	
//		$delivery				 = addslashes(trim($data->sheets[0]['cells'][$k][13])); //배송회사

	
	/*	$mb_id		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));   //판매자아이디
		$od_id		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));   //주문번호
		$od_no		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));  //일련번호
		$gname		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));   //상품명
		$name		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));   //주문자
		$cellphone		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));   //주문자전화번호
		$telephone		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));   //주문저전화번호
		$b_name		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));   //수령자
		$b_cellphone		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));   //수령자전화번호
		$b_telephone		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));   //수령자전화번호
		$b_zip		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));   //수령자우편번호
		$b_addr1		 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));   //수령자주소
		$delivery	 = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));  //배송회사
		$delivery_no = addslashes(trim($data->sheets[0]['cells'][$k][$j++]));  //운송장번호 */


		$delivery = get_info_delivery($delivery);		

//        if(!$od_id || !$od_no || !$delivery || !$delivery_no) {
        if(!$od_id || !$delivery || !$delivery_no) {
            $fail_cojumunnt++;
//            $fail_od_no[] = $od_no;
			$fail_od_no[] = $od_id;
            continue;
        }

        // 주문정보
//        $od = sql_fetch(" select dan from shop_order where od_id = '$od_id' and od_no = '$od_no' ");
		$od = sql_fetch(" select dan from shop_order where od_id = '$od_id' limit 1");
        if(!$od) {
            $fail_count++;
//            $fail_od_no[] = $od_no;
			$fail_od_no[] = $od_id;
            continue;
        }

        if($od['dan'] != 3) {
            $fail_count++;
//            $fail_od_no[] = $od_no;
			$fail_od_no[] = $od_id;
            continue;
        }				

		$sql = " update shop_order
				    set delivery = '$delivery'
					  , delivery_no = '$delivery_no'
					  , delivery_date = '".MS_TIME_YMDHIS."'
					  , dan = '4'
				  where od_id = '$od_id' ";
//				    and od_no = '$od_no' ";
		sql_query($sql);
		
		$succ_count++;
	}
}
?>

<h2>총 건수</h2>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">총배송건수</th>
		<td><?php echo number_format($total_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">완료건수</th>
		<td><?php echo number_format($succ_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">실패건수</th>
		<td><?php echo number_format($fail_count); ?>건</td>
	</tr>
	<?php if($fail_count > 0) { ?>
	<tr>
		<th scope="row">실패일련번호</th>
		<td><?php echo implode(', ', $fail_od_no); ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<a href="./page.php?code=seller_order_delivery" class="btn_large">확인</a>
</div>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ엑셀자료는 1회 업로드당 최대 1,000건까지 이므로 1,000건씩 나누어 업로드 하시기 바랍니다.</p>
			<p>ㆍ형식은 <strong>배송처리용 엑셀파일</strong>을 다운로드하여 배송 정보를 입력하시면 됩니다.</p>
			<p>ㆍ수정 완료 후 엑셀파일을 업로드하시면 배송정보가 일괄등록됩니다.</p>
			<p>ㆍ엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong> 로 저장하셔야 합니다.</p>
			<p>ㆍ주문상태가 배송준비인 주문에 한해 엑셀파일이 생성됩니다.</p>
			<p>ㆍ배송회사명은 <a href="<?php echo MS_ADMIN_URL; ?>/config.php?code=baesong"><strong>환경설정 > 배송/교환/반품 설정</strong></a>에 등록된 배송업체명과 반드시 일치해야 합니다.</p>
			<p>ㆍ엑셀데이터는 2번째 라인부터 저장되므로 타이틀은 지우시면 안됩니다.</p>
		</div>
	 </div>
</div>

<script>
$(function() {
	// 새로고침(F5) 막기
	$(document).keydown(function (e) {		
		if(e.which === 116) {
			if(typeof event == "object") {
				event.keyCode = 0;
			}
			return false;
		} else if(e.which === 82 && e.ctrlKey) {
			return false;
		}
	});
});
</script>

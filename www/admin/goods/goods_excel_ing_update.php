<?php
include_once("./_common.php");

// 자료가 많을 경우 대비 설정변경
set_time_limit ( 0 );
ini_set('memory_limit', '50M');

check_demo();

check_admin_token();

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

$mb_id				='';
$gcode				='';
$gcate				='';
$gname				='';
$isopen				='';
$supply_price		='';
$normal_price		='';
$goods_price		='';
$naver_price_url	='';

for($k=2; $k<=$data->sheets[0]['numRows']; $k++)
	{

//		if(trim($data->sheets[0]['cells'][$k][3]))
//			continue;

        $total_count++;
	
$mb_id					 = addslashes(trim($data->sheets[0]['cells'][$k][1])); // mb_id 업체코드
$gcode					 = addslashes(trim($data->sheets[0]['cells'][$k][2])); // gcode 상품코드
$naver_price_url		 = addslashes(trim($data->sheets[0]['cells'][$k][3])); // naver_price_url 네이버쇼핑링크주소


$row = sql_fetch(" select * from shop_goods where gcode = '$gcode' ");
$gs_id = $row['index_no'];
$gs_cd = $row['gcode'];


        if(!$row['index_no'] || !$naver_price_url) {
            $fail_count++;
            $fail_od_no[] = $gcate;
            continue;
        }else{
			unset($value);
			$value['naver_price_url'] = $naver_price_url;
			update("shop_goods", $value," where gcode='{$gcode}' ");
			$succ_count++;
		}

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
		<th scope="row">총상품갯수</th>
		<td><?php echo number_format($total_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">등록완료</th>
		<td><?php echo number_format($succ_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">등록실패</th>
		<td><?php echo number_format($fail_count); ?>건</td>
	</tr>
	<?php if($fail_count > 0) { ?>
	<tr>
		<th scope="row">실패상품코드</th>
		<td><?php echo implode(', ', $fail_od_no); ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<a href="<?php echo MS_ADMIN_URL; ?>/goods.php?code=excel_ing" class="btn_large">확인</a>
</div>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ엑셀자료는 1회 업로드당 최대 1,000건까지 이므로 1,000건씩 나누어 업로드 하시기 바랍니다.</p>
			<p>ㆍ형식은 <strong>작성양식 다운로드</strong>버튼을 클릭하여 엑셀파일을 다운받으신후 상품정보를 입력하시면 됩니다.</p>
			<p>ㆍ수정 완료 후 엑셀파일을 업로드하시면 상품정보가 일괄등록됩니다.</p>
			<p>ㆍ엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong> 로 저장하셔야 합니다.</p>
			<p>ㆍ옵션이 있는 경우 옵션의 명칭과 100% 일치해야 합니다. 글자 하나라도 틀리게 입력되면 상품수정이 성공하지 못합니다.</p>
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


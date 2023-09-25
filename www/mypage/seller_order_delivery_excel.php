<?php
include_once("./_common.php");

check_demo();

$od = sql_fetch(" select seller_code from shop_seller where mb_id = '{$member['id']}' limit 1");

$sql = " select * from shop_order where dan = '3' and seller_id = '{$od['seller_code']}' order by od_time desc, index_no asc ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

/** Include PHPExcel */
include_once(MS_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '외부채널 상품주문번호')
	->setCellValue($char++.'1', '외부채널 주문번호')
	->setCellValue($char++.'1', '배송업체명')
	->setCellValue($char++.'1', '송장번호')
	->setCellValue($char++.'1', '배송일')
	->setCellValue($char++.'1', '배송완료일')
	->setCellValue($char++.'1', '주문번호');

for($i=2; $row=sql_fetch_array($result); $i++)
{	
	$gs = unserialize($row['od_goods']);

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['od_id'], PHPExcel_Cell_DataType::TYPE_STRING);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('엑셀배송처리');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="엑셀배송처리-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>
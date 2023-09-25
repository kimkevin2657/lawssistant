<?php
if (!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가

$ca_name= $_POST['ca_name']; // 문서종류.

$app_2	= $_POST['app_2']; // 두번째 결재자
$app_3	= $_POST['app_3']; // 세번째 결재자
$app_4	= $_POST['app_4']; // 네번째 결재자
$cost_sum	= str_replace(",", "", $_POST['cost_sum']); // 지출결의 - 합계금액

//echo $ca_name;


// 문서종류, 결재선, 지출 합계금액 업데이트
// wr_2는 작성자의 아이디 고정
$sql = "update
			{$write_table}
		set
			ca_name = '{$ca_name}',
			wr_option = 'html1',
			wr_2 = '{$member['id']}',
			wr_3 = '0|0',
			wr_4 = '{$app_2}',
			wr_5 = '0|0',
			wr_6 = '{$app_3}',
			wr_7 = '0|0',
			wr_8 = '{$app_4}',
			wr_9 = '0|0',
			wr_10 = '{$cost_sum}',
			wr_11 = '{$_POST['wr_11']}'
		where
			wr_id = '{$wr_id}' ";
//echo "update.skin:".$sql;
sql_query($sql, true);


$id_no		= $_POST['id_no'];	// 지출결의 - 청구 고유번호
$doc_sub	= $_POST['doc_sub'];	// 지출결의 - 적요
$doc_standard = $_POST['doc_standard'];	// 지출결의 - 규격
$doc_cnt	= $_POST['doc_cnt'];	// 지출결의 - 수량
$doc_unit	= $_POST['doc_unit'];	// 지출결의 - 단가
$doc_cost	= $_POST['doc_cost'];	// 지출결의 - 합계(금액)
$doc_etc	= $_POST['doc_etc'];	// 지출결의 - 비고


// 문서를 작성후 서식이 변경될 수 있으므로 상세내역을 모두 삭제후 신규로 재등록한다.
sql_query("DELETE FROM {$write_table}_sub where wr_id = '{$wr_id}'");

$doc_count = ($doc_sub && count($doc_sub) > 0) ? count($doc_sub) : 0 ;
if($doc_count > 0) {

	// 모든 지출내역 신규 등록
	for($i=0; $i < $doc_count; $i++) {
		if($doc_sub[$i]) {
			
			$cnt = str_replace(",", "", $doc_cnt[$i]);
			$unit = str_replace(",", "", $doc_unit[$i]);
			$cost = str_replace(",", "", $doc_cost[$i]);
			
			$sql = "INSERT INTO
						{$write_table}_sub
						(`id_no`, `wr_id`, `mb_id`, `doc_sub`, `doc_standard`, `doc_cnt`, `doc_unit`, `doc_cost`, `doc_etc`)
					VALUES
						('{$id_no[$i]}', '{$wr_id}', '{$member['id']}', '{$doc_sub[$i]}', '{$doc_standard[$i]}', '{$cnt}', '{$unit}', '{$cost}', '{$doc_etc[$i]}') ";
			sql_query($sql, true);

			$cnt = "0";
			$unit = "0";
			$cost = "0";
		}
	} // end for

} // end if : $section = 문서종류 (지출결의서 저장 및 업데이트 끝)
?>